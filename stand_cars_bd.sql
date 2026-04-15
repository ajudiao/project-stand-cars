-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Tempo de geração: 05-Abr-2026 às 13:25
-- Versão do servidor: 10.4.28-MariaDB
-- versão do PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `stand_cars_bd`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `categorias`
--

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Extraindo dados da tabela `categorias`
--

INSERT INTO `categorias` (`id`, `nome`) VALUES
(1, 'SUV'),
(2, 'Sedan'),
(3, 'Hatch'),
(4, 'Pickup'),
(5, 'Crossover');

-- --------------------------------------------------------

--
-- Estrutura da tabela `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `nome_completo` varchar(120) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `telefone` varchar(20) NOT NULL,
  `identidade` varchar(30) DEFAULT NULL,
  `cidade` varchar(60) DEFAULT NULL,
  `municipio` varchar(60) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Extraindo dados da tabela `clientes`
--

INSERT INTO `clientes` (`id`, `nome_completo`, `email`, `telefone`, `identidade`, `cidade`, `municipio`, `created_at`) VALUES
(16, 'André Satchova Judião', 'andresgideao1121@gmail.com', '944221122', '2121', 'Adamantina', '212', '2026-03-17 13:30:52'),
(17, 'André Satchova Judião', 'andresgideao11@gmail.com', '944221122', 'asas', 'Adamantina', '212', '2026-03-17 13:31:11'),
(18, 'Moza', 'moza4211@gmail.com', '944221122', '12121212', 'Adamantina', '212', '2026-03-17 19:07:14'),
(19, 'Moza', 'moza52test@gmail.com', '944221122', '12109', 'Adamantina', '212', '2026-03-19 06:44:09'),
(20, 'Moza', 'moza42111@gmail.com', '944221121', '21212121213', 'Adamantina', '212', '2026-03-24 10:44:40'),
(21, 'Jilson Moteus', 'jilson@gmail.com', '93392221', '2131213121', 'Luanda', 'Maianga', '2026-03-24 10:48:40'),
(22, 'Editar', 'andresgideao@gmail.com', '944221122', '121', 'Adamantina', '212', '2026-03-31 23:54:31');

-- --------------------------------------------------------

--
-- Estrutura da tabela `historico_compras`
--

CREATE TABLE `historico_compras` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `carro_id` int(11) NOT NULL,
  `data_compra` timestamp NOT NULL DEFAULT current_timestamp(),
  `preco_compra` decimal(12,2) NOT NULL,
  `metodo_pagamento` varchar(50) DEFAULT NULL,
  `vendedor_id` int(11) DEFAULT NULL,
  `observacoes` text DEFAULT NULL,
  `status` varchar(40) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Extraindo dados da tabela `historico_compras`
--

INSERT INTO `historico_compras` (`id`, `cliente_id`, `carro_id`, `data_compra`, `preco_compra`, `metodo_pagamento`, `vendedor_id`, `observacoes`, `status`, `created_at`, `updated_at`) VALUES
(1, 16, 34, '2026-02-28 23:00:00', 15000.00, 'Cartão', 1, 'Primeira compra', '', '2026-04-01 10:18:48', '2026-04-01 10:18:48'),
(2, 16, 35, '2026-03-04 23:00:00', 18000.00, 'Transferência', 2, 'Cliente recorrente', '', '2026-04-01 10:18:48', '2026-04-01 10:18:48'),
(3, 18, 40, '2026-03-09 23:00:00', 22000.00, 'Dinheiro', 3, 'Pagamento à vista', '', '2026-04-01 10:18:48', '2026-04-01 10:18:48'),
(4, 19, 42, '2026-03-11 23:00:00', 19500.00, 'Cartão', 1, 'Sem observações', '', '2026-04-01 10:18:48', '2026-04-01 10:18:48'),
(5, 20, 45, '2026-03-14 23:00:00', 25000.00, 'Transferência', 2, 'Inclui garantia estendida', '', '2026-04-01 10:18:48', '2026-04-01 10:18:48'),
(6, 21, 46, '2026-03-17 23:00:00', 27000.00, 'Financiamento', 3, 'Parcelado em 24x', '', '2026-04-01 10:18:48', '2026-04-01 10:18:48'),
(7, 21, 47, '2026-03-19 23:00:00', 30000.00, 'Cartão', 1, 'Entrega imediata', '', '2026-04-01 10:18:48', '2026-04-01 10:18:48');

-- --------------------------------------------------------

--
-- Estrutura da tabela `marcas`
--

CREATE TABLE `marcas` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Extraindo dados da tabela `marcas`
--

INSERT INTO `marcas` (`id`, `nome`) VALUES
(2, 'BMW'),
(4, 'Hyundai'),
(5, 'Kia'),
(3, 'Mercedes'),
(1, 'Toyota');

-- --------------------------------------------------------

--
-- Estrutura da tabela `reservas`
--

CREATE TABLE `reservas` (
  `id` int(11) NOT NULL,
  `id_cliente` int(11) DEFAULT NULL,
  `id_veiculo` int(11) DEFAULT NULL,
  `data_reserva` datetime DEFAULT current_timestamp(),
  `status` enum('Ativa','Cancelada','Convertida') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `perfil` enum('admin','editor') DEFAULT 'admin',
  `senha` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Extraindo dados da tabela `users`
--

INSERT INTO `users` (`id`, `nome`, `email`, `perfil`, `senha`, `created_at`) VALUES
(1, 'amdin', 'admin@gmail.com', 'admin', '$2y$10$IEgixQ9MJdyabO9aNqRji.qVp.rAECHRGk3xgDg0lYeZbpxJcPr7C', '2026-03-17 13:36:13');

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `senha` varchar(255) NOT NULL,
  `perfil` enum('Administrador','Gerente','Vendedor') NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Extraindo dados da tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `telefone`, `senha`, `perfil`, `created_at`) VALUES
(1, 'Carlos Mendes', 'admin@gmail.com', '923000001', '$2y$10$IEgixQ9MJdyabO9aNqRji.qVp.rAECHRGk3xgDg0lYeZbpxJcPr7C', 'Administrador', '2026-03-15 21:29:30'),
(2, 'Ana Silva', 'ana@stand.com', '923000002', '123456', 'Gerente', '2026-03-15 21:29:30'),
(3, 'Pedro Costa', 'pedro@stand.com', '923000003', '$2y$10$IEgixQ9MJdyabO9aNqRji.qVp.rAECHRGk3xgDg0lYeZbpxJcPr7C', 'Vendedor', '2026-03-15 21:29:30');

-- --------------------------------------------------------

--
-- Estrutura da tabela `veiculos`
--

CREATE TABLE `veiculos` (
  `id` int(11) NOT NULL,
  `id_marca` int(11) DEFAULT NULL,
  `id_categoria` int(11) DEFAULT NULL,
  `modelo` varchar(80) NOT NULL,
  `ano` year(4) NOT NULL,
  `cor` varchar(40) DEFAULT NULL,
  `preco` decimal(12,2) NOT NULL,
  `quilometragem` int(11) DEFAULT NULL,
  `combustivel` enum('Gasolina','Diesel','Hibrido','Eletrico') DEFAULT NULL,
  `transmissao` enum('Manual','Automatica') DEFAULT NULL,
  `status` enum('disponivel','reservado','vendido','publicado','rascunho','indisponivel') DEFAULT 'disponivel',
  `descricao` text DEFAULT NULL,
  `destaque` tinyint(1) NOT NULL DEFAULT 0,
  `data_publicacao` datetime DEFAULT NULL,
  `updated_at` datetime NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Extraindo dados da tabela `veiculos`
--

INSERT INTO `veiculos` (`id`, `id_marca`, `id_categoria`, `modelo`, `ano`, `cor`, `preco`, `quilometragem`, `combustivel`, `transmissao`, `status`, `descricao`, `destaque`, `data_publicacao`, `updated_at`, `created_at`) VALUES
(34, 4, 5, '212', '2022', '1212', 212.00, 1212, 'Gasolina', 'Automatica', 'disponivel', NULL, 1, '2026-03-05 15:27:22', '2026-03-31 21:58:05', '2026-03-17 11:39:07'),
(35, 3, 3, 'teste', '2022', 'sdds', 232.00, 3232, 'Gasolina', 'Automatica', 'disponivel', '23232', 0, '2026-03-04 15:27:26', '0000-00-00 00:00:00', '2026-03-17 11:40:57'),
(36, 3, 3, 'teste', '2022', 'sdds', 232.00, 3232, 'Gasolina', 'Automatica', 'disponivel', '23232', 0, '2026-03-02 15:27:32', '0000-00-00 00:00:00', '2026-03-17 11:45:01'),
(37, 3, 3, 'teste', '2022', 'sdds', 232.00, 3232, 'Gasolina', 'Automatica', 'disponivel', '23232', 0, '2026-03-02 15:27:37', '0000-00-00 00:00:00', '2026-03-17 11:49:19'),
(38, 5, 5, '212', '2022', 'asas', 121.00, 2121, 'Gasolina', 'Manual', 'disponivel', 'asas', 0, '2026-03-01 15:27:40', '0000-00-00 00:00:00', '2026-03-17 11:50:54'),
(39, 5, 5, 'asas', '1999', 'sas', 1212.00, 2121, 'Diesel', 'Automatica', 'indisponivel', 'sasa', 0, '2026-03-04 15:27:45', '0000-00-00 00:00:00', '2026-03-17 11:52:46'),
(40, 5, 5, 'teste', '2022', 'dads', 212.00, 2121, 'Gasolina', 'Automatica', 'indisponivel', 'asas', 0, '2026-03-04 15:27:51', '0000-00-00 00:00:00', '2026-03-17 11:53:46'),
(41, 5, 5, 'Teste Andre Gideao', '2022', 'sdsd', 20202.00, 12121, 'Gasolina', 'Automatica', 'indisponivel', NULL, 1, '2026-03-02 15:27:55', '2026-03-31 23:46:32', '2026-03-20 18:41:02'),
(42, 4, 4, 'Teste2020202', '2022', 'asas', 2121.00, 1212, 'Gasolina', 'Automatica', 'indisponivel', 'aasdas', 0, '2026-03-03 15:27:58', '0000-00-00 00:00:00', '2026-03-21 12:47:18'),
(43, 5, 4, 'gggffgfgfg', '1999', 'bfd', 3234.00, 321, 'Gasolina', 'Automatica', 'indisponivel', 'gdfdd', 0, '2026-03-05 15:28:13', '0000-00-00 00:00:00', '2026-03-21 23:00:33'),
(44, 4, 5, 'asas', '2022', '12asa', 12121.00, 1212, 'Gasolina', 'Automatica', 'publicado', 'asas', 0, '2026-03-07 15:28:17', '0000-00-00 00:00:00', '2026-03-28 13:09:28'),
(45, 5, 5, 'Andre Gideao', '2000', 'asa', 2323.00, 23232, 'Gasolina', 'Manual', 'publicado', NULL, 1, '2026-03-06 15:27:08', '2026-03-31 22:05:07', '2026-03-28 14:03:41'),
(46, 4, 5, 'Novo Carro Andre Teste', '2022', 'sdsd', 2323.00, 223, 'Gasolina', 'Automatica', 'vendido', 'sdsdsd', 0, NULL, '2026-03-31 00:00:00', '2026-03-31 19:45:36'),
(47, 5, 5, 'asas', '2022', 'aaa', 2020.00, 222, 'Gasolina', 'Automatica', 'indisponivel', NULL, 1, NULL, '2026-03-31 22:44:34', '2026-03-31 20:01:02');

-- --------------------------------------------------------

--
-- Estrutura da tabela `veiculo_imagens`
--

CREATE TABLE `veiculo_imagens` (
  `id` int(11) NOT NULL,
  `id_veiculo` int(11) DEFAULT NULL,
  `url_imagem` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Extraindo dados da tabela `veiculo_imagens`
--

INSERT INTO `veiculo_imagens` (`id`, `id_veiculo`, `url_imagem`, `created_at`) VALUES
(1, 34, 'car_69b9320e13386.png', '2026-03-17 11:50:54'),
(2, 40, 'car_69b932ba7ce70.jpg', '2026-03-17 11:53:46'),
(3, 41, 'car_69bd86aee0803.png', '2026-03-20 18:41:02'),
(4, 42, 'car_69be8546e6617.jpg', '2026-03-21 12:47:18'),
(5, 42, 'car_69be8546e77e1.png', '2026-03-21 12:47:18'),
(6, 43, 'car_69bf1501afd6e.jpg', '2026-03-21 23:00:33'),
(7, 43, 'car_69bf1501b10da.jpg', '2026-03-21 23:00:33'),
(8, 43, 'car_69bf1501b22c3.jpg', '2026-03-21 23:00:33'),
(9, 43, 'car_69bf1501b5099.jpg', '2026-03-21 23:00:33'),
(10, 43, 'car_69bf1501b629e.jpg', '2026-03-21 23:00:33'),
(11, 44, 'car_69c7c4f82fcea.jpg', '2026-03-28 13:09:28'),
(12, 45, 'car_69c7d1ad7cb51.png', '2026-03-28 14:03:41'),
(13, 46, 'car_69cc165094806.png', '2026-03-31 19:45:36'),
(14, 47, 'car_69cc19eec7f54.png', '2026-03-31 20:01:02');

-- --------------------------------------------------------

--
-- Estrutura da tabela `vendas`
--

CREATE TABLE `vendas` (
  `id` int(11) NOT NULL,
  `id_cliente` int(11) DEFAULT NULL,
  `id_veiculo` int(11) DEFAULT NULL,
  `id_vendedor` int(11) DEFAULT NULL,
  `valor_pago` decimal(12,2) DEFAULT NULL,
  `desconto` int(11) NOT NULL,
  `metodo_pagamento` varchar(40) NOT NULL,
  `data_venda` datetime DEFAULT current_timestamp(),
  `status` varchar(30) NOT NULL,
  `observacoes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Extraindo dados da tabela `vendas`
