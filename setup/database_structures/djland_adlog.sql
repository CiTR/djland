--
-- Table structure for table `adlog`
--
CREATE TABLE `adlog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `playsheet_id` int(11) DEFAULT NULL,
  `num` smallint(6) DEFAULT NULL,
  `time` varchar(45) CHARACTER SET utf8 DEFAULT NULL,
  `type` varchar(45) CHARACTER SET utf8 DEFAULT NULL,
  `name` text CHARACTER SET utf8,
  `played` tinyint(4) DEFAULT NULL,
  `sam_id` int(11) DEFAULT NULL,
  `time_block` int(11) DEFAULT NULL,
  `create_date` timestamp NULL DEFAULT NULL,
  `edit_date` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `unixtime` (`id`,`time_block`)
) ENGINE=InnoDB AUTO_INCREMENT=464911 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
