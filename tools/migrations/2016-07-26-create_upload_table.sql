CREATE TABLE `uploads` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `file_name` tinytext NOT NULL,
  `file_type` varchar(45) NOT NULL,
  `category` varchar(45) NOT NULL,
  `path` tinytext,
  `size` tinytext,
  `description` tinytext,
  `url` tinytext,
  `CREATED_AT` datetime DEFAULT NULL,
  `UPDATED_AT` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
