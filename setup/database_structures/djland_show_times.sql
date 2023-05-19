CREATE TABLE `show_times` (
  `show_id` int(10) NOT NULL,
  `start_day` int(3) NOT NULL,
  `start_time` time NOT NULL,
  `end_day` int(3) NOT NULL,
  `end_time` time NOT NULL,
  `alternating` int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`show_id`,`start_day`,`start_time`,`end_day`,`end_time`,`alternating`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
