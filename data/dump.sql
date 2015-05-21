-- phpMyAdmin SQL Dump
-- version 4.1.12
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: May 21, 2015 at 09:22 PM
-- Server version: 5.5.35-1ubuntu1
-- PHP Version: 5.4.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `zend_loc`
--

-- --------------------------------------------------------

--
-- Table structure for table `calendar`
--

CREATE TABLE IF NOT EXISTS `calendar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text,
  `date` date NOT NULL,
  `client_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `client` (`client_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=22 ;

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE IF NOT EXISTS `clients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fio` varchar(255) NOT NULL,
  `service_id` int(11) NOT NULL,
  `diagnosis` text NOT NULL,
  `contacts` varchar(255) NOT NULL,
  `dos` datetime NOT NULL,
  `status` enum('Не обработан','В работе','Согласование','Архив','Пролечен','Записан в календарь','Сорвался') NOT NULL,
  `comments` text NOT NULL,
  `country_id` int(11) NOT NULL,
  `next_contact_date` datetime DEFAULT NULL,
  `next_contact_comment` varchar(255) DEFAULT NULL,
  `attachments` text NOT NULL,
  `clinic_id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `conclusion` varchar(255) NOT NULL,
  `payment` varchar(255) NOT NULL,
  `date_added` datetime NOT NULL,
  `manager_id` int(11) NOT NULL,
  `informed` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `service_id` (`service_id`),
  KEY `clinic` (`clinic_id`),
  KEY `doctor` (`doctor_id`),
  KEY `manager` (`manager_id`),
  KEY `country` (`country_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Table structure for table `clinic`
--

CREATE TABLE IF NOT EXISTS `clinic` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `country`
--

CREATE TABLE IF NOT EXISTS `country` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(127) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `doctor`
--

CREATE TABLE IF NOT EXISTS `doctor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `clinic_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `clinic_id` (`clinic_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Table structure for table `permission`
--

CREATE TABLE IF NOT EXISTS `permission` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE IF NOT EXISTS `role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`),
  KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `role_permission`
--

CREATE TABLE IF NOT EXISTS `role_permission` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `perm_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `role_id` (`role_id`),
  KEY `perm_id` (`perm_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `service`
--

CREATE TABLE IF NOT EXISTS `service` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `display_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `role` (`role_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `calendar`
--
ALTER TABLE `calendar`
ADD CONSTRAINT `calendar_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `clients`
--
ALTER TABLE `clients`
ADD CONSTRAINT `clients_ibfk_1` FOREIGN KEY (`service_id`) REFERENCES `service` (`id`),
ADD CONSTRAINT `clients_ibfk_2` FOREIGN KEY (`clinic_id`) REFERENCES `clinic` (`id`),
ADD CONSTRAINT `clients_ibfk_3` FOREIGN KEY (`doctor_id`) REFERENCES `doctor` (`id`),
ADD CONSTRAINT `clients_ibfk_4` FOREIGN KEY (`manager_id`) REFERENCES `user` (`id`),
ADD CONSTRAINT `clients_ibfk_5` FOREIGN KEY (`country_id`) REFERENCES `country` (`id`);

--
-- Constraints for table `doctor`
--
ALTER TABLE `doctor`
ADD CONSTRAINT `doctor_ibfk_1` FOREIGN KEY (`clinic_id`) REFERENCES `clinic` (`id`);

--
-- Constraints for table `role`
--
ALTER TABLE `role`
ADD CONSTRAINT `role_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `role` (`id`);

--
-- Constraints for table `role_permission`
--
ALTER TABLE `role_permission`
ADD CONSTRAINT `role_permission_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`),
ADD CONSTRAINT `role_permission_ibfk_2` FOREIGN KEY (`perm_id`) REFERENCES `permission` (`id`);

--
-- Constraints for table `user`
--
ALTER TABLE `user`
ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`);
