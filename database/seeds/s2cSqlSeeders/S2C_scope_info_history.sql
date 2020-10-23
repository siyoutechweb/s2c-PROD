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
-- Table structure for table `scope_info_history`
--

DROP TABLE IF EXISTS `scope_info_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `scope_info_history` (
  `sync_scope_id` varchar(36) NOT NULL,
  `sync_scope_name` varchar(100) NOT NULL,
  `scope_last_sync_timestamp` bigint DEFAULT NULL,
  `scope_last_sync_duration` bigint DEFAULT NULL,
  `scope_last_sync` datetime DEFAULT NULL,
  PRIMARY KEY (`sync_scope_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `scope_info_history`
--

LOCK TABLES `scope_info_history` WRITE;
/*!40000 ALTER TABLE `scope_info_history` DISABLE KEYS */;
INSERT INTO `scope_info_history` VALUES ('0046292e-59d1-474b-8db1-c7cd6b8ca3f1','DefaultScope',15979344113337,151014105,'2020-08-20 14:40:10'),('0478bd65-3307-4ac4-b362-be8d6af0942c','DefaultScope',15968081110546,28528014,'2020-08-07 13:48:31'),('0714af3b-f196-431a-9a45-c6e60379831e','DefaultScope',15970181634729,36409283,'2020-08-10 00:09:24'),('35eb092d-b26b-431d-ba59-a6ea1af8f45f','DefaultScope',15968016721424,15277920,'2020-08-07 12:01:12'),('43ce0f5b-74dc-424f-952b-4f8d77342044','DefaultScope',15970598343434,30032166,'2020-08-10 11:43:55'),('61086804-916d-48b2-9d30-bbf27a2f095f','DefaultScope',15974928536023,118881347,'2020-08-15 12:00:56'),('b81eee6e-d902-4fa2-adff-000e4ed75995','DefaultScope',15974068239199,17861331,'2020-08-14 11:06:53');
/*!40000 ALTER TABLE `scope_info_history` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-08-31 10:57:25
