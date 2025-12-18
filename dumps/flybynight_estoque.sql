-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 18/05/2025 às 05:55
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `flybynight_estoque`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `detalhes_produto`
--

CREATE TABLE `detalhes_produto` (
  `id` int(11) NOT NULL,
  `peso` decimal(7,2) DEFAULT NULL,
  `dimensoes` varchar(255) DEFAULT NULL,
  `codigo_barras` varchar(255) DEFAULT NULL,
  `data_validade` date DEFAULT NULL,
  `produto_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `detalhes_produto`
--

INSERT INTO `detalhes_produto` (`id`, `peso`, `dimensoes`, `codigo_barras`, `data_validade`, `produto_id`) VALUES
(1, 0.30, '15x8x2', '7891234567890', NULL, 1),
(2, 0.20, '30x20x5', '7899876543210', NULL, 2),
(3, 1.00, '20x10x5', '7894561237895', '2025-06-04', 3),
(4, 0.50, '25x15x3', '7893216549876', NULL, 4),
(5, 1.20, '32x22x4', '7896549871234', NULL, 5),
(6, 0.40, '35x25x6', '7897412589630', NULL, 6),
(7, 1.00, '20x10x5', '7893692581472', '2025-09-11', 7),
(9, 0.80, '28x15x10', '7891111111111', NULL, 9),
(10, 0.60, '25x10x8', '7892222222222', NULL, 10),
(11, 0.30, '20x5x5', '7893333333333', '2026-05-15', 11),
(12, 0.40, '18x6x4', '7894444444444', '2026-09-01', 12),
(13, 10.00, '40x30x15', '7895555555555', '2025-12-10', 13),
(14, NULL, NULL, NULL, NULL, 14),
(15, 1.20, '35x20x8', '7897777777777', NULL, 15),
(16, 0.20, '10x4x4', '7898888888888', '2027-01-01', 16),
(17, 0.15, '12x3x1', '7899999999999', NULL, 17),
(18, 0.10, '14x4x2', '7888888888888', NULL, 18),
(19, 0.30, '6x6x6', '7877777777777', NULL, 19),
(20, 0.25, '15x5x4', '7866666666666', '2026-11-11', 20),
(21, 0.20, '18x6x4', '7855555555555', NULL, 21),
(22, 0.50, '22x18x5', '7844444444444', NULL, 22),
(23, 0.70, '30x15x10', '7833333333333', '2025-05-22', 23);

-- --------------------------------------------------------

--
-- Estrutura para tabela `fornecedores`
--

CREATE TABLE `fornecedores` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `fornecedores`
--

INSERT INTO `fornecedores` (`id`, `nome`) VALUES
(1, 'Eletrônicos TechMaster'),
(2, 'Vestuário Moda Fina'),
(3, 'Alimentos Sabor Brasil'),
(4, 'Livros Cultura Global'),
(5, 'Brinquedos Divertix'),
(6, 'Cosméticos BelaVida'),
(7, 'Pet Shop Mundo Animal');

-- --------------------------------------------------------

--
-- Estrutura para tabela `lojas`
--

CREATE TABLE `lojas` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `lojas`
--

INSERT INTO `lojas` (`id`, `nome`) VALUES
(1, 'Loja Bravado'),
(2, 'Loja Cygnus'),
(3, 'Loja 2112'),
(4, 'Loja Hemispheres'),
(5, 'Loja Counterparts'),
(6, 'Loja Permanent Waves'),
(9, 'Loja Presto');

-- --------------------------------------------------------

--
-- Estrutura para tabela `lojas_produtos`
--

CREATE TABLE `lojas_produtos` (
  `loja_id` int(11) NOT NULL,
  `produto_id` int(11) NOT NULL,
  `estoque` int(11) NOT NULL CHECK (`estoque` >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `lojas_produtos`
--

INSERT INTO `lojas_produtos` (`loja_id`, `produto_id`, `estoque`) VALUES
(1, 1, 30),
(1, 3, 150),
(1, 7, 200),
(2, 2, 50),
(2, 4, 40),
(3, 1, 20),
(3, 3, 95),
(3, 5, 10),
(3, 21, 4),
(4, 2, 100),
(4, 6, 10),
(5, 9, 40),
(5, 10, 35),
(5, 13, 25),
(5, 15, 10),
(5, 17, 100),
(5, 21, 90),
(5, 23, 55),
(6, 11, 60),
(6, 12, 50),
(6, 14, 30),
(6, 20, 70),
(6, 22, 20);

-- --------------------------------------------------------

--
-- Estrutura para tabela `produtos`
--

CREATE TABLE `produtos` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `descricao` text DEFAULT NULL,
  `preco` decimal(10,2) NOT NULL CHECK (`preco` >= 0),
  `quantidade` int(11) NOT NULL CHECK (`quantidade` >= 0),
  `fornecedor_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `produtos`
--

INSERT INTO `produtos` (`id`, `nome`, `descricao`, `preco`, `quantidade`, `fornecedor_id`) VALUES
(1, 'Smartphone Galaxy S23', 'Smartphone com Android e câmera de alta resolução', 1100.00, 70, 1),
(2, 'Camisetaa Algodão Premium', 'Camiseta masculina de algodão orgânico', 49.90, 200, 2),
(3, 'Arroz integral 1kg', 'Alimento orgânico, rico em fibras', 8.25, 300, 3),
(4, 'Livro \"Cem Anos de Solidão\"', 'Romance clássico de Gabriel Garcia Márquez', 39.90, 100, 4),
(5, 'Notebook Dell XPS 13', 'Equipamento ultrafino com processador Inter Core i7', 5457.23, 20, 1),
(6, 'Calça Jeans Skinny', NULL, 89.90, 150, 2),
(7, 'Feijão Carioca', NULL, 7.48, 250, 3),
(9, 'Carrinho Controle Remoto', 'Brinquedo elétrico com bateria recarregável', 120.00, 100, 5),
(10, 'Boneca Fashion', 'Boneca com acessórios e roupas variadas', 89.90, 120, 5),
(11, 'Shampoo Revitalizante', 'Shampoo para cabelos oleosos', 29.90, 150, 6),
(12, 'Creme Antirrugas', 'Tratamento facial noturno', 59.90, 80, 6),
(13, 'Ração Premium Cães 10kg', 'Ração rica em proteínas', 135.00, 70, 7),
(14, 'Areia Higiênica para Gatos', 'Areia com controle de odores', 25.50, 90, 7),
(15, 'Lego Criativo', 'Kit com 300 peças coloridas', 150.00, 60, 5),
(16, 'Perfume Floral 100ml', 'Fragrância suave e marcante', 79.90, 50, 6),
(17, 'Coleira com Pingente', 'Ajustável com identificação', 19.90, 200, 7),
(18, 'Pente para Gatos', 'Remoção de pelos mortos', 15.00, 180, 7),
(19, 'Cubo Mágico 3x3', 'Quebra-cabeça clássico', 12.00, 300, 5),
(20, 'Hidratante Corporal 200ml', 'Textura leve com rápida absorção', 34.90, 130, 6),
(21, 'Brinquedo Mordedor Cães', 'Material resistente', 22.90, 140, 7),
(22, 'Conjunto de Maquiagem', 'Paleta com sombras, blush e batom', 110.00, 90, 6),
(23, 'Pelúcia Urso Caramelo', 'Ursinho macio e lavável', 65.00, 110, 5);

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`) VALUES
(1, 'Tiago', 'tiago@msn.com', '$2y$10$SoOiQCFuXAFkdwOXPI7sR.6SMmZUpTEnqE9Ufa8Ym71.XJADf/C9O');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `detalhes_produto`
--
ALTER TABLE `detalhes_produto`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo_barras` (`codigo_barras`),
  ADD KEY `produto_id` (`produto_id`);

--
-- Índices de tabela `fornecedores`
--
ALTER TABLE `fornecedores`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `lojas`
--
ALTER TABLE `lojas`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `lojas_produtos`
--
ALTER TABLE `lojas_produtos`
  ADD PRIMARY KEY (`loja_id`,`produto_id`),
  ADD KEY `produto_id` (`produto_id`);

--
-- Índices de tabela `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fornecedor_id` (`fornecedor_id`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `detalhes_produto`
--
ALTER TABLE `detalhes_produto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de tabela `fornecedores`
--
ALTER TABLE `fornecedores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `lojas`
--
ALTER TABLE `lojas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `detalhes_produto`
--
ALTER TABLE `detalhes_produto`
  ADD CONSTRAINT `detalhes_produto_ibfk_1` FOREIGN KEY (`produto_id`) REFERENCES `produtos` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `lojas_produtos`
--
ALTER TABLE `lojas_produtos`
  ADD CONSTRAINT `lojas_produtos_ibfk_1` FOREIGN KEY (`loja_id`) REFERENCES `lojas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lojas_produtos_ibfk_2` FOREIGN KEY (`produto_id`) REFERENCES `produtos` (`id`);

--
-- Restrições para tabelas `produtos`
--
ALTER TABLE `produtos`
  ADD CONSTRAINT `produtos_ibfk_1` FOREIGN KEY (`fornecedor_id`) REFERENCES `fornecedores` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
