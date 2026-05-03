# Novas Páginas Adicionadas ao Dashboard

## Páginas de Detalhes

### 1. **Detalhes do Veículo** (`detalhes-veiculo.html`)
Acesso via ícone de olho nas tabelas de veículos.

**Conteúdo:**
- Galeria de fotos do veículo
- Especificações técnicas (marca, modelo, ano, combustível, transmissão, etc)
- Características e opcionais
- Preço e status
- Histórico de publicação
- Botões para editar, deletar e voltar

**Link:**
```html
<a href="detalhes-veiculo.html?id=1" class="btn btn-outline-info">
    <i class="bi bi-eye"></i>
</a>
```

---

### 2. **Detalhes do Cliente** (`detalhes-cliente.html`)
Acesso via ícone de olho nas tabelas de clientes.

**Conteúdo:**
- Informações pessoais (nome, CPF, email, telefone)
- Endereço completo
- Histórico de compras
- Contato rápido (ligação, WhatsApp, email)
- Estatísticas de compras
- Interações recentes

**Link:**
```html
<a href="detalhes-cliente.html?id=1" class="btn btn-outline-info">
    <i class="bi bi-eye"></i>
</a>
```

---

### 3. **Detalhes da Venda** (`detalhes-venda.html`)
Acesso via ícone de olho nas tabelas de vendas.

**Conteúdo:**
- Número e status da venda
- Informações do cliente
- Informações do veículo vendido
- Detalhamento de pagamento (valor, desconto, taxas)
- Forma e parcelamento
- Documentos (recibo, contrato, nota fiscal)
- Cronograma de pagamento

**Link:**
```html
<a href="detalhes-venda.html?id=1" class="btn btn-outline-info">
    <i class="bi bi-eye"></i>
</a>
```

---

## Página de Configurações do Site

### **Configurações do Site** (`configuracoes-site.html`)
Acessível via menu lateral (nova opção após Configurações da Conta).

**Abas Incluídas:**

#### 1. **Geral**
- Nome da concessionária
- Tagline e descrição
- Contatos (telefone, email)
- Endereço completo
- Horário de funcionamento
- Ativar/desativar blog e newsletter

#### 2. **Aparência**
- Logo da concessionária
- Cores (primária e secundária)
- Fonte principal
- Imagem de fundo
- Modo escuro

#### 3. **SEO**
- Meta Title
- Meta Description
- Palavras-chave
- Google Analytics
- Google Search Console
- Permitir rastreamento

#### 4. **Redes Sociais**
- Links para Facebook, Instagram, Twitter, YouTube
- WhatsApp
- Botões de compartilhamento

#### 5. **Integrações**
- Google Maps
- Stripe/PayPal
- Mailchimp
- Chatbot

---

## Como os Ícones de Detalhes Funcionam

Cada tabela agora possui 3 ações:
- **Olho (Azul)**: Ver detalhes completos
- **Lápis (Primária)**: Editar registro
- **Lixeira (Vermelha)**: Deletar registro

### Exemplo de Implementação:
```html
<div class="btn-group btn-group-sm" role="group">
    <!-- Ver Detalhes -->
    <a href="detalhes-veiculo.html?id=1" class="btn btn-outline-info" title="Ver Detalhes">
        <i class="bi bi-eye"></i>
    </a>
    <!-- Editar -->
    <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editVehicleModal">
        <i class="bi bi-pencil"></i>
    </button>
    <!-- Deletar -->
    <button class="btn btn-outline-danger" onclick="deleteVehicle(1)">
        <i class="bi bi-trash"></i>
    </button>
</div>
```

---

## Estrutura de URLs

### Páginas de Detalhes:
- `/dashboard/detalhes-veiculo.html?id=1`
- `/dashboard/detalhes-cliente.html?id=1`
- `/dashboard/detalhes-venda.html?id=1`

### Configurações:
- `/dashboard/configuracoes.html` - Configurações da Conta
- `/dashboard/configuracoes-site.html` - Configurações do Site

---

## Dados Estáticos Inclusos

Todos os exemplos usam dados fictícios:

**Veículo de Exemplo:**
- Marca: Toyota
- Modelo: Corolla 2024
- Preço: 95.000.000
- Status: Disponível
- Especificações completas incluídas

**Cliente de Exemplo:**
- Nome: Carlos Mendes
- CPF: 123.456.789-00
- Email: carlos@email.com
- Telefone: (21) 98765-4321
- Localização: Rio de Janeiro, RJ

**Venda de Exemplo:**
- ID: #001
- Cliente: Carlos Mendes
- Veículo: Toyota Corolla 2024
- Valor Total: R$ 90.500
- Status: Concluída
- Parcelamento: 60x R$ 1.508,33

---

## Próximas Etapas de Integração com PHP

Para integrar com PHP, você precisará:

1. **Remover dados estáticos** das páginas de detalhes
2. **Receber parâmetros GET** para identificar qual registro exibir
3. **Fazer consultas ao banco de dados** com base no ID
4. **Popular os campos HTML** com dados dinâmicos do servidor

### Exemplo de Implementação PHP:
```php
// detalhes-veiculo.php
$id = $_GET['id'] ?? null;
$veiculo = $mysqli->query("SELECT * FROM veiculos WHERE id = " . (int)$id);

// Depois renderizar na view...
echo $veiculo['marca'] . " " . $veiculo['modelo'];
```

---

## Responsividade

- ✅ Totalmente responsivo em dispositivos móveis
- ✅ Tabelas adaptáveis
- ✅ Modais otimizados para mobile
- ✅ Botões de ação ajustados

---

## Ícones Bootstrap Utilizados

- `bi-eye` - Ver detalhes
- `bi-pencil` - Editar
- `bi-trash` - Deletar
- `bi-printer` - Imprimir
- `bi-download` - Download/Recibo
- `bi-file-pdf` - Documentos
- `bi-phone` - Telefone
- `bi-whatsapp` - WhatsApp
- `bi-envelope` - Email
- `bi-globe-americas` - Configurações do site

---

**Todas as páginas estão prontas para uso e aguardam sua integração com PHP!**
