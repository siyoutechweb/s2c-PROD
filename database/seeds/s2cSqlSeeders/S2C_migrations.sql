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
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=357 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (295,'2020_01_30_090329_create_roles_table',1),(296,'2020_01_30_090548_create_categories_table',1),(297,'2020_01_30_090653_create_users_table',1),(298,'2020_01_30_090942_create_products_table',1),(299,'2020_01_30_091533_create_shops_table',1),(300,'2020_01_30_092544_create_orders_table',1),(301,'2020_01_30_092726_create_product_order_table',1),(302,'2020_01_30_092824_create_discounts_table',1),(303,'2020_01_30_093121_create_member_levels_table',1),(304,'2020_01_30_094227_create_members_table',1),(305,'2020_01_30_094429_create_product_discount_table',1),(306,'2020_01_31_072551_create_chains_table',1),(307,'2020_01_31_093914_add_column_chain_id_to_users',1),(308,'2020_02_01_215227_add_column_shop_id_to_products',1),(309,'2020_02_10_155241_add_column_store_id_to_member_level_table',1),(310,'2020_02_20_162321_add_column_to_product_discount',1),(311,'2020_02_24_115730_add_discount_price_to_product_discount',2),(312,'2020_03_27_091405_create_product_base_table',2),(313,'2020_03_27_091411_create_product_items_table',2),(314,'2020_03_27_091429_create_product_brands_table',2),(315,'2020_03_27_091439_create_product_images_table',2),(316,'2020_03_27_091502_create_criteria_base_table',2),(317,'2020_03_27_091507_create_criteria_units_table',2),(318,'2020_03_27_091534_create_item_criteria_table',2),(319,'2020_03_27_091548_create_category_criteria_table',2),(320,'2020_04_30_224258_criteria_unit_table',2),(321,'2020_05_29_120145_add_column_to_product_base',2),(322,'2020_06_05_082534_create_cash_register_table',3),(323,'2020_06_05_103052_add_colunm_cash_registers_to_chains',3),(324,'2020_06_17_160616_create_cachier_table',4),(325,'2020_06_23_105714_create_warehouses_table',5),(326,'2020_06_23_140724_create_purchase_products_table',5),(327,'2020_06_23_140735_create_purchase_orders_table',5),(328,'2020_06_23_154826_create_status_table',5),(329,'2020_06_23_155943_create_suppliers_table',5),(330,'2020_06_24_112029_add_discount_price_to_product',5),(331,'2020_06_24_112642_add_discount_value_to_disount_product',5),(332,'2020_06_25_145158_create_shop_supplier_table',5),(333,'2020_06_26_150350_add_column_img_to_category',6),(334,'2020_06_29_092024_create_warehouse_table',7),(335,'2020_06_29_140812_create_inventory_table',7),(336,'2020_06_30_081603_create_inventory_product_table',7),(337,'2020_07_03_110212_add_status2_to_inventory',8),(338,'2020_07_06_140941_add_column_to_inventory_product',9),(339,'2020_07_07_093419_add_column_taxe_to_category',10),(340,'2020_07_08_082905_create_quick_scan_table',11),(341,'2020_07_08_102247_create_quick_discount_table',11),(342,'2020_07_09_140432_add_column_to_cachiers',12),(343,'2020_07_10_141635_create_menu_table',13),(344,'2020_07_13_123629_add_foreign_key_to_menu',14),(345,'2020_07_14_110615_add_columns_to_orders',14),(346,'2020_07_15_091853_add_columns_to_members',15),(347,'2020_07_15_095747_add_card_barcode_to_members',16),(348,'2020_07_15_102557_add_column_adress_to_mambers',17),(349,'2020_07_20_103400_create_payment_methodes_table',18),(350,'2020_07_20_123657_add_payment_methode_to_orders',18),(351,'2020_07_22_102500_add_columns_to_users',19),(352,'2020_08_04_124832_add_column_to_order',20),(353,'2020_06_25_14515_create_shop_supplier_table',21),(354,'2020_08_26_144126_add_columns_to_product_order_table',22),(355,'2020_08_27_094234_add_columns_to_product_discount_table',23),(356,'2020_08_27_100856_update_product_discount_table',23);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-08-31 10:56:26
