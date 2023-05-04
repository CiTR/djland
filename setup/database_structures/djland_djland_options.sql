--
-- Table structure for table `djland_options`
--
CREATE TABLE `djland_options` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `djland_option` tinytext COLLATE utf8_bin NOT NULL,
  `value` text COLLATE utf8_bin NOT NULL,
  `CREATED_AT` datetime NOT NULL,
  `UPDATED_AT` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
