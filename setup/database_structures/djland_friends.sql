CREATE TABLE `friends` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` tinytext NOT NULL,
  `address` tinytext,
  `website` varchar(60) DEFAULT NULL,
  `phone` varchar(17) DEFAULT NULL,
  `discount` tinytext DEFAULT NULL,
  `image_url` varchar(120) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `edited` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
