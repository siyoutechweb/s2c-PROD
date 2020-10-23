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
-- Table structure for table `chains`
--

DROP TABLE IF EXISTS `chains`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `chains` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `store_id` int unsigned DEFAULT NULL,
  `chain_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `adress` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `chain_mobile` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `chain_telephone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `chain_opening_hours` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `chain_close_hours` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `chain_trafic_line` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `chain_img` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `chain_lng` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `chain_lat` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `approved` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `fattura_format` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `next_format_num` int DEFAULT NULL,
  `chain_ip` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `chain_district_id` int unsigned DEFAULT NULL,
  `chain_district_info` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shop_owner_id` int unsigned NOT NULL,
  `manager_id` int unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `cash_registers` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `chains_store_id_foreign` (`store_id`),
  KEY `chains_shop_owner_id_foreign` (`shop_owner_id`),
  KEY `chains_manager_id_foreign` (`manager_id`)
) ENGINE=MyISAM AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chains`
--

LOCK TABLES `chains` WRITE;
/*!40000 ALTER TABLE `chains` DISABLE KEYS */;
INSERT INTO `chains` VALUES (1,1,'Carrefour Market le Passage','Tunis -Tunisia',NULL,NULL,'70 011 000','08:00h','22:00',NULL,NULL,NULL,NULL,'1',NULL,NULL,NULL,NULL,NULL,1,11,'2020-02-21 14:17:56','2020-07-21 14:02:40',NULL,NULL),(2,1,'Carrefour Market ','Les Jardins El Mourouj - tunis -Tunisia',NULL,NULL,'70 248 248','08:00h','22:00',NULL,NULL,NULL,NULL,'1',NULL,NULL,NULL,NULL,NULL,1,28,'2020-02-21 14:17:56','2020-07-09 15:34:27',NULL,NULL),(3,2,'monoprix Bardo ','tunis, Tunisia',NULL,NULL,'71 580 415','08:00h','23:00',NULL,NULL,NULL,NULL,'1',NULL,NULL,NULL,NULL,NULL,5,NULL,'2020-02-21 14:17:56','2020-02-21 14:17:56',NULL,2),(4,2,'monoprix Express ','Place Mizene, Tunis, Tunisia',NULL,NULL,'71 751 429','08:00h','23:00',NULL,NULL,NULL,NULL,'1',NULL,NULL,NULL,NULL,NULL,5,NULL,'2020-02-21 14:17:56','2020-02-21 14:17:56',NULL,3),(8,1,'carrefour manouba','manouba',NULL,'','71485965','08:00 h','22:00 h',NULL,NULL,'','','1',NULL,NULL,NULL,NULL,NULL,1,19,'2020-07-01 14:54:07','2020-07-07 16:34:20',NULL,NULL),(32,1,'Store 1','centre urbain nord','20147789',NULL,NULL,'01:01','14:02',NULL,NULL,NULL,NULL,'1',NULL,NULL,NULL,NULL,NULL,1,11,'2020-07-08 09:13:50','2020-07-09 15:15:31',NULL,NULL),(33,NULL,'store1','italia','21392122',NULL,NULL,'07:30','22:00',NULL,NULL,NULL,NULL,'1',NULL,NULL,NULL,NULL,NULL,25,NULL,'2020-07-10 10:52:20','2020-07-10 10:52:20',NULL,NULL),(34,NULL,'store1','Roma','42156982',NULL,NULL,'08:00','22:00',NULL,NULL,NULL,NULL,'1',NULL,NULL,NULL,NULL,NULL,25,NULL,'2020-07-10 11:10:50','2020-07-10 11:10:50',NULL,NULL),(35,NULL,'store1','Roma Italia','75655220',NULL,NULL,'07:00','22:00',NULL,NULL,NULL,NULL,'1',NULL,NULL,NULL,NULL,NULL,25,NULL,'2020-07-10 11:12:10','2020-07-10 11:12:10',NULL,NULL),(31,1,'TEST YASSINE','TEST','2366666',NULL,NULL,'17:35','22:40',NULL,NULL,NULL,NULL,'1',NULL,NULL,NULL,NULL,NULL,1,26,'2020-07-07 16:35:36','2020-07-09 15:18:25',NULL,NULL);
/*!40000 ALTER TABLE `chains` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-08-31 10:56:53
