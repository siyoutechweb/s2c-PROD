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
-- Table structure for table `members`
--

DROP TABLE IF EXISTS `members`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `members` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `gender` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `card_num` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `points` double(8,2) DEFAULT NULL,
  `level_id` int DEFAULT NULL,
  `store_id` int DEFAULT NULL,
  `expiration_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `birthday` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remarks` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_card` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '0',
  `card_barcode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `adress` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `members_level_id_foreign` (`level_id`),
  KEY `members_store_id_foreign` (`store_id`)
) ENGINE=MyISAM AUTO_INCREMENT=137 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `members`
--

LOCK TABLES `members` WRITE;
/*!40000 ALTER TABLE `members` DISABLE KEYS */;
INSERT INTO `members` VALUES (1,'Agustina','Greenholt','other','7308225310523','florence.schneider@yahoo.com','4000096523351',660.00,12,1,NULL,NULL,'2020-07-15 14:07:18',NULL,NULL,NULL,NULL,1,NULL,NULL),(2,'Kylie','Von','male','5808115834267','beryl.swaniawski@hotmail.com','4000078062103',1878.00,12,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),(3,'Darius','Toy','female','5386963308863','mireille.jacobi@howe.com','4000075459485',753.00,1,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),(4,'Ida','Gusikowski','other','5013701026275','dlynch@yahoo.com','4000497472992',1725.00,2,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),(5,'Oran','Halvorson','female','7763737258064','blaise.breitenberg@hotmail.com','4000762056930',816.00,12,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),(6,'Jerel','Heaney','female','6824089737132','ernest.reilly@hotmail.com','4000062199994',1494.00,12,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),(7,'Collin','Douglas','male','8423765516723','ostreich@yahoo.com','4000650913645',1167.00,1,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),(8,'Weston','Abbott','male','9501607021429','estefania61@gmail.com','4000774086600',623.00,2,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),(9,'Samir','Will','other','8634064961519','oberbrunner.matilda@carter.com','4000596418041',823.00,12,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),(10,'Bert','Steuber','male','1786764506492','pearline77@hotmail.com','4000363238225',39.00,3,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),(11,'Lesly','Trantow','male','4699847207584','pansy95@beier.com','4000728683632',187.00,1,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),(12,'Keegan','Gleichner','female','8631685823450','wilkinson.shanna@hotmail.com','4000316173653',866.00,1,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),(13,'Abe','Schuppe','other','8383519935769','nora.weimann@heaney.info','4000499352812',1758.00,13,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),(14,'Josue','Parisian','male','9462145765709','xrussel@zemlak.net','4000757878430',939.00,12,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),(15,'Kelly','Schinner','female','6303486552079','salma90@mills.com','4000331079480',147.00,12,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),(16,'Vinnie','Marks','other','3738330092463','filomena60@goyette.com','4000053409101',575.00,2,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),(17,'Caterina','Rohan','male','5627084924838','mohr.mackenzie@yahoo.com','4000763851890',60.00,3,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),(18,'Johnpaul','Kris','other','4927184309667','gokeefe@will.com','4000224593337',1818.00,2,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),(19,'Justine','Osinski','other','7454107349599','tiara.larson@gmail.com','4000679689124',363.00,2,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),(20,'Payton','Beer','other','5920647024449','jamaal.bednar@mcdermott.com','4000849397710',402.00,1,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),(21,'Moriah','Schimmel','female','2700275017908','jalon01@gmail.com','4000332625464',1384.00,13,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),(22,'Stephania','Gorczany','male','2786090076824','shaun23@leuschke.biz','4000886966959',904.00,3,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),(23,'Georgianna','McKenzie','female','5876081076693','jleffler@gmail.com','4000417435995',579.00,3,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),(24,'Lora','Cummings','male','8259234088886','jordi88@gmail.com','4000364723268',1275.00,3,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),(25,'Jamel','Ziemann','female','5625365737838','kuhlman.annabelle@dicki.com','4000933645570',724.00,13,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),(26,'Vivienne','Brakus','other','2784363444002','bogisich.norma@crooks.net','4000082933805',419.00,12,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),(27,'Agnes','Braun','female','3140048425525','marks.cortez@gmail.com','4000042578003',444.00,13,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),(28,'Elody','Cremin','male','4837563213574','myriam.hauck@jacobson.com','4000394462565',1496.00,3,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),(29,'Jules','Purdy','female','3053705202059','prosacco.dwight@gmail.com','4000160653180',1734.00,1,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),(30,'Reva','Pouros','other','+6778599277384','ricardo.leffler@kirlin.com','4000894941641',1986.00,1,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),(31,'Ophelia','Armstrong','female','9809075545333','barbara46@kutch.com','4000355404489',1497.00,3,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),(32,'Cleora','Wolf','female','9938535664960','felipe.schmitt@oconnell.info','4000708396599',558.00,13,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),(33,'Jonathan','Emard','female','3883258865505','plesch@tremblay.biz','4000731719221',997.00,2,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),(34,'Charlie','Runolfsson','female','4467483888322','gcollins@hotmail.com','4000573868087',1953.00,12,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),(35,'Esperanza','Quitzon','female','1422882226247','sonya43@stoltenberg.com','4000507799658',1849.00,12,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),(36,'Alexie','Schuster','male','5409130191401','kmcglynn@hotmail.com','4000554280002',1824.00,12,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),(37,'Nathanael','Herman','female','2832299259321','howell.garnet@hotmail.com','4000464166474',357.00,2,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),(38,'Angus','Berge','other','9615119106403','sydnie.heaney@weissnat.com','4000217611798',256.00,12,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),(39,'Merle','Harber','female','7684241459653','federico.lindgren@yahoo.com','4000294248598',348.00,14,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),(40,'Heather','Gerlach','other','9753307663051','larson.alycia@hotmail.com','4000623974699',924.00,13,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),(41,'Matilda','Bashirian','female','1538874108541','rudolph00@dooley.biz','4000790448222',1643.00,3,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),(42,'Pietro','Carter','female','8108602393579','watsica.nolan@hotmail.com','4000532109288',1513.00,12,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),(43,'Golden','Kuphal','other','9260523809899','hagenes.earnest@volkman.com','4000430013131',841.00,14,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),(44,'Dandre','Bauch','female','4655916833379','bethany.bailey@gmail.com','4000217486947',1894.00,12,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),(45,'Henri','Bauch','female','1534281430493','rohan.harrison@gmail.com','4000723305773',769.00,12,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),(46,'Rex','Bailey','male','9128774439671','josue.ohara@thiel.info','4000687731769',1537.00,1,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),(47,'Kelli','Grady','male','6491489826543','clementine.lockman@gerhold.net','4000978965894',848.00,13,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),(48,'Justus','Flatley','female','6362845907460','vlakin@kassulke.com','4000945900605',1215.00,3,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),(49,'Aliza','Nitzsche','female','3701549844990','satterfield.jarod@bogisich.com','4000260852795',1360.00,12,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),(50,'Destin','Kuvalis','other','5632180667395','okeefe.deven@gmail.com','4000670042981',425.00,1,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),(51,'Jerrod','Krajcik','male','6824365549595','rollin.hyatt@yahoo.com','4000452124427',1791.00,3,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),(52,'Kathryn','Bernier','male','7037099404040','beau75@dickinson.com','4000629072061',1421.00,13,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),(53,'Llewellyn','Hand','female','3417124169674','libbie76@gulgowski.com','4000347233307',1392.00,2,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),(54,'Herminia','Luettgen','other','3153225920665','buckridge.nat@runolfsdottir.info','4000748397450',1824.00,1,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),(55,'Alfonzo','Ritchie','male','1371709157748','tressa.murphy@hammes.info','4000874694547',1791.00,14,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),(56,'Nicolas','Lueilwitz','female','9637433598631','gjast@luettgen.com','4000025918077',472.00,14,1,NULL,NULL,'2020-07-17 14:41:25',NULL,NULL,NULL,NULL,1,NULL,NULL),(57,'Aniya','Senger','male','1083574257893','nnolan@yahoo.com','4000622797445',1621.00,3,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),(58,'Zula','Howell','other','1231976208412','arnoldo.wuckert@adams.com','4000333501088',607.00,3,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),(59,'Johnpaul','Watsica','female','9592373206561','vjacobs@daugherty.com','4000697798147',1981.00,13,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),(60,'Dawson','Reilly','male','7473627684745','phahn@beier.com','4000275791385',265.00,2,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),(61,'Lucius','Ledner','other','4834371803906','johnathon.davis@schultz.com','4000545627275',542.00,12,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),(62,'Bobbie','Bogisich','female','7480304405436','olson.coralie@wisoky.com','4000018522481',435.00,12,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),(63,'Janae','Boyer','female','7247318262704','nitzsche.john@schultz.com','4000296095338',1094.00,1,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),(64,'Elvie','Mraz','female','5479518450947','ccrona@yahoo.com','4000056880981',932.00,1,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),(65,'Clifton','Aufderhar','male','2980184767802','cheyanne.abernathy@hotmail.com','4000682852468',966.00,13,1,NULL,NULL,'2020-07-15 14:33:01',NULL,NULL,NULL,NULL,1,NULL,NULL),(66,'Samson','Ebert','female','6341388364864','aprosacco@gmail.com','4000965650641',1149.00,3,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),(67,'Camren','Carroll','female','2711866185893','juvenal.volkman@cummings.biz','4000387586356',769.00,1,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),(68,'Philip','Rodriguez','female','5031123853972','kmertz@tromp.com','4000074726171',1076.00,3,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),(69,'Vada','Graham','other','4425208892890','antone41@yahoo.com','4000213551178',1124.00,3,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),(70,'Ulices','Hoeger','male','1718576187152','gideon07@hotmail.com','4000548075288',537.00,2,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),(71,'Jaime','Little','male','7299185610226','tito15@mosciski.org','4000573038850',1689.00,1,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),(72,'Hadley','Rolfson','female','5018306240154','qvon@gmail.com','4000051011978',784.00,1,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),(73,'Norval','Muller','other','6739936290829','ysenger@mante.biz','4000820914565',384.00,14,1,NULL,NULL,'2020-07-15 14:18:36',NULL,NULL,NULL,NULL,1,NULL,NULL),(74,'Amber','Lind','male','6266752091749','nicolas.kennith@schultz.net','4000003715185',151.00,3,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),(75,'Lane','Hackett','female','1549234082795','gjohnson@yahoo.com','4000628977811',1870.00,12,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),(76,'Marisol','Parker','other','1343080232909','breanna01@wisoky.net','4000136249081',1285.00,2,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),(77,'Laura','Crooks','male','2387249381883','ondricka.thaddeus@yahoo.com','4000202994671',1806.00,2,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),(78,'Duncan','Bogan','male','4573178915019','pansy.larson@hagenes.com','4000176890452',1124.00,14,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),(79,'Charlene','West','male','5030868698202','solon.dare@yahoo.com','4000662172604',497.00,1,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),(80,'Hal','Wiza','female','3630770531676','fmckenzie@gmail.com','4000048548705',1245.00,13,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),(81,'Francisca','Murray','female','1798355566612','edison34@hoeger.com','4000586966716',1069.00,14,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),(82,'Maia','Ryan','male','1655508321862','windler.hugh@gmail.com','4000734042214',1858.00,2,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),(83,'Bridgette','Conroy','male','6828254442823','cartwright.nora@hegmann.com','4000693858417',879.00,3,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),(84,'Blaise','Boehm','other','1762853434087','elna.miller@corkery.biz','4000174659759',1552.00,13,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),(85,'Malinda','Windler','female','4214269932182','parker.syble@oconner.com','4000912102328',834.00,14,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),(86,'Zoey','Jones','female','4170686415623','ben.johns@pagac.org','4000254699403',298.00,13,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),(87,'Teagan','Hane','other','8316100259126','pearl70@gmail.com','4000763206303',1447.00,1,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),(88,'Abigail','Tillman','male','4159219393452','pablo.kuphal@mitchell.com','4000373806288',780.00,1,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),(89,'Lia','Reichel','male','9651514693330','yfriesen@hotmail.com','4000486908675',114.00,2,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),(90,'Marlon','Treutel','female','4239270014715','austin.stracke@yahoo.com','4000927096980',1091.00,14,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),(91,'Santina','Kuhn','other','4530204976588','iliana77@jerde.com','4000130756544',932.00,14,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),(92,'Sasha','Carroll','other','1234663822417','itoy@hotmail.com','4000293750259',1523.00,1,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),(93,'Monica','Schroeder','female','4680637553378','jess20@yahoo.com','4000685535354',1835.00,14,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),(94,'Abel','Cremin','male','4583766550436','rhett73@frami.org','4000498130741',128.00,2,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),(95,'Forrest','Armstrong','other','1640657836614','maurice43@gmail.com','4000052105587',437.00,2,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),(96,'Raven','Marquardt','male','2796202753496','cnader@boehm.com','4000405589052',639.00,13,1,NULL,NULL,'2020-08-28 00:20:07',NULL,NULL,NULL,NULL,1,NULL,NULL),(97,'Emmitt','Gorczany','male','3293883288681','jasen.corkery@breitenberg.com','4000169053214',614.00,13,1,NULL,NULL,'2020-07-24 07:25:01',NULL,NULL,NULL,NULL,1,NULL,NULL),(98,'Rosa','Walsh','other','2200390355426','newell64@mueller.com','4000170515216',1515.00,3,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),(99,'Anastacio','Legros','female','7626951565132','louvenia.schroeder@gmail.com','4000392894929',362.00,13,1,NULL,NULL,'2020-07-21 14:34:22',NULL,NULL,NULL,NULL,1,NULL,NULL),(100,'Josue','Kilback','male','3073436815178','henderson55@hotmail.com','4000649613998',379.00,13,1,NULL,NULL,'2020-07-22 10:51:18',NULL,NULL,NULL,NULL,1,NULL,NULL),(101,'habiba','boujmil','female','203698798','habiba@gmail.com','23698693698698',20.00,1,2,'2018-03-29','2020-06-05 15:27:28','2020-06-05 15:27:28',NULL,NULL,NULL,NULL,0,NULL,NULL),(102,'marwa','boujmil','female','203698798','habiba@gmail.com','23698693698698',20.00,1,2,'2018-03-29','2020-06-05 15:36:40','2020-06-05 15:36:40',NULL,NULL,NULL,NULL,0,NULL,NULL),(103,'soussou','yassouna','other','21656186152','yassouna@yassouna.tn','3120370317120',0.00,14,1,'2021-12-12','2020-06-05 15:55:53','2020-07-21 14:34:18',NULL,NULL,NULL,NULL,1,NULL,NULL),(104,'habiba','boujmil','female','203698798','habiba@gmail.com','23698693698698',20.00,1,2,'2018-03-29','2020-06-08 14:47:57','2020-06-08 14:47:57',NULL,NULL,NULL,NULL,0,NULL,NULL),(105,'marwa','souissi','female','203698798','habiba@gmail.com','23698693698698',20.00,1,2,'2018-03-29','2020-06-17 09:16:10','2020-06-17 09:16:10',NULL,NULL,NULL,NULL,0,NULL,NULL),(106,'marwa','souissi','female','203698798','habiba@gmail.com','23698693698698',20.00,1,2,'2018-03-29','2020-06-17 10:16:54','2020-06-17 10:16:54',NULL,NULL,NULL,NULL,0,NULL,NULL),(107,'marwa','souissi','female','203698798','habiba@gmail.com','23698693698698',20.00,1,2,NULL,'2020-06-17 10:47:56','2020-06-17 10:47:56',NULL,NULL,NULL,NULL,0,NULL,NULL),(108,'marwa','souissi','Female','50213654','marwa@gmail.com','123456789',20.00,2,2,NULL,'2020-06-17 12:32:34','2020-06-17 12:32:34',NULL,NULL,NULL,NULL,0,NULL,NULL),(109,'marwa','souissi','female','203698798','habiba@gmail.com','23698693698698',20.00,1,2,'2018-03-29','2020-06-23 15:06:04','2020-06-23 15:06:04',NULL,NULL,NULL,NULL,0,NULL,NULL),(110,'sirin','souissi','Male','21459876','marwasouissi.souissi@gmail.com','1236547890',25.00,2,2,NULL,'2020-06-23 15:12:07','2020-06-23 15:12:07',NULL,NULL,NULL,NULL,0,NULL,NULL),(111,'amine','bouss','Male','325896','marwasouissi.souissi@gmail.com','1236666666',20.00,2,2,NULL,'2020-06-23 15:55:46','2020-06-23 15:55:46',NULL,NULL,NULL,NULL,0,NULL,NULL),(112,'marwa','souissi','Female','236666','marwasouissi.souissi@gmail.com','144444555566666',99.00,3,2,NULL,'2020-06-23 16:42:50','2020-06-23 16:42:50',NULL,NULL,NULL,NULL,0,NULL,NULL),(113,'marwa','souissi','female','203698798','habiba@gmail.com','23698693698698',20.00,1,2,'2018-03-29','2020-06-23 16:43:39','2020-06-23 16:43:39',NULL,NULL,NULL,NULL,0,NULL,NULL),(114,'aaaaaaaa','mmmmmmmm','female','203698798','habiba@gmail.com','23698693698698',20.00,3,2,'2018-03-29','2020-06-23 16:45:36','2020-06-23 16:45:36',NULL,NULL,NULL,NULL,0,NULL,NULL),(115,'ll','ll','Male','3333333','marwasouissi.souissi@gmail.com','12369855555',23.00,5,2,NULL,'2020-06-23 16:48:03','2020-06-23 16:48:03',NULL,NULL,NULL,NULL,0,NULL,NULL),(116,'aaaaaaaa','mmmmmmmm','female','203698798','habiba@gmail.com','23698693698698',20.00,3,2,'2018-03-29','2020-06-23 16:48:49','2020-06-23 16:48:49',NULL,NULL,NULL,NULL,0,NULL,NULL),(118,'test2020','test2020','Male','20123123','test2020@gmail.com','1234412132332323',30.00,14,1,NULL,'2020-06-26 08:39:55','2020-07-21 14:34:16',NULL,NULL,NULL,NULL,1,NULL,NULL),(119,'test30','test30','Male','20232343','test30@gmail.com','1234123412341234',40.00,14,1,NULL,'2020-06-26 08:42:08','2020-07-16 18:45:34',NULL,NULL,NULL,NULL,1,NULL,NULL),(120,'test40','test40','Male','21589659','test40@gmail.com','1234567891234567',2000.00,13,1,NULL,'2020-06-26 12:42:44','2020-07-16 18:43:29',NULL,NULL,NULL,NULL,1,NULL,NULL),(121,'test50','test50','Female','50145219','test50@gmail.com','1234123412341234',2500.00,12,1,NULL,'2020-06-26 12:44:56','2020-07-15 18:05:27',NULL,NULL,NULL,NULL,1,NULL,NULL),(122,'test60','test60','Female','50145659','test60@gmail.com','1234123412341234',2500.00,14,1,NULL,'2020-06-26 12:45:54','2020-07-15 14:18:29',NULL,NULL,NULL,NULL,1,NULL,NULL),(123,'Tom','Tom','Male','50987654','tom@gmail.com','1234123456785678',1000.00,12,1,NULL,'2020-06-26 19:58:58','2020-07-15 14:18:23',NULL,NULL,NULL,NULL,1,NULL,NULL),(124,'Wiliam','geee','male','3995339909','wiliam@siyoutechnlogy.com','2000946452930',0.00,0,0,'2021-10-10','2020-07-10 10:32:14','2020-07-10 10:32:14',NULL,NULL,NULL,NULL,0,NULL,NULL),(125,'Wiliam','geg','male','3995339909','wiliam@siyoutechnlogy.com','2000946452930',0.00,0,0,'2021-10-10','2020-07-10 10:34:14','2020-07-10 10:34:14',NULL,NULL,NULL,NULL,0,NULL,NULL),(126,'Wiliam','geg','male','3995339909','wiliam@siyoutechnlogy.com','2000946452930',0.00,0,0,'2021-10-10','2020-07-10 10:34:27','2020-07-10 10:34:27',NULL,NULL,NULL,NULL,0,NULL,NULL),(127,'Wiliam','geg','male','3995339909','wiliam@siyoutechnlogy.com','2000946452930',0.00,0,0,'2021-10-10','2020-07-10 10:34:40','2020-07-10 10:34:40',NULL,NULL,NULL,NULL,0,NULL,NULL),(128,'member test 14/07','3.25 pm','Male','0101010101010','member@siyou.com','123456789',5.00,12,1,NULL,'2020-07-14 14:26:50','2020-07-15 14:15:08',NULL,NULL,NULL,NULL,1,NULL,NULL),(129,'test','member','female','203698798','habiba@gmail.com','23698693698698',20.00,12,2,'2018-03-29','2020-07-15 10:13:35','2020-07-15 10:13:35',NULL,'2000-03-29','remarks','22',0,NULL,NULL),(130,'test','member','female','203698798','habiba@gmail.com','23698693698698',20.00,12,2,'2018-03-29','2020-07-15 10:30:02','2020-07-15 10:30:02',NULL,'2000-03-29','remarks','22',0,'3697896369896','ariana tunisia'),(131,'nameeee','nameeeeee','Male','555555','user@gmail.com','52544',20.00,12,1,'2020-07-30','2020-07-15 10:58:23','2020-07-15 10:58:23',NULL,'2020-07-22','remarksssss','54454545',1,'4545454',NULL),(132,'yassine','sami','male','21656186152','xxxtriplxxx@gmail.com','2001456789123',0.00,0,1,'2021-01-01','2020-07-16 17:47:27','2020-07-16 17:47:27',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(133,'yassine','sami','male','21656186152','xxxtriplxxx@gmail.com','2001456789123',0.00,0,1,'2021-01-01','2020-07-16 17:47:36','2020-07-16 17:47:36',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(134,'yassine','sami','male','21656186152','xxxtriplxxx@gmail.com','2001456789123',0.00,0,1,'2021-01-01','2020-07-16 17:47:44','2020-07-16 17:47:44',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(135,'yassine','sami','male','21656186152','xxxtriplxxx@gmail.com','2001456789123',0.00,0,1,'2021-01-01','2020-07-16 17:47:48','2020-07-16 17:47:48',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(136,'111','11','Male','11','222','111',111.00,13,1,'1911-01-01','2020-07-24 07:26:37','2020-07-24 07:26:37',NULL,'1901-01-01','11','11',1,'111',NULL);
/*!40000 ALTER TABLE `members` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-08-31 10:57:05
