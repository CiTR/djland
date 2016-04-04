CREATE TABLE IF NOT EXISTS `djland_options` (
  `index` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `djland_option` tinytext COLLATE utf8_bin NOT NULL,
  `value` text COLLATE utf8_bin NOT NULL,
  `CREATED_AT` datetime NOT NULL,
  `UPDATED_AT` datetime NOT NULL,
  PRIMARY KEY (`index`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
INSERT INTO `djland_options` (index,djland_option,value) VALUES (1,'membership_cutoff','2014/2015');