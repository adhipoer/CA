-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
-- Host: 127.0.0.1
-- Generation Time: May 06, 2015 at 04:12 PM
-- Server version: 5.6.21
-- PHP Version: 5.6.3
=======
=======
>>>>>>> origin/master
-- Host: localhost
-- Generation Time: May 06, 2015 at 09:31 PM
-- Server version: 5.5.43-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.9
<<<<<<< HEAD
>>>>>>> origin/master
=======
-- Host: 127.0.0.1
-- Generation Time: May 03, 2015 at 09:22 AM
-- Server version: 5.6.21
-- PHP Version: 5.6.3
>>>>>>> parent of 0771db4... edit database
=======
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

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