--

INSERT INTO `vendas` (`id`, `id_cliente`, `id_veiculo`, `id_vendedor`, `valor_pago`, `desconto`, `metodo_pagamento`, `data_venda`, `status`, `observacoes`) VALUES
(2, 16, 35, 2, 17250.00, 0, 'sas', '2026-03-12 00:00:00', 'Concluido', 'Cliente negociou desconto'),
(3, 17, 36, 3, 19800.00, 0, '', '2026-03-14 00:00:00', 'Concluido', 'Pagamento parcelado'),
(4, 18, 38, 1, 21000.00, 0, 'dssfsdfd', '2026-03-15 00:00:00', 'Concluido', 'Inclui garantia extra'),
(6, 19, 41, 3, 121.00, 0, '', '2026-03-20 00:00:00', 'Pendente', 'asasa'),
(7, 19, 37, 1, 323232.00, 0, '', '2026-03-24 00:00:00', 'Pendente', 'sasa'),
(8, 21, 39, 1, 2000.00, 0, '', '2026-03-24 00:00:00', '1', 'Feito'),
(9, 18, 44, 1, 202002.00, 0, '', '2026-03-28 00:00:00', 'Concluido', 'hjshshhs'),
(10, 22, 40, 1, 11.00, 0, '', '2026-03-31 00:00:00', '1', 'sdsd\r\n\r\n'),
(11, 21, 47, 1, 499393.00, 20, 'asasa', '2026-04-01 00:00:00', 'Concluida', 'asas'),
(12, 21, 46, 1, 500.00, 2320, 'Transferecia', '2026-04-01 00:00:00', 'Concluida', ''),
(13, 21, 45, 1, 500.00, 10, '', '2026-04-01 00:00:00', 'Concluida', 'hgfhdfgdgdg'),
(14, 21, 47, 1, 499393.00, 20, 'Transferência', '2026-04-01 00:00:00', 'Concluida', 'Venda com desconto aplicado');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `bi` (`identidade`);

