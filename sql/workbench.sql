-- phpMyAdmin SQL Dump
-- version 4.1.6
-- http://www.phpmyadmin.net
--
-- Machine: 10.0.0.142
-- Gegenereerd op: 27 jun 2015 om 13:30
-- Serverversie: 5.5.40-log
-- PHP-versie: 5.3.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Databank: `switchpro`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `cart`
--

CREATE TABLE IF NOT EXISTS `cart` (
  `session_id` varchar(40) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
  `user_data` varchar(2000) COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `field_defaults`
--

CREATE TABLE IF NOT EXISTS `field_defaults` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `value` varchar(255) COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=6 ;

--
-- Gegevens worden uitgevoerd voor tabel `field_defaults`
--

INSERT INTO `field_defaults` (`id`, `title`, `value`) VALUES
(1, 'status', 'active'),
(2, 'active', 'Y'),
(3, 'deleted', 'N'),
(4, 'createtime', 'CURRENT_TIMESTAMP'),
(5, 'updatetime', 'CURRENT_TIMESTAMP');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `page`
--

CREATE TABLE IF NOT EXISTS `page` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `desc` text COLLATE latin1_general_ci NOT NULL,
  `title` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `site_id` int(11) NOT NULL,
  `active` enum('Y','N') COLLATE latin1_general_ci NOT NULL DEFAULT 'Y',
  `deleted` enum('Y','N') COLLATE latin1_general_ci NOT NULL DEFAULT 'N',
  `createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updatetime` datetime NOT NULL,
  `page_id` int(11) NOT NULL,
  `require_login` enum('Y','N') COLLATE latin1_general_ci NOT NULL DEFAULT 'N',
  `menu_item` enum('Y','N') COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=10 ;

--
-- Gegevens worden uitgevoerd voor tabel `page`
--

INSERT INTO `page` (`id`, `status`, `desc`, `title`, `site_id`, `active`, `deleted`, `createtime`, `updatetime`, `page_id`, `require_login`, `menu_item`) VALUES
(1, 'active', 'Home', 'Home', 0, 'Y', 'N', '2015-05-13 11:56:45', '2015-05-13 13:56:45', 0, 'N', 'Y'),
(2, 'active', 'Page', 'Page', 0, 'Y', 'N', '2015-05-19 19:19:32', '2015-05-13 13:56:45', 0, 'Y', 'N'),
(6, 'active', 'Login', 'Login', 0, 'Y', 'N', '2015-05-13 11:56:45', '2015-05-13 13:56:45', 0, 'N', 'Y'),
(9, 'active', 'User_Group', 'User_Group', 0, 'Y', 'N', '2015-06-27 11:14:02', '2015-06-27 13:14:02', 0, 'Y', 'Y');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `portal`
--

CREATE TABLE IF NOT EXISTS `portal` (
  `id` int(11) NOT NULL,
  `status` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `desc` text COLLATE latin1_general_ci NOT NULL,
  `title` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `site_id` int(11) NOT NULL,
  `active` enum('Y','N') COLLATE latin1_general_ci NOT NULL DEFAULT 'Y',
  `deleted` enum('Y','N') COLLATE latin1_general_ci NOT NULL DEFAULT 'N',
  `createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updatetime` datetime NOT NULL,
  `portal_id` int(11) NOT NULL,
  `email` varchar(255) COLLATE latin1_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Gegevens worden uitgevoerd voor tabel `portal`
--

INSERT INTO `portal` (`id`, `status`, `desc`, `title`, `site_id`, `active`, `deleted`, `createtime`, `updatetime`, `portal_id`, `email`) VALUES
(1, 'active', 'Dit is het standaard portaal', 'ontwik', 0, 'Y', 'N', '2015-05-07 14:44:18', '2015-05-06 10:15:36', 0, 'ingmar1505@hotmail.com'),
(2, 'active', 'Dit is jts', 'jts_test', 0, 'Y', 'N', '2015-05-06 08:17:36', '2015-05-06 10:17:36', 0, ''),
(3, 'active', 'Portal van mascot', 'mascot', 3, 'Y', 'N', '2015-05-12 07:04:56', '2015-05-12 09:04:56', 0, 'ivh@mrwebsites.nl'),
(0, 'active', 'active', 'mascot_test', 0, 'Y', 'N', '2015-05-20 09:23:34', '2015-05-20 11:21:03', 0, '');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `user_group`
--

CREATE TABLE IF NOT EXISTS `user_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `desc` text COLLATE latin1_general_ci NOT NULL,
  `title` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `site_id` int(11) NOT NULL,
  `active` enum('Y','N') COLLATE latin1_general_ci NOT NULL DEFAULT 'Y',
  `deleted` enum('Y','N') COLLATE latin1_general_ci NOT NULL DEFAULT 'N',
  `createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updatetime` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=18 ;

--
-- Gegevens worden uitgevoerd voor tabel `user_group`
--

INSERT INTO `user_group` (`id`, `status`, `desc`, `title`, `site_id`, `active`, `deleted`, `createtime`, `updatetime`) VALUES
(15, 'active', 'gebruiker', 'gebruiker', 0, 'Y', 'N', '2015-05-22 12:49:13', '2015-05-22 14:49:13'),
(16, 'active', 'Manager', 'Manager', 0, 'Y', 'N', '2015-05-22 12:49:45', '2015-05-22 14:49:45'),
(17, 'active', 'Admin', 'Admin', 0, 'Y', 'N', '2015-05-22 12:49:54', '2015-05-22 14:49:54');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `user_group_enrolment`
--

CREATE TABLE IF NOT EXISTS `user_group_enrolment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `desc` text COLLATE latin1_general_ci NOT NULL,
  `title` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `site_id` int(11) NOT NULL,
  `active` enum('Y','N') COLLATE latin1_general_ci NOT NULL DEFAULT 'Y',
  `deleted` enum('Y','N') COLLATE latin1_general_ci NOT NULL DEFAULT 'N',
  `createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updatetime` datetime NOT NULL,
  `user_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=35 ;

--
-- Gegevens worden uitgevoerd voor tabel `user_group_enrolment`
--

INSERT INTO `user_group_enrolment` (`id`, `status`, `desc`, `title`, `site_id`, `active`, `deleted`, `createtime`, `updatetime`, `user_id`, `group_id`) VALUES
(18, 'active', '', '', 0, 'Y', 'N', '2015-06-11 12:15:35', '2015-06-11 14:15:35', 13, 15),
(32, 'active', '', '', 0, 'Y', 'N', '2015-06-12 12:18:06', '2015-06-12 14:18:06', 13, 16),
(33, 'active', '', '', 0, 'Y', 'N', '2015-06-12 12:24:27', '2015-06-12 14:24:27', 13, 17);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `user_login`
--

CREATE TABLE IF NOT EXISTS `user_login` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `username` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `password` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `site_id` int(11) NOT NULL,
  `active` enum('Y','N') COLLATE latin1_general_ci NOT NULL DEFAULT 'Y',
  `deleted` enum('Y','N') COLLATE latin1_general_ci NOT NULL DEFAULT 'N',
  `createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updatetime` datetime NOT NULL,
  `email` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `verifylink` varchar(255) COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=53 ;

--
-- Gegevens worden uitgevoerd voor tabel `user_login`
--

INSERT INTO `user_login` (`id`, `status`, `username`, `password`, `site_id`, `active`, `deleted`, `createtime`, `updatetime`, `email`, `verifylink`) VALUES
(13, 'active', '', '46c825f0a71db9ab46eb4bd549f1d971', 0, 'Y', 'N', '2015-05-08 07:19:02', '2015-05-08 09:18:02', 'martijn@mrwebsites.nl', ''),
(51, 'active', '', 'f3c8b7bf151eb0659fcbed422899414c', 0, 'Y', 'N', '2015-05-12 08:39:53', '2015-05-11 09:54:39', 'info@ivhdevelopment.com', 'd2239ef3a5c42eeb3a84a3ee211264e8');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
