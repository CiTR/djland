CREATE TABLE IF NOT EXISTS `log` (
  `index` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `error` tinytext COLLATE utf8_bin NOT NULL,
  `data` tinytext COLLATE utf8_bin NOT NULL,
  `user` varchar(40) NOT NULL,
  `DATE_CREATED` datetime NOT NULL,
  PRIMARY KEY (`index`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;