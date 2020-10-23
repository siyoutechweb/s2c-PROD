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
-- Table structure for table `product_items`
--

DROP TABLE IF EXISTS `product_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_items` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `item_online_price` double(8,2) DEFAULT NULL,
  `item_offline_price` double(8,2) DEFAULT NULL,
  `unit_price` double(8,2) DEFAULT NULL,
  `cost_price` double(8,2) DEFAULT NULL,
  `item_barcode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `item_warn_quantity` int DEFAULT NULL,
  `item_quantity` int NOT NULL,
  `item_discount_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `item_discount_price` double(8,2) DEFAULT NULL,
  `member_price` double(8,2) DEFAULT NULL,
  `member_point` double(8,2) DEFAULT NULL,
  `product_base_id` int unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_items_product_base_id_foreign` (`product_base_id`),
  CONSTRAINT `product_items_product_base_id_foreign` FOREIGN KEY (`product_base_id`) REFERENCES `product_base` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_items`
--

LOCK TABLES `product_items` WRITE;
/*!40000 ALTER TABLE `product_items` DISABLE KEYS */;
INSERT INTO `product_items` VALUES (1,299.99,299.99,NULL,NULL,'101010101',1,100,'1',299.99,NULL,NULL,1,'2020-04-20 19:24:39','2020-04-20 19:24:39'),(2,499.99,499.99,NULL,NULL,'202020202',5,100,NULL,NULL,NULL,NULL,1,'2020-04-20 19:24:39','2020-04-20 19:24:39');
/*!40000 ALTER TABLE `product_items` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-08-31 10:56:11
