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
-- Table structure for table `products`
--
CREATE TABLE IF NOT EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `product_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_barcode` bigint NOT NULL,
  `product_description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_image` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unit_price` double(8,2) NOT NULL,
  `cost_price` double(8,2) NOT NULL,
  `discount_price` decimal(8,2) DEFAULT NULL,
  `member_price` double(8,2) DEFAULT NULL,
  `member_point` double(8,2) DEFAULT NULL,
  `tax_rate` double(8,2) NOT NULL DEFAULT '0.02',
  `product_weight` double(8,2) DEFAULT NULL,
  `product_size` double(8,2) DEFAULT NULL,
  `product_color` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `supplier_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_quantity` int DEFAULT NULL,
  `warn_quantity` int DEFAULT NULL,
  `expired_date` date DEFAULT NULL,
  `category_id` int unsigned NOT NULL,
  `shop_owner_id` int unsigned NOT NULL,
  `shop_id` int unsigned DEFAULT NULL,
  `chain_id` int unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `products_category_id_foreign` (`category_id`),
  KEY `products_shop_owner_id_foreign` (`shop_owner_id`),
  KEY `products_shop_id_foreign` (`shop_id`),
  KEY `products_chain_id_foreign` (`chain_id`)
) ENGINE=MyISAM AUTO_INCREMENT=200115 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,'Product 2560',2147483647,'good','https://storage.googleapis.com/siyou-b2s.appspot.com/products/Vz6GOIWnpBHo9qdMtZcvKUT4KLAXuRCd7lrDIXAB.jpeg',332.00,6.00,NULL,NULL,NULL,0.00,NULL,NULL,NULL,'6',477,0,NULL,35,1,1,2,'2020-07-22 12:41:33','2020-08-26 14:12:32',NULL)
INSERT INTO `products` VALUES (2,'Smartphone SAMSUNG Galaxy A21S',5145898567,'Weight: 1 ; color: #f5f0f0 ; ',NULL,659.00,659.00,NULL,NULL,NULL,2.00,NULL,NULL,NULL,'31',12,NULL,NULL,27,1,NULL,NULL,NULL,'2020-08-06 08:51:44',NULL)
INSERT INTO `products` VALUES (3,'Product 4093',2000336449656,NULL,'https://storage.googleapis.com/siyou-b2s.appspot.com/products/Vz6GOIWnpBHo9qdMtZcvKUT4KLAXuRCd7lrDIXAB.jpeg',308.63,5.92,NULL,308.63,0.00,0.02,0.00,0.00,'0',NULL,487,0,NULL,1,1,1,2,'2020-07-22 12:41:33','2020-08-04 15:13:16',NULL)
INSERT INTO `products` VALUES (4,'aqua',80582588,NULL,'https://media-exp1.licdn.com/dms/image/C4D0BAQGjCiqglI2oUw/company-logo_200_200/0?e=2159024400&v=beta&t=Tj739azJkBXu_C1ZU8s9aBaSX1Kamz6igqn7he2Prms',1.30,1.00,NULL,1.30,0.00,0.02,0.00,0.00,'0',NULL,30,0,NULL,1,1,1,2,'2020-07-22 12:41:33','2020-08-21 07:11:07',NULL)
INSERT INTO `products` VALUES (5,'qwe',2000421031964,NULL,'https://media-exp1.licdn.com/dms/image/C4D0BAQGjCiqglI2oUw/company-logo_200_200/0?e=2159024400&v=beta&t=Tj739azJkBXu_C1ZU8s9aBaSX1Kamz6igqn7he2Prms',0.00,0.00,NULL,0.00,0.00,0.00,0.00,0.00,'0',NULL,0,0,NULL,158,1,1,2,'2020-08-28 00:32:26','2020-08-28 00:32:26',NULL)
INSERT INTO `products` VALUES (6,'Product 6078',2000879886499,NULL,'https://storage.googleapis.com/siyou-b2s.appspot.com/products/Vz6GOIWnpBHo9qdMtZcvKUT4KLAXuRCd7lrDIXAB.jpeg',702.68,5.67,NULL,702.68,0.00,0.02,0.00,0.00,'0',NULL,41,50,NULL,1,1,1,1,'2020-07-22 12:41:33','2020-08-26 14:12:32',NULL)

-- /*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-08-31 10:56:50
