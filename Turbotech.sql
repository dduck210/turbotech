-- Turbotech database dump — full, portable, one-shot setup.
--
-- Regenerated 2026-07-13 from the live `codemoi2` database. Includes
-- everything needed for a fresh machine to run the app correctly as the
-- laptop store it actually is: schema for every table actually used by the
-- app (bank, bill, cart, category, comment, coupons, product, question,
-- user — including `coupons` + `bill.coupon_code`/`discount_amount`,
-- previously a separate manual step via create_table.php), the real laptop
-- catalog (names/specs/images matching public/admin/uploads/), and user
-- passwords already bcrypt-hashed (no separate migration needed).
--
-- `history_bank` (unused legacy VietQR transaction log, zero code
-- references) was dropped per Phase 06 of the codebase-professionalization
-- plan — do not reintroduce it.
--
-- The previous version of this file was a stale 2023 dump from an earlier
-- phone-store version of this project ("virtualphone") — wrong for this app
-- and never updated when the project pivoted to laptops. Do not reintroduce
-- phone-branded seed data here.
--
-- Setup on a new machine: create the `codemoi2` database, then
-- `mysql -u root codemoi2 < Turbotech.sql` — that's the only step needed.

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `bank`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bank` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name_bank` varchar(255) NOT NULL,
  `num_bank` varchar(255) NOT NULL,
  `name_num` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `bank` WRITE;
