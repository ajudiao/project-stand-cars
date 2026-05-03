CREATE TABLE IF NOT EXISTS categorias (
  id INT(11) NOT NULL AUTO_INCREMENT,
  nome VARCHAR(50) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS clientes (
  id INT(11) NOT NULL AUTO_INCREMENT,
  nome_completo VARCHAR(120) NOT NULL,
  email VARCHAR(100) DEFAULT NULL,
  telefone VARCHAR(20) NOT NULL,
  identidade VARCHAR(30) DEFAULT NULL,
  cidade VARCHAR(60) DEFAULT NULL,
  municipio VARCHAR(60) DEFAULT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY email (email),
  UNIQUE KEY bi (identidade)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS historico_compras (
  id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  cliente_id INT(11) NOT NULL,
  carro_id INT(11) NOT NULL,
  data_compra TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  preco_compra DECIMAL(12,2) NOT NULL,
  metodo_pagamento VARCHAR(50) DEFAULT NULL,
  vendedor_id INT(11) DEFAULT NULL,
  observacoes TEXT DEFAULT NULL,
  status VARCHAR(40) NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY fk_carro (carro_id),
  KEY cliente_id (cliente_id),
  KEY vendedor_id (vendedor_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS marcas (
  id INT(11) NOT NULL AUTO_INCREMENT,
  nome VARCHAR(50) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY nome (nome)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS reservas (
  id INT(11) NOT NULL AUTO_INCREMENT,
  id_cliente INT(11) DEFAULT NULL,
  id_veiculo INT(11) DEFAULT NULL,
  data_reserva DATETIME DEFAULT CURRENT_TIMESTAMP,
  status ENUM('Ativa','Cancelada','Convertida') DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS users (
  id INT(11) NOT NULL AUTO_INCREMENT,
  nome VARCHAR(150) NOT NULL,
  email VARCHAR(150) NOT NULL,
  perfil ENUM('admin','editor') DEFAULT 'admin',
  senha VARCHAR(255) NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS usuarios (
  id INT(11) NOT NULL AUTO_INCREMENT,
  nome VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL,
  telefone VARCHAR(20) DEFAULT NULL,
  senha VARCHAR(255) NOT NULL,
  perfil ENUM('Administrador','Gerente','Vendedor') NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS veiculos (
  id INT(11) NOT NULL AUTO_INCREMENT,
  id_marca INT(11) DEFAULT NULL,
  id_categoria INT(11) DEFAULT NULL,
  modelo VARCHAR(80) NOT NULL,
  ano YEAR(4) NOT NULL,
  cor VARCHAR(40) DEFAULT NULL,
  preco DECIMAL(12,2) NOT NULL,
  quilometragem INT(11) DEFAULT NULL,
  combustivel ENUM('Gasolina','Diesel','Hibrido','Eletrico') DEFAULT NULL,
  transmissao ENUM('Manual','Automatica') DEFAULT NULL,
  status ENUM('disponivel','reservado','vendido','publicado','rascunho','indisponivel') DEFAULT 'disponivel',
  descricao TEXT DEFAULT NULL,
  destaque TINYINT(1) NOT NULL DEFAULT 0,
  data_publicacao DATETIME DEFAULT NULL,
  updated_at DATETIME NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS veiculo_imagens (
  id INT(11) NOT NULL AUTO_INCREMENT,
  id_veiculo INT(11) DEFAULT NULL,
  url_imagem VARCHAR(255) DEFAULT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS vendas (
  id INT(11) NOT NULL AUTO_INCREMENT,
  id_cliente INT(11) DEFAULT NULL,
  id_veiculo INT(11) DEFAULT NULL,
  id_vendedor INT(11) DEFAULT NULL,
  valor_pago DECIMAL(12,2) DEFAULT NULL,
  desconto INT(11) NOT NULL DEFAULT 0,
  metodo_pagamento VARCHAR(40) NOT NULL,
  data_venda DATETIME DEFAULT CURRENT_TIMESTAMP,
  status VARCHAR(30) NOT NULL,
  observacoes TEXT DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
