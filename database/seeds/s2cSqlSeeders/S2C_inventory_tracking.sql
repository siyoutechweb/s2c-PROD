-- MySQL dump 10.13  Distrib 8.0.19, for Win64 (x86_64)
--
-- Host: 46.105.184.235    Database: S2C
-- ------------------------------------------------------
-- Server version	8.0.19

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
-- Table structure for table `inventory_tracking`
--

DROP TABLE IF EXISTS `inventory_tracking`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inventory_tracking` (
  `id` int NOT NULL,
  `update_scope_id` varchar(36) DEFAULT NULL,
  `timestamp` bigint DEFAULT NULL,
  `sync_row_is_tombstone` bit(1) NOT NULL DEFAULT b'0',
  `last_change_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventory_tracking`
--

LOCK TABLES `inventory_tracking` WRITE;
/*!40000 ALTER TABLE `inventory_tracking` DISABLE KEYS */;
INSERT INTO `inventory_tracking` VALUES (77,NULL,15982588599061,_binary '','2020-08-24 08:47:39'),(78,NULL,15982588599708,_binary '','2020-08-24 08:47:39'),(79,NULL,15982589797229,_binary '','2020-08-24 08:49:39'),(82,NULL,15982590136180,_binary '','2020-08-24 08:50:13'),(83,NULL,15982590769705,_binary '','2020-08-24 08:51:16'),(85,NULL,15982589117548,_binary '','2020-08-24 08:48:31'),(94,NULL,15982588494027,_binary '','2020-08-24 08:47:29'),(95,NULL,15982589118195,_binary '','2020-08-24 08:48:31'),(96,NULL,15982589118842,_binary '','2020-08-24 08:48:31'),(97,NULL,15982588404016,_binary '','2020-08-24 08:47:20'),(98,NULL,15982588404676,_binary '','2020-08-24 08:47:20'),(99,NULL,15982588405335,_binary '','2020-08-24 08:47:20'),(100,NULL,15982588405980,_binary '','2020-08-24 08:47:20'),(101,NULL,15982589119488,_binary '','2020-08-24 08:48:31'),(103,NULL,15985738715169,_binary '\0','2020-08-28 00:17:51'),(104,NULL,15985740957190,_binary '\0','2020-08-28 00:21:35');
/*!40000 ALTER TABLE `inventory_tracking` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-08-31 10:56:12