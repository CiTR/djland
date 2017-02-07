CREATE TABLE `submissions_archive` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `contact` tinytext,
  `catalog` tinytext,
  `artist` tinytext,
  `title` tinytext,
  `submitted` date DEFAULT NULL,
  `format_id` tinyint(3) DEFAULT NULL,
  `cancon` tinyint(1) DEFAULT NULL,
  `femcon` tinyint(1) DEFAULT NULL,
  `local` tinyint(1) DEFAULT NULL,
  `label` tinytext,
  `review_comments` tinytext,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
