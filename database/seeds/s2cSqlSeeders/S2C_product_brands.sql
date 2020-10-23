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
-- Table structure for table `product_brands`
--

DROP TABLE IF EXISTS `product_brands`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_brands` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `brand_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `brand_logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_brands`
--

LOCK TABLES `product_brands` WRITE;
/*!40000 ALTER TABLE `product_brands` DISABLE KEYS */;
INSERT INTO `product_brands` VALUES (14,'Microsoft','https://storage.googleapis.com/siyou-b2s.appspot.com/brands/Dw8dR9iRhnoiCLnkSBIRkRr6dN00rjkgr3iktyQB.jpeg','2020-04-08 17:03:27','2020-04-08 17:04:07'),(16,'Lenovo','https://storage.googleapis.com/siyou-b2s.appspot.com/brands/qiHVbnBZjomf6vmglgDr0iPyUt6Omq89Ks3tZmFX.jpeg','2020-04-08 17:04:33','2020-04-08 17:04:33'),(17,'Huawei','https://storage.googleapis.com/siyou-b2s.appspot.com/brands/id2R1ow5p6QeqRGXvSLPYrGoU7G1Lc1LeG6lrfB7.jpeg','2020-04-08 17:05:06','2020-04-08 17:05:06'),(20,'Dell','https://storage.googleapis.com/siyou-b2s.appspot.com/brands/hkYo3Y7liX3vvCtkY8VduSToYTkUmW8WTbQq8d6e.jpeg','2020-04-08 18:24:57','2020-04-08 18:24:57'),(21,'Asus','https://storage.googleapis.com/siyou-b2s.appspot.com/brands/r9UlzpQiEwJWGEkgaTuME7yNM9QlJ9RawfOEDT26.png','2020-04-08 18:25:24','2020-04-08 18:25:24'),(28,'Siyou','https://storage.googleapis.com/siyou-b2s.appspot.com/brands/KLjcBzKuHDK208bEJXW2u1EYASIXYsHxtvsHXUsU.png','2020-04-20 19:06:33','2020-04-20 19:06:33'),(29,'Apple','https://storage.googleapis.com/siyou-b2s.appspot.com/brands/FLsyJRiUK2o2uqdKYiFiyJnLROGIrubPOt860WoO.jpeg','2020-05-03 14:56:49','2020-05-03 14:56:49'),(30,'Dior','https://storage.googleapis.com/siyou-b2s.appspot.com/brands/I6Gx83purGC7sNmNmOIWY5rGz1TlcInZ2uDePhPE.jpeg','2020-05-06 21:23:52','2020-05-06 21:23:52');
/*!40000 ALTER TABLE `product_brands` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-08-31 10:56:19
