CREATE TABLE `group_members` (
  `user_id` int(10) unsigned NOT NULL,
  `operator` varchar(1) DEFAULT '0',
  `administrator` varchar(1) DEFAULT '0',
  `staff` varchar(1) DEFAULT '0',
  `workstudy` varchar(1) DEFAULT '0',
  `volunteer_leader` varchar(1) DEFAULT '0',
  `volunteer` varchar(45) DEFAULT '0',
  `dj` varchar(1) DEFAULT '0',
  `member` varchar(1) DEFAULT '0',
  PRIMARY KEY (`user_id`),
  KEY `userid_idx` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;;
