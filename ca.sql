-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
<<<<<<< HEAD
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
=======
-- Host: 127.0.0.1
-- Generation Time: May 03, 2015 at 09:22 AM
-- Server version: 5.6.21
-- PHP Version: 5.6.3
>>>>>>> parent of 0771db4... edit database

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
  `organization` text NOT NULL,
  `organization_unit` text NOT NULL,
  `country` text NOT NULL,
  `state` text NOT NULL,
  `locality` text NOT NULL,
  `common_name` text NOT NULL,
  `email` text NOT NULL,
  `publickey` longblob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
