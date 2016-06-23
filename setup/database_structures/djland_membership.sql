-- MySQL dump 10.13  Distrib 5.6.23, for Win32 (x86)
--
-- Host: 127.0.0.1    Database: djland
-- ------------------------------------------------------
-- Server version	5.6.24

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `membership`
--

DROP TABLE IF EXISTS `membership`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
  `edit_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  UNIQUE KEY `student_no_UNIQUE` (`student_no`)
) ENGINE=InnoDB AUTO_INCREMENT=965 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-06-20 15:07:26
