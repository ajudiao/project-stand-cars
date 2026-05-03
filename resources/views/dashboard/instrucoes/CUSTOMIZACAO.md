# Guia de Customização - Dashboard AutoVendas

Este guia mostra como personalizar o dashboard para suas necessidades.

## 🎨 Cores e Tema

### Modificar Paleta de Cores

Edite as variáveis em `../css/style.css`:

```css
:root {
    --primary-color: #0d6efd;          /* Azul */
    --secondary-color: #6c757d;        /* Cinza */
    --success-color: #198754;          /* Verde */
    --danger-color: #dc3545;           /* Vermelho */
    --warning-color: #ffc107;          /* Amarelo */
    --info-color: #0dcaf0;             /* Ciano */
    --light-color: #f8f9fa;            /* Claro */
    --dark-color: #212529;             /* Escuro */
    --sidebar-bg: #ffffff;             /* Fundo sidebar */
    --sidebar-text: #333333;           /* Texto sidebar */
    --topbar-bg: #ffffff;              /* Fundo topbar */
    --topbar-text: #333333;            /* Texto topbar */
}
```

### Exemplo: Tema Dark

```css
:root {
    --sidebar-bg: #1e1e1e;
    --sidebar-text: #ffffff;
    --topbar-bg: #2d2d2d;
    --topbar-text: #ffffff;
    --light-color: #2d2d2d;
}
```

### Exemplo: Tema Green

```css
:root {
    --primary-color: #10b981;
    --success-color: #059669;
    --sidebar-bg: #ecfdf5;
}
```

## 🔤 Tipografia

### Adicionar Fonte Custom

Em `style.css`:

```css
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap');

:root {
    --font-family: 'Poppins', sans-serif;
}

* {
    font-family: var(--font-family);
}
```

## 📐 Layout e Dimensões

### Mudar Largura da Sidebar

```css
.sidebar {
    width: 300px;  /* Padrão: 280px */
}

.sidebar.collapsed {
    width: 100px;  /* Padrão: 80px */
}
```

### Ajustar Padding da Página

```css
.page-content {
    padding: 40px;  /* Padrão: 30px */
}
```

### Modificar Tamanho das Fontes

```css
h1 { font-size: 32px; }    /* Padrão: 2rem */
h2 { font-size: 24px; }    /* Padrão: 1.5rem */
h5 { font-size: 18px; }    /* Padrão: 1.25rem */
```

## 📱 Adicionar Página Nova

### Passo 1: Criar HTML

Copie um arquivo existente (ex: `veiculos.html`) para `nova-pagina.html`

### Passo 2: Atualizar Conteúdo

```html
<h2>Minha Nova Página</h2>
<p class="text-muted">Descrição da página</p>
```

### Passo 3: Adicionar ao Sidebar

Em TODAS as páginas, adicione o link ao menu:

```html
<li>
    <a href="nova-pagina.html" class="nav-link" data-page="nova-pagina">
        <i class="bi bi-star"></i>
        <span>Minha Página</span>
    </a>
</li>
```

### Passo 4: Criar JS Específico (Opcional)

Se precisar de funcionalidades, crie `../js/nova-pagina.js`:

```javascript
document.addEventListener('DOMContentLoaded', function() {
    console.log('Nova página carregada');
    // Seu código aqui
});
```

E inclua antes de `</body>`:

```html
<script src="../js/nova-pagina.js"></script>
```

## 🎯 Customizar Modais

### Alterar Tamanho do Modal

No HTML:

```html
<!-- Pequeno -->
<div class="modal-dialog modal-sm">

<!-- Padrão -->
<div class="modal-dialog">

<!-- Grande -->
<div class="modal-dialog modal-lg">

<!-- Extra Grande -->
<div class="modal-dialog modal-xl">
```

### Adicionar Validação a Formulário

```javascript
document.getElementById('addVehicleForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const marca = document.querySelector('input[placeholder*="marca"]').value;
    
    if (!marca) {
        showNotification('Erro', 'Marca é obrigatória!', 'danger');
        return;
    }
    
    saveVehicle();
});
```

## 🔔 Customizar Notificações

### Mudar Posição

Edite o CSS:

```css
.toast-container {
    position: fixed;
    bottom: 0;      /* Mudar para: top */
    right: 0;       /* Mudar para: left */
    padding: 3rem;
}
```

### Mudar Duração

Em `app.js`, função `showNotification`:

```javascript
setTimeout(() => {
    toast.remove();
}, 3000);  // 3 segundos (padrão: 5)
```

### Customizar Estilo

```css
.toast {
    background-color: #f0f0f0;
    border-radius: 10px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
}

.toast-header {
    border-radius: 10px 10px 0 0;
}
```

## 📊 Customizar Cards de Estatísticas

### Mudar Ícone e Cor

```html
<div class="stat-icon bg-success">
    <i class="bi bi-check-circle"></i>  <!-- Trocar ícone -->
</div>

<!-- Cores disponíveis: bg-primary, bg-success, bg-warning, bg-danger, bg-info -->
```

## 🗂️ Organizar Menu Sidebar

### Adicionar Categorias

```html
<li class="sidebar-menu-category">
    <span class="category-title">VENDAS</span>
</li>
<li>
    <a href="vendas.html" class="nav-link">
        <i class="bi bi-graph-up"></i>
        <span>Vendas</span>
    </a>
</li>
<li>
    <a href="relatorios.html" class="nav-link">
        <i class="bi bi-file-earmark-pdf"></i>
        <span>Relatórios</span>
    </a>
</li>

<li class="sidebar-menu-category">
    <span class="category-title">CONFIGURAÇÕES</span>
</li>
```

