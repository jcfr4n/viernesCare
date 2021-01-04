-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 18, 2020 at 08:55 AM
-- Server version: 5.7.24
-- PHP Version: 7.4.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bd_paciente`
--
CREATE DATABASE IF NOT EXISTS `bd_paciente` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `bd_paciente`;

-- --------------------------------------------------------

--
-- Table structure for table `estado_paciente`
--

DROP TABLE IF EXISTS `estado_paciente`;
CREATE TABLE `estado_paciente` (
  `id_estado` int(11) NOT NULL,
  `estado` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `estado_paciente`
--

INSERT INTO `estado_paciente` (`id_estado`, `estado`) VALUES
(1, 'enfermo'),
(2, 'enfermo'),
(3, 'curado');

-- --------------------------------------------------------

--
-- Table structure for table `nota`
--

DROP TABLE IF EXISTS `nota`;
CREATE TABLE `nota` (
  `id_nota` int(11) NOT NULL,
  `seguimiento` varchar(5000) NOT NULL,
  `fecha` datetime NOT NULL,
  `id_estado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `nota`
--

INSERT INTO `nota` (`id_nota`, `seguimiento`, `fecha`, `id_estado`) VALUES
(1, 'paciente en recuperacion', '2020-12-14 06:14:26', 1),
(2, 'paciente con sintomas pendiente de examen', '2020-12-15 12:29:29', 2),
(3, 'paciente con examen negativo', '2020-12-16 11:27:01', 3);

-- --------------------------------------------------------

--
-- Table structure for table `paciente`
--

DROP TABLE IF EXISTS `paciente`;
CREATE TABLE `paciente` (
  `id_paciente` int(11) NOT NULL,
  `dni` varchar(9) NOT NULL,
  `codigo` varchar(8) NOT NULL,
  `email` varchar(50) NOT NULL,
  `telefono` int(9) NOT NULL,
  `id_estado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `paciente`
--

INSERT INTO `paciente` (`id_paciente`, `dni`, `codigo`, `email`, `telefono`, `id_estado`) VALUES
(1, '12345678i', '1234567a', 'fej@mail.mail', 123123123, 1),
(2, '23145657u', '1234567b', 'gre@mail.mail', 987654321, 2),
(3, '31245678y', '1234567c', 'cec@mail.mail', 1234567689, 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `estado_paciente`
--
ALTER TABLE `estado_paciente`
  ADD PRIMARY KEY (`id_estado`);

--
-- Indexes for table `nota`
--
ALTER TABLE `nota`
  ADD PRIMARY KEY (`id_nota`);

--
-- Indexes for table `paciente`
--
ALTER TABLE `paciente`
  ADD PRIMARY KEY (`id_paciente`),
  ADD UNIQUE KEY `dni` (`dni`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `estado_paciente`
--
ALTER TABLE `estado_paciente`
  MODIFY `id_estado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `nota`
--
ALTER TABLE `nota`
  MODIFY `id_nota` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `paciente`
--
ALTER TABLE `paciente`
  MODIFY `id_paciente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
