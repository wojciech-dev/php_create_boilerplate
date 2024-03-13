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
-- Table structure for table `body`
--

DROP TABLE IF EXISTS `body`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `body` (
  `id` int NOT NULL AUTO_INCREMENT,
  `parent_id` int NOT NULL DEFAULT '0',
  `listorder` int DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text,
  `slug` varchar(100) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `more` tinyint(1) NOT NULL DEFAULT '0',
  `more_link` varchar(100) DEFAULT NULL,
  `more_label` varchar(50) DEFAULT 'read more',
  `layout` tinyint(1) NOT NULL DEFAULT '1',
  `inheritance` tinyint DEFAULT '0',
  `add_form` tinyint(1) NOT NULL,
  `photo1` varchar(50) DEFAULT NULL,
  `photo2` varchar(50) DEFAULT NULL,
  `photo3` varchar(50) DEFAULT NULL,
  `photo4` varchar(50) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `body`
--

LOCK TABLES `body` WRITE;
/*!40000 ALTER TABLE `body` DISABLE KEYS */;
INSERT INTO `body` VALUES (1,1,5,'Quisque mi velit','Mauris interdum consectetur','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec suscipit sagittis eleifend. Nulla facilisi. Praesent at erat id quam placerat sollicitudin vitae in purus. Integer ut pharetra odio. Ut tellus risus, laoreet in velit in, dignissim posuere est. Vestibulum ac eleifend quam, nec semper metus. In aliquam aliquet consectetur. Maecenas ac suscipit dolor, rhoncus suscipit dolor. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Duis tincidunt velit non nisi ullamcorper, et bibendum dui suscipit. Curabitur et fringilla eros. Phasellus vitae nisi at justo luctus mollis. Donec posuere eleifend ligula et scelerisque. Morbi molestie aliquam enim, ac varius eros pharetra at.<br />\r\n<br />\r\nNulla auctor sem quis blandit tempus. Praesent volutpat magna arcu, a suscipit arcu ullamcorper feugiat. Fusce ut sem eget nisl volutpat tristique a vitae sem. Cras non aliquet arcu, sed malesuada nisl. Vivamus commodo, ante a lacinia consequat, ligula est pharetra nunc, in placerat libero purus eget massa. Morbi imperdiet, tortor vel scelerisque vulputate, erat lectus feugiat urna, sit amet auctor neque augue at nibh. Praesent at urna felis. In auctor, mauris id viverra consectetur, arcu nibh porttitor nisl, sed consectetur enim turpis et orci. Nulla ultricies, purus nec dignissim vulputate, eros leo venenatis lectus, et efficitur sem diam a nibh. Suspendisse pulvinar, sapien sed interdum tristique, tellus justo faucibus orci, sit amet malesuada nibh nisi ut enim. Pellentesque mattis sodales convallis. Quisque maximus nulla at tempor tristique. Quisque mi velit, vestibulum ut sodales ac, consectetur non dolor. Mauris interdum consectetur eros a vehicula. Donec vulputate nibh risus, ut vehicula sapien hendrerit eget. Nullam eu lacinia dolor.','quisque-mi-velit',1,1,'','Read more',2,0,0,'XvtVIGm5C8.jpg','1Gxmba2lBD.jpg','BaVSkOrvC6.jpg','TYrtcObQGL.jpg','2022-01-07 17:33:45'),(2,1,4,'test','Curabitur pulvinar dolo','Duis neque orci, maximus nec tempor quis, vulputate vitae elit. Nullam porta tristique pharetra. Nullam nec scelerisque mi, sed finibus odio. Nam id lacus nulla. Cras sit amet orci egestas, cursus ante non, rhoncus nisi. Etiam vulputate quis orci sed varius. In condimentum consectetur nunc at tristique. Etiam fermentum enim at quam vehicula scelerisque. Curabitur pulvinar dolor at gravida pulvinar. Interdum et malesuada fames ac ante ipsum primis in faucibus. Sed tempor est sed ex pulvinar ultrices. Praesent venenatis, dolor et varius maximus, eros dolor bibendum ipsum, et efficitur velit lacus sit amet justo. Curabitur laoreet mattis erat, vel convallis justo fermentum non.','test',1,1,'','Read more',1,0,0,NULL,NULL,NULL,NULL,'2022-01-20 15:56:22'),(4,1,1,'Auctor varius sem','lorem ipsum dolor','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin vel sapien vel urna iaculis tincidunt. Vivamus vitae blandit ex, non mattis metus. Aenean finibus accumsan eros, at volutpat lectus. Fusce pretium ante vel orci laoreet, nec malesuada justo sodales. Suspendisse blandit interdum lectus at hendrerit. Nullam tristique tellus ipsum, eu euismod mauris tincidunt et. Vestibulum dapibus at turpis non congue. Proin vel dui sed quam tincidunt porta quis eu dui. Nullam elit dui, efficitur et sollicitudin ac, faucibus non neque. Nam vel lectus ut sem commodo efficitur nec congue justo. Proin ac ipsum viverra mauris sollicitudin faucibus non in dolor. Aenean tempor sed arcu ac porta. Sed elit ex, lacinia ut enim vel, dapibus volutpat ipsum.','auctor-varius-sem',1,1,'','Read more',1,0,0,NULL,NULL,NULL,NULL,'2022-01-21 14:34:57'),(6,1,2,'fgh','fgh','fgh','fgh',1,1,'','Read more',1,0,0,NULL,NULL,NULL,NULL,'2022-01-22 19:13:19'),(7,31,6,'fdgdfg','dfg','dfgdfg','fdgdfg',1,0,'','Read more',3,0,1,NULL,NULL,NULL,NULL,'2023-06-09 18:04:48');
/*!40000 ALTER TABLE `body` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-06-10 22:43:58
