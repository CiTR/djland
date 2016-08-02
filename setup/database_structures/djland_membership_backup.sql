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
-- Table structure for table `membership_backup`
--

DROP TABLE IF EXISTS `membership_backup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `membership_backup` (
  `id` int(11) NOT NULL DEFAULT '0',
  `lastname` tinytext CHARACTER SET latin1,
  `firstname` tinytext CHARACTER SET latin1,
  `gender` tinytext CHARACTER SET latin1,
  `cdn` tinyint(1) DEFAULT NULL,
  `address` tinytext CHARACTER SET latin1,
  `city` tinytext CHARACTER SET latin1,
  `postal` tinytext CHARACTER SET latin1,
  `cell` tinytext CHARACTER SET latin1,
  `home` tinytext CHARACTER SET latin1,
  `work` tinytext CHARACTER SET latin1,
  `email` tinytext CHARACTER SET latin1,
  `status_id` int(11) NOT NULL DEFAULT '5',
  `joined` year(4) NOT NULL DEFAULT '0000',
  `last_paid` year(4) NOT NULL DEFAULT '0000',
  `comments` tinytext CHARACTER SET latin1,
  `show` tinytext CHARACTER SET latin1,
  `djs` tinyint(4) NOT NULL DEFAULT '0',
  `mobile` tinyint(4) NOT NULL DEFAULT '0',
  `newsdept` tinyint(4) NOT NULL DEFAULT '0',
  `sportsdept` tinyint(4) NOT NULL DEFAULT '0',
  `board` tinyint(4) NOT NULL DEFAULT '0',
  `discorder` tinyint(4) NOT NULL DEFAULT '0',
  `executive` tinyint(4) NOT NULL DEFAULT '0',
  `women` tinyint(4) NOT NULL DEFAULT '0',
  `fill_in` tinyint(4) NOT NULL DEFAULT '0',
  `dept` tinyint(4) NOT NULL DEFAULT '0',
  `int_music` tinyint(4) NOT NULL DEFAULT '0',
  `int_arts` tinyint(4) NOT NULL DEFAULT '0',
  `int_spoken` tinyint(4) NOT NULL DEFAULT '0',
  `int_magazine` tinyint(4) NOT NULL DEFAULT '0',
  `int_promotions` tinyint(4) NOT NULL DEFAULT '0',
  `int_livesound` tinyint(1) DEFAULT NULL,
  `int_design` tinyint(1) DEFAULT NULL,
  `int_web` tinyint(1) DEFAULT NULL,
  `int_progcom` tinyint(1) DEFAULT NULL,
  `int_adpsa` tinyint(1) DEFAULT NULL,
  `int_video` tinyint(1) DEFAULT NULL,
  `int_other` tinytext CHARACTER SET latin1,
  `added` date DEFAULT NULL,
  `modified` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
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