E adicione CSS:

```css
.sidebar-menu-category {
    padding: 15px 20px 5px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    color: var(--secondary-color);
    letter-spacing: 1px;
}
```

## 🎨 Customizar Badges de Status

### Adicionar Novos Status

Crie classes CSS:

```css
.badge-novo {
    background-color: #0dcaf0;  /* Azul */
}

.badge-arquivado {
    background-color: #6c757d;  /* Cinza */
}

.badge-urgente {
    background-color: #dc3545;  /* Vermelho */
}
```

Use no HTML:

```html
<span class="badge badge-novo">Novo</span>
<span class="badge badge-urgente">Urgente</span>
```

## 🔍 Adicionar Busca Avançada

```html
<div class="row mb-4">
    <div class="col-md-3">
        <input type="text" class="form-control" placeholder="Buscar marca..." id="searchBrand">
    </div>
    <div class="col-md-3">
        <input type="number" class="form-control" placeholder="Preço mínimo..." id="minPrice">
    </div>
    <div class="col-md-3">
        <input type="number" class="form-control" placeholder="Preço máximo..." id="maxPrice">
    </div>
    <div class="col-md-3">
        <button class="btn btn-primary w-100" onclick="advancedSearch()">Buscar</button>
    </div>
</div>
```

JavaScript:

```javascript
function advancedSearch() {
    const brand = document.getElementById('searchBrand').value.toLowerCase();
    const minPrice = parseFloat(document.getElementById('minPrice').value) || 0;
    const maxPrice = parseFloat(document.getElementById('maxPrice').value) || Infinity;
    
    const rows = document.querySelectorAll('table tbody tr');
    
    rows.forEach(row => {
        const cells = row.querySelectorAll('td');
        const rowBrand = cells[1]?.textContent.toLowerCase() || '';
        const rowPrice = parseFloat(cells[3]?.textContent.replace(/[^\d.-]/g, '') || 0);
        
        const show = (brand === '' || rowBrand.includes(brand)) && 
                     (rowPrice >= minPrice && rowPrice <= maxPrice);
        
        row.style.display = show ? '' : 'none';
    });
}
```

## 📈 Adicionar Gráficos

Se quiser usar Chart.js ao invés dos gráficos simples:

```html
<div class="card">
    <div class="card-body">
        <canvas id="myChart" height="300"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('myChart').getContext('2d');
const myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun'],
        datasets: [{
            label: 'Vendas',
            data: [12, 19, 3, 5, 2, 3],
            backgroundColor: '#0d6efd'
        }]
    }
});
</script>
```

## 🔐 Adicionar Permissões

### Mostrar/Ocultar Elementos por Papel

```php
<?php
// No PHP
$usuario_role = $_SESSION['role']; // 'admin', 'vendedor', 'gerente'
?>
```

```html
<!-- Apenas admins veem -->
<?php if ($usuario_role === 'admin'): ?>
    <a href="usuarios.html" class="nav-link">
        <i class="bi bi-people"></i>
        <span>Usuários</span>
    </a>
<?php endif; ?>
```

## 🌍 Internacionalização (i18n)

### Criar arquivo de traduções

`js/translations.json`:

```json
{
    "pt": {
        "dashboard": "Dashboard",
        "veiculos": "Veículos",
        "adicionar": "Adicionar"
    },
    "en": {
        "dashboard": "Dashboard",
        "veiculos": "Vehicles",
        "adicionar": "Add"
    }
}
```

### Usar no JavaScript

```javascript
const translations = {};
const currentLang = 'pt';

function t(key) {
    return translations[currentLang][key] || key;
}

// Usar: t('dashboard')
```

## ⚙️ Adicionar Modo Offline

```javascript
if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('/sw.js');
}

// Detectar conexão
window.addEventListener('online', () => {
    showNotification('Conectado', 'Você está online novamente');
});

window.addEventListener('offline', () => {
    showNotification('Desconectado', 'Você está offline');
});
```

## 📝 Adicionar Auditoria

```php
<?php
function logAction($usuario_id, $acao, $tabela, $registro_id) {
    $db = new Database();
    $query = "INSERT INTO auditoria (usuario_id, acao, tabela, registro_id, data) VALUES (?, ?, ?, ?, NOW())";
    $stmt = $db->prepare($query);
    $stmt->execute([$usuario_id, $acao, $tabela, $registro_id]);
}

// Usar
logAction($_SESSION['usuario_id'], 'CREATE', 'veiculos', $veiculo_id);
?>
```

## 🚀 Performance

### Minificar CSS/JS

Use ferramentas online:
- CSS Minifier: https://cssminifier.com
- JS Minifier: https://jscompress.com

### Lazy Loading de Imagens

```html
<img src="..." alt="..." loading="lazy">
```

### Caching de Browser

No PHP:

```php
<?php
header('Cache-Control: public, max-age=3600');
header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 3600) . ' GMT');
?>
```

## 🧪 Teste de Responsividade

Use Chrome DevTools (F12):

1. Clique no ícone de dispositivo (top-left)
2. Teste em diferentes tamanhos:
   - Mobile: 375x667 (iPhone)
   - Tablet: 768x1024 (iPad)
   - Desktop: 1920x1080

## 📚 Recursos Úteis

- Bootstrap Docs: https://getbootstrap.com/docs
- Bootstrap Icons: https://icons.getbootstrap.com
- MDN Web Docs: https://developer.mozilla.org
- PHP Manual: https://www.php.net/manual

---

**Versão: 1.0 | Atualizado: Fevereiro 2024**
