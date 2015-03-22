-- phpMyAdmin SQL Dump
-- version 4.1.12
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Mar 22, 2015 at 03:27 PM
-- Server version: 5.5.35-1ubuntu1
-- PHP Version: 5.4.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `zend_loc`
--
DROP DATABASE `zend_loc`;
CREATE DATABASE IF NOT EXISTS `zend_loc` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `zend_loc`;

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

DROP TABLE IF EXISTS `clients`;
CREATE TABLE IF NOT EXISTS `clients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fio` varchar(255) NOT NULL,
  `service_id` int(11) NOT NULL,
  `diagnosis` text NOT NULL,
  `contacts` varchar(255) NOT NULL,
  `dos` datetime NOT NULL,
  `status` enum('Не обработан','В работе','Согласование','Архив','Пролечен','Записан в календарь','Сорвался') NOT NULL,
  `comments` text NOT NULL,
  `country` enum('Беларусь','Россия','Украина','Казахстан') NOT NULL,
  `contact_type` varchar(255) NOT NULL,
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
  KEY `manager` (`manager_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`id`, `fio`, `service_id`, `diagnosis`, `contacts`, `dos`, `status`, `comments`, `country`, `contact_type`, `attachments`, `clinic_id`, `doctor_id`, `conclusion`, `payment`, `date_added`, `manager_id`, `informed`) VALUES
(1, 'Ð²Ð°ÑÑ Ð¿ÑƒÐ¿ÐºÐ¸Ð½', 3, 'Ð±Ð¾Ð»Ð¸Ñ‚ Ð½Ð¾Ð³Ð°', 'Ð¹Ñ†Ñƒ', '2015-03-22 00:00:00', '', 'ÑƒÑƒÑƒÑƒÑƒÑƒÑƒÑƒÑƒÑƒ', '', '123', 'a:1:{i:0;s:47:"uploads/Screenshot_from_2014-12-31_08:00:00.png";}', 2, 4, '', '', '2015-03-22 00:00:00', 1, 0),
(2, 'asdqwd', 4, 'qwdqwd', 'qwdqwd', '2015-03-22 15:18:17', '', '12e12', '', 'e1212e', 'a:1:{i:0;s:47:"uploads/Screenshot_from_2014-12-17_05:31:59.png";}', 1, 2, '', 'zzzzz', '2015-03-22 15:18:17', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `clinic`
--

DROP TABLE IF EXISTS `clinic`;
CREATE TABLE IF NOT EXISTS `clinic` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `clinic`
--

INSERT INTO `clinic` (`id`, `name`) VALUES
(1, 'Нордин'),
(2, 'Экомедсервис');

-- --------------------------------------------------------

--
-- Table structure for table `doctor`
--

DROP TABLE IF EXISTS `doctor`;
CREATE TABLE IF NOT EXISTS `doctor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `clinic_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `clinic_id` (`clinic_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `doctor`
--

INSERT INTO `doctor` (`id`, `name`, `clinic_id`) VALUES
(1, 'Пушкин', 1),
(2, 'Гоголь', 1),
(3, 'Лермонтов', 2),
(4, 'Тургенев', 2);

-- --------------------------------------------------------

--
-- Table structure for table `permission`
--

DROP TABLE IF EXISTS `permission`;
CREATE TABLE IF NOT EXISTS `permission` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `permission`
--

INSERT INTO `permission` (`id`, `name`) VALUES
(1, 'view'),
(2, 'edit'),
(3, 'add');

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

DROP TABLE IF EXISTS `role`;
CREATE TABLE IF NOT EXISTS `role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`),
  KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`id`, `name`, `parent_id`) VALUES
(1, 'admin', NULL),
(2, 'manager', 1);

-- --------------------------------------------------------

--
-- Table structure for table `role_permission`
--

DROP TABLE IF EXISTS `role_permission`;
CREATE TABLE IF NOT EXISTS `role_permission` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `perm_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `role_id` (`role_id`),
  KEY `perm_id` (`perm_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `role_permission`
--

INSERT INTO `role_permission` (`id`, `role_id`, `perm_id`) VALUES
(3, 1, 3),
(5, 2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `service`
--

DROP TABLE IF EXISTS `service`;
CREATE TABLE IF NOT EXISTS `service` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

--
-- Dumping data for table `service`
--

INSERT INTO `service` (`id`, `name`) VALUES
(1, 'Эндокринология'),
(2, 'Хирургия (травматология)'),
(3, 'Хирургия (общая)'),
(4, 'Нефрология'),
(5, 'Пластика'),
(6, 'Кардиология'),
(7, 'Урология'),
(8, 'Стоматология'),
(9, 'Реабилитация'),
(10, 'Офтальмология'),
(11, 'ЛОР'),
(12, 'Диагностика'),
(13, 'Гинекология'),
(14, 'Сурр. материнство');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `display_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `role` (`role_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `display_name`, `email`, `password`, `role_id`) VALUES
(1, 'A', 'admin@admin.com', '63076276ad8c688f7c50c68f45d285c1', 1);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `clients`
--
ALTER TABLE `clients`
  ADD CONSTRAINT `clients_ibfk_1` FOREIGN KEY (`service_id`) REFERENCES `service` (`id`);

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
