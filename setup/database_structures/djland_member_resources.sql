CREATE TABLE `member_resources` (
  `index` int(10) NOT NULL AUTO_INCREMENT,
  `num` tinyint(4) DEFAULT NULL,
  `blurb` tinytext,
  `link` tinytext,
  `link_name` tinytext,
  `type` varchar(45) DEFAULT 'general',
  `CREATED_AT` datetime DEFAULT NULL,
  `UPDATED_AT` datetime DEFAULT NULL,
  PRIMARY KEY (`index`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
