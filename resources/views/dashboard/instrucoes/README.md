# Dashboard AutoVendas - Stand de Veículos

Um dashboard completo e responsivo para gerenciamento de uma stand de veículos, desenvolvido com HTML5, CSS3, Bootstrap 5 e JavaScript puro.

## 📋 Características

### ✅ Páginas Implementadas

1. **Dashboard** (`index.html`)
   - KPIs (Veículos, Vendas, Clientes, Taxa de Conversão)
   - Gráficos de vendas
   - Distribuição por marca
   - Últimas vendas

2. **Veículos** (`veiculos.html`)
   - Listagem completa com filtros
   - Adicionar novo veículo (modal)
   - Editar veículo (modal)
   - Deletar veículo (com confirmação)
   - Busca em tempo real

3. **Clientes** (`clientes.html`)
   - Gerenciamento de clientes
   - Adicionar cliente (modal)
   - Editar cliente (modal)
   - Deletar cliente (com confirmação)
   - Filtros avançados

4. **Vendas** (`vendas.html`)
   - Histórico completo de vendas
   - Registrar nova venda
   - Visualizar detalhes da venda
   - Estatísticas de vendas
   - Filtros por status e período

5. **Relatórios** (`relatorios.html`)
   - Relatório de Vendas
   - Relatório de Inventário
   - Relatório Financeiro
   - Relatório de Clientes
   - Relatório de Performance
   - Criar relatório personalizado

6. **Publicação Web** (`publicacao.html`)
   - Gerenciar publicações no site público
   - Estatísticas de visualizações
   - Publicar novo veículo
   - Destacar veículos

7. **Perfil** (`perfil.html`)
   - Gerenciar perfil pessoal
   - Informações da stand
   - Segurança (alterar senha, 2FA)
   - Editar foto de perfil

8. **Configurações** (`configuracoes.html`)
   - Configurações gerais
   - Aparência (tema claro/escuro)
   - Notificações
   - Integrações
   - Backup e dados
   - Exportar dados

### 🎨 Recursos de UI/UX

- **Menu Sidebar Colapsável**: Alterna entre modo completo e compacto
- **Sistema de Notificações**: Notificações em dropdown com exemplos
- **Modais Funcionais**: Para adicionar, editar e confirmar exclusões
- **Responsivo 100%**: Funciona em desktop, tablet e mobile
- **Ícones Bootstrap Icons**: Todos os ícones do Bootstrap Icons
- **Temas**: Suporte para modo claro/escuro
- **Animações Suaves**: Transições e efeitos visuais
- **Toast Notifications**: Notificações flutuantes de feedback

## 📁 Estrutura de Arquivos

```
public/
├── dashboard/
│   ├── index.html           # Dashboard principal
│   ├── veiculos.html        # Gerenciar veículos
│   ├── clientes.html        # Gerenciar clientes
│   ├── vendas.html          # Histórico de vendas
│   ├── relatorios.html      # Gerar relatórios
│   ├── publicacao.html      # Publicação web
│   ├── perfil.html          # Meu perfil
│   ├── configuracoes.html   # Configurações
│   └── README.md            # Esta documentação
├── css/
│   └── style.css            # Estilos principais
└── js/
    ├── app.js               # JavaScript principal
    └── vehicles.js          # Funções específicas de veículos
```

## 🚀 Como Usar

### Estrutura para PHP/Backend

O projeto foi estruturado para facilitar a integração com PHP (SSR):

1. **Views PHP**: Cada arquivo HTML pode ser convertido para PHP incluindo um template base
2. **Dados Dinâmicos**: Os dados estão renderizados no HTML (não carregados via JS)
3. **Formulários**: Já estruturados para POST/PUT/DELETE

### Integração com Backend

Ao usar com PHP, siga este padrão:

```php
<?php
// views/veiculos.php
include 'layout.php';  // Include do header e sidebar

// Aqui seus dados vindos do banco
$veiculos = $db->query("SELECT * FROM veiculos");

// Renderizar na tabela
?>
<table>
    <tbody>
    <?php foreach($veiculos as $veiculo): ?>
        <tr>
            <td><?php echo $veiculo['marca']; ?></td>
            ...
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
```

