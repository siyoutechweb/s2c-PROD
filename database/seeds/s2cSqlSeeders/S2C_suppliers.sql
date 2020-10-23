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
-- Table structure for table `suppliers`
--

DROP TABLE IF EXISTS `suppliers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `suppliers` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `img_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `img_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude` decimal(17,13) DEFAULT NULL,
  `longitude` decimal(17,13) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `suppliers`
--

LOCK TABLES `suppliers` WRITE;
/*!40000 ALTER TABLE `suppliers` DISABLE KEYS */;
INSERT INTO `suppliers` VALUES (1,'Herminia','Altenwerth',NULL,'kolson@smitham.com','https://sliceedit.com/images/avatar.png',NULL,36.8480320000000,10.1967710000000,NULL,NULL),(2,'Austin','Sawayn',NULL,'wmorissette@luettgen.info','https://sliceedit.com/images/avatar.png',NULL,36.8480320000000,10.1967710000000,NULL,NULL),(3,'Jaylin','Murphy',NULL,'cory.batz@kassulke.net','https://sliceedit.com/images/avatar.png',NULL,36.8480320000000,10.1967710000000,NULL,NULL),(4,'Layne','Kerluke',NULL,'heath.ferry@yahoo.com','https://sliceedit.com/images/avatar.png',NULL,36.8480320000000,10.1967710000000,NULL,NULL),(5,'Hobart','Hilpert',NULL,'derrick97@hotmail.com','https://sliceedit.com/images/avatar.png',NULL,36.8480320000000,10.1967710000000,NULL,NULL),(6,'Marge','Brekke',NULL,'carroll.andres@gmail.com','https://sliceedit.com/images/avatar.png',NULL,36.8480320000000,10.1967710000000,NULL,NULL),(7,'Jenifer','Vandervort',NULL,'allan72@yahoo.com','https://sliceedit.com/images/avatar.png',NULL,36.8480320000000,10.1967710000000,NULL,NULL),(8,'Harmony','Bergstrom',NULL,'adelbert.rosenbaum@fahey.biz','https://sliceedit.com/images/avatar.png',NULL,36.8480320000000,10.1967710000000,NULL,NULL),(9,'Claud','Cruickshank',NULL,'howell.schmitt@mckenzie.com','https://sliceedit.com/images/avatar.png',NULL,36.8480320000000,10.1967710000000,NULL,NULL),(10,'Ubaldo','Upton',NULL,'jast.chaya@yahoo.com','https://sliceedit.com/images/avatar.png',NULL,36.8480320000000,10.1967710000000,NULL,NULL),(11,'Camille','Hilpert',NULL,'cole.mona@weber.biz','https://sliceedit.com/images/avatar.png',NULL,36.8480320000000,10.1967710000000,NULL,NULL),(12,'Haleigh','Bauch',NULL,'elinore.champlin@yahoo.com','https://sliceedit.com/images/avatar.png',NULL,36.8480320000000,10.1967710000000,NULL,NULL),(13,'Carmelo','Funk',NULL,'emily69@parisian.com','https://sliceedit.com/images/avatar.png',NULL,36.8480320000000,10.1967710000000,NULL,NULL),(14,'Marjolaine','Sporer',NULL,'fherman@yahoo.com','https://sliceedit.com/images/avatar.png',NULL,36.8480320000000,10.1967710000000,NULL,NULL),(15,'Conrad','Haley',NULL,'berge.porter@macejkovic.info','https://sliceedit.com/images/avatar.png',NULL,36.8480320000000,10.1967710000000,NULL,NULL),(16,'Darren','Mills',NULL,'murl83@jerde.com','https://sliceedit.com/images/avatar.png',NULL,36.8480320000000,10.1967710000000,NULL,NULL),(17,'Emilia','O\'Kon',NULL,'lang.melyssa@bauch.net','https://sliceedit.com/images/avatar.png',NULL,36.8480320000000,10.1967710000000,NULL,NULL),(18,'Elisha','Price',NULL,'braun.tyler@ankunding.com','https://sliceedit.com/images/avatar.png',NULL,36.8480320000000,10.1967710000000,NULL,NULL),(19,'Derick','Ullrich',NULL,'esteban.herman@yahoo.com','https://sliceedit.com/images/avatar.png',NULL,36.8480320000000,10.1967710000000,NULL,NULL),(20,'Felipe','Beier',NULL,'rosenbaum.natalie@beahan.biz','https://sliceedit.com/images/avatar.png',NULL,36.8480320000000,10.1967710000000,NULL,NULL),(21,'Grover','Deckow',NULL,'felipa.schinner@gmail.com','https://sliceedit.com/images/avatar.png',NULL,36.8480320000000,10.1967710000000,NULL,NULL),(22,'Arlo','Kihn',NULL,'krajcik.audrey@rohan.com','https://sliceedit.com/images/avatar.png',NULL,36.8480320000000,10.1967710000000,NULL,NULL),(23,'Dandre','Maggio',NULL,'fstroman@yahoo.com','https://sliceedit.com/images/avatar.png',NULL,36.8480320000000,10.1967710000000,NULL,NULL),(24,'Laurie','Pagac',NULL,'simone78@hegmann.com','https://sliceedit.com/images/avatar.png',NULL,36.8480320000000,10.1967710000000,NULL,NULL),(25,'Sincere','Daniel',NULL,'torrey57@yahoo.com','https://sliceedit.com/images/avatar.png',NULL,36.8480320000000,10.1967710000000,NULL,NULL),(26,'Dante','Lind',NULL,'arden22@kautzer.com','https://sliceedit.com/images/avatar.png',NULL,36.8480320000000,10.1967710000000,NULL,NULL),(27,'Malika','Veum',NULL,'vince.fay@lang.com','https://sliceedit.com/images/avatar.png',NULL,36.8480320000000,10.1967710000000,NULL,NULL),(28,'Hermina','Treutel',NULL,'dorothea54@hotmail.com','https://sliceedit.com/images/avatar.png',NULL,36.8480320000000,10.1967710000000,NULL,NULL),(29,'Merritt','Hammes',NULL,'cheyenne69@bergnaum.com','https://sliceedit.com/images/avatar.png',NULL,36.8480320000000,10.1967710000000,NULL,NULL),(30,'Linnie','Skiles',NULL,'ray87@yahoo.com','https://sliceedit.com/images/avatar.png',NULL,36.8480320000000,10.1967710000000,NULL,NULL),(31,'MyTech','supplier',NULL,'supplier@siyoutech.tn','https://cdn2.webmanagercenter.com/di/wp-content/uploads/2015/02/mytek-di.jpg',NULL,36.8480320000000,36.8480320000000,NULL,NULL),(32,'siyou','Technology',NULL,'william@siyoutechnology.com',NULL,NULL,45.2000000000000,45.2000000000000,NULL,NULL),(33,'Vertak','Vertak',NULL,'vertak@vertak.com','https://storage.googleapis.com/siyou-b2s.appspot.com/profils/fCKXF51WpnsiVU2Fg5JZuT8q1yeWmKyMK4bYLttn.png','fCKXF51WpnsiVU2Fg5JZuT8q1yeWmKyMK4bYLttn.png',37.1530206870317,37.1530206870317,NULL,NULL),(34,'CIF','RELEX',NULL,'CIFRELEX@CIFRELEX.com','https://drive.google.com/file/d/1szTDnNyYzEf3wAcIcKKhn_TxA8whUEUZ/view',NULL,37.2381568986579,37.2381568986579,NULL,NULL),(35,'Metz','Metz',NULL,'Metz@Metz.com','https://drive.google.com/file/d/1w-xNV2Z2pw1NrK0LyHdoWXdq1qHq3EAI/view',NULL,41.2979808450178,41.2979808450178,NULL,NULL);
/*!40000 ALTER TABLE `suppliers` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-08-31 10:57:19