--
-- Índices para tabela `historico_compras`
--
ALTER TABLE `historico_compras`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_carro` (`carro_id`),
  ADD KEY `cliente_id` (`cliente_id`),
  ADD KEY `vendedor_id` (`vendedor_id`);

--
-- Índices para tabela `marcas`
--
ALTER TABLE `marcas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nome` (`nome`);

--
-- Índices para tabela `reservas`
--
ALTER TABLE `reservas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_cliente` (`id_cliente`),
  ADD KEY `id_veiculo` (`id_veiculo`);

--
-- Índices para tabela `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Índices para tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Índices para tabela `veiculos`
--
ALTER TABLE `veiculos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_marca` (`id_marca`),
  ADD KEY `id_categoria` (`id_categoria`);

--
-- Índices para tabela `veiculo_imagens`
--
ALTER TABLE `veiculo_imagens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_veiculo` (`id_veiculo`);

--
-- Índices para tabela `vendas`
--
ALTER TABLE `vendas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_vendedor` (`id_vendedor`),
  ADD KEY `vendas_ibfk_2` (`id_veiculo`),
  ADD KEY `vendas_ibfk_1` (`id_cliente`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de tabela `historico_compras`
--
ALTER TABLE `historico_compras`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `marcas`
--
ALTER TABLE `marcas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `reservas`
--
ALTER TABLE `reservas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `veiculos`
--
ALTER TABLE `veiculos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT de tabela `veiculo_imagens`
--
ALTER TABLE `veiculo_imagens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de tabela `vendas`
--
ALTER TABLE `vendas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `historico_compras`
--
ALTER TABLE `historico_compras`
  ADD CONSTRAINT `fk_carro` FOREIGN KEY (`carro_id`) REFERENCES `veiculos` (`id`),
  ADD CONSTRAINT `fk_cliente` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`),
  ADD CONSTRAINT `fk_vendedor` FOREIGN KEY (`vendedor_id`) REFERENCES `usuarios` (`id`);

--
-- Limitadores para a tabela `reservas`
--
ALTER TABLE `reservas`
  ADD CONSTRAINT `reservas_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id`),
  ADD CONSTRAINT `reservas_ibfk_2` FOREIGN KEY (`id_veiculo`) REFERENCES `veiculos` (`id`);

--
-- Limitadores para a tabela `veiculos`
--
ALTER TABLE `veiculos`
  ADD CONSTRAINT `veiculos_ibfk_1` FOREIGN KEY (`id_marca`) REFERENCES `marcas` (`id`),
  ADD CONSTRAINT `veiculos_ibfk_2` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id`);

--
-- Limitadores para a tabela `veiculo_imagens`
--
ALTER TABLE `veiculo_imagens`
  ADD CONSTRAINT `veiculo_imagens_ibfk_1` FOREIGN KEY (`id_veiculo`) REFERENCES `veiculos` (`id`);

--
-- Limitadores para a tabela `vendas`
--
ALTER TABLE `vendas`
  ADD CONSTRAINT `vendas_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `vendas_ibfk_2` FOREIGN KEY (`id_veiculo`) REFERENCES `veiculos` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `vendas_ibfk_3` FOREIGN KEY (`id_vendedor`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