## 🎯 Funcionalidades JavaScript

### Modais

- `editVehicle(id)` - Editar veículo
- `deleteVehicle(id)` - Deletar veículo
- `saveVehicle()` - Salvar novo veículo
- `updateVehicle()` - Atualizar veículo
- Equivalentes para clientes, vendas, etc.

### Notificações

```javascript
showNotification(title, message, type);
// Types: 'info', 'success', 'warning', 'danger'
```

### Utilitários

- `formatCurrency(value)` - Formatar moeda BRL
- `formatDate(date)` - Formatar data PT-BR
- `filterTable(searchId, tableId)` - Busca em tempo real
- `Cache.set()`, `Cache.get()` - Sistema de cache local

## 📱 Responsividade

- **Desktop** (1024px+): Layout completo com sidebar visível
- **Tablet** (768px-1023px): Sidebar reduzida
- **Mobile** (<768px): Sidebar oculta, menu toggle
- **Small Mobile** (<480px): Layout otimizado para telas pequenas

## 🎨 Variáveis CSS Personalizáveis

Editar em `style.css`:

```css
:root {
    --primary-color: #0d6efd;
    --secondary-color: #6c757d;
    --success-color: #198754;
    --sidebar-bg: #ffffff;
    --topbar-bg: #ffffff;
}
```

## 🔐 Segurança

- ⚠️ **Este é um template frontend**. Toda validação real deve ser feita no backend.
- Use senhas hasheadas (bcrypt) no PHP
- Implemente CSRF tokens em formulários
- Valide dados no servidor, não confie apenas no cliente
- Use prepared statements para prevenir SQL injection

## 📊 Dados Fictícios

Todos os dados mostrados são fictícios:
- Veículos: 4 exemplos
- Clientes: 4 exemplos
- Vendas: 4 exemplos
- Notificações: 3 exemplos

Ao integrar com backend, substitua pelos dados reais do banco.

## 🔧 Customização

### Adicionar Nova Página

1. Crie um novo arquivo HTML baseado em uma página existente
2. Altere o título e conteúdo
3. Atualize o sidebar para incluir o novo link
4. Crie JS específico se necessário

### Alterar Cores

Edite as cores em `:root` no `style.css`.

### Alterar Ícones

Todos usam Bootstrap Icons. Veja: https://icons.getbootstrap.com/

## 📝 Notas de Desenvolvimento

- **Sem dependências externas**: Apenas Bootstrap 5 e Bootstrap Icons via CDN
- **Dados no HTML**: Facilita integração com qualquer backend
- **Modular**: JavaScript dividido por funcionalidade
- **Comentado**: Código bem documentado para fácil manutenção

## 🎓 Próximos Passos

1. **Integrar com API REST**:
   - Substituir chamadas `showNotification()` com fetch real
   - Fazer POST/PUT/DELETE para servidor

2. **Autenticação**:
   - Adicionar página de login
   - Implementar JWT ou sessões PHP

3. **Banco de Dados**:
   - MySQL/PostgreSQL com tabelas de veículos, clientes, vendas

4. **Validação**:
   - Servidor: Validar dados recebidos
   - Cliente: Adicionar mais validações frontend

5. **Testes**:
   - Testar em diferentes browsers e tamanhos de tela
   - Testes de performance

## 💡 Dicas

- Use DevTools (F12) para debug
- Abra Console para ver logs com `log()`
- Teste em mobile com `Ctrl+Shift+M` no Firefox/Chrome
- Cache Local armazena configurações do usuário

## 📞 Suporte

Para questões sobre implementação:
1. Consulte o código comentado
2. Verifique os exemplos existentes
3. Use o console do navegador para debug

---

**Criado com ❤️ para AutoVendas**

Versão: 1.0.0
Data: Fevereiro 2024
