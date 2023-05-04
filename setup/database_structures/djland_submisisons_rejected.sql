CREATE TABLE `submissions_rejected` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `email` tinytext,
  `artist` tinytext,
  `title` tinytext,
  `submitted` date DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;;
