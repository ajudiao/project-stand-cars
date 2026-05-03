# Mapa Visual do Dashboard AutoVendas

## 🗺️ Estrutura de Navegação

```
┌─────────────────────────────────────────────────────────────┐
│                    DASHBOARD AUTOVENDAS                      │
│                   Stand de Veículos Pro                      │
└─────────────────────────────────────────────────────────────┘

┌──────────────────┬──────────────────────────────────────────┐
│                  │                                          │
│   SIDEBAR        │         PÁGINA PRINCIPAL                │
│   (280px)        │         (Responsivo)                    │
│                  │                                          │
│ ┌──────────────┐ │  ┌────────────────────────────────────┐ │
│ │   Logo       │ │  │  Header                            │ │
│ │  AutoVendas  │ │  │  - Título da Página               │ │
│ └──────────────┘ │  │  - Notificações (3)               │ │
│                  │  │  - User Menu                       │ │
│ MENU:            │  └────────────────────────────────────┘ │
│ ─────────────    │                                          │
│                  │  ┌────────────────────────────────────┐ │
│ 📊 Dashboard    │  │  Conteúdo Principal                │ │
│ 🚗 Veículos     │  │  - Cards de Estatísticas           │ │
│ 👥 Clientes     │  │  - Gráficos                        │ │
│ 💰 Vendas       │  │  - Tabelas com Dados               │ │
│ 📄 Relatórios   │  │  - Modais para CRUD                │ │
│ 🌐 Publicação   │  │  - Filtros e Busca                 │ │
│                  │  │  - Paginação                       │ │
│ ─────────────    │  └────────────────────────────────────┘ │
│                  │                                          │
│ 👤 Perfil       │                                          │
│ ⚙️  Configurações│                                          │
│ 🚪 Sair         │                                          │
│                  │                                          │
└──────────────────┴──────────────────────────────────────────┘
```

## 📄 Páginas e Conteúdo

### 1. 📊 Dashboard (`index.html`)
```
┌─ KPI Cards ────────────────────────────────────────┐
│  📊 Veículos    💰 Vendas    👥 Clientes    % Taxa │
│  24              R$ 2M      156             24.5%  │
└────────────────────────────────────────────────────┘

┌─ Gráfico de Vendas (últimos 12 meses) ────────────┐
│  [=============================]                   │
│  J F M A M J J A S O N D                           │
└────────────────────────────────────────────────────┘

┌─ Distribuição por Marca ──────────────────────────┐
│  🔵 Toyota .................... 8                  │
│  🟢 Honda ..................... 6                  │
│  🟡 Fiat ...................... 5                  │
│  🔴 Outros .................... 5                  │
└────────────────────────────────────────────────────┘

┌─ Últimas Vendas ──────────────────────────────────┐
│ Cliente      │ Veículo      │ Valor    │ Status    │
│ Carlos       │ Toyota       │ R$ 95k   │ ✓ Concluída│
│ Maria        │ Honda        │ R$ 87k   │ ✓ Concluída│
│ João         │ Fiat         │ R$ 45k   │ ⏳ Pendente  │
│ Ana          │ Volkswagen   │ R$ 38k   │ ✓ Concluída│
└────────────────────────────────────────────────────┘
```

### 2. 🚗 Veículos (`veiculos.html`)
```
┌─ Filtros ─────────────────────────────────────────┐
│ [Buscar marca/modelo] [Status ▼] [Marca ▼]       │
└────────────────────────────────────────────────────┘

┌─ Tabela de Veículos ──────────────────────────────┐
│ Foto │ Modelo           │ Ano│ Preço  │ Status    │
│ 📷   │ Toyota Corolla   │2024│ 95.000 │ Disponível│
│ 📷   │ Honda Civic      │2023│ 87.500 │ Negociação│
│ 📷   │ Fiat Argo        │2022│ 45.000 │ Disponível│
│ 📷   │ Volkswagen Gol   │2021│ 38.500 │ Vendido   │
│                     [Ações: Editar | Deletar]    │
└────────────────────────────────────────────────────┘

[+ Adicionar Veículo]
```

### 3. 👥 Clientes (`clientes.html`)
```
┌─ Clientes Cadastrados ────────────────────────────┐
│ Nome         │ CPF      │ Email    │ Telefone    │
│ Carlos Mendes│ 123...   │ c@em.com │ (21) 98... │
│ Maria Silva  │ 987...   │ m@em.com │ (11) 97... │
│ João Santos  │ 456...   │ j@em.com │ (31) 96... │
│ Ana Costa    │ 789...   │ a@em.com │ (61) 95... │
└────────────────────────────────────────────────────┘
```

