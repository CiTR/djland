CREATE TABLE `socan` (
  `id` int(10) unsigned NOT NULL,
  `socanStart` date DEFAULT NULL,
  `socanEnd` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
