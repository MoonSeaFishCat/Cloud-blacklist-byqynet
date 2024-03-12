-- MySQL dump 10.13  Distrib 5.7.40, for Linux (x86_64)
--
-- Host: localhost    Database: yh
-- ------------------------------------------------------
-- Server version	5.7.40-log

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
-- Table structure for table `qzy_Appeal information backup`
--

DROP TABLE IF EXISTS `qzy_Appeal information backup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `qzy_Appeal information backup` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `black_info` varchar(255) NOT NULL,
  `black_reason` varchar(255) NOT NULL,
  `appeal_reason` text NOT NULL,
  `appeal_evidence` text NOT NULL,
  `contact_email` varchar(255) NOT NULL,
  `approved` tinyint(1) NOT NULL,
  `reject_reason` varchar(255) NOT NULL,
  `auditor` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `qzy_Appeal information backup`
--

LOCK TABLES `qzy_Appeal information backup` WRITE;
/*!40000 ALTER TABLE `qzy_Appeal information backup` DISABLE KEYS */;
/*!40000 ALTER TABLE `qzy_Appeal information backup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `qzy_Cloud black information backup`
--

DROP TABLE IF EXISTS `qzy_Cloud black information backup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `qzy_Cloud black information backup` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cloud_black_info` varchar(255) NOT NULL,
  `cloud_black_reason` text NOT NULL,
  `scammed_amount` decimal(10,2) NOT NULL,
  `contact_email` varchar(255) NOT NULL,
  `image_paths` text,
  `reason` varchar(255) NOT NULL,
  `auditor` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `qzy_Cloud black information backup`
--