/*!40000 ALTER TABLE `bank` DISABLE KEYS */;
/*!40000 ALTER TABLE `bank` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `bill`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bill` (
  `id_bill` int(11) NOT NULL AUTO_INCREMENT,
  `bill_code` varchar(255) DEFAULT NULL,
  `id_user` int(11) NOT NULL,
  `id_pro` int(11) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `name_pro` varchar(255) NOT NULL,
  `full_name` varchar(55) NOT NULL,
  `address` varchar(255) NOT NULL,
  `phone` int(25) NOT NULL,
  `email` varchar(255) NOT NULL,
  `payment` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1.Thanh toán khi nhận hàng 2.Chuyển khoản ngân hàng 3.Thanh toán online',
  `order_date` datetime NOT NULL,
  `total_amount` int(10) NOT NULL,
  `status` tinyint(4) NOT NULL COMMENT '0.Đơn hàng mới \r\n1.Đang xử lý\r\n2.Đang giao hàng\r\n3.Đã giao hàng',
  `status_pay` varchar(11) NOT NULL DEFAULT '0',
  `coupon_code` varchar(50) DEFAULT NULL,
  `discount_amount` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id_bill`),
  KEY `lk_user_bill` (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=131 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `bill` WRITE;
/*!40000 ALTER TABLE `bill` DISABLE KEYS */;
INSERT INTO `bill` VALUES (88,'83762',16,0,'admin','','admin tubortech','r2w',987654334,'zatuzick03@gmail.com',1,'2026-06-23 03:47:54',95900000,0,'1',NULL,0),(89,'89261',30,0,'trantrung123','','Tran Trung','Gia Lam, Ha Noi',987654321,'trantrung0@gmail.com',2,'2026-06-25 09:51:51',196080000,3,'1',NULL,0),(90,'31924',30,0,'trantrung123','','Tran Trung','Gia Lam, Ha Noi',987654321,'trantrung0@gmail.com',1,'2026-06-25 09:58:20',39990000,3,'1',NULL,0),(91,'27159',30,0,'trantrung123','','Tran Trung','Gia Lam, Ha Noi',987654321,'trantrung0@gmail.com',1,'2026-06-25 10:14:13',44990000,3,'1',NULL,0),(92,'13854',30,0,'trantrung123','','Tran Trung','Gia Lam, Ha Noi',987654334,'trantrung0@gmail.com',1,'2026-06-25 10:14:40',28490000,3,'1',NULL,0),(93,'24761',30,0,'trantrung123','','Tran Trung','Gia Lam, Ha Noi',987654321,'trantrung0@gmail.com',2,'2026-06-25 10:21:02',95900000,0,'0',NULL,0),(94,'57126',30,0,'trantrung123','','Tran Trung','Gia Lam, Ha Noi',987654321,'trantrung0@gmail.com',2,'2026-06-25 10:30:27',36990000,0,'0',NULL,0),(95,'39217',30,0,'trantrung123','','Tran Trung','Gia Lam, Ha Noi',987654321,'trantrung0@gmail.com',2,'2026-06-25 10:54:06',36990000,0,'0',NULL,0),(96,'17689',28,0,'tuananh910','','Tuan Anh','Gia Lam, Ha Noi',987654321,'nguyenductuananh910@gmail.com',2,'2026-06-26 09:11:32',20590000,0,'0',NULL,0),(97,'42698',28,0,'tuananh910','','Tuan Anh','Gia Lam, Ha Noi',987654321,'nguyenductuananh910@gmail.com',2,'2026-06-26 09:24:11',168990000,0,'0',NULL,0),(98,'28963',28,0,'tuananh910','','Tuan Anh','Gia Lam, Ha Noi',987654321,'nguyenductuananh910@gmail.com',2,'2026-06-26 09:26:16',44990000,0,'1',NULL,0),(99,'85234',30,0,'trantrung123','','Tran Trung','Gia Lam, Ha Noi',987654321,'trantrung0@gmail.com',2,'2026-06-28 03:42:17',95900000,0,'1',NULL,0),(100,'97456',30,0,'trantrung123','','Tran Trung','Gia Lam, Ha Noi',987654321,'trantrung0@gmail.com',2,'2026-06-28 04:41:09',95900000,2,'1',NULL,0),(101,'79368',30,0,'trantrung123','','Tran Trung','Gia Lam, Ha Noi',987654321,'trantrung0@gmail.com',2,'2026-06-28 04:52:26',168990000,0,'0',NULL,0),(102,'53468',30,0,'trantrung123','','Tran Trung','Gia Lam, Ha Noi',987654334,'trantrung0@gmail.com',1,'2026-06-28 05:19:18',95900000,3,'1',NULL,0),(103,'19364',16,0,'admin','','admin tubortech','Near Pingalwara, GT Road',123456789,'zatuzick03@gmail.com',1,'2026-07-09 05:48:04',95900000,0,'0',NULL,0),(104,'26915',27,0,'nam2108004','','Nguyen Van Test','123 Test St',912345678,'test@example.com',1,'2026-07-09 07:20:13',500000,0,'0',NULL,0),(105,'69814',27,0,'nam2108004','','Nguyen Van QR','456 QR St',987654321,'qr@example.com',2,'2026-07-09 07:20:34',300000,0,'0',NULL,0),(106,'65321',35,0,'phase05test1','','Phase05 Tester','123 Test St',912345678,'phase05test1@example.com',1,'2026-07-09 07:34:07',200000,0,'0',NULL,0),(107,'23685',35,0,'phase05test1','','Phase05 Tester','123 Test St',912345678,'phase05test1@example.com',2,'2026-07-09 07:35:55',50000,0,'0',NULL,0),(108,'25498',35,0,'phase05test1','','Direct ViewBill Test','456 Direct Ave',987654321,'directtest@example.com',1,'2026-07-09 07:36:57',75000,0,'0',NULL,0),(112,'89753',16,0,'admin','','admin tubortech','Near Pingalwara, GT Road',123456789,'zatuzick03@gmail.com',1,'2026-07-09 08:59:42',28900000,0,'0',NULL,0),(113,'47139',16,0,'admin','','admin tubortech','Near Pingalwara, GT Road',123456789,'zatuzick03@gmail.com',1,'2026-07-09 09:00:45',28900000,0,'0',NULL,0),(114,'79638',16,0,'admin','','admin tubortech','Near Pingalwara, GT Road',123456789,'zatuzick03@gmail.com',1,'2026-07-09 09:06:18',21690000,0,'0',NULL,0),(115,'36874',16,0,'admin','','admin tubortech','Near Pingalwara, GT Road',123456789,'zatuzick03@gmail.com',1,'2026-07-09 09:35:53',28490000,0,'0',NULL,0),(120,'19846',16,0,'admin','','admin tubortech','bfdgdgf, Xã An Châu, Tỉnh An Giang',123456789,'zatuzick03@gmail.com',2,'2026-07-10 10:11:34',92290000,0,'0',NULL,0),(122,'92348',16,0,'admin','','admin tubortech','fasda, Phường Ninh Kiều, Thành phố Cần Thơ',123456789,'zatuzick03@gmail.com',2,'2026-07-10 10:33:10',15000000,0,'0',NULL,0),(129,'93625',16,0,'admin','','admin tubortech','hfghfgh, Phường Thuận An, Thành phố Huế',123456789,'zatuzick03@gmail.com',2,'2026-07-10 11:14:24',32000000,0,'0',NULL,0),(130,'69184',16,0,'admin','','admin tubortech','fasfasd, Phường Sóc Trăng, Thành phố Cần Thơ',123456789,'zatuzick03@gmail.com',2,'2026-07-10 11:26:44',15000000,0,'0',NULL,0);
/*!40000 ALTER TABLE `bill` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `cart`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cart` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `user_name` varchar(50) NOT NULL,
  `id_pro` int(11) NOT NULL,
  `img_pro` varchar(255) NOT NULL,
  `name_pro` varchar(255) NOT NULL,
  `price_pro` int(10) NOT NULL,
  `quantity` int(11) NOT NULL,
  `total_amount` int(10) NOT NULL,
  `id_bill` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `lk_bill_cart` (`id_bill`),
  KEY `lk_pro_cart` (`id_pro`),
  KEY `lk_user_cart` (`id_user`,`id_pro`),
  CONSTRAINT `lk_bill_cart` FOREIGN KEY (`id_bill`) REFERENCES `bill` (`id_bill`),
  CONSTRAINT `lk_pro_cart` FOREIGN KEY (`id_pro`) REFERENCES `product` (`id_pro`)
) ENGINE=InnoDB AUTO_INCREMENT=121 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `cart` WRITE;
/*!40000 ALTER TABLE `cart` DISABLE KEYS */;
INSERT INTO `cart` VALUES (80,16,'admin',30,'-ai-phn16s-71-non-fingerprint-with-backlit-on-wp-oled-abyssal-black-01_5f32a1e84b6e40bdaf70f2878d7cc639_master.png','Laptop gaming Acer Predator Helios Neo 1',95900000,1,95900000,88),(81,30,'trantrung123',30,'-ai-phn16s-71-non-fingerprint-with-backlit-on-wp-oled-abyssal-black-01_5f32a1e84b6e40bdaf70f2878d7cc639_master.png','Laptop gaming Acer Predator Helios Neo 1',95900000,1,95900000,89),(82,30,'trantrung123',25,'asus-rog-zephyrus-g14-ga403uv-qs171w_4aef4676f5ef4fddbb5fd6d0c5fec7d5_295d6371914e47bf83d840031a95751f_master.png','Laptop gaming ASUS ROG Zephyrus G14',71280000,1,71280000,89),(83,30,'trantrung123',11,'af4lhmww5i_a0e1976f7e564523b3f0fc7f1b911222_master.png','Laptop gaming HP VICTUS 15',28900000,1,28900000,89),(84,30,'trantrung123',26,'r-nitro-v-16s-ai-anv16s-61-non-fingerprint-with-backlit-on-wp-black-01_92ea59f5d46746f2a15f3daa59ed61b7_master.png','Laptop gaming Acer Nitro ProPanel',39990000,1,39990000,90),(85,30,'trantrung123',20,'legion_5_15irx10_ct1_02_3956e3fe2fd640f29a51788d335c186d_master.png','Laptop gaming Lenovo Legion 5',44990000,1,44990000,91),(86,30,'trantrung123',8,'image_7741ad2158d04fb9a7ba550ac603fdde_master.png','Laptop gaming ASUS V16 ',28490000,1,28490000,92),(87,30,'trantrung123',30,'-ai-phn16s-71-non-fingerprint-with-backlit-on-wp-oled-abyssal-black-01_5f32a1e84b6e40bdaf70f2878d7cc639_master.png','Laptop gaming Acer Predator Helios Neo 1',95900000,1,95900000,93),(88,30,'trantrung123',24,'dak8r5vaqq_f6b9d3e708644144aa2e09c6b25e9108_master.png','Laptop gaming HP OMEN 16-am0180TXGB ',36990000,1,36990000,94),(89,30,'trantrung123',24,'dak8r5vaqq_f6b9d3e708644144aa2e09c6b25e9108_master.png','Laptop gaming HP OMEN 16-am0180TXGB ',36990000,1,36990000,95),(90,28,'tuananh910',27,'white_backlit_loq_15arp9_ct1_03_934d509494e04471bfb7a1d774864551_master.png','Laptop gaming Lenovo LOQ 15ARP9',20590000,1,20590000,96),(91,28,'tuananh910',28,'titan18a14vx_2_522x522_54442b2d0_fb6873b42a1b40ca9ead89505f995da5_master.png','Laptop gaming MSI Titan 18 HX',168990000,1,168990000,97),(92,28,'tuananh910',20,'legion_5_15irx10_ct1_02_3956e3fe2fd640f29a51788d335c186d_master.png','Laptop gaming Lenovo Legion 5',44990000,1,44990000,98),(93,30,'trantrung123',30,'-ai-phn16s-71-non-fingerprint-with-backlit-on-wp-oled-abyssal-black-01_5f32a1e84b6e40bdaf70f2878d7cc639_master.png','Laptop gaming Acer Predator Helios Neo 1',95900000,1,95900000,99),(94,30,'trantrung123',30,'-ai-phn16s-71-non-fingerprint-with-backlit-on-wp-oled-abyssal-black-01_5f32a1e84b6e40bdaf70f2878d7cc639_master.png','Laptop gaming Acer Predator Helios Neo 1',95900000,1,95900000,100),(95,30,'trantrung123',28,'titan18a14vx_2_522x522_54442b2d0_fb6873b42a1b40ca9ead89505f995da5_master.png','Laptop gaming MSI Titan 18 HX',168990000,1,168990000,101),(96,30,'trantrung123',30,'-ai-phn16s-71-non-fingerprint-with-backlit-on-wp-oled-abyssal-black-01_5f32a1e84b6e40bdaf70f2878d7cc639_master.png','Laptop gaming Acer Predator Helios Neo 1',95900000,1,95900000,102),(98,16,'admin',30,'-ai-phn16s-71-non-fingerprint-with-backlit-on-wp-oled-abyssal-black-01_5f32a1e84b6e40bdaf70f2878d7cc639_master.png','Laptop gaming Acer Predator Helios Neo 1',95900000,1,95900000,103),(99,27,'nam2108004',27,'test.jpg','CheckoutTestProd',500000,1,500000,104),(100,27,'nam2108004',22,'test.jpg','QRTestProd',300000,1,300000,105),(101,35,'phase05test1',11,'x.jpg','TestProd',100000,2,200000,106),(102,35,'phase05test1',24,'y.jpg','TestProd2',50000,1,50000,107),(103,16,'admin',40,'56156_hp_240r_g9_ax3c6at.jpg','Laptop HP 240R',28900000,1,28900000,112),(104,16,'admin',40,'56156_hp_240r_g9_ax3c6at.jpg','Laptop HP 240R',28900000,1,28900000,113),(105,16,'admin',43,'56156_hp_240r_g9_ax3c6at (1).jpg','Laptop HP OmniBook',21690000,1,21690000,114),(106,16,'admin',8,'image_7741ad2158d04fb9a7ba550ac603fdde_master.png','Laptop gaming ASUS V16 ',28490000,1,28490000,115),(109,16,'admin',42,'Laptop-HP-Gaming-VICTUS-15-fa2451TX-i5-13420H16GB512GB-SSD15.6FHDWLBT4C6G_RTX-4050LKBWin11Den-1.jpg','Laptop HP Gaming VICTUS 15',34490000,1,34490000,120),(110,16,'admin',41,'AY8X9PA-3.jpg',' Laptop gaming HP Victus 16',28900000,2,57800000,120),(112,16,'admin',48,'test.jpg','QR Coupon Test Laptop 2',15000000,1,15000000,122),(119,16,'admin',50,'test.jpg','QR Polish Check Laptop',32000000,1,32000000,129),(120,16,'admin',48,'test.jpg','QR Coupon Test Laptop 2',15000000,1,15000000,130);
/*!40000 ALTER TABLE `cart` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `category` (
  `id_cate` int(11) NOT NULL AUTO_INCREMENT,
  `name_cate` varchar(255) NOT NULL,
  PRIMARY KEY (`id_cate`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `category` WRITE;
/*!40000 ALTER TABLE `category` DISABLE KEYS */;
INSERT INTO `category` VALUES (1,'Samsung'),(7,'Asus'),(8,'Acer'),(10,'Apple'),(11,'Lenovo'),(12,'MSI'),(14,'HP');
/*!40000 ALTER TABLE `category` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `comment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comment` (
  `id_cmt` int(11) NOT NULL AUTO_INCREMENT,
  `content` varchar(255) NOT NULL,
  `id_user` int(11) NOT NULL,
  `user_name` varchar(50) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `id_pro` int(11) NOT NULL,
  `comment_date` varchar(30) NOT NULL,
  PRIMARY KEY (`id_cmt`),
  KEY `lk_user_cmt` (`id_user`),
  KEY `lk_pro_cmt` (`id_pro`),
  CONSTRAINT `lk_pro_cmt` FOREIGN KEY (`id_pro`) REFERENCES `product` (`id_pro`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `comment` WRITE;
/*!40000 ALTER TABLE `comment` DISABLE KEYS */;
/*!40000 ALTER TABLE `comment` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `coupons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `coupons` (
  `id_coupon` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(50) NOT NULL,
  `discount_type` tinyint(1) NOT NULL DEFAULT 1,
  `discount_value` int(11) NOT NULL,
  `max_discount` int(11) NOT NULL DEFAULT 0,
  `min_order_value` int(11) NOT NULL DEFAULT 0,
  `product_id` int(11) NOT NULL DEFAULT 0,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `usage_limit` int(11) NOT NULL DEFAULT 0,
  `used_count` int(11) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id_coupon`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `coupons` WRITE;
/*!40000 ALTER TABLE `coupons` DISABLE KEYS */;
/*!40000 ALTER TABLE `coupons` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product` (
  `id_pro` int(11) NOT NULL AUTO_INCREMENT,
  `name_pro` varchar(255) NOT NULL,
  `price` int(11) NOT NULL,
  `discount` int(11) NOT NULL,
  `img_pro` varchar(255) NOT NULL,
  `short_des` text NOT NULL,
  `detail_des` text NOT NULL,
  `view` int(11) NOT NULL DEFAULT 0,
  `stock` int(11) NOT NULL DEFAULT 20,
  `stock_message` varchar(255) DEFAULT NULL,
  `idcate` int(11) NOT NULL,
  PRIMARY KEY (`id_pro`),
  KEY `lk_cate_product` (`idcate`),
  CONSTRAINT `lk_cate_product` FOREIGN KEY (`idcate`) REFERENCES `category` (`id_cate`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `product` WRITE;
/*!40000 ALTER TABLE `product` DISABLE KEYS */;
INSERT INTO `product` VALUES (7,'Laptop gaming Acer Nitro Lite ',29990000,10,'gearvn-laptop-gaming-acer-nitro-lite-nl16-71g-71fn-1_cebbe117071d42b99f05427cebf47687_master.png','CPU: Intel Core i7-13620H (10 nhân, 16 luồng, Turbo tối đa Efficient-core: 3.6 GHz, Performance-core: 4.9 GHz, 24 MB Smart Cache) Card đồ họa: NVIDIA GeForce RTX 4050 6 GB GDDR6 VRAM, hỗ trợ 2560 NVIDIA CUDA Cores Ram:  16GB 2 khe (1 x 8GB Onboard + 1 x 8GB rời, nâng cấp lên tối đa 56GB (8GB Onboard + 48GB 1 thanh RAM rời) SSD: 512GB PCIe NVMe SSD (nâng cấp tối đa 2TB Gen4 NVMe Màn hình: 16 inch','<p>CPU: Intel Core i7-13620H (10 nh&acirc;n, 16 luồng, Turbo tối đa Efficient-core: 3.6 GHz, Performance-core: 4.9 GHz, 24 MB Smart Cache)</p>\r\n\r\n<p>Card đồ họa: NVIDIA GeForce RTX 4050 6 GB GDDR6 VRAM, hỗ trợ 2560 NVIDIA CUDA Cores</p>\r\n\r\n<p>Ram:&nbsp; 16GB 2 khe (1 x 8GB Onboard + 1 x 8GB rời, n&acirc;ng cấp l&ecirc;n tối đa 56GB (8GB Onboard + 48GB 1 thanh RAM rời)</p>\r\n\r\n<p>SSD: 512GB PCIe NVMe SSD (n&acirc;ng cấp tối đa 2TB Gen4 NVMe</p>\r\n\r\n<p>M&agrave;n h&igrave;nh: 16 inch</p>\r\n',141,19,NULL,8),(8,'Laptop gaming ASUS V16 ',28490000,0,'image_7741ad2158d04fb9a7ba550ac603fdde_master.png','CPU: Intel® Core™ 5 Processor 210H 2.2 GHz (12MB Cache, up to 4.8 GHz, 8 cores, 12 Threads) Card đồ họa: NVIDIA® GeForce RTX™ 5050 Laptop Ram:  16GB (1x16GB) DDR5 ( Tổng 2 slot Ram, Còn trống 1 Slot) SSD: 512GB M.2 NVMe™ PCIe® 4.0 SSD ( Tổng 1 khe M.2 2280 PCIe 4.0x4) Màn hình: 16 inch','<p>CPU: Intel&reg; Core&trade; 5 Processor 210H 2.2 GHz (12MB Cache, up to 4.8 GHz, 8 cores, 12 Threads)</p>\r\n\r\n<p>Card đồ họa: NVIDIA&reg; GeForce RTX&trade; 5050 Laptop</p>\r\n\r\n<p>Ram:&nbsp; 16GB (1x16GB) DDR5 ( Tổng 2 slot Ram, C&ograve;n trống 1 Slot)</p>\r\n\r\n<p>SSD: 512GB M.2 NVMe&trade; PCIe&reg; 4.0 SSD ( Tổng 1 khe M.2 2280 PCIe 4.0x4)</p>\r\n\r\n<p>M&agrave;n h&igrave;nh: 16 inch</p>\r\n',48,19,NULL,7),(9,'Laptop gaming MSI Katana 15 HX ',34490000,0,'1024__1__852ded286c3944d6994c2dc4b8c172f0_master.png','CPU: Intel® Core™ i9 processor 14900HX Card đồ họa: NVIDIA® GeForce RTX™ 5050 Ram:  16GB (1x16GB) DDR5 ( Tổng 2 slot Ram, Còn trống 1 Slot) SSD: 512GB Màn hình: 15.6 inch','<p>CPU: Intel&reg; Core&trade; i9 processor 14900HX</p>\r\n\r\n<p>Card đồ họa: NVIDIA&reg; GeForce RTX&trade; 5050</p>\r\n\r\n<p>Ram:&nbsp; 16GB (1x16GB) DDR5 ( Tổng 2 slot Ram, C&ograve;n trống 1 Slot)</p>\r\n\r\n<p>SSD: 512GB</p>\r\n\r\n<p>M&agrave;n h&igrave;nh: 15.6 inch</p>\r\n',186,20,NULL,12),(11,'Laptop gaming HP VICTUS 15',28900000,0,'af4lhmww5i_a0e1976f7e564523b3f0fc7f1b911222_master.png','CPU: AMD Ryzen™ 7 7445H (up to 4.7 GHz max boost clock, 16 MB L3 cache, 6 cores, 12 threads) Card đồ họa: NVIDIA® GeForce RTX™ 5050 Ram:  16GB (1 x 16GB) DDR5 5600MHz (2x SO-DIMM socket, up to 64GB SDRAM) SSD: 512 GB PCIe® Gen4 NVMe™ M.2 SSD (1 slot up to 2TB) Màn hình: 15.6 inch','<p>CPU: AMD Ryzen&trade; 7 7445H (up to 4.7 GHz max boost clock, 16 MB L3 cache, 6 cores, 12 threads)</p>\r\n\r\n<p>Card đồ họa: NVIDIA&reg; GeForce RTX&trade; 5050</p>\r\n\r\n<p>Ram:&nbsp; 16GB (1 x 16GB) DDR5 5600MHz (2x SO-DIMM socket, up to 64GB SDRAM)</p>\r\n\r\n<p>SSD: 512 GB PCIe&reg; Gen4 NVMe&trade; M.2 SSD (1 slot up to 2TB)</p>\r\n\r\n<p>M&agrave;n h&igrave;nh: 15.6 inch</p>\r\n',26,20,NULL,14),(20,'Laptop gaming Lenovo Legion 5',44990000,0,'legion_5_15irx10_ct1_02_3956e3fe2fd640f29a51788d335c186d_master.png','CPU: Intel Core i7-14700HX, 20 lõi (8P + 12E) / 28 luồng, P-core 2.1 / 5.5GHz, E-core 1.5 / 3.9GHz, 33MB Card đồ họa: NVIDIA GeForce RTX 5050 8GB GDDR7, Boost Clock 2662MHz, TGP 115W, 440 AI TOPS Ram:  16GB (1 x 16GB) DDR5-5600 SO-DIMM (1x SO-DIMM socket, up to 32GB SDRAM) SSD: 512GB SSD M.2 2242 PCIe 4.0x4 NVMe (Tối đa hai ổ đĩa, 2x M.2 SSD • M.2 2242 SSD tối đa 1TB mỗi ổ) Màn hình: 15 inch','<p>CPU: Intel Core i7-14700HX, 20 l&otilde;i (8P + 12E) / 28 luồng, P-core 2.1 / 5.5GHz, E-core 1.5 / 3.9GHz, 33MB</p>\r\n\r\n<p>Card đồ họa: NVIDIA GeForce RTX 5050 8GB GDDR7, Boost Clock 2662MHz, TGP 115W, 440 AI TOPS</p>\r\n\r\n<p>Ram:&nbsp; 16GB (1 x 16GB) DDR5-5600 SO-DIMM (1x SO-DIMM socket, up to 32GB SDRAM)</p>\r\n\r\n<p>SSD: 512GB SSD M.2 2242 PCIe 4.0x4 NVMe (Tối đa hai ổ đĩa, 2x M.2 SSD &bull; M.2 2242 SSD tối đa 1TB mỗi ổ)</p>\r\n\r\n<p>M&agrave;n h&igrave;nh: 15 inch</p>\r\n',120,20,NULL,11),(21,'Laptop gaming Acer Nitro ProPanel',29900000,10,'acer-nitro-v-15-anv15-52-non-fingerprint-with-backlit-on-wp-black-04_b6b36b2ce35b4bcba21e63865f81ddf9_master.png','CPU: AMD Ryzen™ 5 7535HS ( 6 Nhân, 12 Luồng) up to 4.55 GHz, 3MB L2 Cache Card đồ họa: NVIDIA GeForce RTX 3050 6GB GDDR6 Ram:  16GB (1x16GB) DDR5 4800MHz (2x SO-DIMM socket, up to 96GB SDRAM) SSD: 512GB PCIe NVMe SED SSD (Còn trống 1 khe SSD M.2 PCIE, nâng cấp tối đa 2TB) Màn hình: 15.6 inch','<p>CPU: AMD Ryzen&trade; 5 7535HS ( 6 Nh&acirc;n, 12 Luồng) up to 4.55 GHz, 3MB L2 Cache</p>\r\n\r\n<p>Card đồ họa: NVIDIA GeForce RTX 3050 6GB GDDR6</p>\r\n\r\n<p>Ram:&nbsp; 16GB (1x16GB) DDR5 4800MHz (2x SO-DIMM socket, up to 96GB SDRAM)</p>\r\n\r\n<p>SSD: 512GB PCIe NVMe SED SSD (C&ograve;n trống 1 khe SSD M.2 PCIE, n&acirc;ng c&acirc;́p t&ocirc;́i đa 2TB)</p>\r\n\r\n<p>M&agrave;n h&igrave;nh: 15.6 inch</p>\r\n',22,20,NULL,8),(22,'Laptop gaming MSI Sword 16 HX',31190000,0,'7_p064955_139c353254d341aaad85985c64056786_master.jpg','CPU Intel Core i7-14700HX (tốc độ tối đa: 5.5GHz, 20 lõi / 28 luồng, 33MB Cache) Card đồ họa: NVIDIA GeForce RTX 4050 6GB GDDR6 Ram:  16GB (2x 8GB) DDR5-5600 SO-DIMM (2x SO-DIMM socket, up to 96GB SDRAM) SSD: 1TB SSD 1x M.2 SSD slot (NVMe PCIe Gen4) 1x M.2 SSD slot (NVMe PCIe Gen5) Compatible Màn hình: 16 inch','<p>CPU Intel Core i7-14700HX (tốc độ tối đa: 5.5GHz, 20 l&otilde;i / 28 luồng, 33MB Cache)</p>\r\n\r\n<p>Card đồ họa: NVIDIA GeForce RTX 4050 6GB GDDR6</p>\r\n\r\n<p>Ram:&nbsp; 16GB (2x 8GB) DDR5-5600 SO-DIMM (2x SO-DIMM socket, up to 96GB SDRAM)</p>\r\n\r\n<p>SSD: 1TB SSD 1x M.2 SSD slot (NVMe PCIe Gen4) 1x M.2 SSD slot (NVMe PCIe Gen5) Compatible</p>\r\n\r\n<p>M&agrave;n h&igrave;nh: 16 inch</p>\r\n',252,20,NULL,12),(23,'Laptop gaming Acer Predator Helios 16',108000000,15,'lios-16-ai-ph16-73-non-fingerprint-with-backlit-on-wp-abyssal-black-01_f2575f2266b14906816a1ce563c469bf_master.png','CPU: Intel Core Ultra 9 275HX Card đồ họa:  NVIDIA GeForce RTX 5090 Laptop GPU, 24GB GDDR7, hỗ trợ Ray Tracing, DLSS, Advanced Optimus Ram:  32GB DDR5-6400 (1x32GB), 2 khe, nâng cấp tối đa 96GB SSD: 2TB SSD PCIe Gen4 NVMe, hỗ trợ mở rộng tối đa 4TB Màn hình: 16 inch','<p>CPU: Intel Core Ultra 9 275HX</p>\r\n\r\n<p>Card đồ họa:<br />\r\nNVIDIA GeForce RTX 5090 Laptop GPU, 24GB GDDR7, hỗ trợ Ray Tracing, DLSS, Advanced Optimus</p>\r\n\r\n<p>Ram:&nbsp; 32GB DDR5-6400 (1x32GB), 2 khe, n&acirc;ng cấp tối đa 96GB</p>\r\n\r\n<p>SSD: 2TB SSD PCIe Gen4 NVMe, hỗ trợ mở rộng tối đa 4TB</p>\r\n\r\n<p>M&agrave;n h&igrave;nh: 16 inch</p>\r\n',43,20,NULL,8),(24,'Laptop gaming HP OMEN 16-am0180TXGB ',36990000,0,'dak8r5vaqq_f6b9d3e708644144aa2e09c6b25e9108_master.png','CPU: Intel® Core™ Ultra 5 225H (up to 4.9 GHz with Intel® Turbo Boost Technology, 18 MB L3 cache, 14 cores, 14 threads) Card đồ họa: NVIDIA® GeForce RTX™ 5050 Laptop GPU (8 GB GDDR7 dedicated) Ram:  16GB (2 x 8GB) DDR5 5600MHz (2x SO-DIMM socket, up to 32GB SDRAM) SSD: 512 GB PCIe® Gen4 NVMe™ M.2 SSD Màn hình: 16 inch','<p>CPU: Intel&reg; Core&trade; Ultra 5 225H (up to 4.9 GHz with Intel&reg; Turbo Boost Technology, 18 MB L3 cache, 14 cores, 14 threads)</p>\r\n\r\n<p>Card đồ họa: NVIDIA&reg; GeForce RTX&trade; 5050 Laptop GPU (8 GB GDDR7 dedicated)</p>\r\n\r\n<p>Ram:&nbsp; 16GB (2 x 8GB) DDR5 5600MHz (2x SO-DIMM socket, up to 32GB SDRAM)</p>\r\n\r\n<p>SSD: 512 GB PCIe&reg; Gen4 NVMe&trade; M.2 SSD</p>\r\n\r\n<p>M&agrave;n h&igrave;nh: 16 inch</p>\r\n',47,20,NULL,14),(25,'Laptop gaming ASUS ROG Zephyrus G14',71280000,0,'asus-rog-zephyrus-g14-ga403uv-qs171w_4aef4676f5ef4fddbb5fd6d0c5fec7d5_295d6371914e47bf83d840031a95751f_master.png','CPU: AMD Ryzen AI 9 HX 370 2.0GHz (36MB Cache, up to 5.1GHz, 12 lõi, 24 luồng) Card đồ họa: NVIDIA GeForce RTX 5070 Ti 12GB GDDR7 AMD Radeon Graphics Ram:  32GB LPDDR5X 8000 on board (Không nâng cấp được) SSD: 1TB PCIe® 4.0 NVMe™ M.2 SSD - Tổng 1 slot support : SSD M.2 2280 G4X4 1TB SSD M.2 2280 G4X4 2TB Màn hình: 14 inch','<p>CPU: AMD Ryzen AI 9 HX 370 2.0GHz (36MB Cache, up to 5.1GHz, 12 l&otilde;i, 24 luồng)</p>\r\n\r\n<p>Card đồ họa: NVIDIA GeForce RTX 5070 Ti 12GB GDDR7 AMD Radeon Graphics</p>\r\n\r\n<p>Ram:&nbsp; 32GB LPDDR5X 8000 on board (Kh&ocirc;ng n&acirc;ng cấp được)</p>\r\n\r\n<p>SSD: 1TB PCIe&reg; 4.0 NVMe&trade; M.2 SSD - Tổng 1 slot support : SSD M.2 2280 G4X4 1TB SSD M.2 2280 G4X4 2TB</p>\r\n\r\n<p>M&agrave;n h&igrave;nh: 14 inch</p>\r\n',12,20,NULL,7),(26,'Laptop gaming Acer Nitro ProPanel',39990000,0,'r-nitro-v-16s-ai-anv16s-61-non-fingerprint-with-backlit-on-wp-black-01_92ea59f5d46746f2a15f3daa59ed61b7_master.png','CPU: AMD Ryzen™ AI 5 340( 6 nhân, 12 luồng) Max. Boost Clock : Up to 4.8 GHz, 6MB L2 Cache Card đồ họa: NVIDIA® GeForce RTX™ 5050 Laptop Ram:  16GB (1x16GB) DDR5 5600MHz (2x SO-DIMM socket, up to 96GB SDRAM) SSD:  512 GB PCIe NVMe SED SSD (Còn trống 1 khe SSD M.2 PCIE, nâng cấp tối đa 4TB) Màn hình: 16 inch','<p>CPU: AMD Ryzen&trade; AI 5 340( 6 nh&acirc;n, 12 luồng) Max. Boost Clock : Up to 4.8 GHz, 6MB L2 Cache</p>\r\n\r\n<p>Card đồ họa: NVIDIA&reg; GeForce RTX&trade; 5050 Laptop</p>\r\n\r\n<p>Ram:&nbsp; 16GB (1x16GB) DDR5 5600MHz (2x SO-DIMM socket, up to 96GB SDRAM)</p>\r\n\r\n<p>SSD: &nbsp;512 GB PCIe NVMe SED SSD (C&ograve;n trống 1 khe SSD M.2 PCIE, n&acirc;ng c&acirc;́p t&ocirc;́i đa 4TB)</p>\r\n\r\n<p>M&agrave;n h&igrave;nh: 16 inch</p>\r\n',12,20,NULL,8),(27,'Laptop gaming Lenovo LOQ 15ARP9',20590000,10,'white_backlit_loq_15arp9_ct1_03_934d509494e04471bfb7a1d774864551_master.png','CPU: AMD Ryzen 5 8645HS Card đồ họa: NVIDIA GeForce RTX 3050 6GB GDDR6 Ram:  16GB SSD:  512 GB  Màn hình: 15 inch','<p>CPU: AMD Ryzen 5 8645HS</p>\r\n\r\n<p>Card đồ họa: NVIDIA GeForce RTX 3050 6GB GDDR6</p>\r\n\r\n<p>Ram:&nbsp; 16GB</p>\r\n\r\n<p>SSD: &nbsp;512 GB</p>\r\n\r\n<p>M&agrave;n h&igrave;nh: 15 inch</p>\r\n',456,20,NULL,11),(28,'Laptop gaming MSI Titan 18 HX',168990000,0,'titan18a14vx_2_522x522_54442b2d0_fb6873b42a1b40ca9ead89505f995da5_master.png','CPU: Intel Core Ultra 9 Arrow Lake H, 2.8Ghz, 24 lõi (8 P-cores + 16 E-cores), 24 luồng, 40MB cache, Max Turbo Frequency 5.5 GHz Card đồ họa: NVIDIA GeForce RTX 5090 Laptop GPU, GDDR7 24GB Ram:  64GB (2 x 32GB) DDR5 6400MHz nâng cấp tối đa 96GB) SSD:  6TB (2TB NVMe PCIe Gen5x4 SSD w/o DRAM + 2TB NVMe PCIe Gen4x4 SSD x2) Màn hình: 18 inch','<p>CPU: Intel Core Ultra 9 Arrow Lake H, 2.8Ghz, 24 l&otilde;i (8 P-cores + 16 E-cores), 24 luồng, 40MB cache, Max Turbo Frequency 5.5 GHz</p>\r\n\r\n<p>Card đồ họa: NVIDIA GeForce RTX 5090 Laptop GPU, GDDR7 24GB</p>\r\n\r\n<p>Ram:&nbsp; 64GB (2 x 32GB) DDR5 6400MHz n&acirc;ng cấp tối đa 96GB)</p>\r\n\r\n<p>SSD: &nbsp;6TB (2TB NVMe PCIe Gen5x4 SSD w/o DRAM + 2TB NVMe PCIe Gen4x4 SSD x2)</p>\r\n\r\n<p>M&agrave;n h&igrave;nh: 18 inch</p>\r\n',75,20,NULL,12),(30,'Laptop gaming Acer Predator Helios Neo 1',95900000,15,'-ai-phn16s-71-non-fingerprint-with-backlit-on-wp-oled-abyssal-black-01_5f32a1e84b6e40bdaf70f2878d7cc639_master.png','CPU: Intel Core Ultra 9 275HX Card đồ họa: NVIDIA® GeForce RTX™ 5070 Ti 12GB Ram:  64GB (32GBx2) DDR5 6400MHz (2x SO-DIMM socket, up to 96GB SDRAM) SSD:  2TB (1TB x 2) M.2 NVMe ( Total 2 Slot) Màn hình: 16 inch','<p>CPU: Intel Core Ultra 9 275HX</p>\r\n\r\n<p>Card đồ họa: NVIDIA&reg; GeForce RTX&trade; 5070 Ti 12GB</p>\r\n\r\n<p>Ram:&nbsp; 64GB (32GBx2) DDR5 6400MHz (2x SO-DIMM socket, up to 96GB SDRAM)</p>\r\n\r\n<p>SSD: &nbsp;2TB (1TB x 2) M.2 NVMe ( Total 2 Slot)</p>\r\n\r\n<p>M&agrave;n h&igrave;nh: 16 inch</p>\r\n',21,20,NULL,7),(37,'Laptop HP EliteBook 640 G11 A7LB4PT',25490000,0,'56206_laptop_hp_elitebook_640_g11_4.jpg','CPU: AMD Ryzen™ 7 7445H (up to 4.7 GHz max boost clock, 16 MB L3 cache, 6 cores, 12 threads) Card đồ họa: NVIDIA® GeForce RTX™ 5050 Ram:  16GB (1 x 16GB) DDR5 5600MHz (2x SO-DIMM socket, up to 64GB SDRAM) SSD: 512 GB PCIe® Gen4 NVMe™ M.2 SSD (1 slot up to 2TB) Màn hình: 15.6 inch','<p>CPU: AMD Ryzen&trade; 7 7445H (up to 4.7 GHz max boost clock, 16 MB L3 cache, 6 cores, 12 threads)</p>\r\n\r\n<p>Card đồ họa: NVIDIA&reg; GeForce RTX&trade; 5050</p>\r\n\r\n<p>Ram: &nbsp;16GB (1 x 16GB) DDR5 5600MHz (2x SO-DIMM socket, up to 64GB SDRAM)</p>\r\n\r\n<p>SSD: 512 GB PCIe&reg; Gen4 NVMe&trade; M.2 SSD (1 slot up to 2TB)</p>\r\n\r\n<p>M&agrave;n h&igrave;nh: 15.6 inch</p>\r\n',0,20,NULL,14),(38,'Laptop HP Elitebook 8470p',15490000,0,'Laptop-Cu-HP-Elitebook-8470P-Mua-Ban-Cung-Cap-Phan-Phoi-Laptop-Cu-Gia-Re-Moi-Nhap-Khau-Thanh-Ly-Cho-Thue-Laptop-Gia-Re-Nhat-My-Usa-Japan-Tphcm-Laptopgiasi.Vn-8.png','CPU: AMD Ryzen™ 7 7445H (up to 4.7 GHz max boost clock, 16 MB L3 cache, 6 cores, 12 threads) Card đồ họa: NVIDIA® GeForce RTX™ 5050 Ram:  16GB (1 x 16GB) DDR5 5600MHz (2x SO-DIMM socket, up to 64GB SDRAM) SSD: 512 GB PCIe® Gen4 NVMe™ M.2 SSD (1 slot up to 2TB) Màn hình: 15.6 inch','<p>CPU: AMD Ryzen&trade; 7 7445H (up to 4.7 GHz max boost clock, 16 MB L3 cache, 6 cores, 12 threads)</p>\r\n\r\n<p>Card đồ họa: NVIDIA&reg; GeForce RTX&trade; 5050</p>\r\n\r\n<p>Ram: &nbsp;16GB (1 x 16GB) DDR5 5600MHz (2x SO-DIMM socket, up to 64GB SDRAM)</p>\r\n\r\n<p>SSD: 512 GB PCIe&reg; Gen4 NVMe&trade; M.2 SSD (1 slot up to 2TB)</p>\r\n\r\n<p>M&agrave;n h&igrave;nh: 15.6 inch</p>\r\n',0,20,NULL,14),(39,'Laptop HP Gaming 15',28900000,15,'30348_hp_pavilion_gaming_1.jpg','CPU: AMD Ryzen™ 7 7445H (up to 4.7 GHz max boost clock, 16 MB L3 cache, 6 cores, 12 threads) Card đồ họa: NVIDIA® GeForce RTX™ 5050 Ram:  16GB (1 x 16GB) DDR5 5600MHz (2x SO-DIMM socket, up to 64GB SDRAM) SSD: 512 GB PCIe® Gen4 NVMe™ M.2 SSD (1 slot up to 2TB) Màn hình: 15.6 inch','<p>CPU: AMD Ryzen&trade; 7 7445H (up to 4.7 GHz max boost clock, 16 MB L3 cache, 6 cores, 12 threads)</p>\r\n\r\n<p>Card đồ họa: NVIDIA&reg; GeForce RTX&trade; 5050</p>\r\n\r\n<p>Ram: &nbsp;16GB (1 x 16GB) DDR5 5600MHz (2x SO-DIMM socket, up to 64GB SDRAM)</p>\r\n\r\n<p>SSD: 512 GB PCIe&reg; Gen4 NVMe&trade; M.2 SSD (1 slot up to 2TB)</p>\r\n\r\n<p>M&agrave;n h&igrave;nh: 15.6 inch</p>\r\n',2,20,NULL,14),(40,'Laptop HP 240R',28900000,0,'56156_hp_240r_g9_ax3c6at.jpg','CPU: AMD Ryzen™ 7 7445H (up to 4.7 GHz max boost clock, 16 MB L3 cache, 6 cores, 12 threads) Card đồ họa: NVIDIA® GeForce RTX™ 5050 Ram:  16GB (1 x 16GB) DDR5 5600MHz (2x SO-DIMM socket, up to 64GB SDRAM) SSD: 512 GB PCIe® Gen4 NVMe™ M.2 SSD (1 slot up to 2TB) Màn hình: 15.6 inch','<p>CPU: AMD Ryzen&trade; 7 7445H (up to 4.7 GHz max boost clock, 16 MB L3 cache, 6 cores, 12 threads)</p>\r\n\r\n<p>Card đồ họa: NVIDIA&reg; GeForce RTX&trade; 5050</p>\r\n\r\n<p>Ram: &nbsp;16GB (1 x 16GB) DDR5 5600MHz (2x SO-DIMM socket, up to 64GB SDRAM)</p>\r\n\r\n<p>SSD: 512 GB PCIe&reg; Gen4 NVMe&trade; M.2 SSD (1 slot up to 2TB)</p>\r\n\r\n<p>M&agrave;n h&igrave;nh: 15.6 inch</p>\r\n',1,20,NULL,14),(41,' Laptop gaming HP Victus 16',28900000,15,'AY8X9PA-3.jpg','CPU: AMD Ryzen™ 7 7445H (up to 4.7 GHz max boost clock, 16 MB L3 cache, 6 cores, 12 threads) Card đồ họa: NVIDIA® GeForce RTX™ 5050 Ram:  16GB (1 x 16GB) DDR5 5600MHz (2x SO-DIMM socket, up to 64GB SDRAM) SSD: 512 GB PCIe® Gen4 NVMe™ M.2 SSD (1 slot up to 2TB) Màn hình: 15.6 inch','<p>CPU: AMD Ryzen&trade; 7 7445H (up to 4.7 GHz max boost clock, 16 MB L3 cache, 6 cores, 12 threads)</p>\r\n\r\n<p>Card đồ họa: NVIDIA&reg; GeForce RTX&trade; 5050</p>\r\n\r\n<p>Ram: &nbsp;16GB (1 x 16GB) DDR5 5600MHz (2x SO-DIMM socket, up to 64GB SDRAM)</p>\r\n\r\n<p>SSD: 512 GB PCIe&reg; Gen4 NVMe&trade; M.2 SSD (1 slot up to 2TB)</p>\r\n\r\n<p>M&agrave;n h&igrave;nh: 15.6 inch</p>\r\n',3,18,NULL,14),(42,'Laptop HP Gaming VICTUS 15',34490000,0,'Laptop-HP-Gaming-VICTUS-15-fa2451TX-i5-13420H16GB512GB-SSD15.6FHDWLBT4C6G_RTX-4050LKBWin11Den-1.jpg','CPU: AMD Ryzen™ 7 7445H (up to 4.7 GHz max boost clock, 16 MB L3 cache, 6 cores, 12 threads) Card đồ họa: NVIDIA® GeForce RTX™ 5050 Ram:  16GB (1 x 16GB) DDR5 5600MHz (2x SO-DIMM socket, up to 64GB SDRAM) SSD: 512 GB PCIe® Gen4 NVMe™ M.2 SSD (1 slot up to 2TB) Màn hình: 15.6 inch','<p>CPU: AMD Ryzen&trade; 7 7445H (up to 4.7 GHz max boost clock, 16 MB L3 cache, 6 cores, 12 threads)</p>\r\n\r\n<p>Card đồ họa: NVIDIA&reg; GeForce RTX&trade; 5050</p>\r\n\r\n<p>Ram: &nbsp;16GB (1 x 16GB) DDR5 5600MHz (2x SO-DIMM socket, up to 64GB SDRAM)</p>\r\n\r\n<p>SSD: 512 GB PCIe&reg; Gen4 NVMe&trade; M.2 SSD (1 slot up to 2TB)</p>\r\n\r\n<p>M&agrave;n h&igrave;nh: 15.6 inch</p>\r\n',0,19,NULL,14),(43,'Laptop HP OmniBook',21690000,0,'56156_hp_240r_g9_ax3c6at (1).jpg','CPU: AMD Ryzen™ 7 7445H (up to 4.7 GHz max boost clock, 16 MB L3 cache, 6 cores, 12 threads) Card đồ họa: NVIDIA® GeForce RTX™ 5050 Ram:  16GB (1 x 16GB) DDR5 5600MHz (2x SO-DIMM socket, up to 64GB SDRAM) SSD: 512 GB PCIe® Gen4 NVMe™ M.2 SSD (1 slot up to 2TB) Màn hình: 15.6 inch','<p>CPU: AMD Ryzen&trade; 7 7445H (up to 4.7 GHz max boost clock, 16 MB L3 cache, 6 cores, 12 threads)</p>\r\n\r\n<p>Card đồ họa: NVIDIA&reg; GeForce RTX&trade; 5050</p>\r\n\r\n<p>Ram: &nbsp;16GB (1 x 16GB) DDR5 5600MHz (2x SO-DIMM socket, up to 64GB SDRAM)</p>\r\n\r\n<p>SSD: 512 GB PCIe&reg; Gen4 NVMe&trade; M.2 SSD (1 slot up to 2TB)</p>\r\n\r\n<p>M&agrave;n h&igrave;nh: 15.6 inch</p>\r\n',4,19,NULL,14),(48,'QR Coupon Test Laptop 2',15000000,0,'test.jpg','test','test',1,8,NULL,1),(50,'QR Polish Check Laptop',32000000,0,'test.jpg','test','test',0,7,NULL,1);
/*!40000 ALTER TABLE `product` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `question`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `question` (
  `id_ques` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL,
  `email` varchar(250) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `contennt` text NOT NULL,
  PRIMARY KEY (`id_ques`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `question` WRITE;
/*!40000 ALTER TABLE `question` DISABLE KEYS */;
INSERT INTO `question` VALUES (16,'Tran Trung','trantrung123@gmail.com','0987654321','Khi nào có máy mới hả shop'),(17,'Tuan Anh','nguyenductuananh910@gmail.com','0987654321','bh co may'),(18,'Test','t@x.com','0912345678','Test question'),(19,'Test','t@x.com','0912345678','Test contact');
/*!40000 ALTER TABLE `question` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `sex` tinyint(4) NOT NULL DEFAULT 0,
  `email_user` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `phone_user` varchar(25) NOT NULL,
  `img_user` varchar(255) NOT NULL,
  `register_date` date DEFAULT NULL,
  `last_login` date DEFAULT NULL,
  `role` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `idx_user_name` (`user_name`),
  UNIQUE KEY `idx_email_user` (`email_user`)
) ENGINE=InnoDB AUTO_INCREMENT=83 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (16,'admin','$2y$10$3xaDL7btNlSYDeh2uAi69.VeunfFPoXJTrQWMmdNweo66GwjdhHwK','Turbotech Admin',0,'zatuzick03@gmail.com','','','',NULL,NULL,1),(27,'nam2108004','$2y$10$0sShHdM7f7fOPlVHCpEXb.sUq8OLz8x1AdMn5EbUCmAk9J9BZEgP6','nguyen van nam',0,'nguyenvannam5131@gmail.com','','','',NULL,NULL,0),(28,'tuananh910','$2y$10$rRYbFqdSdQJWUWJ7/HJkQOiUFesQz5yi/LCtK6IoQ5fzk0TQWgMUu','Tuan Anh',0,'nguyenductuananh910@gmail.com','','','',NULL,NULL,0),(29,'ductuan123','$2y$10$eB5KqVKciqYJ2Gy7qLbN8u1bNY2LQ0K6qPtNbqGSmvPWtP2xzXuQq','Duc Tuan',0,'ductuan99@gmail.com','','','',NULL,NULL,0),(30,'trantrung123','$2y$10$I6rXJPJBDgoVfFlxcf5WnO6gpZW/wWEh1n/achIvRLFwzrFLvooGe','Tran Trung',0,'trantrung0@gmail.com','','','bo-vong-tron-nguoi-dung_78370-4704.avif',NULL,NULL,0),(31,'quanghuy','$2y$10$EheLA8sHNgdmtf8eJ7OcReESpQGCdBwvnYxsE3oiNLyHZfOrTTa/u','Quang Huy',0,'quanghuyy0@gmail.com','','','',NULL,NULL,0),(32,'minhtuan','$2y$10$XHJenoehNmDvYdxZEYhLOu.aQynfYyviNxrHp5qrlVF7PkVxGGgmm','Minh Tuan',0,'minhtuan123@gmail.com','','','',NULL,NULL,0),(33,'ducminh','$2y$10$7bJRhySQnNlmWNBxhBNRTu703qqdaF2nJf9Eb3SowaL0LqFwYAnUi','Duc Minh',0,'ducminhabc@gmail.com','','','',NULL,NULL,0),(35,'phase05test1','$2y$10$Skm3hPzcNQCKglZWjxbdG.1JxW.g9pzX1WPHjfT2x4f/OAFZbyauq','Phase05 Tester',0,'phase05test1@example.com','','','',NULL,NULL,0),(50,'duonganhduc','$2y$10$S5DhlUPEiEh0BapDgi2VXOosXg78fjbxElQjTwQGTbxORHreTnptK','Dương Đức',0,'duonganhduc6a4@gmail.com','Ngọc Lâm, Long Biên, Hà Nội','0123456789','',NULL,NULL,0);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

