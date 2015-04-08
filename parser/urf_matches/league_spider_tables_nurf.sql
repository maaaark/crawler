-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Erstellungszeit: 08. Apr 2015 um 11:35
-- Server Version: 5.6.21
-- PHP-Version: 5.6.3

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
-- Tabellenstruktur für Tabelle `lol_champions_stats_nurf`
--

CREATE TABLE IF NOT EXISTS `lol_champions_stats_nurf` (
`id` int(11) NOT NULL,
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
  `gold_spent` float NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=464 DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lol_champions_stats_nurf_bans`
--

CREATE TABLE IF NOT EXISTS `lol_champions_stats_nurf_bans` (
`id` int(11) NOT NULL,
  `champion` int(11) NOT NULL,
  `patch` varchar(20) COLLATE latin1_german1_ci NOT NULL,
  `bans` int(11) NOT NULL,
  `ban_1` int(11) NOT NULL,
  `ban_2` int(11) NOT NULL,
  `ban_3` int(11) NOT NULL,
  `ban_4` int(11) NOT NULL,
  `ban_5` int(11) NOT NULL,
  `ban_6` int(11) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=221 DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lol_league_parser_matches_nurf`
--

CREATE TABLE IF NOT EXISTS `lol_league_parser_matches_nurf` (
  `id` int(11) NOT NULL,
  `patch` varchar(20) COLLATE latin1_german1_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `lol_champions_stats_nurf`
--
ALTER TABLE `lol_champions_stats_nurf`
 ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `lol_champions_stats_nurf_bans`
--
ALTER TABLE `lol_champions_stats_nurf_bans`
 ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `lol_league_parser_matches_nurf`
--
ALTER TABLE `lol_league_parser_matches_nurf`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `lol_champions_stats_nurf`
--
ALTER TABLE `lol_champions_stats_nurf`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=464;
--
-- AUTO_INCREMENT für Tabelle `lol_champions_stats_nurf_bans`
--
ALTER TABLE `lol_champions_stats_nurf_bans`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=221;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