LOCK TABLES `qzy_Cloud black information backup` WRITE;
/*!40000 ALTER TABLE `qzy_Cloud black information backup` DISABLE KEYS */;
INSERT INTO `qzy_Cloud black information backup` VALUES (2,'114514','测试提交',15.00,'isqynet@outlook.com','../upload/屏幕截图 2023-09-12 092714.png','','','2023-09-12 01:34:20'),(3,'114514','2541541',16402.00,'isqynet@outlook.com','../upload/屏幕截图 2023-09-12 092714.png','','','2023-09-12 01:37:28'),(4,'1265416365','册封为粉色',154544.00,'isqynet@outlook.com','../upload/屏幕截图 2023-09-12 092714.png','','','2023-09-12 01:42:30'),(5,'15481544','产生的我去打球',2318541.00,'isqynet@outlook.com','../upload/屏幕截图 2023-09-12 092714.png','','','2023-09-12 01:44:11');
/*!40000 ALTER TABLE `qzy_Cloud black information backup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `qzy_admin`
--

DROP TABLE IF EXISTS `qzy_admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `qzy_admin` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Username` varchar(255) DEFAULT NULL,
  `Password` varchar(255) DEFAULT NULL,
  `Email` varchar(255) DEFAULT NULL,
  `Created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `qzy_admin`
--

LOCK TABLES `qzy_admin` WRITE;
/*!40000 ALTER TABLE `qzy_admin` DISABLE KEYS */;
INSERT INTO `qzy_admin` VALUES (1,'2456737694','ijY1bvUgG/mvrBVCRo43W1cC2X3VPxveDL29DWnsMrUYafVL2021zjjP3yWUIkTwSU6kzPkSFjzN9gnxw+XCdw==','liluogzs@gmail.com','2023-06-16 12:36:45');
/*!40000 ALTER TABLE `qzy_admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `qzy_appeal`
--

DROP TABLE IF EXISTS `qzy_appeal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `qzy_appeal` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `black_info` varchar(255) NOT NULL,
  `black_reason` varchar(255) NOT NULL,
  `appeal_reason` text NOT NULL,
  `appeal_evidence` text NOT NULL,
  `contact_email` varchar(255) NOT NULL,
  `reject_reason` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `qzy_appeal`
--

LOCK TABLES `qzy_appeal` WRITE;
/*!40000 ALTER TABLE `qzy_appeal` DISABLE KEYS */;
INSERT INTO `qzy_appeal` VALUES (10,'1451455','测试一下','尝试提交','../upload/64f529b445002_屏幕截图 2023-07-05 134914.png','isqynet@outlook.com','','2023-09-04 00:50:06');
/*!40000 ALTER TABLE `qzy_appeal` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `qzy_appeal_pending`
--

DROP TABLE IF EXISTS `qzy_appeal_pending`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `qzy_appeal_pending` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `black_info` varchar(255) NOT NULL,
  `black_reason` varchar(255) NOT NULL,
  `appeal_reason` text NOT NULL,
  `appeal_evidence` text NOT NULL,
  `contact_email` varchar(255) NOT NULL,
  `approved` tinyint(1) NOT NULL,
  `reject_reason` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `qzy_appeal_pending`
--

LOCK TABLES `qzy_appeal_pending` WRITE;
/*!40000 ALTER TABLE `qzy_appeal_pending` DISABLE KEYS */;
/*!40000 ALTER TABLE `qzy_appeal_pending` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `qzy_blacklist`
--

DROP TABLE IF EXISTS `qzy_blacklist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `qzy_blacklist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cloud_black_info` varchar(255) NOT NULL,
  `cloud_black_reason` text NOT NULL,
  `scammed_amount` decimal(10,2) NOT NULL,
  `contact_email` varchar(255) NOT NULL,
  `image_paths` text,
  `cloud_black_level` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `qzy_blacklist`
--

LOCK TABLES `qzy_blacklist` WRITE;
/*!40000 ALTER TABLE `qzy_blacklist` DISABLE KEYS */;
INSERT INTO `qzy_blacklist` VALUES (4,'3125138932','诈骗源码',99999.00,'773692200@qq.com','../upload/4d94442a236b0de0d0e6325cfc926409.jpg',2,'2023-11-24 10:14:22');
/*!40000 ALTER TABLE `qzy_blacklist` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `qzy_blacklist_pending`
--

DROP TABLE IF EXISTS `qzy_blacklist_pending`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `qzy_blacklist_pending` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cloud_black_info` varchar(255) NOT NULL,
  `cloud_black_reason` text NOT NULL,
  `scammed_amount` decimal(10,2) NOT NULL,
  `contact_email` varchar(255) NOT NULL,
  `image_paths` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `qzy_blacklist_pending`
--

LOCK TABLES `qzy_blacklist_pending` WRITE;
/*!40000 ALTER TABLE `qzy_blacklist_pending` DISABLE KEYS */;
/*!40000 ALTER TABLE `qzy_blacklist_pending` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `qzy_config`
--

DROP TABLE IF EXISTS `qzy_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `qzy_config` (
  `id` int(11) NOT NULL DEFAULT '0',
  `Site Name` varchar(255) NOT NULL,
  `Copyright Notice` varchar(255) NOT NULL,
  `contact information` varchar(255) NOT NULL,
  `Announcement content` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `qzy_config`
--

LOCK TABLES `qzy_config` WRITE;
/*!40000 ALTER TABLE `qzy_config` DISABLE KEYS */;
INSERT INTO `qzy_config` VALUES (0,'轻之忆','© 2023-2023 云端黑名单系统','3240919748','','isqynetkj@163.com','KAGMQFOXWDAQLBWM');
/*!40000 ALTER TABLE `qzy_config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `qzy_user`
--

DROP TABLE IF EXISTS `qzy_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `qzy_user` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Username` varchar(255) DEFAULT NULL,
  `Password` varchar(255) DEFAULT NULL,
  `Email` varchar(255) DEFAULT NULL,
  `bind_qq` varchar(20) NOT NULL,
  `Created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `qzy_user`
--

LOCK TABLES `qzy_user` WRITE;
/*!40000 ALTER TABLE `qzy_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `qzy_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'yh'
--

--
-- Dumping routines for database 'yh'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-03-11  2:30:13
