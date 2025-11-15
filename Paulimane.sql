-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: db
-- Generation Time: Nov 15, 2025 at 02:57 PM
-- Server version: 8.0.44
-- PHP Version: 8.3.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `paulimane_Site`
--

-- --------------------------------------------------------

--
-- Table structure for table `Categoria`
--

CREATE TABLE `Categoria` (
  `ID` int NOT NULL,
  `Imagem` varchar(100) NOT NULL,
  `PDF` varchar(100) NOT NULL,
  `Nome` varchar(100) NOT NULL,
  `Descricao` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Categoria`
--

INSERT INTO `Categoria` (`ID`, `Imagem`, `PDF`, `Nome`, `Descricao`) VALUES
(8, '/backoffice/uploads/catalogo/catalogo_6915fac0b7b8a0.29807909.jpg', 'backoffice/uploads/catalogo/pdfs/catalogo_6915fac8d06c8.pdf', 'Puxadores', ''),
(12, '/backoffice/uploads/catalogo/catalogo_6916105fa52ac9.72183037.jpg', '/backoffice/uploads/catalogo/pdfs/catalogo_69161067a3af36.65248757.pdf', 'Dubradiças', ''),
(13, '/backoffice/uploads/catalogo/catalogo_69161072c61097.62845381.jpg', '/backoffice/uploads/catalogo/pdfs/catalogo_6916107d179d88.21685415.pdf', 'Componentes de Cozinha', ''),
(18, '/backoffice/uploads/catalogo/catalogo_691632d9b45691.90127602.jpg', '/backoffice/uploads/catalogo/pdfs-ftp/2020-09-21-20-37-21-Catalogo-2020-C-Roupeiros.pdf', 'Componentes de Roupeiro', ''),
(19, '/backoffice/uploads/catalogo/catalogo_6916332f497f78.03406506.jpg', '/backoffice/uploads/catalogo/pdfs-ftp/2020-09-21-20-37-44-Catalogo-2020-Fechaduras.pdf', 'Fechaduras, Fechos e Cilindros', ''),
(20, '/backoffice/uploads/catalogo/catalogo_6916493028ce48.93550209.jpg', '/backoffice/uploads/catalogo/pdfs-ftp/2020-09-21-20-38-11-Catalogo-2020-C-Portas-Correr.pdf', 'Portas de Correr', ''),
(21, '/backoffice/uploads/catalogo/catalogo_691649a1617888.29575468.webp', '/backoffice/uploads/catalogo/pdfs-ftp/2020-09-21-20-38-34-Catalogo-2020-Produtos-Quimicos.pdf', 'Produtos Químicos', ''),
(22, '/backoffice/uploads/catalogo/catalogo_691649cb717778.55339976.jpg', '/backoffice/uploads/catalogo/pdfs-ftp/2020-09-21-20-38-58-Catalogo-2020-P-Complementares.pdf', 'Produtos Complementares', '');

-- --------------------------------------------------------

--
-- Table structure for table `Clientes`
--

CREATE TABLE `Clientes` (
  `ID` int NOT NULL,
  `imagem` varchar(100) NOT NULL,
  `Nome` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Clientes`
--

INSERT INTO `Clientes` (`ID`, `imagem`, `Nome`) VALUES
(2, 'backoffice/uploads/clientes/cliente_6911d36dd504c4.10840945.png', 'Critical'),
(3, 'backoffice/uploads/clientes/cliente_6911d3785af9a3.38397472.png', 'Barbot'),
(4, 'backoffice/uploads/clientes/cliente_6911d382012748.81303659.png', 'Bimbo'),
(5, 'backoffice/uploads/clientes/cliente_6911d38e779615.47713292.jpg', 'CTT'),
(6, 'backoffice/uploads/clientes/cliente_6911d3aa7956d2.32475103.webp', 'Amorim');

-- --------------------------------------------------------

--
-- Table structure for table `Destaques`
--

CREATE TABLE `Destaques` (
  `ID` int NOT NULL,
  `Imagem` varchar(100) NOT NULL,
  `Nome` varchar(50) NOT NULL,
  `Descricao` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Equipa`
--

CREATE TABLE `Equipa` (
  `ID` int NOT NULL,
  `Imagem` varchar(100) NOT NULL,
  `Nome` varchar(50) NOT NULL,
  `funcao` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Equipa`
--

INSERT INTO `Equipa` (`ID`, `Imagem`, `Nome`, `funcao`) VALUES
(13, 'backoffice/uploads/equipa/equipa_6911d2fb7f75a5.97915418.jpg', 'Ana', 'Atendimento ao publico'),
(14, 'backoffice/uploads/equipa/equipa_6911d30d053143.86564779.jpg', 'Mariana', 'Marketing'),
(15, 'backoffice/uploads/equipa/equipa_6911d32a311d98.09834602.jpg', 'Antonio', 'CEO'),
(16, 'backoffice/uploads/equipa/equipa_6911d33cca6ab2.43358692.jpg', 'Diogo', 'COO'),
(17, 'backoffice/uploads/equipa/equipa_6911d350d45010.28988037.webp', 'Paulo', 'Atendimento ao publico');

-- --------------------------------------------------------

--
-- Table structure for table `Produtos`
--

CREATE TABLE `Produtos` (
  `ID` int NOT NULL,
  `Imagem` varchar(100) NOT NULL,
  `Nome` varchar(100) NOT NULL,
  `Descricao` varchar(100) NOT NULL,
  `CategoriaID` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Textos`
--

CREATE TABLE `Textos` (
  `ID` int NOT NULL,
  `Chave` varchar(20) NOT NULL,
  `Texto` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Textos`
--

INSERT INTO `Textos` (`ID`, `Chave`, `Texto`) VALUES
(1, 'sobrenos', 'A Paulimane - Ferragens Manuel Carmo & Azevedo, Lda é uma empresa portuguesa dedicada à comercialização de ferragens e acessórios para a carpintaria e marcenaria de alta qualidade desde o ano 2000.\n\nCom mais de duas décadas de experiência no mercado, especializamo-nos em fornecer soluções completas em ferragens para os mais diversos sectores, sempre com foco na excelência e satisfação dos nossos clientes.\n\nA nossa missão é oferecer produtos de qualidade superior, aliados a um serviço personalizado e profissional, garantindo que cada cliente encontre exatamente o que precisa para os seus projetos.'),
(2, 'numero1', '25'),
(3, 'numero2', '1500'),
(4, 'numero3', '100%'),
(5, 'numero_texto1', 'Anos de Experiência'),
(6, 'numero_texto2', 'Clientes Satisfeitos'),
(7, 'numero_texto3', 'Qualidade Garantida');

-- --------------------------------------------------------

--
-- Table structure for table `Utilizador`
--

CREATE TABLE `Utilizador` (
  `ID` int NOT NULL,
  `Imagem` varchar(100) NOT NULL,
  `Nome` varchar(100) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Password` varchar(100) NOT NULL,
  `Nivel` int NOT NULL,
  `Ativo` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Utilizador`
--

INSERT INTO `Utilizador` (`ID`, `Imagem`, `Nome`, `Email`, `Password`, `Nivel`, `Ativo`) VALUES
(4, '/backoffice/uploads/users/user_6915f507a0af08.70921975.jpeg', 'admin', 'admin@paulimane.pt', '$2y$12$Hs37qUpDJL8tKbH6t3eKveT3K5l0hO.glbwOmnuh3kPPzwJ2KysU6', 3, 1),
(5, '', 'leandro', 'leandrocmonteiro2005@gmail.com', '$2y$12$8KdgNkac66KCEjOZsURG6eUAVpjDkiLmKRH9IHgv5.XDESAtTDbNW', 1, 1),
(6, '/backoffice/uploads/users/user_6918905a92e9e4.80971703.jpg', 'teste', 'teste@gmail.com', '$2y$12$J6gv11dJtyG3vWImwtz/qOkIzLjyojHA2CZlEfYRj3xBSUJ4FNC.S', 2, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Categoria`
--
ALTER TABLE `Categoria`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `Clientes`
--
ALTER TABLE `Clientes`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `Destaques`
--
ALTER TABLE `Destaques`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `Equipa`
--
ALTER TABLE `Equipa`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `Produtos`
--
ALTER TABLE `Produtos`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `Textos`
--
ALTER TABLE `Textos`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `Utilizador`
--
ALTER TABLE `Utilizador`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Categoria`
--
ALTER TABLE `Categoria`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `Clientes`
--
ALTER TABLE `Clientes`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `Destaques`
--
ALTER TABLE `Destaques`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `Equipa`
--
ALTER TABLE `Equipa`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `Produtos`
--
ALTER TABLE `Produtos`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `Textos`
--
ALTER TABLE `Textos`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `Utilizador`
--
ALTER TABLE `Utilizador`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
