-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 16, 2020 at 03:18 PM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `novel_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_novel`
--

CREATE TABLE `tb_novel` (
  `id` int(10) NOT NULL,
  `judul` varchar(30) NOT NULL,
  `genre` varchar(30) NOT NULL,
  `penulis` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tb_novel`
--

INSERT INTO `tb_novel` (`id`, `judul`, `genre`, `penulis`) VALUES
(1, 'The Little Prince', 'Fiksi/fantasi', 'Antoine de Saint-Exupéry'),
(2, 'harry potter Chap 2', 'Fiksi/fantasi', 'J.K Rowling'),
(3, 'My Bastard Prince', 'Romance,Comedy', 'Daasa'),
(4, 'Harry Potter Chap1', 'Fiksi/fantasi', 'J.K Rowling');

-- --------------------------------------------------------

--
-- Table structure for table `tb_waktu`
--

CREATE TABLE `tb_waktu` (
  `id` int(10) NOT NULL,
  `judul` varchar(30) NOT NULL,
  `tahun` varchar(30) NOT NULL,
  `negara` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tb_waktu`
--

INSERT INTO `tb_waktu` (`id`, `judul`, `tahun`, `negara`) VALUES
(1, 'The Little Prince', '1943', 'Italia'),
(2, 'harry potter and the chamber o', '2002', 'Inggris'),
(3, 'My Bastard Prince', '2018', 'Indonesia'),
(4, 'Harry Potter Chap1', '1999', 'Inggris');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_novel`
--
ALTER TABLE `tb_novel`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_waktu`
--
ALTER TABLE `tb_waktu`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_novel`
--
ALTER TABLE `tb_novel`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tb_waktu`
--
ALTER TABLE `tb_waktu`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
