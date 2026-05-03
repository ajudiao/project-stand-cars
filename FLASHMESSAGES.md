# ✅ Sistema de Mensagens Flash (Sucesso e Erro)

## 📋 Descrição
O sistema está configurado para exibir mensagens de sucesso e erro automaticamente após ações como cadastro, edição e deleção de dados.

## 🔧 Como Funciona

### 1. Classe Helpers
Localização: `app/Helpers/Helpers.php`

**Métodos disponíveis:**
- `setFlash(string $type, string $message)`: Define uma mensagem flash
- `getFlash(): ?array`: Recupera a mensagem flash (é automaticamente limpa após leitura)

### 2. Componente de Alerta
Localização: `resources/views/components/alert.twig`

Exibe automaticamente as mensagens com estilos apropriados:
- ✅ **success** - Verde (sucesso)
- ❌ **error** - Vermelho (erro)
- ⚠️ **warning** - Amarelo (aviso)
- ℹ️ **info** - Azul (informação)

### 3. Layout Admin
Localização: `resources/views/layouts/admin.twig`

O componente de alerta está incluído automaticamente em todas as páginas do admin.

---

## 📝 Exemplos de Uso

### ✅ Mensagem de Sucesso
```php
\App\Helpers\Helpers::setFlash('success', 'Cliente adicionado com sucesso.');
header('Location: /admin/clientes');
exit;
```

### ❌ Mensagem de Erro
```php
\App\Helpers\Helpers::setFlash('error', 'Já existe um cliente com este email.');
header('Location: /admin/clientes');
exit;
```

### ⚠️ Mensagem de Aviso
```php
\App\Helpers\Helpers::setFlash('warning', 'Ação irreversível. Tem certeza?');
```

### ℹ️ Mensagem de Informação
```php
\App\Helpers\Helpers::setFlash('info', 'Operação iniciada. Por favor aguarde.');
```

---

## 🎯 Padrão Recomendado para Métodos

### Método Store (Criar)
```php
public function store()
{
    $data = $_POST;
    
    // Validações
    if (empty($data['nome'])) {
        \App\Helpers\Helpers::setFlash('error', 'Nome é obrigatório.');
        header('Location: /admin/recursos');
        exit;
    }
    
    try {
        $id = $this->repo->create($data);
        
        if ($id) {
            \App\Helpers\Helpers::setFlash('success', 'Recurso criado com sucesso.');
        } else {
            \App\Helpers\Helpers::setFlash('error', 'Erro ao criar recurso.');
        }
    } catch (\Exception $e) {
        error_log($e->getMessage());
        \App\Helpers\Helpers::setFlash('error', 'Erro ao processar: ' . $e->getMessage());
    }
    
    header('Location: /admin/recursos');
    exit;
}
```

### Método Update (Editar)
```php
public function update($id)
{
    $data = $_POST;
    
    if (!$this->repo->update($id, $data)) {
        \App\Helpers\Helpers::setFlash('error', 'Erro ao atualizar recurso.');
        header('Location: /admin/recursos');
        exit;
    }
    
    \App\Helpers\Helpers::setFlash('success', 'Recurso atualizado com sucesso.');
    header('Location: /admin/recursos');
    exit;
}
```

### Método Delete (Deletar)
```php
public function delete($id)
{
    if (!$this->repo->delete($id)) {
        \App\Helpers\Helpers::setFlash('error', 'Recurso não encontrado.');
        header('Location: /admin/recursos');
        exit;
    }
    
    \App\Helpers\Helpers::setFlash('success', 'Recurso excluído com sucesso.');
    header('Location: /admin/recursos');
    exit;
}
```

---

## 🎨 Estilos das Mensagens

As mensagens possuem:
- ✅ Ícone apropriado
- 📝 Títulos (Sucesso!, Erro!, Aviso!, Informação)
- 📄 Mensagem descritiva
- ❌ Botão de fechar

E desaparecem automaticamente quando o usuário fecha ou após a próxima ação.

---

## 📍 Controladoras Atualizadas

✅ **ClientesController**
- `store()` - Criar cliente
- `update()` - Atualizar cliente

✅ **AutomoveisController**
- `store()` - Criar veículo
- `delete()` - Deletar veículo

✅ **UsuariosController**
- `store()` - Criar usuário
- `delete()` - Deletar usuário
- `update()` - Atualizar usuário

✅ **VendasController**
- `store()` - Registrar venda

---

## 🔄 Como Implementar em Outros Controladoras

1. Substitua `echo "mensagem";` por `\App\Helpers\Helpers::setFlash('error', 'mensagem');`
2. Sempre faça um `header('Location: ...');` e `exit;` após definir o flash
3. Use os tipos: `success`, `error`, `warning`, `info`
4. Certifique-se que a view estende do layout `admin.twig`

**Exemplo:**
```php
// ❌ Antes
echo "Erro ao criar.";
return;

// ✅ Depois
\App\Helpers\Helpers::setFlash('error', 'Erro ao criar.');
header('Location: /admin/recursos');
exit;
```

---

## 🧪 Teste Rápido

1. Vá para `/admin/clientes`
2. Clique em "Adicionar Cliente"
3. Preencha os datos e clique em "Salvar"
4. Veja a mensagem de sucesso aparecer no topo

---

## 💡 Dicas

- Use mensagens **claras e específicas**
- Evite mensagens técnicas que confundem o usuário
- Use **sucesso** para confirmação de ações bem-sucedidas
- Use **erro** para falhas
- Use **warning** para ações que requerem confirmação
- Use **info** para instruções ou mudanças de status

