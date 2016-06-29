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
-- Table structure for table `membership_years`
--

DROP TABLE IF EXISTS `membership_years`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `membership_years` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `member_id` int(11) unsigned NOT NULL,
  `membership_year` varchar(9) NOT NULL,
  `paid` varchar(1) NOT NULL DEFAULT '0',
  `sports` varchar(1) DEFAULT '0',
  `news` varchar(1) DEFAULT '0',
  `arts` varchar(1) DEFAULT '0',
  `music` varchar(1) DEFAULT '0',
  `show_hosting` varchar(1) DEFAULT '0',
  `live_broadcast` varchar(1) DEFAULT '0',
  `tech` varchar(1) DEFAULT '0',
  `programming_committee` varchar(1) DEFAULT '0',
  `ads_psa` varchar(1) DEFAULT '0',
  `promotions_outreach` varchar(1) DEFAULT '0',
  `discorder` varchar(1) DEFAULT '0',
  `discorder_2` varchar(1) DEFAULT '0',
  `digital_library` varchar(1) DEFAULT '0',
  `photography` varchar(1) DEFAULT '0',
  `tabling` varchar(45) DEFAULT '0',
  `dj` varchar(1) DEFAULT '0',
  `other` varchar(45) DEFAULT NULL,
  `create_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `edit_date` timestamp NULL DEFAULT NULL,
  `womens_collective` varchar(16) DEFAULT '0',
  `indigenous_collective` varchar(16) DEFAULT '0',
  `accessibility_collective` varchar(16) DEFAULT '0',
  PRIMARY KEY (`id`,`member_id`,`membership_year`),
  KEY `member_id_idx` (`member_id`),
  CONSTRAINT `id` FOREIGN KEY (`member_id`) REFERENCES `membership` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=880 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-06-20 15:07:28
