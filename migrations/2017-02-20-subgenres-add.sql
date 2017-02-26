CREATE TABLE `subgenres` (
  `id` int(11) NOT NULL,
  `subgenre` varchar(255) NOT NULL,
  `parent_genre_id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;