CREATE TABLE `hosts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` tinytext,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `id_2` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2333 DEFAULT CHARSET=latin1;
