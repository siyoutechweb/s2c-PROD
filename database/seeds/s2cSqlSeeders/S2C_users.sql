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
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE IF NOT EXISTS`users` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `birthday` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `token` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `activated_account` int NOT NULL DEFAULT '0',
  `shop_owner_id` int DEFAULT NULL,
  `chain_id` int unsigned DEFAULT NULL,
  `role_id` int unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `facebook_id` bigint DEFAULT NULL,
  `google_id` bigint DEFAULT NULL,
  `cover_img_url` text COLLATE utf8mb4_unicode_ci,
  `cover_img_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `img_url` text COLLATE utf8mb4_unicode_ci,
  `profile_img_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `billing_first_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `billing_last_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `billing_company` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `billing_address_1` text COLLATE utf8mb4_unicode_ci,
  `billing_address_2` text COLLATE utf8mb4_unicode_ci,
  `billing_country` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `billing_state` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `billing_city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `billing_postal_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `billing_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `billing_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_first_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_last_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_company` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_address_1` text COLLATE utf8mb4_unicode_ci,
  `shipping_address_2` text COLLATE utf8mb4_unicode_ci,
  `shipping_country` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_state` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_postal_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `verify_types` bigint DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `users_role_id_foreign` (`role_id`),
  KEY `users_chain_id_foreign` (`chain_id`)
) ENGINE=MyISAM AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'siyou','shop','shop@siyoutech.tn','$2y$10$74HLmu5fsmAq96gmtTZFTOnuoe4083kcXonHGqi02SDs4JbMyreZ.','00393891081886',NULL,'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczpcL1wvc2l5b3UudG5cL3MyY1wvbG9naW4iLCJpYXQiOjE1OTg2Mjg2MTQsImV4cCI6MTU5ODY3MTgxNCwibmJmIjoxNTk4NjI4NjE0LCJqdGkiOiJRbzZFclhGWTlTdWZVZkJ6Iiwic3ViIjoxLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3IiwiZW1haWwiOiJzaG9wQHNpeW91dGVjaC50biJ9.BHjTDjr0CSchcbJWV3csiDMr4ALyi2hoH0R9RGBZNAk',1,NULL,NULL,1,'2020-02-21 14:17:55','2020-08-28 15:30:14',NULL,NULL,NULL,'https://storage.googleapis.com/siyou-b2s.appspot.com/products/Vz6GOIWnpBHo9qdMtZcvKUT4KLAXuRCd7lrDIXAB.jpeg',NULL,'https://storage.googleapis.com/siyou-b2s.appspot.com/products/Vz6GOIWnpBHo9qdMtZcvKUT4KLAXuRCd7lrDIXAB.jpeg',NULL,NULL,NULL,NULL,'undefined',NULL,'undefined',NULL,'undefined','undefined',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0),(2,'habiba','boujmil','habiba@outlook.fr','$2y$10$eS09C87MEuZeel9JmIl9BuyrTxrHnX9XMGwwatyGGXRCP6hsbK5CC',NULL,NULL,'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczpcL1wvc2l5b3UudG5cL3MyY1wvdGVzdGxvZ2luIiwiaWF0IjoxNTk4NjIxOTYwLCJleHAiOjE1OTg2NjUxNjAsIm5iZiI6MTU5ODYyMTk2MCwianRpIjoiZkNPcWVQMzczQnREQWhTSSIsInN1YiI6MiwicHJ2IjoiMjNiZDVjODk0OWY2MDBhZGIzOWU3MDFjNDAwODcyZGI3YTU5NzZmNyJ9.HJ1Dt3EGdwyUzc_xC27-xxNadETCVgYkbJ0wan3kmds',1,1,1,2,'2020-02-21 14:17:55','2020-08-28 13:39:20',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0),(3,'marwa','souissi','marwa@outlook.fr','$2y$10$5gCHRjRWXonPlAfAng3PZemdna0hGulkRJOo576kvd.SIJ9fSKt3a',NULL,NULL,NULL,1,1,NULL,3,'2020-02-21 14:17:55','2020-02-21 14:17:55',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0),(4,'siyou','technology','siyou@outlook.fr','$2y$10$3WdBa/ZC.oURb4osm/mM7uE8M0a2s8fsO5Cwr..ET9GNNYz7N1Bwa',NULL,NULL,'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczpcL1wvc2l5b3UudG5cL3MyY1wvbG9naW4iLCJpYXQiOjE1OTYwMTYyOTYsImV4cCI6MTU5NjA1OTQ5NiwibmJmIjoxNTk2MDE2Mjk2LCJqdGkiOiI3QXB0NEloUnJEUm8xVFoyIiwic3ViIjo0LCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3IiwiZW1haWwiOiJzaXlvdUBvdXRsb29rLmZyIn0.gKVwhcqFORGzUmpxpsGxAtcEuxLING7Xtr04phpVysw',1,NULL,NULL,4,'2020-02-21 14:17:56','2020-07-29 09:51:36',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0),(5,'safwen','derwich','siyou@siyoutech.tn','$2y$10$iozPXzBPdaqE/JTh4M8GU.tNuHaUjEfejuYpueIJt3EMUT4SxGhfy',NULL,NULL,'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczpcL1wvc2l5b3UudG5cL3MyY1wvbG9naW4iLCJpYXQiOjE1OTg2MjcxMjYsImV4cCI6MTU5ODY3MDMyNiwibmJmIjoxNTk4NjI3MTI2LCJqdGkiOiI0ME1VWDNrOGg3ZWdBbmU2Iiwic3ViIjo1LCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3IiwiZW1haWwiOiJzaXlvdUBzaXlvdXRlY2gudG4ifQ.fAZiK_EyqJexm0SFKa6v4lv7djXnQlVzSHh1SCuA4qc',1,NULL,NULL,1,'2020-02-21 14:17:56','2020-08-28 15:05:26',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0),(6,'manager','shop','manager@siyoutech.tn','$2y$10$o1iX6K8mbp5Nj5fjWqM/3eDLg0gaCX9ApfCk5t0yCPo3dkYBJb2b2',NULL,NULL,NULL,1,5,NULL,2,'2020-02-21 14:17:56','2020-02-21 14:17:56',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0),(7,'takwa','missaoui','takwa@outlook.fr','$2y$10$NWyfTIeEIzWq4HhN7.Mf6.QcBsEqvrza5oWQxzZmlEg3y1cGSLwm2',NULL,NULL,NULL,1,5,NULL,3,'2020-02-21 14:17:56','2020-02-21 14:17:56',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0),(8,'siyou','shop','siyouShop@siyoutech.tn','$2y$10$T0B9/MJUmTWuRTi2apniruZTmGK74CV0MUWmRtn8yzU/RfscKSwIK',NULL,NULL,NULL,0,NULL,NULL,1,'2020-06-01 14:04:42','2020-06-01 14:04:42',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0),(9,'siyou','shop','siyouShop@siyoutech.tn','$2y$10$U6gUNhpOmFaTn1b1brCHCOkmlxf/xmG3BX48JU9tK7pui2u5By2FK',NULL,NULL,NULL,0,NULL,NULL,1,'2020-06-01 14:34:12','2020-06-01 14:34:12',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0),(11,'yassin','siyou','yassin@gmail.com','$2y$10$khDUl4IZbZy6XSggjJJk9edLsmkPUVlhkRQo8l4yUPptbjNzZMKAK','22222',NULL,NULL,1,1,NULL,2,'2020-06-30 15:37:27','2020-06-30 15:37:27',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0),(27,'ddd','ddd','ddd@gmail.com','$2y$10$tQfo1PoIODlQg3myhXajN.3EusMeyQbc6L2JT9Q6ZEuSkjiRTcory','200200',NULL,NULL,1,1,NULL,2,'2020-07-09 12:58:20','2020-07-09 12:58:20',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0),(28,'dddddd','ddddd','shoooooooooop@gmail.com','$2y$10$tEMhrujKtEIMJAd/GKARm.MSSzF2N5oCpiluddg/CO23L4wRBSWx6','80808080',NULL,NULL,1,1,NULL,2,'2020-07-09 12:59:46','2020-07-09 12:59:46',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0),(29,'hh','313','3192319@qq.com','$2y$10$pYfhjSJxgRq2VO91dK7sFedUHia3CSMDtgizsNJtGtuEPIZoqj2Pi','213123123',NULL,NULL,1,1,NULL,2,'2020-07-13 12:43:15','2020-07-13 12:43:15',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0),(30,'smaali','ahmed','siyouahmed@gmail.com','$2y$10$GVvB/FosOCDlcfUdS4xzY.yLp7urPttcVjx5EmSuN98DlhlYhitk2','52335205',NULL,'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczpcL1wvc2l5b3UudG5cL3MyY1wvbG9naW4iLCJpYXQiOjE1OTUyNjE1MTYsImV4cCI6MTU5NTMwNDcxNiwibmJmIjoxNTk1MjYxNTE2LCJqdGkiOiJ4eW54ZTN1NXNrQkl5eW1CIiwic3ViIjozMCwicHJ2IjoiMjNiZDVjODk0OWY2MDBhZGIzOWU3MDFjNDAwODcyZGI3YTU5NzZmNyIsImVtYWlsIjoic2l5b3VhaG1lZEBnbWFpbC5jb20ifQ.oot4EwUevNsecHKHhKszK31ERtqgapKenoTRLomL_9Y',1,NULL,NULL,1,NULL,'2020-07-20 16:11:56',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0),(19,'amine','boussaa','yassin@gmail.com','$2y$10$10DgMCfD1DmIXnf6YuyI7.ecZOF7ctigUgsoqoxeUSlaTzyJgoQZK','22222',NULL,NULL,1,1,NULL,2,'2020-07-07 09:15:29','2020-07-07 09:15:29',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0),(20,'testttttt 11','tessst 11 ','test11@siyoutech.tn','$2y$10$gBrXbs6bxEqybh7giKkuseAh71GXPcSpvaglB0nBEXFwr1BTuuLyy','20214254',NULL,NULL,1,1,NULL,2,'2020-07-08 09:04:59','2020-07-08 09:04:59',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0),(26,'Ahmed','Ben Ahmed','ahmed@gmail.com','$2y$10$tVasIE8iRezI0OsGjqlEuORyWft.qBMKB2MYphNkAdeoLxP6qdpiG','20199434',NULL,NULL,1,1,NULL,2,'2020-07-09 12:56:29','2020-07-09 12:56:29',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0),(25,'shop','siyou','shop@siyoutechnology.com','$2y$10$Qc7.t0uq/1sdcIaYo7s8rus8oxbAAbJKKoPAl3lav45tKIsiHzC1K','21478955',NULL,'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczpcL1wvc2l5b3UudG5cL3MyY1wvbG9naW4iLCJpYXQiOjE1OTY2MTg1NDAsImV4cCI6MTU5NjY2MTc0MCwibmJmIjoxNTk2NjE4NTQwLCJqdGkiOiJrelJ3ZkFXNnpIc3kxS0pKIiwic3ViIjoyNSwicHJ2IjoiMjNiZDVjODk0OWY2MDBhZGIzOWU3MDFjNDAwODcyZGI3YTU5NzZmNyIsImVtYWlsIjoiU2hvcEBzaXlvdXRlY2hub2xvZ3kuY29tIn0.iJJRDcYhkyoexzVGcIvXFFq3slqZHJueBjN2TLtaIBU',1,NULL,NULL,1,NULL,'2020-08-05 09:09:00',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0),(31,'test06','teest06','test@gmail.com','$2y$10$N9BQznISF.iMBTlnOD0U4eTqsDNNrPgVlMhCKgCoFP3nil1AU2IRq','50123456',NULL,'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczpcL1wvc2l5b3UudG5cL3MyY1wvbG9naW4iLCJpYXQiOjE1OTY0NTkyODIsImV4cCI6MTU5NjUwMjQ4MiwibmJmIjoxNTk2NDU5MjgyLCJqdGkiOiJxdUQxTE55d0NvS2pxbHhGIiwic3ViIjozMSwicHJ2IjoiMjNiZDVjODk0OWY2MDBhZGIzOWU3MDFjNDAwODcyZGI3YTU5NzZmNyIsImVtYWlsIjoidGVzdEBnbWFpbC5jb20ifQ.TLljWlbiZRQVKdrquanlc4BrdAJh-bmBYbGC49yksxc',1,1,NULL,2,'2020-08-03 12:52:38','2020-08-03 12:54:42',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0),(32,'habiba','boujmil','habiba@outlook.fr','$2y$10$1CLU9bCD6pUUXguSaCIQxOXY78hvLN0BFUuqbTOM22KDqgLfecHhi','236969','20-2-2000',NULL,0,NULL,NULL,1,'2020-08-06 08:57:20','2020-08-06 08:57:20',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0),(33,'habiba','boujmil','habiba@outlook.fr','$2y$10$KnaAOTyXle0azN0zY45KN.FqMeY6J3.HCw7pkCCBrnAV7yXRFVz/i','236969','20-2-2000',NULL,0,NULL,NULL,1,'2020-08-06 08:58:45','2020-08-06 08:58:45',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0),(34,'habiba','boujmil','habiba@outlook.fr','$2y$10$eoNB7.Iu1AA8N0WsgslyeuAnX9fXFoD.PWQAV21BfcqKewjHTE1ri','236969','20-2-2000',NULL,0,NULL,NULL,1,'2020-08-06 09:02:30','2020-08-06 09:02:30',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0),(35,'habiba','boujmil','habiba@outlook.fr','$2y$10$mGvunBE4EXlKnJyoNvqn4.iDrLAiFHVAwrYHC1wwmEcLyfHBpy022','236969','20-2-2000',NULL,0,NULL,NULL,1,'2020-08-06 09:03:31','2020-08-06 09:03:31',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0),(36,'habiba','boujmil','habiba@outlook.fr','$2y$10$wOAaeGvqxNe7NTBVMIiaOudZacl6mFB2Bdm7xL3R6W9ysILGo/evS','236969','20-2-2000',NULL,0,NULL,NULL,1,'2020-08-06 09:03:43','2020-08-06 09:03:43',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0),(37,'habiba','boujmil','habiba@outlook.fr','$2y$10$D7isAkZCeG5cq2Gh6CY0luAMMYcHAUZt.K3QZYZ7qulMVex72ZUr6','236969','20-2-2000',NULL,0,NULL,NULL,1,'2020-08-06 09:35:06','2020-08-06 09:35:06',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0),(38,'habiba','boujmil','habiba@outlook.fr','$2y$10$nvE6kGcihOg9.5ur/tDxoe5YbUInVJamO3Vok3WMi9mVb/9RXTGSG','236969','20-2-2000',NULL,0,NULL,NULL,1,'2020-08-06 13:43:09','2020-08-06 13:43:09',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0),(39,'habiba','boujmil','habiba@outlook.fr','$2y$10$0G6dvp2sy1AzRNY3XHIUg.VzcT0UTt5N.xlOVnb26wl1M9xgHzCMq','236969','20-2-2000',NULL,0,NULL,NULL,1,'2020-08-24 08:40:18','2020-08-24 08:40:18',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0),(40,'test','ok','test@test.com','$2y$10$Ah8VJGMq71mX8.P8PncH8OoUV8Mv5MTLRCwUZdYwCFZKyia1YLfQC','0021654326205',NULL,NULL,1,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-08-31 10:57:08
