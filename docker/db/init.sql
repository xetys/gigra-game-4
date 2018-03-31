-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Host: mysql
-- Erstellungszeit: 09. Apr 2018 um 22:32
-- Server-Version: 5.5.59
-- PHP-Version: 7.2.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Datenbank: `gigra`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `allianz`
--

CREATE TABLE IF NOT EXISTS `allianz` (
  `id` varchar(10) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `tag` varchar(10) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `name` varchar(35) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `stati` text CHARACTER SET latin1 NOT NULL,
  `logo` varchar(255) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `text` text CHARACTER SET latin1 NOT NULL,
  `hp` varchar(255) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `founder` varchar(255) NOT NULL,
  `founderName` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `allianzforum`
--

CREATE TABLE IF NOT EXISTS `allianzforum` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ally_id` varchar(10) NOT NULL,
  `uid` varchar(10) NOT NULL,
  `type` enum('topic','post') NOT NULL,
  `topic` int(11) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `post_time` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `allianzmember`
--

CREATE TABLE IF NOT EXISTS `allianzmember` (
  `id` char(5) NOT NULL DEFAULT '',
  `status` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `new_forum_posts` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `allianzrecht`
--

CREATE TABLE IF NOT EXISTS `allianzrecht` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `aid` varchar(255) NOT NULL,
  `rang` varchar(255) NOT NULL,
  `recht_memberlist` tinyint(1) NOT NULL DEFAULT '0',
  `recht_rundmail` tinyint(1) NOT NULL DEFAULT '0',
  `recht_admin` tinyint(1) NOT NULL DEFAULT '0',
  `recht_delete_ally` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `bauschleife`
--

CREATE TABLE IF NOT EXISTS `bauschleife` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `coords` varchar(12) NOT NULL DEFAULT '',
  `sid` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `count` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `time` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `rest` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `typ` enum('prod','vert') NOT NULL DEFAULT 'prod',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `bericht`
--

CREATE TABLE IF NOT EXISTS `bericht` (
  `id` varchar(50) NOT NULL DEFAULT '',
  `time` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `fromc` varchar(12) NOT NULL DEFAULT '',
  `toc` varchar(12) NOT NULL DEFAULT '',
  `b` mediumblob NOT NULL,
  `typ` enum('kampf','spio') NOT NULL,
  `a_lost` bigint(30) NOT NULL,
  `v_lost` bigint(30) NOT NULL,
  `is_public` tinyint(1) NOT NULL DEFAULT '0',
  `hauptkommentar` text NOT NULL,
  `winner` char(11) DEFAULT NULL,
  `publisher` varchar(255) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `bericht_kommentar`
--

CREATE TABLE IF NOT EXISTS `bericht_kommentar` (
  `kom_id` int(11) NOT NULL AUTO_INCREMENT,
  `kom_name` varchar(255) NOT NULL,
  `kom_time` bigint(20) NOT NULL,
  `kom_bericht` varchar(255) NOT NULL,
  `kom_text` text NOT NULL,
  PRIMARY KEY (`kom_id`),
  KEY `kom_bericht` (`kom_bericht`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `bericht_recht`
--

CREATE TABLE IF NOT EXISTS `bericht_recht` (
  `user_id` varchar(255) NOT NULL,
  `bericht_id` varchar(255) NOT NULL,
  KEY `user_id` (`user_id`),
  KEY `bericht_id` (`bericht_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `bewerbungen`
--

CREATE TABLE IF NOT EXISTS `bewerbungen` (
  `uid` varchar(5) NOT NULL DEFAULT '',
  `aid` varchar(10) NOT NULL DEFAULT '',
  `time` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `text` text NOT NULL,
  KEY `aid` (`aid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `bugs`
--

CREATE TABLE IF NOT EXISTS `bugs` (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `subj` varchar(255) NOT NULL DEFAULT '',
  `beschr` text NOT NULL,
  `status` enum('open','fixed','nab','nmi','loc') NOT NULL DEFAULT 'open',
  `uid` varchar(5) NOT NULL DEFAULT '',
  `time` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `typ` enum('post','ans') NOT NULL DEFAULT 'post',
  `fuer_adm` text NOT NULL,
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `diplomatie`
--

CREATE TABLE IF NOT EXISTS `diplomatie` (
  `a_typ` enum('s','a') NOT NULL,
  `a_id` varchar(255) NOT NULL,
  `b_typ` enum('s','a') NOT NULL,
  `b_id` varchar(255) NOT NULL,
  `diplotyp` enum('bnd','nap','war') NOT NULL,
  `begin` int(20) NOT NULL,
  `end` int(20) NOT NULL,
  `status` tinyint(4) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `einstellungen`
--

CREATE TABLE IF NOT EXISTS `einstellungen` (
  `uid` varchar(5) NOT NULL DEFAULT '',
  `skinpfad` varchar(255) NOT NULL DEFAULT '',
  `baumsg` tinyint(4) NOT NULL DEFAULT '1',
  `spioanz` int(11) NOT NULL DEFAULT '1',
  KEY `id` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `einstellungen`
--

INSERT INTO `einstellungen` (`uid`, `skinpfad`, `baumsg`, `spioanz`) VALUES
('admin', '', 1, 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `erfahrung`
--

CREATE TABLE IF NOT EXISTS `erfahrung` (
  `uid` varchar(255) NOT NULL,
  `infra` int(10) NOT NULL,
  `krieg` int(10) NOT NULL,
  `forsch` int(10) NOT NULL,
  `ehrenpunkte` int(10) NOT NULL,
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `erfahrung`
--

INSERT INTO `erfahrung` (`uid`, `infra`, `krieg`, `forsch`, `ehrenpunkte`) VALUES
('admin', 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `events`
--

CREATE TABLE IF NOT EXISTS `events` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `coords` varchar(11) NOT NULL DEFAULT '',
  `uid` varchar(5) NOT NULL DEFAULT '',
  `starttime` bigint(20) NOT NULL,
  `time` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `command` text NOT NULL,
  `param` text NOT NULL,
  `prio` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `time` (`time`,`prio`),
  KEY `coords` (`coords`),
  KEY `uid` (`uid`),
  KEY `starttime` (`starttime`),
  KEY `time_2` (`time`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `flotten`
--

CREATE TABLE IF NOT EXISTS `flotten` (
  `id` varchar(12) NOT NULL DEFAULT '',
  `userid` varchar(5) NOT NULL DEFAULT '',
  `fromc` varchar(12) NOT NULL DEFAULT '',
  `toc` varchar(12) NOT NULL DEFAULT '',
  `typ` enum('ag','ag_p','kolo','trans','vert','stat','recy','asteroid','inva','spio','dest','hold','aks_lead','aks') NOT NULL DEFAULT 'ag_p',
  `tthere` int(11) NOT NULL DEFAULT '0',
  `tback` int(11) NOT NULL DEFAULT '0',
  `schiffe` text NOT NULL,
  `respref` char(3) NOT NULL DEFAULT '',
  `load1` bigint(20) NOT NULL DEFAULT '0',
  `load2` bigint(20) NOT NULL DEFAULT '0',
  `load3` bigint(20) NOT NULL DEFAULT '0',
  `load4` bigint(20) NOT NULL DEFAULT '0',
  `thold` int(11) NOT NULL DEFAULT '0',
  `parentfleet` varchar(255) NOT NULL,
  `flytime` bigint(20) NOT NULL,
  `tsee` bigint(20) NOT NULL DEFAULT '0',
  `fuel` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `toc` (`toc`),
  KEY `fromc` (`fromc`),
  KEY `userid` (`userid`),
  KEY `tthere` (`tthere`),
  KEY `tback` (`tback`),
  KEY `parentfleet` (`parentfleet`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `forschung`
--

CREATE TABLE IF NOT EXISTS `forschung` (
  `uid` varchar(5) NOT NULL DEFAULT '',
  `f` text NOT NULL,
  `punkte` int(10) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `forschung`
--

INSERT INTO `forschung` (`uid`, `f`, `punkte`) VALUES
('admin', '', 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `gebaeude`
--

CREATE TABLE IF NOT EXISTS `gebaeude` (
  `coords` char(12) NOT NULL DEFAULT '',
  `k1` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `k2` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `k3` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `k4` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `k5` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `k6` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `k7` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `k8` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `k9` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `k10` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `k11` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `k12` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `k13` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `k14` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `k15` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `k16` int(11) NOT NULL DEFAULT '0',
  `k17` int(11) NOT NULL DEFAULT '0',
  `k18` int(11) NOT NULL DEFAULT '0',
  `k19` int(11) NOT NULL DEFAULT '0',
  `k20` int(11) NOT NULL DEFAULT '0',
  `k21` int(11) NOT NULL DEFAULT '0',
  UNIQUE KEY `coords` (`coords`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `gebaeude`
--

INSERT INTO `gebaeude` (`coords`, `k1`, `k2`, `k3`, `k4`, `k5`, `k6`, `k7`, `k8`, `k9`, `k10`, `k11`, `k12`, `k13`, `k14`, `k15`, `k16`, `k17`, `k18`, `k19`, `k20`, `k21`) VALUES
('1:1:10:3', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
('1:1:10:1', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `itemqueue`
--

CREATE TABLE IF NOT EXISTS `itemqueue` (
  `coords` varchar(100) NOT NULL,
  `s` text,
  `v` text,
  `r1` bigint(20) NOT NULL DEFAULT '0',
  `r2` bigint(20) NOT NULL DEFAULT '0',
  `r3` bigint(20) NOT NULL DEFAULT '0',
  `r4` bigint(20) NOT NULL DEFAULT '0',
  KEY `coords` (`coords`),
  KEY `r1` (`r1`,`r2`,`r3`,`r4`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `loeschen`
--

CREATE TABLE IF NOT EXISTS `loeschen` (
  `uid` char(5) NOT NULL DEFAULT '',
  `time` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `code` char(12) NOT NULL DEFAULT '',
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `log`
--

CREATE TABLE IF NOT EXISTS `log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` varchar(5) NOT NULL DEFAULT '',
  `time` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `entry` text NOT NULL,
  `done` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `maxplanets`
--

CREATE TABLE IF NOT EXISTS `maxplanets` (
  `sys` char(7) NOT NULL DEFAULT '',
  `maxp` smallint(5) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`sys`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `maxplanets`
--

INSERT INTO `maxplanets` (`sys`, `maxp`) VALUES
('1:1', 10);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `msg`
--

CREATE TABLE IF NOT EXISTS `msg` (
  `msg_id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` varchar(5) NOT NULL DEFAULT '',
  `id` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `coords` varchar(12) NOT NULL DEFAULT '',
  `fromuid` varchar(5) NOT NULL DEFAULT '',
  `mode` enum('cmd','text') NOT NULL DEFAULT 'text',
  `subj` varchar(72) NOT NULL DEFAULT '',
  `text` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `red` enum('yes','no') NOT NULL DEFAULT 'no',
  `deleted` tinyint(4) NOT NULL DEFAULT '0',
  `ordner` varchar(10) NOT NULL DEFAULT '''other''',
  PRIMARY KEY (`msg_id`),
  KEY `time` (`time`),
  KEY `userid` (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `news`
--

CREATE TABLE IF NOT EXISTS `news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `news_datum` int(20) NOT NULL,
  `news_titel` varchar(255) NOT NULL,
  `news_text` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `optionen`
--

CREATE TABLE IF NOT EXISTS `optionen` (
  `uid` varchar(5) NOT NULL DEFAULT '',
  `opt` text NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `partei`
--

CREATE TABLE IF NOT EXISTS `partei` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parteiname` varchar(255) NOT NULL,
  `parteikurz` varchar(10) NOT NULL,
  `farbe` varchar(255) NOT NULL,
  `parteitext` text NOT NULL,
  `chef` varchar(50) NOT NULL,
  `kanzler` varchar(50) NOT NULL,
  `handelm` varchar(50) NOT NULL,
  `kriegm` varchar(50) NOT NULL,
  `flottenmacht` double(2,2) NOT NULL,
  `kandidat` tinyint(4) NOT NULL DEFAULT '1',
  `zugelassen` tinyint(4) NOT NULL DEFAULT '0',
  `stimmen` bigint(20) NOT NULL,
  `gruenddat` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `planets`
--

CREATE TABLE IF NOT EXISTS `planets` (
  `coords` varchar(12) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `owner` varchar(5) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `pname` varchar(63) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `temp` int(11) NOT NULL DEFAULT '0',
  `dia` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `pbild` varchar(63) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `punkte` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `destructed` tinyint(4) NOT NULL DEFAULT '0',
  `bau_percent` int(11) NOT NULL DEFAULT '0',
  `bau_until` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`coords`),
  KEY `owner` (`owner`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `planets`
--

INSERT INTO `planets` (`coords`, `owner`, `pname`, `temp`, `dia`, `pbild`, `punkte`, `destructed`, `bau_percent`, `bau_until`) VALUES
('1:1:10:3', '0', 'TF', 254, 0, 'planet10.png', 0, 0, 0, 0),
('1:1:10:1', 'admin', 'Gigrania', 434, 2079, 'gigrania.png', 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `produktion`
--

CREATE TABLE IF NOT EXISTS `produktion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `coords` varchar(12) NOT NULL DEFAULT '',
  `pos` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `sid` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `count` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `typ` enum('S','V') NOT NULL,
  `bauzeit` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `ptime` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `typ` (`typ`),
  KEY `pos` (`pos`),
  KEY `coords` (`coords`),
  KEY `bauzeit` (`bauzeit`),
  KEY `ptime` (`ptime`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `rang`
--

CREATE TABLE IF NOT EXISTS `rang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rangname` varchar(255) NOT NULL,
  `bild` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `rohstoffe`
--

CREATE TABLE IF NOT EXISTS `rohstoffe` (
  `coords` char(12) NOT NULL DEFAULT '',
  `r1` double UNSIGNED NOT NULL DEFAULT '0',
  `r2` double UNSIGNED NOT NULL DEFAULT '0',
  `r3` double UNSIGNED NOT NULL DEFAULT '0',
  `r4` double UNSIGNED NOT NULL DEFAULT '0',
  `e_used` int(11) NOT NULL,
  `e_all` int(11) NOT NULL,
  `u1` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `tf1` double UNSIGNED NOT NULL DEFAULT '0',
  `tf2` double UNSIGNED NOT NULL DEFAULT '0',
  `tf3` double UNSIGNED NOT NULL DEFAULT '0',
  `tf4` double UNSIGNED NOT NULL DEFAULT '0',
  `boost_percent` int(11) NOT NULL DEFAULT '10',
  `boost_until` int(20) NOT NULL,
  `prod1` int(3) NOT NULL DEFAULT '10',
  `prod2` int(3) NOT NULL DEFAULT '10',
  `prod3` int(3) NOT NULL DEFAULT '10',
  `prod4` int(11) NOT NULL DEFAULT '10',
  `prod5` int(11) NOT NULL DEFAULT '10',
  `recalc` tinyint(4) NOT NULL DEFAULT '1',
  `capa1` bigint(20) NOT NULL,
  `capa2` bigint(20) NOT NULL,
  `capa3` bigint(20) NOT NULL,
  `capa4` bigint(20) NOT NULL,
  `mine1` bigint(20) NOT NULL,
  `mine2` bigint(20) NOT NULL,
  `mine3` bigint(20) NOT NULL,
  `mine4` bigint(20) NOT NULL,
  `mine5` bigint(20) NOT NULL,
  PRIMARY KEY (`coords`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `rohstoffe`
--

INSERT INTO `rohstoffe` (`coords`, `r1`, `r2`, `r3`, `r4`, `e_used`, `e_all`, `u1`, `tf1`, `tf2`, `tf3`, `tf4`, `boost_percent`, `boost_until`, `prod1`, `prod2`, `prod3`, `prod4`, `prod5`, `recalc`, `capa1`, `capa2`, `capa3`, `capa4`, `mine1`, `mine2`, `mine3`, `mine4`, `mine5`) VALUES
('1:1:10:3', 0, 0, 0, 0, 0, 0, 1523312090, 0, 0, 0, 0, 10, 0, 10, 10, 10, 10, 10, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0),
('1:1:10:1', 5010.1333333333, 5010.1333333333, 5010.1333333333, 0, 0, 0, 1523313002, 0, 0, 0, 0, 0, 0, 10, 10, 10, 10, 10, 0, 200000, 200000, 200000, 200000, 40, 40, 40, 0, 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `schiffe`
--

CREATE TABLE IF NOT EXISTS `schiffe` (
  `coords` varchar(12) NOT NULL DEFAULT '',
  `s` text NOT NULL,
  `punkte` int(20) NOT NULL,
  `anzahl` int(20) NOT NULL,
  PRIMARY KEY (`coords`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `schiffe`
--

INSERT INTO `schiffe` (`coords`, `s`, `punkte`, `anzahl`) VALUES
('1:1:10:3', '', 0, 0),
('1:1:10:1', '', 0, 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `skills`
--

CREATE TABLE IF NOT EXISTS `skills` (
  `uid` varchar(255) NOT NULL,
  `krieg_flugzeit` int(11) NOT NULL DEFAULT '0',
  `krieg_treffer` int(11) NOT NULL DEFAULT '0',
  `infra_planeten` int(11) NOT NULL DEFAULT '0',
  `infra_rohstoff` int(11) NOT NULL DEFAULT '0',
  `infra_bauzeit` int(11) NOT NULL DEFAULT '0',
  `forsch_zeit` int(11) NOT NULL DEFAULT '0',
  `forsch_geheimschiff1` int(11) NOT NULL DEFAULT '0',
  `forsch_geheimschiff2` int(11) NOT NULL DEFAULT '0',
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `skills`
--

INSERT INTO `skills` (`uid`, `krieg_flugzeit`, `krieg_treffer`, `infra_planeten`, `infra_rohstoff`, `infra_bauzeit`, `forsch_zeit`, `forsch_geheimschiff1`, `forsch_geheimschiff2`) VALUES
('', 0, 0, 0, 0, 0, 0, 0, 0),
('admin', 0, 0, 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `targets`
--

CREATE TABLE IF NOT EXISTS `targets` (
  `uid` varchar(5) NOT NULL DEFAULT '',
  `coords` varchar(12) NOT NULL DEFAULT '',
  `comment` varchar(128) NOT NULL DEFAULT '',
  PRIMARY KEY (`uid`,`coords`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tutorial`
--

CREATE TABLE IF NOT EXISTS `tutorial` (
  `onpage` varchar(255) NOT NULL,
  `jquery_path` varchar(400) NOT NULL,
  `html` text NOT NULL,
  `prio` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` char(5) NOT NULL DEFAULT '',
  `name` char(115) NOT NULL DEFAULT '',
  `pw` char(15) NOT NULL DEFAULT '',
  `email` char(64) NOT NULL DEFAULT '',
  `allianz` char(10) NOT NULL DEFAULT '',
  `mainplanet` char(12) NOT NULL DEFAULT '',
  `lastlogin` int(4) UNSIGNED NOT NULL DEFAULT '0',
  `admin` tinyint(4) NOT NULL DEFAULT '0',
  `lastip` varchar(255) NOT NULL,
  `lastclick` bigint(20) NOT NULL,
  `lastpage` varchar(500) NOT NULL,
  `lastqry` varchar(1000) NOT NULL,
  `registertime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `umod` bigint(20) NOT NULL,
  `lastnamechange` bigint(20) NOT NULL,
  `lastmailchange` bigint(20) NOT NULL,
  `werberid` varchar(255) NOT NULL,
  `werbestatus` tinyint(4) NOT NULL DEFAULT '0',
  `active` tinyint(4) NOT NULL DEFAULT '0',
  `donator` tinyint(4) NOT NULL DEFAULT '0',
  `registered_multi` tinyint(4) NOT NULL DEFAULT '0',
  `lastsession` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `lastclick` (`lastclick`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `users`
--

INSERT INTO `users` (`id`, `name`, `pw`, `email`, `allianz`, `mainplanet`, `lastlogin`, `admin`, `lastip`, `lastclick`, `lastpage`, `lastqry`, `registertime`, `umod`, `lastnamechange`, `lastmailchange`, `werberid`, `werbestatus`, `active`, `donator`, `registered_multi`, `lastsession`) VALUES
('admin', 'admin', 'admin', 'admin@admin', '', '1:1:10:1', 1523312874, 1, '', 1523312987, '/v3.php', '', '2018-04-05 00:00:42', 0, 0, 0, '', 0, 1, 0, 0, '');

--
-- Trigger `users`
--
DELIMITER $$
CREATE TRIGGER `tri_ad_users` AFTER DELETE ON `users` FOR EACH ROW BEGIN
   DELETE FROM forschung WHERE uid = old.id;
   DELETE FROM erfahrung WHERE uid = old.id;
   DELETE FROM user_punkte WHERE uid = old.id;
   DELETE FROM flotten WHERE userid = old.id;
   UPDATE planets SET owner = '0' WHERE owner = old.id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user_chronik`
--

CREATE TABLE IF NOT EXISTS `user_chronik` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` varchar(255) NOT NULL,
  `ctime` bigint(20) NOT NULL,
  `wasActive` tinyint(4) NOT NULL DEFAULT '0',
  `punkte` bigint(20) NOT NULL,
  `rank` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user_desc`
--

CREATE TABLE IF NOT EXISTS `user_desc` (
  `uid` varchar(5) NOT NULL DEFAULT '',
  `text` text NOT NULL,
  UNIQUE KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user_gigron`
--

CREATE TABLE IF NOT EXISTS `user_gigron` (
  `uid` varchar(50) NOT NULL,
  `gigron_found` bigint(20) NOT NULL,
  `gigron_buyed` bigint(20) NOT NULL,
  `items` text NOT NULL,
  `forsch_percent` int(11) NOT NULL,
  `forsch_until` bigint(20) NOT NULL,
  `kampf_percent` int(11) NOT NULL,
  `kampf_until` bigint(20) NOT NULL,
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `user_gigron`
--

INSERT INTO `user_gigron` (`uid`, `gigron_found`, `gigron_buyed`, `items`, `forsch_percent`, `forsch_until`, `kampf_percent`, `kampf_until`) VALUES
('admin', 0, 0, '', 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user_gvf`
--

CREATE TABLE IF NOT EXISTS `user_gvf` (
  `uid` char(20) NOT NULL,
  `good_points` int(11) NOT NULL,
  `bad_points` int(11) NOT NULL,
  `valid_until` bigint(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user_punkte`
--

CREATE TABLE IF NOT EXISTS `user_punkte` (
  `uid` char(5) NOT NULL,
  `planeten` int(11) NOT NULL,
  `forschung` int(11) NOT NULL,
  `flotten` int(11) NOT NULL,
  `verteidigung` int(11) NOT NULL,
  `rank` int(11) NOT NULL,
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `user_punkte`
--

INSERT INTO `user_punkte` (`uid`, `planeten`, `forschung`, `flotten`, `verteidigung`, `rank`) VALUES
('admin', 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user_rang`
--

CREATE TABLE IF NOT EXISTS `user_rang` (
  `userID` varchar(10) NOT NULL,
  `rangID` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user_vote`
--

CREATE TABLE IF NOT EXISTS `user_vote` (
  `uid` varchar(255) NOT NULL,
  `topic` varchar(255) NOT NULL,
  `voteoption` bigint(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `verteidigung`
--

CREATE TABLE IF NOT EXISTS `verteidigung` (
  `coords` varchar(12) NOT NULL DEFAULT '',
  `v` text NOT NULL,
  `punkte` int(11) NOT NULL,
  `anzahl` int(20) NOT NULL,
  PRIMARY KEY (`coords`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `verteidigung`
--

INSERT INTO `verteidigung` (`coords`, `v`, `punkte`, `anzahl`) VALUES
('1:1:10:3', '', 0, 0),
('1:1:10:1', '', 0, 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `verwarnung`
--

CREATE TABLE IF NOT EXISTS `verwarnung` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wertigkeit` int(11) NOT NULL,
  `verwarndat` bigint(20) NOT NULL,
  `verwarntext` text NOT NULL,
  `uid` varchar(20) NOT NULL,
  `uname` varchar(255) NOT NULL,
  `admin` varchar(255) NOT NULL,
  `read` int(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_diplomatie`
-- (Siehe unten für die tatsächliche Ansicht)
--
CREATE TABLE IF NOT EXISTS `v_diplomatie` (
`a` varchar(115)
,`b` varchar(115)
,`diplotyp` enum('bnd','nap','war')
,`begin` int(20)
,`end` int(20)
,`status` tinyint(4)
,`a_id` varchar(255)
,`b_id` varchar(255)
,`a_typ` enum('s','a')
,`b_typ` enum('s','a')
);

-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_events`
-- (Siehe unten für die tatsächliche Ansicht)
--
CREATE TABLE IF NOT EXISTS `v_events` (
`id` varchar(20)
,`command` text
,`coords` varchar(12)
,`prio` bigint(20)
,`start` bigint(20) unsigned
);

-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_flotten`
-- (Siehe unten für die tatsächliche Ansicht)
--
CREATE TABLE IF NOT EXISTS `v_flotten` (
`besitzer` char(115)
,`fromc` varchar(12)
,`toc` varchar(12)
,`angeflogener` varchar(115)
,`typ` enum('ag','ag_p','kolo','trans','vert','stat','recy','asteroid','inva','spio','dest','hold','aks_lead','aks')
,`ankunft` time
,`rueckkehr` time
,`schiffe` text
);

-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_gigron`
-- (Siehe unten für die tatsächliche Ansicht)
--
CREATE TABLE IF NOT EXISTS `v_gigron` (
`name` char(115)
,`gigron_all` bigint(21)
);

-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_gvf`
-- (Siehe unten für die tatsächliche Ansicht)
--
CREATE TABLE IF NOT EXISTS `v_gvf` (
`uid` char(5)
,`good_points` decimal(32,0)
,`bad_points` decimal(32,0)
,`gvf` decimal(36,4)
);

-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_kurse`
-- (Siehe unten für die tatsächliche Ansicht)
--
CREATE TABLE IF NOT EXISTS `v_kurse` (
`r1` double(18,1)
,`r2` double(18,1)
,`r3` double(18,1)
,`r4` double(18,1)
);

-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_loeschen`
-- (Siehe unten für die tatsächliche Ansicht)
--
CREATE TABLE IF NOT EXISTS `v_loeschen` (
`name` char(115)
,`delTime` datetime
,`email` char(64)
);

-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_log`
-- (Siehe unten für die tatsächliche Ansicht)
--
CREATE TABLE IF NOT EXISTS `v_log` (
`name` char(115)
,`TIMESTAMP(FROM_UNIXTIME(time))` datetime
,`entry` text
);

-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_msg`
-- (Siehe unten für die tatsächliche Ansicht)
--
CREATE TABLE IF NOT EXISTS `v_msg` (
`empf` char(115)
,`abs` char(115)
,`zeit` datetime
,`subj` varchar(72)
,`text` text
,`readStatus` varchar(9)
);

-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_multi`
-- (Siehe unten für die tatsächliche Ansicht)
--
CREATE TABLE IF NOT EXISTS `v_multi` (
`name` char(115)
,`lastip` varchar(255)
);

-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_neuspieler`
-- (Siehe unten für die tatsächliche Ansicht)
--
CREATE TABLE IF NOT EXISTS `v_neuspieler` (
`neueSpieler` bigint(21)
);

-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_online`
-- (Siehe unten für die tatsächliche Ansicht)
--
CREATE TABLE IF NOT EXISTS `v_online` (
`id` char(5)
,`name` char(115)
,`lastAction` time
,`lastpage` varchar(500)
,`lastAct` varchar(1000)
,`aktiviert?` varchar(24)
);

-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_planets`
-- (Siehe unten für die tatsächliche Ansicht)
--
CREATE TABLE IF NOT EXISTS `v_planets` (
`coords` varchar(12)
,`owner` varchar(5)
,`pname` varchar(63)
,`temp` int(11)
,`dia` int(10) unsigned
,`pbild` varchar(63)
,`punkte` int(10) unsigned
,`destructed` tinyint(4)
,`bau_percent` int(11)
,`bau_until` bigint(20)
,`gal` bigint(12) unsigned
,`sys` bigint(12) unsigned
,`plan` bigint(12) unsigned
,`type` bigint(12) unsigned
);

-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_produktion`
-- (Siehe unten für die tatsächliche Ansicht)
--
CREATE TABLE IF NOT EXISTS `v_produktion` (
`id` int(11)
,`coords` varchar(12)
,`ptimestamp` datetime
,`sid` int(10) unsigned
,`count` int(10) unsigned
,`bauzeit` int(10) unsigned
,`nexttime` datetime
);

-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_punkte`
-- (Siehe unten für die tatsächliche Ansicht)
--
CREATE TABLE IF NOT EXISTS `v_punkte` (
`uid` char(5)
,`planeten` int(11)
,`forschung` int(11)
,`flotten` int(11)
,`verteidigung` int(11)
,`pgesamt` bigint(14)
,`rank` int(11)
);

-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_ressum`
-- (Siehe unten für die tatsächliche Ansicht)
--
CREATE TABLE IF NOT EXISTS `v_ressum` (
`res` double
,`type` varchar(2)
);

-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_test`
-- (Siehe unten für die tatsächliche Ansicht)
--
CREATE TABLE IF NOT EXISTS `v_test` (
`s` text
,`coords` varchar(12)
,`punkte` int(20)
,`name` char(115)
,`online` varchar(4)
,`tag` varchar(10)
,`lastaction` datetime
);

-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_tf`
-- (Siehe unten für die tatsächliche Ansicht)
--
CREATE TABLE IF NOT EXISTS `v_tf` (
`TF_Amount` double(17,0)
,`coords` char(12)
,`tf1` double unsigned
,`tf2` double unsigned
,`tf3` double unsigned
,`tf4` double unsigned
);

-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_tmp`
-- (Siehe unten für die tatsächliche Ansicht)
--
CREATE TABLE IF NOT EXISTS `v_tmp` (
`pcount` bigint(21)
);

-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_useronlineactive`
-- (Siehe unten für die tatsächliche Ansicht)
--
CREATE TABLE IF NOT EXISTS `v_useronlineactive` (
`lastAct` datetime
,`lastLogin` datetime
,`lastqry` varchar(1000)
,`lastip` varchar(255)
,`name` char(115)
);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `wartung`
--

CREATE TABLE IF NOT EXISTS `wartung` (
  `global_wartung` int(11) DEFAULT NULL,
  `live_wartung` int(11) DEFAULT NULL,
  `grund` varchar(500) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur des Views `v_diplomatie`
--
DROP TABLE IF EXISTS `v_diplomatie`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_diplomatie`  AS  select if((`d`.`a_typ` = 's'),(select `ux`.`name` AS `name` from `users` `ux` where (`ux`.`id` = 'd.a_id')),(select `ax`.`tag` AS `tag` from `allianz` `ax` where (`ax`.`id` = `d`.`a_id`))) AS `a`,if((`d`.`b_typ` = 's'),(select `ux`.`name` AS `name` from `users` `ux` where (`ux`.`id` = 'd.b_id')),(select `ax`.`tag` AS `tag` from `allianz` `ax` where (`ax`.`id` = `d`.`b_id`))) AS `b`,`d`.`diplotyp` AS `diplotyp`,`d`.`begin` AS `begin`,`d`.`end` AS `end`,`d`.`status` AS `status`,`d`.`a_id` AS `a_id`,`d`.`b_id` AS `b_id`,`d`.`a_typ` AS `a_typ`,`d`.`b_typ` AS `b_typ` from `diplomatie` `d` ;

-- --------------------------------------------------------

--
-- Struktur des Views `v_events`
--
DROP TABLE IF EXISTS `v_events`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_events`  AS  select `events`.`id` AS `id`,`events`.`command` AS `command`,substring_index(`events`.`coords`,':',3) AS `coords`,50 AS `prio`,`events`.`time` AS `start` from `events` where (`events`.`time` <= unix_timestamp()) union all select `flotten`.`id` AS `id`,'fleet_there' AS `command`,substring_index(`flotten`.`toc`,':',3) AS `coords`,(case `flotten`.`typ` when 'ag_p' then 90 when 'ag' then 90 when 'aks_lead' then 90 when 'dest' then 91 when 'recy' then 95 else 100 end) AS `prio`,`flotten`.`tthere` AS `start` from `flotten` where ((`flotten`.`tthere` > 0) and (`flotten`.`tthere` <= unix_timestamp())) union all select `flotten`.`id` AS `id`,'fleet_back' AS `command`,substring_index(`flotten`.`toc`,':',3) AS `coords`,80 AS `prio`,`flotten`.`tback` AS `start` from `flotten` where ((`flotten`.`tthere` = 0) and (`flotten`.`tback` <= unix_timestamp())) union all select `produktion`.`id` AS `id`,'v3prod' AS `command`,substring_index(`produktion`.`coords`,':',3) AS `coords`,10 AS `prio`,(`produktion`.`ptime` + `produktion`.`bauzeit`) AS `start` from `produktion` where ((`produktion`.`ptime` + `produktion`.`bauzeit`) <= unix_timestamp()) union all select `rohstoffe`.`coords` AS `id`,'resRecalc' AS `command`,`rohstoffe`.`coords` AS `coords`,100 AS `prio`,`rohstoffe`.`boost_until` AS `start` from `rohstoffe` where ((`rohstoffe`.`boost_until` > 0) and (`rohstoffe`.`boost_until` < unix_timestamp())) order by `start`,`prio` ;

-- --------------------------------------------------------

--
-- Struktur des Views `v_flotten`
--
DROP TABLE IF EXISTS `v_flotten`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_flotten`  AS  select `u1`.`name` AS `besitzer`,`f`.`fromc` AS `fromc`,`f`.`toc` AS `toc`,(select `u2`.`name` AS `name` from `users` `u2` where (`u2`.`id` = (select `p1`.`owner` AS `owner` from `planets` `p1` where (`p1`.`coords` = `f`.`toc`)))) AS `angeflogener`,`f`.`typ` AS `typ`,cast(from_unixtime(`f`.`tthere`) as time) AS `ankunft`,cast(from_unixtime(`f`.`tback`) as time) AS `rueckkehr`,`f`.`schiffe` AS `schiffe` from (`flotten` `f` left join `users` `u1` on((`f`.`userid` = `u1`.`id`))) ;

-- --------------------------------------------------------

--
-- Struktur des Views `v_gigron`
--
DROP TABLE IF EXISTS `v_gigron`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_gigron`  AS  select `u`.`name` AS `name`,(`g`.`gigron_found` + `g`.`gigron_buyed`) AS `gigron_all` from (`user_gigron` `g` left join `users` `u` on((`u`.`id` = `g`.`uid`))) order by (`g`.`gigron_found` + `g`.`gigron_buyed`) desc ;

-- --------------------------------------------------------

--
-- Struktur des Views `v_gvf`
--
DROP TABLE IF EXISTS `v_gvf`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_gvf`  AS  select `u`.`id` AS `uid`,if((coalesce(sum(`g`.`good_points`),0) = 0),1,sum(`g`.`good_points`)) AS `good_points`,if((coalesce(sum(`g`.`bad_points`),0) = 0),1,sum(`g`.`bad_points`)) AS `bad_points`,(if((coalesce(sum(`g`.`good_points`),0) = 0),1,sum(`g`.`good_points`)) / (if((coalesce(sum(`g`.`good_points`),0) = 0),1,sum(`g`.`good_points`)) + if((coalesce(sum(`g`.`bad_points`),0) = 0),1,sum(`g`.`bad_points`)))) AS `gvf` from (`users` `u` left join `user_gvf` `g` on(((`g`.`uid` = `u`.`id`) and (`g`.`valid_until` > unix_timestamp())))) group by `u`.`id` ;

-- --------------------------------------------------------

--
-- Struktur des Views `v_kurse`
--
DROP TABLE IF EXISTS `v_kurse`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_kurse`  AS  select round((`r1`.`res` / min(`r`.`res`)),1) AS `r1`,round((`r2`.`res` / min(`r`.`res`)),1) AS `r2`,round((`r3`.`res` / min(`r`.`res`)),1) AS `r3`,round((`r4`.`res` / min(`r`.`res`)),1) AS `r4` from ((((`v_ressum` `r` join `v_ressum` `r1`) join `v_ressum` `r2`) join `v_ressum` `r3`) join `v_ressum` `r4`) where ((`r1`.`type` = 'r1') and (`r2`.`type` = 'r2') and (`r3`.`type` = 'r3') and (`r4`.`type` = 'r4')) ;

-- --------------------------------------------------------

--
-- Struktur des Views `v_loeschen`
--
DROP TABLE IF EXISTS `v_loeschen`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_loeschen`  AS  select `users`.`name` AS `name`,cast(from_unixtime(`loeschen`.`time`) as datetime) AS `delTime`,`users`.`email` AS `email` from (`loeschen` left join `users` on((`users`.`id` = `loeschen`.`uid`))) ;

-- --------------------------------------------------------

--
-- Struktur des Views `v_log`
--
DROP TABLE IF EXISTS `v_log`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_log`  AS  select `u`.`name` AS `name`,cast(from_unixtime(`l`.`time`) as datetime) AS `TIMESTAMP(FROM_UNIXTIME(time))`,`l`.`entry` AS `entry` from (`log` `l` left join `users` `u` on((`l`.`uid` = `u`.`id`))) order by `l`.`time` desc ;

-- --------------------------------------------------------

--
-- Struktur des Views `v_msg`
--
DROP TABLE IF EXISTS `v_msg`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_msg`  AS  select `u1`.`name` AS `empf`,`u2`.`name` AS `abs`,cast(from_unixtime(`msg`.`time`) as datetime) AS `zeit`,`msg`.`subj` AS `subj`,`msg`.`text` AS `text`,if((`msg`.`red` = 'yes'),'gelesen','ungelesen') AS `readStatus` from ((`msg` left join `users` `u1` on((`u1`.`id` = `msg`.`userid`))) left join `users` `u2` on((`u2`.`id` = `msg`.`fromuid`))) where (`msg`.`mode` = 'text') order by `msg`.`time` desc ;

-- --------------------------------------------------------

--
-- Struktur des Views `v_multi`
--
DROP TABLE IF EXISTS `v_multi`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_multi`  AS  select `users`.`name` AS `name`,`users`.`lastip` AS `lastip` from `users` where (((select count(0) AS `COUNT(*)` from `users` `u2` where ((`users`.`lastip` = `u2`.`lastip`) and (`u2`.`lastip` <> ''))) > 1) and (`users`.`registered_multi` = 0)) order by `users`.`lastip` ;

-- --------------------------------------------------------

--
-- Struktur des Views `v_neuspieler`
--
DROP TABLE IF EXISTS `v_neuspieler`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_neuspieler`  AS  select count(0) AS `neueSpieler` from `users` where ((unix_timestamp(`users`.`registertime`) + (3600 * 24)) > unix_timestamp()) ;

-- --------------------------------------------------------

--
-- Struktur des Views `v_online`
--
DROP TABLE IF EXISTS `v_online`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_online`  AS  select `users`.`id` AS `id`,`users`.`name` AS `name`,cast(from_unixtime(((unix_timestamp() + (3600 * 23)) - `users`.`lastclick`)) as time) AS `lastAction`,`users`.`lastpage` AS `lastpage`,`users`.`lastqry` AS `lastAct`,if((`users`.`active` = 1),'aktiviert','neu(noch nicht aktiviert') AS `aktiviert?` from `users` where (`users`.`lastclick` > (unix_timestamp() - 600)) ;

-- --------------------------------------------------------

--
-- Struktur des Views `v_planets`
--
DROP TABLE IF EXISTS `v_planets`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_planets`  AS  select `planets`.`coords` AS `coords`,`planets`.`owner` AS `owner`,`planets`.`pname` AS `pname`,`planets`.`temp` AS `temp`,`planets`.`dia` AS `dia`,`planets`.`pbild` AS `pbild`,`planets`.`punkte` AS `punkte`,`planets`.`destructed` AS `destructed`,`planets`.`bau_percent` AS `bau_percent`,`planets`.`bau_until` AS `bau_until`,cast(substring_index(`planets`.`coords`,':',1) as unsigned) AS `gal`,cast(substring_index(substring_index(`planets`.`coords`,':',2),':',-(1)) as unsigned) AS `sys`,cast(substring_index(substring_index(`planets`.`coords`,':',-(2)),':',1) as unsigned) AS `plan`,cast(substring_index(`planets`.`coords`,':',-(1)) as unsigned) AS `type` from `planets` ;

-- --------------------------------------------------------

--
-- Struktur des Views `v_produktion`
--
DROP TABLE IF EXISTS `v_produktion`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_produktion`  AS  select `produktion`.`id` AS `id`,`produktion`.`coords` AS `coords`,cast(from_unixtime(`produktion`.`ptime`) as datetime) AS `ptimestamp`,`produktion`.`sid` AS `sid`,`produktion`.`count` AS `count`,`produktion`.`bauzeit` AS `bauzeit`,cast(from_unixtime((`produktion`.`ptime` + (`produktion`.`count` * `produktion`.`bauzeit`))) as datetime) AS `nexttime` from `produktion` order by `produktion`.`ptime` ;

-- --------------------------------------------------------

--
-- Struktur des Views `v_punkte`
--
DROP TABLE IF EXISTS `v_punkte`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_punkte`  AS  select `user_punkte`.`uid` AS `uid`,`user_punkte`.`planeten` AS `planeten`,`user_punkte`.`forschung` AS `forschung`,`user_punkte`.`flotten` AS `flotten`,`user_punkte`.`verteidigung` AS `verteidigung`,(((`user_punkte`.`planeten` + `user_punkte`.`forschung`) + `user_punkte`.`flotten`) + `user_punkte`.`verteidigung`) AS `pgesamt`,`user_punkte`.`rank` AS `rank` from `user_punkte` ;

-- --------------------------------------------------------

--
-- Struktur des Views `v_ressum`
--
DROP TABLE IF EXISTS `v_ressum`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_ressum`  AS  select sum(`rohstoffe`.`r1`) AS `res`,'r1' AS `type` from `rohstoffe` where (`rohstoffe`.`r1` > (select avg(`rohstoffe`.`r1`) from `rohstoffe`)) union all select sum(`rohstoffe`.`r2`) AS `res`,'r2' AS `type` from `rohstoffe` where (`rohstoffe`.`r2` > (select avg(`rohstoffe`.`r2`) from `rohstoffe`)) union all select sum(`rohstoffe`.`r3`) AS `res`,'r3' AS `type` from `rohstoffe` where (`rohstoffe`.`r3` > (select avg(`rohstoffe`.`r3`) from `rohstoffe`)) union all select sum(`rohstoffe`.`r4`) AS `res`,'r4' AS `type` from `rohstoffe` where (`rohstoffe`.`r4` > (select avg(`rohstoffe`.`r4`) from `rohstoffe`)) ;

-- --------------------------------------------------------

--
-- Struktur des Views `v_test`
--
DROP TABLE IF EXISTS `v_test`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_test`  AS  select `ts`.`s` AS `s`,`ts`.`coords` AS `coords`,`ts`.`punkte` AS `punkte`,`tu`.`name` AS `name`,if((`tu`.`lastclick` > (unix_timestamp() - 600)),'ja','nein') AS `online`,`a`.`tag` AS `tag`,cast(from_unixtime(`tu`.`lastclick`) as datetime) AS `lastaction` from (((`schiffe` `ts` left join `planets` `tp` on((`ts`.`coords` = `tp`.`coords`))) left join `users` `tu` on((`tu`.`id` = `tp`.`owner`))) left join `allianz` `a` on((`tu`.`allianz` = `a`.`id`))) order by `ts`.`punkte` desc ;

-- --------------------------------------------------------

--
-- Struktur des Views `v_tf`
--
DROP TABLE IF EXISTS `v_tf`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_tf`  AS  select round((((`rohstoffe`.`tf1` + `rohstoffe`.`tf2`) + `rohstoffe`.`tf3`) + `rohstoffe`.`tf4`),0) AS `TF_Amount`,`rohstoffe`.`coords` AS `coords`,`rohstoffe`.`tf1` AS `tf1`,`rohstoffe`.`tf2` AS `tf2`,`rohstoffe`.`tf3` AS `tf3`,`rohstoffe`.`tf4` AS `tf4` from `rohstoffe` order by round((((`rohstoffe`.`tf1` + `rohstoffe`.`tf2`) + `rohstoffe`.`tf3`) + `rohstoffe`.`tf4`),0) desc ;

-- --------------------------------------------------------

--
-- Struktur des Views `v_tmp`
--
DROP TABLE IF EXISTS `v_tmp`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_tmp`  AS  select (select count(0) AS `COUNT(*)` from `planets` where (`planets`.`owner` = `u`.`id`)) AS `pcount` from (`users` `u` left join `v_punkte` `vp` on((`vp`.`uid` = `u`.`id`))) where (`vp`.`pgesamt` > 100000) ;

-- --------------------------------------------------------

--
-- Struktur des Views `v_useronlineactive`
--
DROP TABLE IF EXISTS `v_useronlineactive`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_useronlineactive`  AS  select cast(from_unixtime(`users`.`lastclick`) as datetime) AS `lastAct`,cast(from_unixtime(`users`.`lastlogin`) as datetime) AS `lastLogin`,`users`.`lastqry` AS `lastqry`,`users`.`lastip` AS `lastip`,`users`.`name` AS `name` from `users` ;

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `allianz`
--
ALTER TABLE `allianz` ADD FULLTEXT KEY `tag` (`tag`,`name`,`stati`,`text`,`hp`);

--
-- Indizes für die Tabelle `user_desc`
--
ALTER TABLE `user_desc` ADD FULLTEXT KEY `text` (`text`);
COMMIT;

