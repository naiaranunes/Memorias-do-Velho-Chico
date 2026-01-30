-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 30/01/2026 às 01:13
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
-- Banco de dados: `memorias_velho_chico`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `historias`
--

CREATE TABLE `historias` (
  `id` int(11) NOT NULL,
  `usuario_id` int(10) UNSIGNED NOT NULL,
  `titulo` varchar(150) NOT NULL,
  `descricao` varchar(200) NOT NULL,
  `data_memoria` date NOT NULL,
  `imagem` varchar(255) NOT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `historias`
--

INSERT INTO `historias` (`id`, `usuario_id`, `titulo`, `descricao`, `data_memoria`, `imagem`, `criado_em`) VALUES
(4, 4, 'carranquinhas', 'carrancas', '2026-01-07', 'uploads/hist_696d2cb779e5b4.03737826.png', '2026-01-18 18:55:51'),
(7, 4, 'vapor do vinho', 'vapor do vinho', '2026-01-08', 'uploads/hist_697b019257c1d3.94751945.jpeg', '2026-01-29 06:43:30'),
(9, 13, 'parque do povo', 'legal.', '2026-01-15', 'uploads/hist_697b94bcc6e421.17156111.jpeg', '2026-01-29 17:11:24');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(10) UNSIGNED NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `data_nascimento` date NOT NULL,
  `senha` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `data_nascimento`, `senha`) VALUES
(1, 'naiara', 'nunesnaaiara@gmail.com', '2026-01-14', '81dc9bdb52d04dc20036dbd8313ed055'),
(2, 'carol', 'carol@gmail.com', '2013-03-17', '827ccb0eea8a706c4c34a16891f84e7b'),
(3, 'charlene', 'charleninha@gmail.com', '2020-07-17', '827ccb0eea8a706c4c34a16891f84e7b'),
(4, 'joao00', 'joa4@gmail.com', '2019-07-17', '827ccb0eea8a706c4c34a16891f84e7b'),
(5, 'Hariel', 'hariel@gmail.com', '2010-02-17', 'e10adc3949ba59abbe56e057f20f883e'),
(7, 'jurema', 'jureminhadograu@gmail.com', '2009-07-17', 'e10adc3949ba59abbe56e057f20f883e'),
(8, 'lucas', 'lukitas@gmail.com', '2010-06-17', 'e10adc3949ba59abbe56e057f20f883e'),
(10, 'Tadeu', 'tadeu.@gmail.com', '2026-01-21', '827ccb0eea8a706c4c34a16891f84e7b'),
(13, 'geovana', 'geovana@gmail.com', '2026-01-14', 'fcea920f7412b5da7be0cf42b8c93759');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `historias`
--
ALTER TABLE `historias`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

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
-- AUTO_INCREMENT de tabela `historias`
--
ALTER TABLE `historias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `historias`
--
ALTER TABLE `historias`
  ADD CONSTRAINT `fk_historias_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
