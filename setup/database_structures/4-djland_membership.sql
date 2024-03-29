CREATE TABLE `membership` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `lastname` varchar(90) NOT NULL,
  `firstname` varchar(90) NOT NULL,
  `canadian_citizen` varchar(1) NOT NULL COMMENT 'citizen, immigrant, visitor',
  `address` varchar(55) NOT NULL,
  `city` varchar(45) NOT NULL DEFAULT 'Vancouver',
  `province` varchar(4) NOT NULL DEFAULT 'BC',
  `postalcode` varchar(6) NOT NULL,
  `member_type` varchar(9) NOT NULL COMMENT 'student, community, alumni, lifetime',
  `is_new` varchar(1) NOT NULL DEFAULT '0',
  `alumni` varchar(1) NOT NULL DEFAULT '0',
  `since` varchar(9) NOT NULL DEFAULT '2014/2015',
  `faculty` varchar(22) DEFAULT NULL,
  `schoolyear` varchar(2) DEFAULT NULL,
  `student_no` varchar(8) DEFAULT NULL COMMENT 'Student Number',
  `integrate` varchar(1) NOT NULL DEFAULT '0',
  `has_show` varchar(1) NOT NULL DEFAULT '0',
  `show_name` varchar(100) DEFAULT NULL,
  `primary_phone` varchar(10) NOT NULL,
  `secondary_phone` varchar(10) DEFAULT NULL,
  `email` tinytext NOT NULL,
  `comments` tinytext,
  `about` text,
  `skills` text,
  `status` varchar(10) NOT NULL DEFAULT 'pending',
  `exposure` text,
  `station_tour` varchar(1) DEFAULT '0',
  `technical_training` varchar(1) DEFAULT '0',
  `programming_training` varchar(1) DEFAULT '0',
  `production_training` varchar(1) DEFAULT '0',
  `spoken_word_training` varchar(1) DEFAULT '0',
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `edit_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  UNIQUE KEY `student_no_UNIQUE` (`student_no`)
) ENGINE=InnoDB AUTO_INCREMENT=965 DEFAULT CHARSET=utf8;