### 4. 💰 Vendas (`vendas.html`)
```
┌─ Estatísticas ────────────────────────────────────┐
│ ✓ Concluídas │ ⏳ Negociação │ 📅 Este Mês │ 💵 Total │
│ 18           │ 5             │ 6           │ R$ 1.2M  │
└────────────────────────────────────────────────────┘

┌─ Histórico de Vendas ─────────────────────────────┐
│ ID  │ Cliente      │ Veículo   │ Valor  │ Status   │
│ #001│ Carlos       │ Toyota    │ 95.000 │ ✓ Concl. │
│ #002│ Maria        │ Honda     │ 87.500 │ ✓ Concl. │
│ #003│ João         │ Fiat      │ 45.000 │ ⏳ Pend.  │
│ #004│ Ana          │ VW        │ 38.500 │ ✓ Concl. │
└────────────────────────────────────────────────────┘
```

### 5. 📄 Relatórios (`relatorios.html`)
```
┌─────────────────────────────────────────────────┐
│ 📊 Vendas      │ 🚗 Inventário │ 💰 Financeiro  │
│ [Gerar]        │ [Gerar]       │ [Gerar]        │
└─────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────┐
│ 👥 Clientes    │ 📈 Performance │ 📝 Personalizado│
│ [Gerar]        │ [Gerar]        │ [Criar]        │
└─────────────────────────────────────────────────┘
```

### 6. 🌐 Publicação Web (`publicacao.html`)
```
┌─ Estatísticas ────────────────────────────────────┐
│ 🌐 Publicados │ 👁️ Visualizações │ ❤️ Favoritos   │
│ 18            │ 1,240            │ 89             │
└────────────────────────────────────────────────────┘

┌─ Veículos Publicados ─────────────────────────────┐
│ Modelo              │ Status  │ Vis │ Data Pub.   │
│ Toyota Corolla 2024 │ Ativo   │ 245 │ 10/02/2024  │
│ Honda Civic 2023    │ Ativo   │ 189 │ 09/02/2024  │
└────────────────────────────────────────────────────┘
```

### 7. 👤 Perfil (`perfil.html`)
```
┌─ Perfil Pessoal ──────────────────────────────────┐
│              [👤 Avatar]                          │
│              João Silva                           │
│              Gerente da Stand                     │
│              [Editar] [Mudar Foto]                │
└────────────────────────────────────────────────────┘

┌─ Informações de Contato ──────────────────────────┐
│ Email: joao.silva@autovendas.com                 │
│ Telefone: (21) 98765-4321                        │
│ Desde: 15/01/2023                                │
└────────────────────────────────────────────────────┘

┌─ Informações da Stand ────────────────────────────┐
│ Nome: AutoVendas Premium                         │
│ NIF: 12.345.678/0001-00                         │
│ Endereço: Av. Principal, 1000                    │
│ [Editar]                                         │
└────────────────────────────────────────────────────┘

┌─ Segurança ───────────────────────────────────────┐
│ Senha: ••••••••  [Alterar]                       │
│ 2FA: Desativada  [Ativar]                        │
└────────────────────────────────────────────────────┘
```

### 8. ⚙️ Configurações (`configuracoes.html`)
```
┌─ Configurações Gerais ────────────────────────────┐
│ Idioma: [Português ▼]                            │
│ Moeda: [Real (R$) ▼]                             │
│ Timezone: [Brasília ▼]                           │
│ ☐ Modo de Manutenção                            │
└────────────────────────────────────────────────────┘

┌─ Aparência ───────────────────────────────────────┐
│ Tema: [☀️ Claro] [🌙 Escuro] [🔄 Automático]     │
│ ☐ Modo Compacto                                  │
└────────────────────────────────────────────────────┘

┌─ Notificações ────────────────────────────────────┐
│ ☑️ Novo veículo                                  │
│ ☑️ Venda realizada                               │
│ ☑️ Novo cliente                                  │
│ ☑️ Notificações por email                        │
└────────────────────────────────────────────────────┘

┌─ Integrações ─────────────────────────────────────┐
│ WhatsApp          [Conectar]                     │
│ Email Marketing   [Conectar]                     │
│ Google Analytics  [Conectar]                     │
└────────────────────────────────────────────────────┘
```

## 🎨 Componentes Visuais

### Modal de Adicionar
```
┌─────────────────────────────────────┐
│ Adicionar Novo Veículo         [×]  │
├─────────────────────────────────────┤
│ Marca: [Toyota____]                 │
│ Modelo: [Corolla___]                │
│ Ano: [2024]  Cor: [Branco_]        │
│ Tipo: [Automático ▼]                │
│ Preço: [95000]  Status: [Disponível]│
│ Quilometragem: [5000]               │
│ Descrição: [____________]           │
│ Foto: [Escolher arquivo]            │
├─────────────────────────────────────┤
│ [Cancelar] [Adicionar Veículo]     │
└─────────────────────────────────────┘
```

