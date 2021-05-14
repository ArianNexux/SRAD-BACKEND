-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 14, 2021 at 07:07 PM
-- Server version: 10.4.18-MariaDB
-- PHP Version: 7.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `srad`
--

-- --------------------------------------------------------

--
-- Table structure for table `casos`
--

CREATE TABLE `casos` (
  `id` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `doenca` int(11) NOT NULL,
  `municipio` int(11) NOT NULL DEFAULT 1,
  `usuario` int(11) NOT NULL DEFAULT 1,
  `tipo` enum('M','R','A') NOT NULL,
  `date` date NOT NULL DEFAULT current_timestamp(),
  `estado` enum('Em Análise','Válido','Inválido','') NOT NULL DEFAULT 'Em Análise',
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `alterado_em` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `casos`
--

INSERT INTO `casos` (`id`, `quantidade`, `doenca`, `municipio`, `usuario`, `tipo`, `date`, `estado`, `criado_em`, `alterado_em`) VALUES
(1, 15, 1, 1, 4, 'M', '2020-03-02', 'Em Análise', '2021-03-22 16:24:27', '2021-03-30 09:28:47'),
(2, 16, 1, 1, 4, 'M', '2020-04-02', 'Em Análise', '2021-03-22 16:24:27', '2021-03-22 16:24:27'),
(3, 4, 2, 2, 4, 'M', '2020-04-02', 'Em Análise', '2021-03-22 16:24:27', '2021-03-30 08:03:35'),
(4, 89, 1, 2, 4, 'M', '2020-04-04', 'Em Análise', '2021-03-22 16:24:27', '2021-03-30 08:14:50'),
(5, 24, 1, 3, 4, 'M', '2020-04-02', 'Em Análise', '2021-03-22 16:24:27', '2021-03-23 12:14:40'),
(6, 12, 1, 2, 4, 'M', '2020-04-02', 'Em Análise', '2021-03-22 16:24:27', '2021-03-30 09:29:27'),
(7, 3, 1, 3, 4, 'M', '2020-04-02', 'Em Análise', '2021-03-22 16:24:27', '2021-03-30 09:29:31'),
(8, 3, 1, 3, 4, 'A', '2020-04-08', 'Em Análise', '2021-03-22 16:24:27', '2021-03-30 09:29:52'),
(9, 15, 1, 1, 4, 'M', '2020-03-02', 'Em Análise', '2021-03-22 16:24:27', '2021-03-30 09:29:38'),
(10, 3, 1, 3, 4, 'R', '2020-04-08', 'Em Análise', '2021-03-22 16:24:27', '2021-03-30 09:29:52'),
(19, 9, 2, 1, 1, 'R', '2021-04-01', 'Em Análise', '2021-04-01 13:30:49', '2021-04-06 09:56:07'),
(20, 5, 2, 1, 1, 'M', '2021-04-01', 'Em Análise', '2021-04-01 14:37:08', '2021-04-01 14:37:08'),
(21, 10, 1, 1, 1, 'A', '2021-04-01', 'Em Análise', '2021-04-01 16:04:03', '2021-04-01 16:04:03'),
(22, 1, 3, 1, 4, 'R', '2020-03-02', 'Em Análise', '2021-03-22 16:24:27', '2021-04-08 15:00:30'),
(23, 15, 1, 1, 4, 'M', '2020-03-02', 'Em Análise', '2021-04-08 21:25:32', '2021-04-08 21:25:32'),
(24, 15, 1, 1, 4, 'M', '2020-03-02', 'Em Análise', '2021-04-08 21:27:25', '2021-04-08 21:27:25'),
(25, 15, 1, 1, 4, 'M', '2020-03-02', 'Em Análise', '2021-04-08 22:02:30', '2021-04-08 22:02:30'),
(26, 15, 1, 1, 4, 'M', '2020-03-02', 'Em Análise', '2021-04-08 22:02:59', '2021-04-08 22:02:59'),
(27, 15, 1, 1, 4, 'M', '2020-03-02', 'Em Análise', '2021-04-08 22:05:13', '2021-04-08 22:05:13');

-- --------------------------------------------------------

--
-- Table structure for table `cidadaos`
--

CREATE TABLE `cidadaos` (
  `id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cidadaos`
--

INSERT INTO `cidadaos` (`id`, `first_name`, `last_name`, `email`) VALUES
(1, 'Paulino', 'Ross', 'paulinoross08@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `doencas`
--

CREATE TABLE `doencas` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `alterado_em` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `doencas`
--

INSERT INTO `doencas` (`id`, `nome`, `criado_em`, `alterado_em`) VALUES
(1, 'Covid-19', '2021-03-22 16:23:00', '2021-03-22 16:23:00'),
(2, 'VIH-SIDA', '2021-03-22 16:55:20', '2021-03-22 16:55:20'),
(3, 'Malária', '2021-04-01 15:05:42', '2021-04-01 15:05:42');

-- --------------------------------------------------------

--
-- Table structure for table `informacoes`
--

CREATE TABLE `informacoes` (
  `id` int(11) NOT NULL,
  `descricao` text NOT NULL,
  `doenca` int(11) NOT NULL,
  `usuario` int(11) NOT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `alterado_em` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `titulo` varchar(255) DEFAULT NULL,
  `img` varchar(600) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `informacoes`
--

INSERT INTO `informacoes` (`id`, `descricao`, `doenca`, `usuario`, `criado_em`, `alterado_em`, `titulo`, `img`) VALUES
(14, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', 1, 4, '2021-04-08 18:44:09', '2021-04-08 19:55:02', 'COVID- PREVENÇÃO', 'quem-somos-saudebemestar.jpg'),
(17, 'asdasdas', 1, 4, '2021-04-08 21:20:01', '2021-04-08 21:20:01', 'COVID- PREVENÇÃO', '606f7381b7229-169455096_523078595761755_537340972208347022_n.jpg'),
(19, 'asdasdas', 1, 4, '2021-04-08 21:20:55', '2021-04-08 21:20:55', 'COVID- PREVENÇÃO', '606f73b7f1aec-169455096_523078595761755_537340972208347022_n.jpg'),
(21, 'asdasdas', 1, 4, '2021-04-08 21:41:45', '2021-04-08 21:41:45', 'COVID- PREVENÇÃO', '606f7899d9635-169455096_523078595761755_537340972208347022_n.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `municipios`
--

CREATE TABLE `municipios` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `provincia` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `municipios`
--

INSERT INTO `municipios` (`id`, `nome`, `provincia`) VALUES
(1, 'Cacuaco', 11),
(2, 'Luquembo', 14),
(3, 'Maianga', 11),
(4, 'Belas', 11),
(5, 'Cazenga', 11),
(6, 'Icolo e Bengo\r\n', 11),
(7, 'Luanda', 11),
(8, 'Viana', 11),
(9, 'Talatona', 11),
(10, 'Kilamba Kiaxi', 11),
(11, 'Quiçama', 11),
(12, 'Bula Atumba', 1),
(13, 'Pango Aluquêm\r\n\r\n', 1),
(14, 'Nambuangongo', 1),
(16, 'Dembos', 1),
(17, 'Dande', 1),
(19, 'Ambriz', 1),
(20, 'Balombo', 2),
(21, 'Baía Farta', 2),
(22, 'Benguela', 2),
(23, 'Bocoio', 2),
(24, 'Caimbambo', 2),
(25, 'Catumbela', 2),
(26, 'Chongoroi', 2),
(27, 'Cubal', 2),
(28, 'Ganda', 2),
(29, 'Lobito', 2),
(34, 'Belize', 4),
(35, 'Buco-Zau', 4),
(36, 'Cacongo', 4),
(37, 'Cabinda', 4),
(38, 'Menongue', 8),
(39, 'Mavinga', 8),
(40, 'Longa', 8),
(41, 'Dirico', 8),
(42, 'Cuito Cuanavale', 8),
(43, 'Cuchi', 8),
(44, 'Cuangar', 8),
(45, 'Calai', 8),
(46, 'Cuangar', 8),
(47, 'Cuimba', 18),
(48, 'M\'Banza Kongo', 18),
(53, 'Tomboco', 18),
(54, 'Noqui', 18),
(55, 'N\'Zeto', 18),
(56, 'Soyo', 18),
(57, 'Alto Cauale', 17),
(58, 'Ambuíla', 17),
(59, 'Bembe', 17),
(60, 'Buengas', 17),
(61, 'Bungo', 17),
(62, 'Damba', 17),
(63, 'Macocola', 17),
(64, 'Mucaba', 17),
(65, 'Negage', 17),
(66, 'Puri', 17),
(67, 'Quimbele', 17),
(68, 'Quitexe', 17),
(69, 'Sanza Pombo', 17),
(70, 'Songo', 17),
(71, 'Uíge', 17),
(72, 'Maquela do Zombo', 17),
(73, 'Bibala', 16),
(74, 'Camulo', 16),
(75, 'Namibe', 16),
(76, 'Tômbua', 16),
(77, 'Virei', 16),
(78, 'Andulo', 3),
(79, 'Camacupa', 3),
(80, 'Catabola', 3),
(81, 'Chinguar', 3),
(82, 'Chitembo', 3),
(83, 'Cuemba', 3),
(84, 'Cunhinga', 3),
(85, 'Kuito', 3),
(86, 'Nharea', 3),
(87, 'Cahama', 5),
(88, 'Cuanhama', 5),
(89, 'Curoca', 5),
(90, 'Cuvelay', 5),
(91, 'Namacunde', 5),
(92, 'Ombadja', 5),
(93, 'Ucuma', 6),
(94, 'Tchindjenje', 6),
(95, 'Tchicala-Tcholoanga', 6),
(96, 'Mungo', 6),
(97, 'Longongo', 6),
(98, 'Londuimbale', 6),
(99, 'Huambo', 6),
(100, 'Ekunha', 6),
(101, 'Caála', 6),
(102, 'Catchiungo', 6),
(103, 'Bailundo', 6),
(104, 'Quipungo', 7),
(105, 'Quilengues', 7),
(106, 'Matala', 7),
(107, 'Lubango', 7),
(108, 'Kuvango', 7),
(109, 'Jamba', 7),
(110, 'Humpata', 7),
(111, 'Chipindo', 7),
(112, 'Chicomba', 7),
(113, 'Chibia', 7),
(114, 'Chiange', 7),
(115, 'Caluquembe', 7),
(116, 'Cacula', 7),
(117, 'Caconda', 7),
(118, 'Samba Caju', 9),
(119, 'Quiculungo', 9),
(120, 'Lucala', 9),
(121, 'Gonguembo', 9),
(122, 'Golungo Alto', 9),
(123, 'Cazengo', 9),
(124, 'Cambambe', 9),
(125, 'Bolongongo', 9),
(126, 'Banga', 9),
(127, 'Ambaca', 9),
(128, 'Amboim', 10),
(129, 'Waku Kungo', 10),
(130, 'Sumbe', 10),
(131, 'Seles', 10),
(132, 'Quilenda', 10),
(133, 'Quibala', 10),
(134, 'Mussende', 10),
(135, 'Porto Amboim', 10),
(136, 'Libolo', 10),
(137, 'Ebo', 10),
(138, 'Conda', 10),
(139, 'Cassongue', 10),
(140, 'Xá Muteba', 12),
(141, 'Lucapa', 12),
(142, 'Lubalo', 12),
(143, 'Lóvua', 12),
(144, 'Cuilo', 12),
(145, 'Cuango', 12),
(146, 'Chitato', 12),
(147, 'Caungula', 12),
(148, 'Capenda-Camulemba', 12),
(149, 'Cambulo', 12),
(150, 'Cacolo', 13),
(151, 'Dala', 13),
(152, 'Muconda', 13),
(153, 'Saurimo', 13),
(154, 'Quirima', 14),
(155, 'Quela', 14),
(156, 'Caculama-Mucari', 14),
(157, 'Massango', 14),
(158, 'Marimba', 14),
(159, 'Malange', 14),
(160, 'Cunda-Diaza', 14),
(161, 'Cuaba Nzogo', 14),
(162, 'Caombo', 14),
(163, 'Cangandala', 14),
(164, 'Cambundi-Catembo', 14),
(165, 'Calandula', 14),
(166, 'Cacuso', 14),
(168, 'Moxico', 15),
(169, 'Léua', 15),
(170, 'Luchazes', 15),
(171, 'Luau', 15),
(172, 'Lucano', 15),
(173, 'Cameia', 15),
(174, 'Camanongue', 15),
(175, 'Bundas', 15),
(176, 'Alto Zambeze', 15);

-- --------------------------------------------------------

--
-- Table structure for table `provincias`
--

CREATE TABLE `provincias` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `abreviacao` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `provincias`
--

INSERT INTO `provincias` (`id`, `nome`, `abreviacao`) VALUES
(1, 'Bengo', 'BO'),
(2, 'Benguela', 'BA'),
(3, 'Bié', 'BE'),
(4, 'Cabinda', 'CA'),
(5, 'Cunene', 'CE'),
(6, 'Huambo', 'HO'),
(7, 'Huíla', 'HA'),
(8, 'Cuando Cubango', 'CC'),
(9, 'Cuanza Norte', 'CN'),
(10, 'Cuanza Sul', 'CS'),
(11, 'Luanda', 'LA'),
(12, 'Lunda Norte', 'LN'),
(13, 'Lunda Sul', 'LS'),
(14, 'Malanje', 'ME'),
(15, 'Moxico', 'MO'),
(16, 'Namibe', 'NE'),
(17, 'UÍGE', 'UE'),
(18, 'Zaire', 'ZE');

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `municipio` int(11) NOT NULL,
  `tipo` enum('DP','DM','Admin') NOT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `actualizado_em` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `municipio`, `tipo`, `criado_em`, `actualizado_em`) VALUES
(4, 'Bento Julio', 'bento.julio@itel.gov.ao', '$2y$10$XEiXUQ.Uq.KrxQXHqwANreqS9DcAcOV0AlbprkvA.iwndCag4boAO', 1, 'DM', '2021-03-22 16:15:23', '2021-03-22 16:15:23'),
(5, 'Bento Julio', 'bento.julio@infosi.gov.ao', '$2y$10$vyXtTps5ud0K9vHjJm51cOzP4OiK1v1fUolqPgu6jfpZLNwV6BNk2', 1, 'DM', '2021-03-29 22:35:55', '2021-03-29 22:35:55'),
(6, 'Xavier Cabeto', 'xavier.cabeto@provincial.gov.ao', '$2y$10$1glljuw1Rc1fZmYum.Os8u/xrgIqmRlK9u11a60DMmlhUcGwvZftS', 1, 'DP', '2021-04-01 13:07:43', '2021-04-01 13:07:43'),
(7, 'Apolinario Manuel', 'apolinario.manuel@municipal.gov.ao', '$2y$10$mW2cpV7iXecOKXeYNRnlreO2ZSdq26k546gSCZLfWQxOsyN8t1Wnq', 1, 'DM', '2021-04-01 13:08:38', '2021-04-01 13:08:38'),
(8, 'Deni Vibes', 'deni.vibes@admin.gov.ao', '$2y$10$FdOGKPa/GcF0zfwct5X1AuFhAgylFyqzJEJqcRWrC9uxpSIOD.9Re', 1, 'Admin', '2021-04-01 13:09:00', '2021-04-01 13:09:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `casos`
--
ALTER TABLE `casos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_caso` (`usuario`),
  ADD KEY `doenca_caso` (`doenca`),
  ADD KEY `municipio_caso` (`municipio`);

--
-- Indexes for table `cidadaos`
--
ALTER TABLE `cidadaos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `doencas`
--
ALTER TABLE `doencas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `informacoes`
--
ALTER TABLE `informacoes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `doenca_informacao` (`doenca`),
  ADD KEY `usuario_informacao` (`usuario`);

--
-- Indexes for table `municipios`
--
ALTER TABLE `municipios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `provincia_municipio` (`provincia`);

--
-- Indexes for table `provincias`
--
ALTER TABLE `provincias`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_provincia` (`municipio`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `casos`
--
ALTER TABLE `casos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `cidadaos`
--
ALTER TABLE `cidadaos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `doencas`
--
ALTER TABLE `doencas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `informacoes`
--
ALTER TABLE `informacoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `municipios`
--
ALTER TABLE `municipios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=177;

--
-- AUTO_INCREMENT for table `provincias`
--
ALTER TABLE `provincias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=121;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `informacoes`
--
ALTER TABLE `informacoes`
  ADD CONSTRAINT `doenca_informacao` FOREIGN KEY (`doenca`) REFERENCES `doencas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `usuario_informacao` FOREIGN KEY (`usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `municipios`
--
ALTER TABLE `municipios`
  ADD CONSTRAINT `provincia_municipio` FOREIGN KEY (`provincia`) REFERENCES `provincias` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuario_provincia` FOREIGN KEY (`municipio`) REFERENCES `municipios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
