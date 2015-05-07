-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: May 07, 2015 at 02:01 AM
-- Server version: 5.6.21
-- PHP Version: 5.5.19

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
  `request_serial` text,
  `request_csr` varchar(2000) DEFAULT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `request`
--

INSERT INTO `request` (`request_id`, `request_ou`, `request_cm`, `request_serial`, `request_csr`, `user_id`) VALUES
(1, 'LPTSI', 'lptsi.its.ac.id', NULL, NULL, 4),
(2, 'Informatics', 'if.its.ac.id', NULL, NULL, 4);

-- --------------------------------------------------------

--
-- Table structure for table `root`
--

CREATE TABLE IF NOT EXISTS `root` (
`id_root` int(11) NOT NULL,
  `private_key` varchar(2000) NOT NULL,
  `ca_root` varchar(2000) NOT NULL,
  `public_key` varchar(2000) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `root`
--

INSERT INTO `root` (`id_root`, `private_key`, `ca_root`, `public_key`) VALUES
(1, '-----BEGIN RSA PRIVATE KEY----- \r\nMIICXQIBAAKBgQC7mMZThCsFDCqsmGFEBP01yTiOgAkAylqf+7tRSKLBzP1IqR4V vsCSL3k8inyCYxHzjhtVwBoLrLSm9xpdsu6Akwvr4zJs1PVIKxZHIQTi0CCMwwSS wg8eja25kldQBtX2voxhAChRVlScXWebLv96lcOzxdMIj2nHUwByUJIzIQIDAQAB AoGAc54RxVE0zlSUTHFRqQFGKYsNj027vr/4IJed99fDb5vuEoUgZJh+yNn3Z2eW mymB29CeajgOFVnosOqkVlE8CfR5Pl3swB9LBSSJ064VdqxfE7eOZs7e4qS11zsK So79UwJ4s9/0+nAzeELU/UWaFJmYJC8sXC2Wnah7HriIVVECQQDzY59ANLz1K4Z5 ibWPhV5WIgfR11KfB7SIJzMUVmxLJ8BPN1tWmHKed7dU+ANLmQhwYaA8Jrkj7LfT Mi0zNWZ9AkEAxVEbnf7vcCr6vr6+3E++Kp48zGcVOF8Ar5J6sqZ62w+Ywh8aJ8j8 Z4w4mSHEjhm8OIgwOmZSxEXuP845bfaMdQJBAIFPRXW0T0wmssxxyJ+W6Rbz/5mS P9g0HMtVoELG48ROO1MbAxEP752X1zRyjDWm+Z/TjnFG1YhrpQK1HuSdE3UCQQCg i9n4uBGCJrZWtUT+ZTPJ4W5+sfEmKMaaFIkZCzQzQYF9kWjqrjIQ3pq+nicbp/zp 0oXLPq5hXLT53YYE3vJxAkA2wnuVsHAIKTR6y5FS4XDGqBcb9zxr1afN6MRdS/bW fbeOi5z7CKHjxfJhBOgWtLGAtuQ2rifuBf3WdAdduK6k -----END RSA PRIVATE KEY-----', '-----BEGIN CERTIFICATE-----MIICfjCCAemgAwIBAgIBMTALBgkqhkiG9w0BAQUwgYcxETAPBgNVBAoMCFNQVUZGLUNBMQswCQYDVQQLDAJJVDELMAkGA1UEBgwCSUQxEjAQBgNVBAgMCUVhc3QgSmF2YTERMA8GA1UEBwwIU3VyYWJheWExEjAQBgNVBAMMCXNwdWZmLm5ldDEdMBsGCSqGSIb3DQEJAQwObWFpbEBzcHVmZi5uZXQwHhcNMTUwNTA2MTc1MzA3WhcNMTYwNTA2MTc1MzA3WjCBhzERMA8GA1UECgwIU1BVRkYtQ0ExCzAJBgNVBAsMAklUMQswCQYDVQQGDAJJRDESMBAGA1UECAwJRWFzdCBKYXZhMREwDwYDVQQHDAhTdXJhYmF5YTESMBAGA1UEAwwJc3B1ZmYubmV0MR0wGwYJKoZIhvcNAQkBDA5tYWlsQHNwdWZmLm5ldDCBnTALBgkqhkiG9w0BAQEDgY0AMIGJAoGBALuYxlOEKwUMKqyYYUQE/TXJOI6ACQDKWp/7u1FIosHM/UipHhW+wJIveTyKfIJjEfOOG1XAGgustKb3Gl2y7oCTC+vjMmzU9UgrFkchBOLQIIzDBJLCDx6NrbmSV1AG1fa+jGEAKFFWVJxdZ5su/3qVw7PF0wiPacdTAHJQkjMhAgMBAAEwCwYJKoZIhvcNAQEFA4GBAB+wc9vsLkId9OdWlmM5D1QvYgxl0FP5Tuzp26g3x/TUTwWPOmiw8XcfEGcFTnUDphml/A3bgnxPWU0bNADs44X6tkUGmC/CY6e5rOqGhCiAwWtRZMyajjP9yS7AvYIx0Dk98Jdd71h712xld+/TeH28Y9PiA9mHPTytQkeE4a3S-----END CERTIFICATE-----', '-----BEGIN PUBLIC KEY----- MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQC7mMZThCsFDCqsmGFEBP01yTiO gAkAylqf+7tRSKLBzP1IqR4VvsCSL3k8inyCYxHzjhtVwBoLrLSm9xpdsu6Akwvr 4zJs1PVIKxZHIQTi0CCMwwSSwg8eja25kldQBtX2voxhAChRVlScXWebLv96lcOz xdMIj2nHUwByUJIzIQIDAQAB -----END PUBLIC KEY-----');

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
MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `root`
--
ALTER TABLE `root`
MODIFY `id_root` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
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
