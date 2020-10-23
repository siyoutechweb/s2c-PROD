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
-- Table structure for table `inventory`
--

DROP TABLE IF EXISTS `inventory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inventory` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `batch_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `operator` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `verifier` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` date DEFAULT NULL,
  `warehouse_id` int unsigned DEFAULT NULL,
  `supplier_id` int unsigned DEFAULT NULL,
  `shop_owner_id` int unsigned DEFAULT NULL,
  `operator_status` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `verifier_status` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=105 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventory`
--

LOCK TABLES `inventory` WRITE;
/*!40000 ALTER TABLE `inventory` DISABLE KEYS */;
INSERT INTO `inventory` VALUES (1,'20200630-153339','operator test','verifier test','2020-07-03',1,3,1,3,'2020-07-01 13:08:36','2020-07-01 13:08:36',5),(3,'20200701-153339','operator test','verifier test','2020-06-22',1,4,1,3,'2020-07-01 13:47:18','2020-07-07 14:47:14',5),(80,'20200803-105757','TEST','TEST',NULL,1,2,NULL,1,'2020-08-03 10:58:15','2020-08-03 10:58:15',2),(81,'20200803-110038','test','test',NULL,1,2,NULL,1,'2020-08-03 11:01:17','2020-08-03 11:01:17',1),(84,'20200630-153337','operator 5','verifier 5',NULL,2,34,NULL,2,'2020-08-03 12:57:37','2020-08-03 12:57:37',4),(86,'20200803-140445','Test 6','Test 6',NULL,2,34,NULL,3,'2020-08-03 14:07:00','2020-08-03 14:07:00',2),(87,'20200803-140445','Test 6','Test 6',NULL,2,34,NULL,3,'2020-08-03 14:07:29','2020-08-03 14:07:29',2),(88,'20200803-140445','Test 6','Test 6',NULL,2,30,NULL,3,'2020-08-03 14:07:46','2020-08-03 14:07:46',2),(89,'20200803-141542','mathieu','mathieu',NULL,2,2,NULL,1,'2020-08-03 14:16:41','2020-08-03 14:16:41',1),(90,'20200803-142959','Operator','Verifier',NULL,2,30,NULL,2,'2020-08-03 14:30:31','2020-08-03 14:30:31',2),(91,'20200803-143335','test07','test07',NULL,1,2,NULL,2,'2020-08-03 14:34:35','2020-08-03 14:34:35',2),(92,'20200803-143444','test08','test08',NULL,1,2,NULL,1,'2020-08-03 14:35:40','2020-08-03 14:35:40',1),(93,'20200803-143806','Test09','test09',NULL,2,2,NULL,3,'2020-08-03 14:39:08','2020-08-04 15:37:38',5),(103,'','','',NULL,NULL,NULL,NULL,NULL,'2020-08-28 00:17:51','2020-08-28 00:17:51',NULL),(104,'20200828-002111','eewq','qwe',NULL,NULL,34,NULL,2,'2020-08-28 00:21:35','2020-08-28 00:21:35',1);
/*!40000 ALTER TABLE `inventory` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-08-31 10:56:31
