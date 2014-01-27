-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 27, 2014 at 09:18 PM
-- Server version: 5.5.8-log
-- PHP Version: 5.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `lwt_toshow`
--
CREATE DATABASE IF NOT EXISTS `lwt_toshow` DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;
USE `lwt_toshow`;

-- --------------------------------------------------------

--
-- Table structure for table `archivedtexts`
--

CREATE TABLE IF NOT EXISTS `archivedtexts` (
  `AtID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `AtLgID` int(11) unsigned NOT NULL,
  `AtTitle` varchar(200) NOT NULL,
  `AtText` text NOT NULL,
  `AtAnnotatedText` longtext NOT NULL,
  `AtAudioURI` varchar(200) DEFAULT NULL,
  `AtSourceURI` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`AtID`),
  KEY `AtLgID` (`AtLgID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `archtexttags`
--

CREATE TABLE IF NOT EXISTS `archtexttags` (
  `AgAtID` int(11) unsigned NOT NULL,
  `AgT2ID` int(11) unsigned NOT NULL,
  PRIMARY KEY (`AgAtID`,`AgT2ID`),
  KEY `AgAtID` (`AgAtID`),
  KEY `AgT2ID` (`AgT2ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `dictionaries`
--

CREATE TABLE IF NOT EXISTS `dictionaries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `languagesLgID` int(11) NOT NULL,
  `URI` varchar(200) COLLATE utf8_bin NOT NULL,
  `name` varchar(10) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`),
  KEY `languagesLgID` (`languagesLgID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE IF NOT EXISTS `languages` (
  `LgID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `LgName` varchar(40) NOT NULL,
  `LgGoogleTranslateURI` varchar(200) DEFAULT NULL,
  `LgExportTemplate` varchar(1000) DEFAULT NULL,
  `LgTextSize` int(5) unsigned NOT NULL DEFAULT '100',
  `LgCharacterSubstitutions` varchar(500) NOT NULL,
  `LgRegexpSplitSentences` varchar(500) NOT NULL,
  `LgExceptionsSplitSentences` varchar(500) NOT NULL,
  `LgRegexpWordCharacters` varchar(500) NOT NULL,
  `LgRemoveSpaces` int(1) unsigned NOT NULL DEFAULT '0',
  `LgSplitEachChar` int(1) unsigned NOT NULL DEFAULT '0',
  `LgRightToLeft` int(1) unsigned NOT NULL DEFAULT '0',
  `LgDefaultDictionary` int(11) NOT NULL,
  `iso_3` varchar(3) NOT NULL COMMENT '3 sign language code',
  PRIMARY KEY (`LgID`),
  UNIQUE KEY `LgName` (`LgName`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `sentences`
--

CREATE TABLE IF NOT EXISTS `sentences` (
  `SeID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `SeLgID` int(11) unsigned NOT NULL,
  `SeTxID` int(11) unsigned NOT NULL,
  `SeOrder` int(11) unsigned NOT NULL,
  `SeText` text,
  `SeSplit` varchar(1024) NOT NULL,
  `SeTranslation` varchar(1024) NOT NULL,
  PRIMARY KEY (`SeID`),
  KEY `SeLgID` (`SeLgID`),
  KEY `SeTxID` (`SeTxID`),
  KEY `SeOrder` (`SeOrder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `StKey` varchar(40) NOT NULL,
  `StValue` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`StKey`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`StKey`, `StValue`) VALUES
('dbversion', 'v001005015'),
('currenttext', '25'),
('showallwords', '0'),
('lastscorecalc', '2014-01-27'),
('currentlanguage', '5'),
('currenttabletestsetting1', '1'),
('currenttabletestsetting2', '1'),
('currenttabletestsetting3', '1'),
('currenttabletestsetting4', '1'),
('currenttabletestsetting5', '1'),
('currenttabletestsetting6', '1'),
('set-text-h-frameheight-no-audio', '140'),
('set-text-h-frameheight-with-audio', '200'),
('set-text-l-framewidth-percent', '50'),
('set-text-r-frameheight-percent', '50'),
('set-test-h-frameheight', '140'),
('set-test-l-framewidth-percent', '50'),
('set-test-r-frameheight-percent', '50'),
('set-player-skin-name', 'jplayer.blue.monday'),
('set-test-main-frame-waiting-time', '0'),
('set-test-edit-frame-waiting-time', '500'),
('set-test-sentence-count', '1'),
('set-term-sentence-count', '1'),
('set-archivedtexts-per-page', '100'),
('set-texts-per-page', '10'),
('set-terms-per-page', '100'),
('set-tags-per-page', '100'),
('set-show-text-word-counts', '1'),
('set-term-translation-delimiters', '/;|'),
('set-mobile-display-mode', '0'),
('currentnativelanguage', 'English');

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE IF NOT EXISTS `tags` (
  `TgID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `TgText` varchar(20) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `TgComment` varchar(200) NOT NULL DEFAULT '',
  PRIMARY KEY (`TgID`),
  UNIQUE KEY `TgText` (`TgText`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=35 ;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`TgID`, `TgText`, `TgComment`) VALUES
(1, 'masc', ''),
(2, 'fem', ''),
(8, '3p-sg', ''),
(5, '1p-sg', ''),
(6, '2p-sg', ''),
(7, 'verb', ''),
(9, '1p-pl', ''),
(10, '2p-pl', ''),
(11, '3p-pl', ''),
(12, 'adj', ''),
(13, 'adv', ''),
(14, 'interj', ''),
(15, 'conj', ''),
(16, 'num', ''),
(17, 'infinitive', ''),
(18, 'noun', ''),
(19, 'pronoun', ''),
(20, 'informal', ''),
(21, 'colloc', ''),
(22, 'pres', ''),
(23, 'impf', ''),
(24, 'subj', ''),
(25, 'pastpart', ''),
(26, 'prespart', ''),
(27, 'name', ''),
(28, 'greeting', ''),
(29, 'あらし', ''),
(30, 'らん', ''),
(31, 'まち', ''),
(32, 'ちょう', ''),
(33, 'incomplete', ''),
(34, 'はえ', '');

-- --------------------------------------------------------

--
-- Table structure for table `tags2`
--

CREATE TABLE IF NOT EXISTS `tags2` (
  `T2ID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `T2Text` varchar(20) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `T2Comment` varchar(200) NOT NULL DEFAULT '',
  PRIMARY KEY (`T2ID`),
  UNIQUE KEY `T2Text` (`T2Text`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `tags2`
--

INSERT INTO `tags2` (`T2ID`, `T2Text`, `T2Comment`) VALUES
(1, 'demo', ''),
(2, 'basic', ''),
(3, 'goethe', ''),
(4, 'conversation', ''),
(5, 'joke', ''),
(6, 'chinesepod', ''),
(7, 'literature', ''),
(8, 'fragment', ''),
(9, 'annotation', '');

-- --------------------------------------------------------

--
-- Table structure for table `texts`
--

CREATE TABLE IF NOT EXISTS `texts` (
  `TxID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `TxLgID` int(11) unsigned NOT NULL,
  `TxTitle` varchar(200) NOT NULL,
  `TxText` text NOT NULL,
  `TxAnnotatedText` longtext NOT NULL,
  `TxAudioURI` varchar(200) DEFAULT NULL,
  `TxSourceURI` varchar(1000) DEFAULT NULL,
  `words` int(11) NOT NULL,
  `words_saved` int(11) NOT NULL,
  `length` int(11) NOT NULL,
  PRIMARY KEY (`TxID`),
  KEY `TxLgID` (`TxLgID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `texttags`
--

CREATE TABLE IF NOT EXISTS `texttags` (
  `TtTxID` int(11) unsigned NOT NULL,
  `TtT2ID` int(11) unsigned NOT NULL,
  PRIMARY KEY (`TtTxID`,`TtT2ID`),
  KEY `TtTxID` (`TtTxID`),
  KEY `TtT2ID` (`TtT2ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `words`
--

CREATE TABLE IF NOT EXISTS `words` (
  `WoID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `WoLgID` int(11) unsigned NOT NULL,
  `WoText` varchar(250) NOT NULL,
  `WoTextLC` varchar(250) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `WoStatus` tinyint(4) NOT NULL,
  `WoTranslation` varchar(500) NOT NULL DEFAULT '*',
  `WoRomanization` varchar(100) DEFAULT NULL,
  `WoSentence` varchar(1000) DEFAULT NULL,
  `WoCreated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `WoStatusChanged` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `WoTodayScore` double NOT NULL DEFAULT '0',
  `WoTomorrowScore` double NOT NULL DEFAULT '0',
  `WoRandom` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`WoID`),
  UNIQUE KEY `WoLgIDTextLC` (`WoLgID`,`WoTextLC`),
  KEY `WoLgID` (`WoLgID`),
  KEY `WoStatus` (`WoStatus`),
  KEY `WoTextLC` (`WoTextLC`),
  KEY `WoTranslation` (`WoTranslation`(333)),
  KEY `WoCreated` (`WoCreated`),
  KEY `WoStatusChanged` (`WoStatusChanged`),
  KEY `WoTodayScore` (`WoTodayScore`),
  KEY `WoTomorrowScore` (`WoTomorrowScore`),
  KEY `WoRandom` (`WoRandom`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `wordtags`
--

CREATE TABLE IF NOT EXISTS `wordtags` (
  `WtWoID` int(11) unsigned NOT NULL,
  `WtTgID` int(11) unsigned NOT NULL,
  PRIMARY KEY (`WtWoID`,`WtTgID`),
  KEY `WtTgID` (`WtTgID`),
  KEY `WtWoID` (`WtWoID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `wordtags`
--

INSERT INTO `wordtags` (`WtWoID`, `WtTgID`) VALUES
(1, 27),
(2, 1),
(2, 14),
(2, 18),
(2, 28),
(3, 13),
(6, 15),
(7, 5),
(7, 19),
(16, 29),
(16, 30),
(20, 31),
(20, 32),
(22, 33),
(70, 34);

-- --------------------------------------------------------

--
-- Table structure for table `_lwtgeneral`
--

CREATE TABLE IF NOT EXISTS `_lwtgeneral` (
  `LWTKey` varchar(40) NOT NULL,
  `LWTValue` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`LWTKey`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `_lwtgeneral`
--

INSERT INTO `_lwtgeneral` (`LWTKey`, `LWTValue`) VALUES
('current_table_prefix', NULL);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
