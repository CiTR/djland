CREATE TABLE `podcast_episodes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `playsheet_id` bigint(20) unsigned NOT NULL,
  `show_id` int(11) DEFAULT NULL,
  `title` text,
  `subtitle` text,
  `summary` text,
  `date` datetime DEFAULT NULL,
  `iso_date` text,
  `url` text,
  `length` int(11) DEFAULT NULL,
  `author` text,
  `active` tinyint(1) DEFAULT '0',
  `duration` int(7) DEFAULT '0',
  `UPDATED_AT` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `CREATED_AT` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28272 DEFAULT CHARSET=utf8;
