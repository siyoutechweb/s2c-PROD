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
-- Table structure for table `product_images`
--

DROP TABLE IF EXISTS `product_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_images` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `image_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_item_id` int unsigned DEFAULT NULL,
  `is_selected` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_images_product_item_id_foreign` (`product_item_id`),
  CONSTRAINT `product_images_product_item_id_foreign` FOREIGN KEY (`product_item_id`) REFERENCES `product_items` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_images`
--

LOCK TABLES `product_images` WRITE;
/*!40000 ALTER TABLE `product_images` DISABLE KEYS */;
INSERT INTO `product_images` VALUES (1,'https://storage.googleapis.com/siyou-b2s.appspot.com/products/rpcW8AfslhO6pBv7KXW32oqpyJ2GQkznE5VW3Luf.png',2,0,'2020-04-20 19:24:20','2020-04-20 19:24:39'),(2,'https://storage.googleapis.com/siyou-b2s.appspot.com/products/mEUww2bqskIFpt0yF3Ur0pzr5B1r4vyoHDsG5nuQ.png',2,0,'2020-04-20 19:24:21','2020-04-20 19:24:39'),(3,'https://storage.googleapis.com/siyou-b2s.appspot.com/products/N3NTEUpAPyln1vuIbo1dNbsysbEtHaYE4jnZWbIz.png',NULL,0,'2020-04-13 13:07:22','2020-04-13 13:07:22'),(4,'https://storage.googleapis.com/siyou-b2s.appspot.com/products/MbCGwnDUD87nhAbY15S9OjpuhEEh862XNSGEMTef.png',NULL,0,'2020-04-15 10:13:30','2020-04-15 10:13:30'),(5,'https://storage.googleapis.com/siyou-b2s.appspot.com/products/VSKc7opTbZ81IoBg54nO9szHr5a15XUhy0l8kY37.png',NULL,0,'2020-04-15 10:13:41','2020-04-15 10:13:41'),(6,'https://storage.googleapis.com/siyou-b2s.appspot.com/products/MTZSpIgDrgbuzjH6ULqD9ystpB2NFeW8v3FMHMbm.jpeg',NULL,0,'2020-04-15 10:13:42','2020-04-15 10:13:42'),(13,'https://storage.googleapis.com/siyou-b2s.appspot.com/products/e5b0RtSdl5muH7tD1BNfomCeBbwOEJNys5k0U4Tf.jpeg',NULL,0,'2020-04-17 09:29:14','2020-04-17 09:29:14'),(14,'https://storage.googleapis.com/siyou-b2s.appspot.com/products/xyDWP4Vma1lWLvsN8v1aU3awvJQH1sPl8nHI7eq5.jpeg',NULL,0,'2020-04-17 09:32:13','2020-04-17 09:32:13'),(15,'https://storage.googleapis.com/siyou-b2s.appspot.com/products/3YMOXadrdUyIgEMNiSVYgDmybLCS6ci8X7jWwqir.jpeg',NULL,0,'2020-04-17 16:45:18','2020-04-17 16:45:18'),(16,'https://storage.googleapis.com/siyou-b2s.appspot.com/products/fZzngO00pyJMg7L4fG38Y5EyPUQPN3uqcvcY2FQs.jpeg',NULL,0,'2020-04-17 16:57:33','2020-04-17 16:57:33'),(17,'https://storage.googleapis.com/siyou-b2s.appspot.com/products/n1bIaOFRZr6gN9KIY9KqPdZ1exRsPiMB1T8BUH01.jpeg',NULL,0,'2020-04-17 17:06:17','2020-04-17 17:06:35'),(18,'https://storage.googleapis.com/siyou-b2s.appspot.com/products/wiocBltxzzZBm62yKkSMPMlJAFil1rK2QTPQMeW2.jpeg',NULL,0,'2020-04-17 17:06:23','2020-04-17 17:06:35'),(19,'https://storage.googleapis.com/siyou-b2s.appspot.com/products/sCervoy1IzdaZtuav0tSq2tJPf57Fj45kugu34tv.jpeg',NULL,0,'2020-04-17 17:06:24','2020-04-17 17:06:35'),(20,'https://storage.googleapis.com/siyou-b2s.appspot.com/products/XyoJBokw954fRawveZPJS5NbpAWROiIQID81v13n.jpeg',NULL,0,'2020-04-17 17:09:11','2020-04-17 17:09:11'),(21,'https://storage.googleapis.com/siyou-b2s.appspot.com/products/L3By96AFA4YEKzRtJuhALajAokph65R1POgpbZrD.jpeg',NULL,0,'2020-04-17 17:12:45','2020-04-17 17:12:45'),(22,'https://storage.googleapis.com/siyou-b2s.appspot.com/products/lZe4kEnIpAkzzERbxOiovXcOek9fwvhlJ5cLBOyl.jpeg',NULL,0,'2020-04-17 17:14:28','2020-04-17 17:14:28'),(23,'https://storage.googleapis.com/siyou-b2s.appspot.com/products/PHRkOLkG3fzAuSVOLWBPMvdTHiRLTqoq2f5FJqxX.jpeg',NULL,0,'2020-04-17 17:21:03','2020-04-17 17:21:33'),(24,'https://storage.googleapis.com/siyou-b2s.appspot.com/products/Ut7vWwFfui4imlSKyMAiYPEJIOD7GeRAEPNRZFmF.png',NULL,0,'2020-04-17 18:04:37','2020-04-17 18:05:33'),(25,'https://storage.googleapis.com/siyou-b2s.appspot.com/products/7ltMCpmps9npgXUUWT6hBt0gJGKp5RcqGqbuLAaH.png',NULL,0,'2020-04-17 21:27:13','2020-04-17 21:27:38'),(26,'https://storage.googleapis.com/siyou-b2s.appspot.com/products/FZcBTJQZxCuW8kbFb9pA8ADy7Hu4f8PuygoWCfUQ.jpeg',NULL,0,'2020-04-20 19:09:17','2020-04-20 19:09:36'),(27,'https://storage.googleapis.com/siyou-b2s.appspot.com/products/I7pak2gzuzpkya80fMWlg8cfxKp3u4FFckkmWgHt.jpeg',NULL,0,'2020-04-20 19:09:19','2020-04-20 19:09:36'),(28,'https://storage.googleapis.com/siyou-b2s.appspot.com/products/itMUNxEGXPPi7xvPTZ9zv7W4uSaox4cAPas4POxf.png',NULL,0,'2020-04-20 19:23:07','2020-04-20 19:24:39'),(29,'https://storage.googleapis.com/siyou-b2s.appspot.com/products/uRB5YSu5HQB58AKkAaYVh9riLBvEiULeSeTRTPt8.png',NULL,0,'2020-04-20 19:23:07','2020-04-20 19:24:39'),(30,'https://storage.googleapis.com/siyou-b2s.appspot.com/products/9E7xGePeXg0WBbfGZtIPbjr7KXe9hqET2GCno0WO.png',NULL,0,'2020-04-20 19:23:07','2020-04-20 19:24:39'),(31,'https://storage.googleapis.com/siyou-b2s.appspot.com/products/rpcW8AfslhO6pBv7KXW32oqpyJ2GQkznE5VW3Luf.png',NULL,0,'2020-04-20 19:24:20','2020-04-20 19:24:39'),(32,'https://storage.googleapis.com/siyou-b2s.appspot.com/products/mEUww2bqskIFpt0yF3Ur0pzr5B1r4vyoHDsG5nuQ.png',NULL,0,'2020-04-20 19:24:21','2020-04-20 19:24:39'),(33,'https://storage.googleapis.com/siyou-b2s.appspot.com/products/qjeLuvX36nDKbYPuubj1TNyDRCp3o7JEEkcOkxRT.jpeg',NULL,0,'2020-04-20 19:32:20','2020-04-20 19:32:44');
/*!40000 ALTER TABLE `product_images` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-08-31 10:57:28
