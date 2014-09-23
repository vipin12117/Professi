-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 22, 2014 at 06:37 PM
-- Server version: 5.5.8
-- PHP Version: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `professi`
--

-- --------------------------------------------------------

--
-- Table structure for table `preview_requests`
--

CREATE TABLE IF NOT EXISTS `preview_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL,
  `post_meta_id` int(11) NOT NULL,
  `guid` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `dateofmodification` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `preview_requests`
--

INSERT INTO `preview_requests` (`id`, `post_id`, `post_meta_id`, `guid`, `status`, `dateofmodification`) VALUES
(2, 96, 284, '{FC23FA50-B2D5-28BA-7428-D4C375C8E967}', 0, '2014-09-21 14:03:23');