### Card de Estatísticas
```
┌────────────────────────┐
│ [📊] Veículos         │
│        24              │
│ ↑ 2 novos este mês    │
└────────────────────────┘
```

### Notificação Toast
```
╔════════════════════════════╗
║ ✓ Sucesso                 ║
╠════════════════════════════╣
║ Veículo adicionado!       ║
╚════════════════════════════╝
(desaparece em 5 segundos)
```

### Dropdown de Notificações
```
┌─────────────────────────────┐
│ Notificações (3)     [Clear]│
├─────────────────────────────┤
│ [🚗] Novo veículo           │
│     Toyota Corolla 2024     │
│     há 2 minutos            │
├─────────────────────────────┤
│ [✓] Venda realizada         │
│     Honda Civic - R$ 95k    │
│     há 15 minutos           │
├─────────────────────────────┤
│ [⚠️] Documento vencido       │
│     Seguro #12345           │
│     há 1 hora               │
├─────────────────────────────┤
│ Ver todas as notificações   │
└─────────────────────────────┘
```

## 🎯 Fluxos de Usuário

### Fluxo: Adicionar Veículo
```
1. Dashboard
   ↓
2. Clica em "Veículos"
   ↓
3. Clica em "+ Adicionar Veículo"
   ↓
4. Modal abre
   ↓
5. Preenche formulário
   ↓
6. Clica "Adicionar Veículo"
   ↓
7. ✓ Notificação de sucesso
   ↓
8. Tabela atualiza
```

### Fluxo: Editar Veículo
```
1. Veículos
   ↓
2. Clica no botão "Editar"
   ↓
3. Modal abre com dados preenchidos
   ↓
4. Altera campos
   ↓
5. Clica "Salvar Alterações"
   ↓
6. ✓ Notificação de sucesso
```

### Fluxo: Deletar Veículo
```
1. Veículos
   ↓
2. Clica no botão "Deletar"
   ↓
3. Modal de confirmação aparece
   ↓
4. Clica "Excluir"
   ↓
5. ✓ Notificação de sucesso
   ↓
6. Linha removida da tabela
```

## 📱 Responsividade

### Desktop (1920x1080)
```
┌──────────┬──────────────────────────┐
│ Sidebar  │                          │
│ 280px    │     Conteúdo             │
│ (Visível)│                          │
└──────────┴──────────────────────────┘
```

### Tablet (768x1024)
```
┌────────┬──────────────────────┐
│Sidebar │                      │
│ 80px   │   Conteúdo           │
│(Compact)                      │
└────────┴──────────────────────┘
```

### Mobile (375x667)
```
┌─────────────────────────┐
│ ☰ | Título  | 🔔 | 👤 │  ← Toggle
├─────────────────────────┤
│                         │
│    Conteúdo             │
│    (Sidebar oculta)     │
│                         │
│                         │
└─────────────────────────┘
```

## 🔐 Hierarquia de Informações

```
Sistema
├── Usuário (Login)
├── Dashboard (Resumo)
├── Veículos
│   ├── Listar
│   ├── Detalhes
│   ├── Adicionar
│   └── Editar
├── Clientes
│   ├── Listar
│   ├── Detalhes
│   ├── Adicionar
│   └── Editar
├── Vendas
│   ├── Histórico
│   ├── Registrar
│   └── Detalhes
├── Relatórios
│   ├── Vendas
│   ├── Inventário
│   ├── Financeiro
│   ├── Clientes
│   ├── Performance
│   └── Personalizado
├── Publicação Web
│   ├── Listar
│   └── Publicar
├── Perfil
│   ├── Dados Pessoais
│   ├── Dados da Stand
│   └── Segurança
└── Configurações
    ├── Gerais
    ├── Aparência
    ├── Notificações
    ├── Integrações
    └── Backup
```

## 🎨 Paleta de Cores

```
Primária:    #0d6efd (Azul)      ███
Sucesso:     #198754 (Verde)     ███
Aviso:       #ffc107 (Amarelo)   ███
Perigo:      #dc3545 (Vermelho)  ███
Info:        #0dcaf0 (Ciano)     ███
Secundária:  #6c757d (Cinza)     ███
Claro:       #f8f9fa (Branco)    ███
Escuro:      #212529 (Preto)     ███
```

---

Este mapa visual ajuda a entender a estrutura e o fluxo do dashboard!
