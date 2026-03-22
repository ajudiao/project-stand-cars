# Guia de Integração com PHP

Este documento explica como integrar o dashboard com um backend em PHP.

## 📋 Estrutura Recomendada

```
projeto/
├── index.php              # Arquivo raiz
├── public/
│   ├── dashboard/         # Este projeto
│   ├── css/
│   ├── js/
│   └── images/
├── app/
│   ├── controllers/       # Lógica da aplicação
│   ├── models/            # Modelos de dados
│   ├── views/             # Templates base
│   └── config/            # Configurações
├── database/
│   └── schema.sql         # Schema do banco
└── .htaccess             # Rewrite rules
```

## 🗄️ Schema do Banco de Dados

```sql
-- Usuários
CREATE TABLE usuarios (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    cargo VARCHAR(100),
    telefone VARCHAR(20),
    foto VARCHAR(255),
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Veículos
CREATE TABLE veiculos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    marca VARCHAR(100) NOT NULL,
    modelo VARCHAR(100) NOT NULL,
    ano INT NOT NULL,
    cor VARCHAR(50),
    tipo VARCHAR(50), -- 'Automático' ou 'Manual'
    preco DECIMAL(10, 2),
    quilometragem INT,
    status VARCHAR(50), -- 'Disponível', 'Em Negociação', 'Vendido'
    descricao TEXT,
    foto VARCHAR(255),
    publicado_web BOOLEAN DEFAULT 0,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Clientes
CREATE TABLE clientes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(255) NOT NULL,
    cpf VARCHAR(14) UNIQUE,
    email VARCHAR(255),
    telefone VARCHAR(20),
    endereco TEXT,
    cidade VARCHAR(100),
    estado VARCHAR(2),
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Vendas
CREATE TABLE vendas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    cliente_id INT,
    veiculo_id INT,
    valor DECIMAL(10, 2),
    data_venda DATE,
    status VARCHAR(50), -- 'Concluída', 'Pendente', 'Cancelada'
    observacoes TEXT,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id),
    FOREIGN KEY (veiculo_id) REFERENCES veiculos(id)
);

-- Notificações
CREATE TABLE notificacoes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    usuario_id INT,
    titulo VARCHAR(255),
    mensagem TEXT,
    tipo VARCHAR(50), -- 'info', 'success', 'warning', 'danger'
    lida BOOLEAN DEFAULT 0,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);
```

## 🔧 Arquivo de Configuração

`app/config/database.php`:

```php
<?php
class Database {
    private $host = 'localhost';
    private $db_name = 'autovendas';
    private $user = 'root';
    private $password = '';
    
    public function connect() {
        try {
            $pdo = new PDO(
                'mysql:host=' . $this->host . ';dbname=' . $this->db_name,
                $this->user,
                $this->password
            );
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch(PDOException $e) {
            die('Connection Error: ' . $e->getMessage());
        }
    }
}
?>
```

## 📱 Model de Veículo

`app/models/Veiculo.php`:

```php
<?php
class Veiculo {
    private $db;
    
    public function __construct() {
        require_once '../app/config/database.php';
        $database = new Database();
        $this->db = $database->connect();
    }
    
    // GET TODOS
    public function getAll() {
        $query = "SELECT * FROM veiculos ORDER BY criado_em DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // GET UM
    public function getById($id) {
        $query = "SELECT * FROM veiculos WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // CRIAR
    public function create($data) {
        $query = "INSERT INTO veiculos (marca, modelo, ano, cor, tipo, preco, quilometragem, status, descricao)
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            $data['marca'],
            $data['modelo'],
            $data['ano'],
            $data['cor'],
            $data['tipo'],
            $data['preco'],
            $data['quilometragem'],
            $data['status'],
            $data['descricao']
        ]);
    }
    
    // ATUALIZAR
    public function update($id, $data) {
        $query = "UPDATE veiculos SET marca = ?, modelo = ?, ano = ?, cor = ?, tipo = ?, preco = ?, quilometragem = ?, status = ?, descricao = ? WHERE id = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            $data['marca'],
            $data['modelo'],
            $data['ano'],
            $data['cor'],
            $data['tipo'],
            $data['preco'],
            $data['quilometragem'],
            $data['status'],
            $data['descricao'],
            $id
        ]);
    }
    
    // DELETAR
    public function delete($id) {
        $query = "DELETE FROM veiculos WHERE id = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$id]);
    }
}
?>
```

## 🎮 Controller de Veículos

`app/controllers/VeiculosController.php`:

```php
<?php
require_once '../app/models/Veiculo.php';

class VeiculosController {
    private $model;
    
    public function __construct() {
        $this->model = new Veiculo();
    }
    
    // Listar todos
    public function index() {
        $veiculos = $this->model->getAll();
        require_once '../public/dashboard/veiculos.php';
    }
    
    // Criar novo
    public function store($data) {
        if ($this->model->create($data)) {
            return ['success' => true, 'message' => 'Veículo criado com sucesso!'];
        }
        return ['success' => false, 'message' => 'Erro ao criar veículo'];
    }
    
    // Atualizar
    public function update($id, $data) {
        if ($this->model->update($id, $data)) {
            return ['success' => true, 'message' => 'Veículo atualizado com sucesso!'];
        }
        return ['success' => false, 'message' => 'Erro ao atualizar veículo'];
    }
    
    // Deletar
    public function delete($id) {
        if ($this->model->delete($id)) {
            return ['success' => true, 'message' => 'Veículo deletado com sucesso!'];
        }
        return ['success' => false, 'message' => 'Erro ao deletar veículo'];
    }
}
?>
```

