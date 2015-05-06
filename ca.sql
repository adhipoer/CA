-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
<<<<<<< HEAD
-- Host: 127.0.0.1
-- Generation Time: May 06, 2015 at 04:12 PM
-- Server version: 5.6.21
-- PHP Version: 5.6.3
=======
-- Host: localhost
-- Generation Time: May 06, 2015 at 09:31 PM
-- Server version: 5.5.43-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.9
>>>>>>> origin/master

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
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `user_id` int(100) NOT NULL AUTO_INCREMENT,
  `user_organization` varchar(100) NOT NULL,
  `user_country` varchar(50) NOT NULL,
  `user_state` varchar(50) NOT NULL,
  `user_locality` varchar(50) NOT NULL,
  `user_email` varchar(50) NOT NULL,
  `user_password` varchar(100) NOT NULL,
  `user_salt` varchar(100) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `user_organization`, `user_country`, `user_state`, `user_locality`, `user_email`, `user_password`, `user_salt`) VALUES
(4, 'Institut Teknologi Sepuluh Nopember', 'ID', 'Jawa TImur', 'Surabaya', 'admin@its.ac.id', '6c0cc97e0754dfd9de60b41140d43697d05108df5a2e72fdd94c4958ca31b6f0', '7307834911e8dbbb');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `user_organization` text NOT NULL,
  `user_country` text NOT NULL,
  `user_state` text NOT NULL,
  `user_locality` text NOT NULL,
  `user_email` text NOT NULL,
  `user_password` text NOT NULL,
  `user_salt` longblob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_organization`, `user_country`, `user_state`, `user_locality`, `user_email`, `user_password`, `user_salt`) VALUES
('its', 'Indonesia', 'sumatra selatan', 'palembang', 'izdiharfarahdina@gmail.com', 'd722dc55428465fe285a6e4b94adaaf8e9fb90a1a7738c72686a9df6a71f4302', 0x33636138383463323631633565333135);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
