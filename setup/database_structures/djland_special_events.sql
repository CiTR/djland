CREATE TABLE `special_events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(455) DEFAULT NULL,
  `show_id` int(11) DEFAULT NULL,
  `description` text,
  `start` int(11) DEFAULT NULL,
  `end` int(11) DEFAULT NULL,
  `image` varchar(455) DEFAULT NULL,
  `url` varchar(455) DEFAULT NULL,
  `edited` timestamp NULL DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8;
