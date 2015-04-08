-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 07. Apr 2015 um 16:36
-- Server Version: 5.5.40-0ubuntu0.14.04.1
-- PHP-Version: 5.5.9-1ubuntu4.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Datenbank: `nurf`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lol_champions_stats`
--

CREATE TABLE IF NOT EXISTS `lol_champions_stats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `champion` int(11) NOT NULL,
  `patch` varchar(20) COLLATE latin1_german1_ci NOT NULL,
  `season` varchar(20) COLLATE latin1_german1_ci NOT NULL,
  `matches_count` int(11) NOT NULL,
  `wins` float NOT NULL,
  `kills` float NOT NULL,
  `deaths` float NOT NULL,
  `assists` float NOT NULL,
  `lasthits` float NOT NULL,
  `lasthits_jungle` float NOT NULL,
  `lasthits_jungle_team` float NOT NULL,
  `lasthits_jungle_enemy` float NOT NULL,
  `gold_earned` float NOT NULL,
  `gold_spent` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci AUTO_INCREMENT=464 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lol_champions_stats_bans`
--

CREATE TABLE IF NOT EXISTS `lol_champions_stats_bans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `champion` int(11) NOT NULL,
  `patch` varchar(20) COLLATE latin1_german1_ci NOT NULL,
  `bans` int(11) NOT NULL,
  `ban_1` int(11) NOT NULL,
  `ban_2` int(11) NOT NULL,
  `ban_3` int(11) NOT NULL,
  `ban_4` int(11) NOT NULL,
  `ban_5` int(11) NOT NULL,
  `ban_6` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci AUTO_INCREMENT=221 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lol_league_parser_matches`
--

CREATE TABLE IF NOT EXISTS `lol_league_parser_matches` (
  `id` int(11) NOT NULL,
  `patch` varchar(20) COLLATE latin1_german1_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lol_league_parser_summoner`
--

CREATE TABLE IF NOT EXISTS `lol_league_parser_summoner` (
  `id` int(11) NOT NULL,
  `last_update` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
