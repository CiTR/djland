CREATE TABLE `uploads` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `file_name` tinytext NOT NULL,
  `file_type` varchar(45) NOT NULL,
  `category` varchar(45) NOT NULL,
  `path` tinytext,
  `size` tinytext,
  `description` tinytext,
  `url` tinytext,
  `relation_id` int(10) DEFAULT NULL,
  `CREATED_AT` datetime DEFAULT NULL,
  `UPDATED_AT` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;;
