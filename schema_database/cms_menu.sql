-- MySQL dump 10.13  Distrib 8.0.27, for Win64 (x86_64)
--
-- Host: localhost    Database: cms
-- ------------------------------------------------------
-- Server version	8.0.27

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `menu`
--

DROP TABLE IF EXISTS `menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `menu` (
  `id` int NOT NULL AUTO_INCREMENT,
  `parent_id` int NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `reverse` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menu`
--

LOCK TABLES `menu` WRITE;
/*!40000 ALTER TABLE `menu` DISABLE KEYS */;
INSERT INTO `menu` VALUES (1,0,'Start','start',1,1,'2021-12-09 20:10:17'),(2,0,'About','about',0,0,'2021-12-09 20:10:34'),(3,16,'Overview','carriers/overview',1,0,'2021-12-09 20:11:12'),(4,2,'Who we are','about/who-we-are',1,0,'2021-12-09 20:11:36'),(5,2,'Our history','about/our-history',1,0,'2021-12-09 20:11:51'),(6,2,'Giving back','about/giving-back',1,0,'2021-12-09 20:12:30'),(7,2,'Renaissance Alliance Insurance','about/renaissance-alliance-insurance',1,0,'2021-12-13 18:16:20'),(8,2,'Referral Network','about/referral-network',1,0,'2021-12-13 18:40:37'),(9,0,'Home and auto','home-and-auto',1,0,'2021-12-13 18:41:24'),(10,9,'Overview','home-and-auto/overview',1,0,'2021-12-13 18:42:04'),(11,9,'Flood Insurance','home-and-auto/flood-insurance',1,0,'2021-12-13 18:42:31'),(12,9,'Auto Insurance','home-and-auto/auto-insurance',1,0,'2021-12-13 18:42:51'),(13,9,'Marine Solutions','home-and-auto/marine-solutions',1,0,'2021-12-13 18:43:04'),(14,9,'Homeowners','home-and-auto/homeowners',1,0,'2021-12-13 18:43:23'),(15,0,'Business Insurance','business-insurance',1,0,'2021-12-21 21:04:40'),(16,0,'Carriers','carriers',1,0,'2021-12-21 21:05:17'),(17,0,'Contact','contact',1,0,'2021-12-21 21:05:26'),(18,0,'Register','register',1,0,'2022-01-01 15:22:11'),(19,0,'Login','login',1,0,'2022-01-01 18:23:15'),(31,0,'Test','test',1,0,'2023-06-03 20:32:33');
/*!40000 ALTER TABLE `menu` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-06-10 22:43:57
