-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: May 06, 2015 at 06:07 PM
-- Server version: 5.6.21
-- PHP Version: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `ca`
--

-- --------------------------------------------------------

--
-- Table structure for table `request`
--

CREATE TABLE IF NOT EXISTS `request` (
`request_id` int(11) NOT NULL,
  `request_ou` text NOT NULL,
  `request_cm` text NOT NULL,
  `request_serial` text NOT NULL,
  `request_csr` varchar(2000) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `root`
--

CREATE TABLE IF NOT EXISTS `root` (
`id_root` int(11) NOT NULL,
  `private_key` varchar(2000) NOT NULL,
  `ca_root` varchar(2000) NOT NULL,
  `public_key` varchar(2000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
`user_id` int(100) NOT NULL,
  `user_organization` varchar(100) NOT NULL,
  `user_country` varchar(50) NOT NULL,
  `user_state` varchar(50) NOT NULL,
  `user_locality` varchar(50) NOT NULL,
  `user_email` varchar(50) NOT NULL,
  `user_password` varchar(100) NOT NULL,
  `user_salt` varchar(100) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `user_organization`, `user_country`, `user_state`, `user_locality`, `user_email`, `user_password`, `user_salt`) VALUES
(4, 'Institut Teknologi Sepuluh Nopember', 'ID', 'Jawa TImur', 'Surabaya', 'admin@its.ac.id', '6c0cc97e0754dfd9de60b41140d43697d05108df5a2e72fdd94c4958ca31b6f0', '7307834911e8dbbb'),
(5, 'its', 'Indonesia', 'sumatra selatan', 'palembang', 'izdiharfarahdina@gmail.com', '69f9a724279b2e188e1319acaef2f8b3ea8869814718b631b83cca0556230ce0', '7dde42c627ff4a2a');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `request`
--
ALTER TABLE `request`
 ADD PRIMARY KEY (`request_id`), ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `root`
--
ALTER TABLE `root`
 ADD PRIMARY KEY (`id_root`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
 ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `request`
--
ALTER TABLE `request`
MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `root`
--
ALTER TABLE `root`
MODIFY `id_root` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
MODIFY `user_id` int(100) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `request`
--
ALTER TABLE `request`
ADD CONSTRAINT `user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
