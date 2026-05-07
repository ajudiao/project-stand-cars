# Diagnóstico e Correção: Upload de Imagens ao Cadastrar Carro

## Problema Identificado

Ao cadastrar um novo carro, as imagens **não estavam sendo registradas no banco de dados**, embora os arquivos fossem criados na pasta `/public/uploads/cars/`.

### Sintomas
- Arquivos de imagem criados na pasta mas com 0 registros no BD para veículos recentes
- Sem mensagem de erro clara ao usuário
- Falhas silenciosas no upload

## Raiz do Problema

### 1. **Falta de Validação Obrigatória**
O campo de fotos tinha `required` no HTML, mas não havia validação no PHP antes de criar o veículo. Isso permitia que veículos fossem criados sem imagens.

### 2. **Sem Tratamento de Erro**
A função `uploadImages()` tinha múltiplos pontos de falha com `continue` silencioso:
```php
if (!in_array($ext, $allowed)) continue;  // Sem log do erro
if ($size > $maxSize) continue;           // Sem log do erro
```

### 3. **Sem Feedback ao Usuário**
A mensagem de sucesso era exibida mesmo que nenhuma imagem fosse salva, porque era feita ANTES do upload.

### 4. **Falta de Logging**
Sem `error_log()`, era impossível debugar problemas no servidor.

## Soluções Implementadas

### ✅ Validação Obrigatória de Arquivo
```php
if (empty($_FILES['fotos']['name'][0])) {
    \App\Helpers\Helpers::setFlash('error', 'É obrigatório enviar pelo menos uma foto.');
    header('Location: /admin/automoveis');
    exit;
}
```

### ✅ Logging Detalhado
Cada ponto de falha agora registra o erro:
```php
if ($images['error'][$i] !== 0) {
    error_log("Erro no upload do arquivo {$images['name'][$i]}: Código {$images['error'][$i]}");
    continue;
}
```

### ✅ Retorno de Quantidade de Imagens
```php
private function uploadImages($images, $carId): int
{
    // ... código ...
    return $uploadedCount;  // Retorna quantidade de imagens salvas
}
```

### ✅ Mensagem de Sucesso Corrigida
```php
$imagesUploaded = $this->uploadImages($_FILES['fotos'], $carId);

if ($imagesUploaded > 0) {
    \App\Helpers\Helpers::setFlash('success', 
        "Veículo adicionado com sucesso ({$imagesUploaded} imagem(ns)).");
} else {
    \App\Helpers\Helpers::setFlash('error', 
        'Veículo criado, mas nenhuma imagem foi salva.');
}
```

### ✅ Remoção de Arquivo em Caso de Falha no BD
```php
if (!$this->carRepo->saveImage($carId, $fileName)) {
    error_log("Erro ao salvar imagem no BD: {$fileName}");
    @unlink($destination);  // Remove arquivo se BD falhar
}
```

## Como Verificar o Funcionamento

### 1. **Verificar Logs de Erro**
```bash
tail -f /opt/lampp/logs/php_error.log
```

### 2. **Testar o Upload**
1. Acesse `/admin/automoveis`
2. Clique em "Adicionar Automóvel"
3. Preencha os dados
4. Selecione 1-5 imagens (máx 2MB cada, formatos: jpg, jpeg, png, webp)
5. Clique em "Cadastrar Automóvel"
6. Verifique a mensagem de feedback

### 3. **Verificar BD**
```bash
mysql -u root stand_cars_bd
> SELECT v.id, v.modelo, COUNT(vi.id) as num_imagens 
>   FROM veiculos v 
>   LEFT JOIN veiculo_imagens vi ON vi.id_veiculo = v.id 
>   ORDER BY v.id DESC 
>   LIMIT 5;
```

## Possíveis Erros Residuais

Se ainda houver problemas, verifique:

1. **Permissões da pasta**: `chmod 777 public/uploads/cars/`
2. **Limite de upload no php.ini**:
   ```
   post_max_size = 40M
   upload_max_filesize = 40M
   ```
3. **Logs**: `tail -f /opt/lampp/logs/php_error.log`
4. **Extensões permitidas**: jpg, jpeg, png, webp (máx 2MB)

## Arquivos Modificados

- `/app/Controllers/Admin/AutomoveisController.php`
  - Método `store()`: Adicionada validação obrigatória
  - Método `uploadImages()`: Adicionado logging e retorno de quantidade
