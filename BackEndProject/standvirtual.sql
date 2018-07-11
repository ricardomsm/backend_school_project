-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 14-Maio-2018 às 18:04
-- Versão do servidor: 10.1.31-MariaDB
-- PHP Version: 7.2.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `standvirtual`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `automoveis`
--

CREATE TABLE `automoveis` (
  `cod_automovel` mediumint(8) UNSIGNED NOT NULL,
  `cod_utilizador` mediumint(8) UNSIGNED NOT NULL,
  `titulo` varchar(256) NOT NULL,
  `marca` tinyint(3) UNSIGNED NOT NULL,
  `modelo` mediumint(8) UNSIGNED NOT NULL,
  `mes` varchar(15) NOT NULL,
  `ano` year(4) NOT NULL,
  `cilindrada` smallint(5) UNSIGNED NOT NULL,
  `potencia` smallint(5) UNSIGNED NOT NULL,
  `combustivel` varchar(1) NOT NULL,
  `kms` mediumint(20) UNSIGNED NOT NULL,
  `cor` varchar(25) NOT NULL,
  `nr_portas` tinyint(1) UNSIGNED NOT NULL,
  `descricao` varchar(256) NOT NULL,
  `caracteristicas` varchar(256) NOT NULL,
  `preco` mediumint(8) UNSIGNED NOT NULL,
  `destaque` tinyint(1) NOT NULL,
  `fotos` varchar(256) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `automoveis`
--

INSERT INTO `automoveis` (`cod_automovel`, `cod_utilizador`, `titulo`, `marca`, `modelo`, `mes`, `ano`, `cilindrada`, `potencia`, `combustivel`, `kms`, `cor`, `nr_portas`, `descricao`, `caracteristicas`, `preco`, `destaque`, `fotos`) VALUES
(22, 4, 'BMW 120 d Pack M', 6, 4, 'Setembro', 2013, 1995, 184, 'D', 67435, 'Cinzento metalizada', 3, 'oioi', 'JLL,FC,ESP,VE,CB,FN', 30900, 0, '201805131532351318175141.jpg,20180513153235340197110.jpg,201805131532351899255745.jpg,201805131532351124109184.jpg,20180513153235275827341.jpg'),
(23, 4, 'Renault Mégane Sport Tourer 1.5 dCi', 42, 12, 'Fevereiro', 2012, 1461, 90, 'D', 125000, 'Branco metalizada', 5, 'ola', 'JLL,DA,ESP,VE,CB,FN,LR', 9990, 1, '20180513153435819476170.jpg,201805131534351538066374.jpg,20180513153435958588906.jpg,20180513153435656510797.jpg,20180513153435997536819.jpg');

-- --------------------------------------------------------

--
-- Estrutura da tabela `marcas`
--

CREATE TABLE `marcas` (
  `cod_marca` tinyint(3) UNSIGNED NOT NULL,
  `marca` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `marcas`
--

INSERT INTO `marcas` (`cod_marca`, `marca`) VALUES
(1, 'Alfa Romeo'),
(2, 'Aston Martin'),
(3, 'Audi'),
(4, 'Austin Morris'),
(5, 'Bentley'),
(6, 'BMW'),
(7, 'Chevrolet'),
(8, 'Chrysler'),
(9, 'Citroën'),
(10, 'Dacia'),
(11, 'Daewoo'),
(12, 'Daihatsu'),
(13, 'Dodge'),
(14, 'DS'),
(15, 'Ferrari'),
(16, 'Fiat'),
(17, 'Ford'),
(18, 'GMC'),
(19, 'Honda'),
(20, 'Hummer'),
(21, 'Hyundai'),
(22, 'Isuzu'),
(23, 'Jaguar'),
(24, 'Jeep'),
(25, 'Kia'),
(26, 'Lada'),
(27, 'Lamborghini'),
(28, 'Lancia'),
(29, 'Land Rover'),
(30, 'Lexus'),
(31, 'Lotus'),
(32, 'Maserati'),
(33, 'Mazda'),
(34, 'Mercedes-Benz'),
(35, 'MG'),
(36, 'MINI'),
(37, 'Mitsubishi'),
(38, 'Nissan'),
(39, 'Opel'),
(40, 'Peugeot'),
(41, 'Porsche'),
(42, 'Renault'),
(43, 'Rolls Royce'),
(44, 'Rover'),
(45, 'Saab'),
(46, 'Seat'),
(47, 'Skoda'),
(48, 'Smart'),
(49, 'SsangYong'),
(50, 'Subaru'),
(51, 'Suzuki'),
(52, 'Tata'),
(53, 'Toyota'),
(54, 'UMM'),
(55, 'Vauxhall'),
(56, 'Volvo'),
(57, 'VW');

-- --------------------------------------------------------

--
-- Estrutura da tabela `modelos`
--

CREATE TABLE `modelos` (
  `cod_modelo` mediumint(8) UNSIGNED NOT NULL,
  `cod_marca` tinyint(3) UNSIGNED NOT NULL,
  `modelo` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `modelos`
--

INSERT INTO `modelos` (`cod_modelo`, `cod_marca`, `modelo`) VALUES
(1, 1, '146'),
(2, 1, '156'),
(3, 1, 'Giulia'),
(4, 6, 'Série 1'),
(5, 6, 'Série 2'),
(6, 6, 'Série 3'),
(7, 6, 'Série 4'),
(8, 6, 'Série 5'),
(9, 6, 'Série 6'),
(10, 6, 'Série 7'),
(11, 42, 'Clio'),
(12, 42, 'Mégane');

-- --------------------------------------------------------

--
-- Estrutura da tabela `utilizadores`
--

CREATE TABLE `utilizadores` (
  `cod_utilizador` mediumint(8) UNSIGNED NOT NULL,
  `nome` varchar(80) NOT NULL,
  `email` varchar(50) NOT NULL,
  `morada` varchar(120) NOT NULL,
  `localidade` varchar(30) NOT NULL,
  `cp_numerico` char(8) NOT NULL,
  `cp_localidade` varchar(30) NOT NULL,
  `telefone` int(11) NOT NULL,
  `senha` varchar(256) NOT NULL,
  `token` varchar(40) DEFAULT NULL,
  `estado` char(1) NOT NULL,
  `data_registo` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `utilizadores`
--

INSERT INTO `utilizadores` (`cod_utilizador`, `nome`, `email`, `morada`, `localidade`, `cp_numerico`, `cp_localidade`, `telefone`, `senha`, `token`, `estado`, `data_registo`) VALUES
(4, 'Miguel', 'r@r.pt', 'ali', 'acolá', '4565-676', 'acolá', 918643778, '$2y$10$fbHHsEJ73shHmnTGi4od3u5L7dg55UV7iK.8hJpOsiolen47epGua', '', 'A', '2018-05-09 13:08:44');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `automoveis`
--
ALTER TABLE `automoveis`
  ADD PRIMARY KEY (`cod_automovel`),
  ADD KEY `cod_utilizador` (`cod_utilizador`),
  ADD KEY `marca` (`marca`),
  ADD KEY `modelo` (`modelo`);

--
-- Indexes for table `marcas`
--
ALTER TABLE `marcas`
  ADD PRIMARY KEY (`cod_marca`);

--
-- Indexes for table `modelos`
--
ALTER TABLE `modelos`
  ADD PRIMARY KEY (`cod_modelo`),
  ADD KEY `cod_marca` (`cod_marca`);

--
-- Indexes for table `utilizadores`
--
ALTER TABLE `utilizadores`
  ADD PRIMARY KEY (`cod_utilizador`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `automoveis`
--
ALTER TABLE `automoveis`
  MODIFY `cod_automovel` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `marcas`
--
ALTER TABLE `marcas`
  MODIFY `cod_marca` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `modelos`
--
ALTER TABLE `modelos`
  MODIFY `cod_modelo` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `utilizadores`
--
ALTER TABLE `utilizadores`
  MODIFY `cod_utilizador` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `automoveis`
--
ALTER TABLE `automoveis`
  ADD CONSTRAINT `automoveis_ibfk_1` FOREIGN KEY (`cod_utilizador`) REFERENCES `utilizadores` (`cod_utilizador`),
  ADD CONSTRAINT `automoveis_ibfk_2` FOREIGN KEY (`marca`) REFERENCES `marcas` (`cod_marca`),
  ADD CONSTRAINT `automoveis_ibfk_3` FOREIGN KEY (`modelo`) REFERENCES `modelos` (`cod_modelo`);

--
-- Limitadores para a tabela `modelos`
--
ALTER TABLE `modelos`
  ADD CONSTRAINT `modelos_ibfk_1` FOREIGN KEY (`cod_marca`) REFERENCES `marcas` (`cod_marca`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
