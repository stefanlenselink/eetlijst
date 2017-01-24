-- phpMyAdmin SQL Dump
-- version 2.6.3-pl1
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generatie Tijd: 23 Jan 2006 om 18:08
-- Server versie: 4.1.14
-- PHP Versie: 5.0.4
-- 
-- 

-- --------------------------------------------------------

-- 
-- Tabel structuur voor tabel `date`
-- 

CREATE TABLE `date` (
  `ratio_from` date NOT NULL default '0000-00-00',
  `exp_from` date NOT NULL default '0000-00-00',
  PRIMARY KEY  (`ratio_from`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Gegevens worden uitgevoerd voor tabel `date`
-- 


-- --------------------------------------------------------

-- 
-- Tabel structuur voor tabel `e_detail`
-- 

CREATE TABLE `e_detail` (
  `exp_id` int(11) NOT NULL default '0',
  `exp_date` date NOT NULL default '0000-00-00',
  `user_id` tinyint(4) NOT NULL default '0',
  `nb` tinyint(4) NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Gegevens worden uitgevoerd voor tabel `e_detail`
-- 


-- --------------------------------------------------------

-- 
-- Tabel structuur voor tabel `enroll`
-- 

CREATE TABLE `enroll` (
  `id` tinyint(4) NOT NULL auto_increment,
  `exp_date` date NOT NULL default '0000-00-00',
  `user_id` tinyint(4) default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Gegevens worden uitgevoerd voor tabel `enroll`
-- 


-- --------------------------------------------------------

-- 
-- Tabel structuur voor tabel `exp_detail`
-- 

CREATE TABLE `exp_detail` (
  `exp_id` int(4) NOT NULL default '0',
  `exp_date` date NOT NULL default '0000-00-00',
  `user_id` tinyint(4) NOT NULL default '0',
  `nb` tinyint(4) NOT NULL default '0',
  `exp_pu` decimal(11,10) NOT NULL default '0.0000000000',
  KEY `exp_id` (`exp_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Gegevens worden uitgevoerd voor tabel `exp_detail`
-- 


-- --------------------------------------------------------

-- 
-- Tabel structuur voor tabel `expenses`
-- 

CREATE TABLE `expenses` (
  `id` int(4) NOT NULL auto_increment,
  `exp_date` date NOT NULL default '0000-00-00',
  `user_id` varchar(15) NOT NULL default '',
  `exp` decimal(5,2) NOT NULL default '0.00',
  `description` tinytext,
  `number` tinyint(4) default NULL,
  `exp_pp` decimal(11,10) NOT NULL default '0.0000000000',
  PRIMARY KEY  (`id`),
  KEY `kosten_date` (`exp_date`),
  KEY `kosten_id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 PACK_KEYS=0 AUTO_INCREMENT=1 ;

-- 
-- Gegevens worden uitgevoerd voor tabel `expenses`
-- 


-- --------------------------------------------------------

-- 
-- Tabel structuur voor tabel `logins`
-- 

CREATE TABLE `logins` (
  `tijdstip` datetime NOT NULL default '0000-00-00 00:00:00',
  `client_ip` varchar(15) NOT NULL default '',
  `validate` varchar(32) NOT NULL default '',
  `user_id` int(8) unsigned NOT NULL default '0',
  PRIMARY KEY  (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Gegevens worden uitgevoerd voor tabel `logins`
-- 


-- --------------------------------------------------------

-- 
-- Tabel structuur voor tabel `site`
-- 

CREATE TABLE `site` (
  `id` tinyint(4) NOT NULL default '1',
  `title` tinytext NOT NULL,
  `version` tinytext NOT NULL,
  `text` mediumtext NOT NULL,
  `updated` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Gegevens worden uitgevoerd voor tabel `site`
-- 

INSERT INTO `site` VALUES (1, 'Eetlijst Heemskerkstraat', 'v. 0.6.8', '<br>\r\n<ul>\r\n<li>Weer een nieuwe eetlijst/layout, versie 0.6.8!</li>\r\n<li>Nieuwe functie eetlijst: OPGEVEN voor maaltijd</li>\r\n<li>Eerste balk hieronder toont wie vandaag mee-eet.</li>\r\n<li>Voor compleet changelog klik <a class="hyp_2" target="_blank" href="changelog.txt">hier</a></li>\r\n<li>Vergeet niet uit te loggen op openbare pc''s!</li>\r\n</ul>', '2006-01-23 18:04:20');

-- --------------------------------------------------------

-- 
-- Tabel structuur voor tabel `tmp_list_1`
-- 

CREATE TABLE `tmp_list_1` (
  `user_id` tinyint(4) NOT NULL default '0',
  `saldo` decimal(15,10) NOT NULL default '0.0000000000',
  PRIMARY KEY  (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Gegevens worden uitgevoerd voor tabel `tmp_list_1`
-- 


-- --------------------------------------------------------

-- 
-- Tabel structuur voor tabel `tmp_list_2`
-- 

CREATE TABLE `tmp_list_2` (
  `creditor` tinyint(4) NOT NULL default '0',
  `debtor` tinyint(4) NOT NULL default '0',
  `amount` decimal(15,10) NOT NULL default '0.0000000000'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Gegevens worden uitgevoerd voor tabel `tmp_list_2`
-- 


-- --------------------------------------------------------

-- 
-- Tabel structuur voor tabel `users`
-- 

CREATE TABLE `users` (
  `id` int(8) unsigned NOT NULL auto_increment,
  `username` varchar(20) NOT NULL default '',
  `password` varchar(32) NOT NULL default '',
  `user_email` varchar(40) NOT NULL default '',
  `user_level` set('0','9') NOT NULL default '0',
  `user_active` set('yes','no') NOT NULL default 'yes',
  `user_in` date NOT NULL default '0000-00-00',
  `user_out` date NOT NULL default '0000-00-00',
  `firstname` varchar(20) NOT NULL default '',
  `lastname` varchar(30) NOT NULL default '',
  `birthdate` date NOT NULL default '0000-00-00',
  `room` varchar(20) NOT NULL default '',
  `study` varchar(20) NOT NULL default '',
  `bankaccount` varchar(10) NOT NULL default '',
  `account_place` tinytext NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 PACK_KEYS=0 AUTO_INCREMENT=2 ;

-- 
-- Gegevens worden uitgevoerd voor tabel `users`
-- 

INSERT INTO `users` VALUES (1, 'Admin', 'e3afed0047b08059d0fada10f400c1e5', '', '9', 'yes', '0000-00-00', '0000-00-00', '', '', '0000-00-00', '', '', '', '');