## 📄 Template PHP

Converter `veiculos.html` para `veiculos.php`:

```php
<?php
session_start();

// Validar autenticação
if (!isset($_SESSION['usuario_id'])) {
    header('Location: /login.php');
    exit;
}

require_once 'app/controllers/VeiculosController.php';
$controller = new VeiculosController();

// Processar requisições
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'create') {
        $response = $controller->store($_POST);
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action'])) {
    if ($_GET['action'] === 'edit' && isset($_GET['id'])) {
        $veiculo = $this->model->getById($_GET['id']);
    }
    if ($_GET['action'] === 'delete' && isset($_GET['id'])) {
        $response = $controller->delete($_GET['id']);
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
}

// Carregar dados
$veiculos = $controller->getAll();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Veículos - AutoVendas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/public/css/style.css">
</head>
<body>
    <!-- Sidebar e estrutura -->
    <?php include 'app/views/sidebar.php'; ?>
    
    <div class="main-content">
        <!-- Top bar -->
        <?php include 'app/views/topbar.php'; ?>
        
        <main class="page-content">
            <div class="container-fluid">
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center">
                            <h2>Gerenciar Veículos</h2>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addVehicleModal">
                                <i class="bi bi-plus-circle"></i> Adicionar Veículo
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Tabela de veículos -->
                <div class="card">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Foto</th>
                                    <th>Modelo</th>
                                    <th>Ano</th>
                                    <th>Preço</th>
                                    <th>Status</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($veiculos as $veiculo): ?>
                                <tr>
                                    <td>
                                        <img src="<?php echo $veiculo['foto']; ?>" alt="<?php echo $veiculo['marca']; ?>" class="rounded" style="width: 80px; height: 60px; object-fit: cover;">
                                    </td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($veiculo['marca'] . ' ' . $veiculo['modelo']); ?></strong><br>
                                        <small class="text-muted"><?php echo htmlspecialchars($veiculo['tipo']) . ' | ' . htmlspecialchars($veiculo['cor']); ?></small>
                                    </td>
                                    <td><?php echo $veiculo['ano']; ?></td>
                                    <td>R$ <?php echo number_format($veiculo['preco'], 2, ',', '.'); ?></td>
                                    <td><span class="badge bg-success"><?php echo $veiculo['status']; ?></span></td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="?action=edit&id=<?php echo $veiculo['id']; ?>" class="btn btn-outline-primary">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <a href="?action=delete&id=<?php echo $veiculo['id']; ?>" class="btn btn-outline-danger" onclick="return confirm('Tem certeza?')">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
    
    <!-- Modais -->
    <!-- ... copiar dos HTML originais ... -->
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/public/js/app.js"></script>
</body>
</html>
```

## 🔐 Autenticação

`login.php`:

```php
<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar credenciais
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';
    
    // Conectar BD e verificar
    // ...
    
    if ($usuario_valido) {
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome'];
        header('Location: /public/dashboard/index.php');
        exit;
    }
}
?>
<!-- Formulário de login -->
```

## 🛡️ Validação

Sempre validar no servidor:

```php
<?php
function validarVeiculo($data) {
    $erros = [];
    
    if (empty($data['marca'])) {
        $erros[] = 'Marca é obrigatória';
    }
    
    if (empty($data['modelo'])) {
        $erros[] = 'Modelo é obrigatório';
    }
    
    if (!is_numeric($data['preco']) || $data['preco'] < 0) {
        $erros[] = 'Preço inválido';
    }
    
    return $erros;
}
?>
```

## 📧 API REST (Alternativa)

Se preferir usar API REST com JSON:

```php
<?php
// api/veiculos.php
header('Content-Type: application/json');

require_once '../app/models/Veiculo.php';
$veiculo = new Veiculo();

$method = $_SERVER['REQUEST_METHOD'];

switch($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            echo json_encode($veiculo->getById($_GET['id']));
        } else {
            echo json_encode($veiculo->getAll());
        }
        break;
    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        echo json_encode($veiculo->create($data));
        break;
    case 'PUT':
        $data = json_decode(file_get_contents('php://input'), true);
        echo json_encode($veiculo->update($_GET['id'], $data));
        break;
    case 'DELETE':
        echo json_encode($veiculo->delete($_GET['id']));
        break;
}
?>
```

Então no JavaScript:

```javascript
// Criar veículo via API
fetch('/api/veiculos.php', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json'
    },
    body: JSON.stringify({
        marca: 'Toyota',
        modelo: 'Corolla',
        ano: 2024,
        // ...
    })
})
.then(response => response.json())
.then(data => showNotification('Sucesso', 'Veículo criado!'))
.catch(error => showNotification('Erro', 'Falha ao criar'));
```

## ✅ Checklist de Implementação

- [ ] Schema do banco criado
- [ ] Arquivo de configuração do banco
- [ ] Models criados (Veiculo, Cliente, Venda)
- [ ] Controllers implementados
- [ ] Autenticação funcionando
- [ ] Templates PHP criados
- [ ] Validação no servidor
- [ ] Upload de imagens
- [ ] Notificações funcionando
- [ ] Testes em diferentes navegadores
- [ ] Deployment preparado

---

**Documentação Versão 1.0**
