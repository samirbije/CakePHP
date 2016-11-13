-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 13, 2016 at 12:25 PM
-- Server version: 5.6.33
-- PHP Version: 7.0.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `db_techtest`
--

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

CREATE TABLE `contact` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `organization` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `reason` varchar(255) NOT NULL,
  `specify` text NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `contact`
--

INSERT INTO `contact` (`id`, `name`, `last_name`, `organization`, `email`, `text`, `reason`, `specify`, `created`) VALUES
(1, 'h', 'l', 'o', 'hggg@hhhh.com', 't', 'Other', 'hhghghgh', '0000-00-00 00:00:00'),
(2, 'h', 'l', 'o', 'hggg@hhhh.com', 't', 'Other', 'ddd', '2016-11-13 03:37:54'),
(3, 'n', 'l', 'o', 'cvvvvv@ffff.com', 'n', 'Other', 'ffff', '2016-11-13 04:30:38'),
(4, 'h', 'i', 'hgggggh', 'ghgghggg@ggggg.com', 'h', 'Other', 'gggg', '2016-11-13 07:21:22'),
(5, 'h', 'h', 'h', 'hggg@hhhh.com', 'hq', 'Help', '', '2016-11-13 08:06:02');

-- --------------------------------------------------------

--
-- Table structure for table `test`
--

CREATE TABLE `test` (
  `foobar` enum('1','2') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `password`, `email`, `created`, `modified`) VALUES
(1, 'Sam', '$2y$10$YE9LAXniBT/M37IIez3LDOG1cbSpRdc0vSEPImEfCxpsZ2g/2b1FC', 'samir@otech.ne.jp', NULL, '2016-11-11 07:15:42'),
(2, 'yyyy', '$2y$10$qJlOdb3Jmpkf0TQdE4mB.O4qVDmsPIGTgvHIDKkCaayfY.fUbX6gy', 'samiry@otech.ne.jp', '2016-11-13 08:27:33', '2016-11-13 08:41:36');

