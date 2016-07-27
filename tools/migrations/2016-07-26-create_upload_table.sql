CREATE TABLE `uploads` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `file_name` tinytext NOT NULL,
  `file_type` varchar(45) NOT NULL,
  `path` tinytext,
  `size` tinytext,
  `category` varchar(45) DEFAULT NULL,
  `description` tinytext,
  `url` tinytext,
  `CREATED_AT` datetime DEFAULT NULL,
  `EDITED_AT` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
