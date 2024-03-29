CREATE TABLE `shows` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` tinytext CHARACTER SET utf8,
  `host` tinytext COLLATE utf8_unicode_ci,
  `primary_genre_tags` text COLLATE utf8_unicode_ci,
  `secondary_genre_tags` text CHARACTER SET utf8,
  `weekday` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `start_time` time NOT NULL DEFAULT '00:00:00',
  `end_time` time NOT NULL DEFAULT '00:00:00',
  `pl_req` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `cc_req` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `indy_req` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `fem_req` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `last_show` datetime NOT NULL DEFAULT '0000-01-01 00:00:00',
  `create_date` datetime NOT NULL DEFAULT '0000-01-01 00:00:00',
  `create_name` tinytext CHARACTER SET utf8 NOT NULL,
  `edit_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `edit_name` tinytext CHARACTER SET utf8,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `crtc_default` int(8) NOT NULL DEFAULT '20',
  `lang_default` tinytext CHARACTER SET utf8,
  `website` tinytext CHARACTER SET utf8,
  `rss` tinytext CHARACTER SET utf8,
  `show_desc` text CHARACTER SET utf8,
  `notes` text CHARACTER SET utf8,
  `show_img` tinytext CHARACTER SET utf8,
  `sponsor_name` tinytext CHARACTER SET utf8,
  `sponsor_url` tinytext CHARACTER SET utf8,
  `showtype` varchar(45) CHARACTER SET utf8 DEFAULT 'Live',
  `explicit` varchar(1) COLLATE utf8_unicode_ci DEFAULT '0',
  `alerts` text CHARACTER SET utf8,
  `podcast_xml` tinytext COLLATE utf8_unicode_ci,
  `podcast_slug` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `podcast_title` tinytext COLLATE utf8_unicode_ci,
  `podcast_subtitle` tinytext COLLATE utf8_unicode_ci,
  `podcast_summary` text COLLATE utf8_unicode_ci,
  `podcast_author` tinytext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `id_2` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=447 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
