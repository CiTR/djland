CREATE TABLE `member_resources` (
  `index` int(10) NOT NULL,
  `blurb` tinytext,
  `link` tinytext,
  `type` varchar(45) DEFAULT 'general',
  `CREATED_AT` timestamp NULL DEFAULT NULL,
  `UPDATED_AT` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`index`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
