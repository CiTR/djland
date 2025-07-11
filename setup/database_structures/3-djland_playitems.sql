CREATE TABLE `playitems` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `show_id` int(10) unsigned DEFAULT NULL,
  `playsheet_id` bigint(20) unsigned DEFAULT NULL,
  `song_id` bigint(20) unsigned DEFAULT NULL,
  `format_id` tinyint(3) unsigned DEFAULT NULL,
  `is_playlist` tinyint(1) unsigned DEFAULT '0',
  `is_canadian` tinyint(1) unsigned DEFAULT '0',
  `is_yourown` tinyint(1) unsigned DEFAULT '0',
  `is_indy` tinyint(1) unsigned DEFAULT '0',
  `is_accesscon` tinyint(1) unsigned DEFAULT '0',
  `is_afrocon` tinyint(1) unsigned DEFAULT '0',
  `is_fem` tinyint(3) unsigned DEFAULT '0',
  `is_indigicon` tinyint(1) unsigned DEFAULT '0',
  `is_poccon` tinyint(1) unsigned DEFAULT '0',
  `is_queercon` tinyint(1) unsigned DEFAULT '0',
  `show_date` date DEFAULT NULL,
  `duration` tinytext,
  `is_theme` tinyint(3) unsigned DEFAULT NULL,
  `is_background` tinyint(3) unsigned DEFAULT NULL,
  `crtc_category` int(8) DEFAULT '20',
  `lang` tinytext,
  `is_part` int(1) NOT NULL DEFAULT '0',
  `is_inst` int(1) NOT NULL DEFAULT '0',
  `is_hit` int(1) NOT NULL DEFAULT '0',
  `insert_song_start_hour` tinyint(4) DEFAULT '0',
  `insert_song_start_minute` tinyint(4) DEFAULT '0',
  `insert_song_length_minute` tinyint(4) DEFAULT '0',
  `insert_song_length_second` tinyint(4) DEFAULT '0',
  `artist` varchar(80) DEFAULT NULL,
  `song` varchar(80) DEFAULT NULL,
  `album` varchar(80) DEFAULT NULL,
  `composer` varchar(80) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `id_2` (`id`),
  KEY `playitem_playsheet_id_idx` (`playsheet_id`),
  KEY `playitem_show_id_idx` (`show_id`),
  CONSTRAINT `playitem_playsheet_id` FOREIGN KEY (`playsheet_id`) REFERENCES `playsheets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
