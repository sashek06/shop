-- MariaDB dump 10.17  Distrib 10.4.11-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: shop
-- ------------------------------------------------------
-- Server version	10.4.11-MariaDB

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

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) DEFAULT NULL,
  `descr` text DEFAULT NULL,
  `parent` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (0,' не выбрано','',0),(16,'Снаряжение','',0),(17,'Репутация','',0),(28,'Достижения ','',0);
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `discounts`
--

DROP TABLE IF EXISTS `discounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `discounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `value` decimal(10,2) NOT NULL,
  `start` date NOT NULL,
  `stop` date NOT NULL,
  `name` varchar(200) NOT NULL COMMENT 'название акции/скидки',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='скидки и акции';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `discounts`
--

LOCK TABLES `discounts` WRITE;
/*!40000 ALTER TABLE `discounts` DISABLE KEYS */;
INSERT INTO `discounts` VALUES (1,5.00,'2010-06-01','2025-12-31','подарочная'),(2,10.00,'2023-04-01','2023-12-31','Скидка'),(5,1.00,'2023-10-01','2023-10-31','новая');
/*!40000 ALTER TABLE `discounts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `items`
--

DROP TABLE IF EXISTS `items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ord_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=64 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `items`
--

LOCK TABLES `items` WRITE;
/*!40000 ALTER TABLE `items` DISABLE KEYS */;
INSERT INTO `items` VALUES (17,5,27,1.00,451.00,1),(20,5,24,2.00,105.00,1),(22,5,28,1.00,2100.00,1),(25,8,27,3.00,270.00,1),(28,7,28,1.00,2100.00,20),(30,10,24,8.00,2700.00,1),(31,10,28,2.00,4200.00,1),(32,9,28,1.00,4200.00,20),(34,9,27,1.00,4800.00,20),(38,11,28,1.00,4200.00,19),(40,12,32,1.00,5400.00,19),(42,13,28,1.00,4200.00,1),(45,14,28,1.00,4200.00,11),(46,15,26,1.00,4200.00,19),(47,15,32,1.00,6850.00,19),(48,15,38,1.00,5040.00,19),(50,16,26,1.00,2000.00,19),(51,16,37,1.00,1320.00,19),(52,16,32,1.00,1600.00,19),(53,16,41,1.00,4370.00,19),(54,16,38,1.00,5320.00,19),(59,17,32,1.00,3420.00,11),(60,17,38,1.00,13300.00,11),(61,17,27,1.00,3250.00,11),(62,18,45,1.00,570.00,1),(63,18,36,1.00,1600.00,1);
/*!40000 ALTER TABLE `items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `levels`
--

DROP TABLE IF EXISTS `levels`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `levels` (
  `id` int(11) NOT NULL,
  `descr` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `levels`
--

LOCK TABLES `levels` WRITE;
/*!40000 ALTER TABLE `levels` DISABLE KEYS */;
INSERT INTO `levels` VALUES (1,'пользователь'),(2,'менеджер'),(10,'администратор');
/*!40000 ALTER TABLE `levels` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dt` datetime DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (1,'2023-12-04 19:29:00',1,1),(2,'2023-12-02 19:16:52',19,0),(3,'2023-12-02 21:30:58',20,0),(5,'2023-12-26 08:31:14',1,0),(6,'2023-10-26 08:31:26',1,0),(7,'2022-11-26 08:38:57',20,0),(8,'2023-11-30 08:30:39',1,0),(9,'2023-11-14 13:53:48',20,0),(10,'2023-04-22 08:08:06',1,0),(11,'2023-12-22 20:17:44',19,0),(12,'2023-10-22 20:17:59',19,0),(13,'2023-11-24 17:23:01',1,0),(14,'2023-12-24 17:39:32',11,0),(15,'2023-12-28 12:58:31',19,0),(16,'2024-01-09 13:20:56',19,0),(17,'2024-01-10 13:04:29',11,0),(18,'2024-01-11 10:53:33',1,0);
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) DEFAULT NULL,
  `descr` text DEFAULT NULL,
  `cat_id` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `discount_id` int(11) NOT NULL COMMENT 'id акции или скидки',
  `date_add` datetime NOT NULL DEFAULT current_timestamp(),
  `weight` int(11) NOT NULL COMMENT 'масса',
  `length` int(11) NOT NULL COMMENT 'длина',
  `width` int(11) NOT NULL COMMENT 'ширина',
  `height` int(11) NOT NULL COMMENT 'высота',
  `amount` int(11) NOT NULL COMMENT 'количество',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (28,'Тлеющее яйцо Миллагазора','Мы улучшим экипировку вашего персонажа',16,1500.00,0,'2024-01-11 12:52:52',0,0,0,0,999),(36,'Военачальник','Достижение и титул - это куммулятивное достижение, которое включает в себя множество',28,1600.00,0,'2023-12-23 14:37:58',0,0,0,0,999),(42,'Уровень чести','Система уровней чести снова была переработана, но большинство наград и достижений остались',17,2500.00,1,'2024-01-09 10:41:35',0,0,0,0,1),(44,'Слава герою Дренора','Выполнив достижение вы получите не только маунта вепря',28,2900.00,0,'2024-01-11 12:50:07',0,0,0,0,999),(45,'Образец репутации','Повышение репутации',17,600.00,1,'2024-01-11 12:49:08',0,0,0,0,999);
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `statuses`
--

DROP TABLE IF EXISTS `statuses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `statuses` (
  `id` int(11) NOT NULL,
  `descr` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `statuses`
--

LOCK TABLES `statuses` WRITE;
/*!40000 ALTER TABLE `statuses` DISABLE KEYS */;
INSERT INTO `statuses` VALUES (0,'открыт'),(1,'закрыт'),(2,'удален'),(4,'отменен'),(5,'ожидает доставку');
/*!40000 ALTER TABLE `statuses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `surname` varchar(100) DEFAULT NULL COMMENT 'фамилия',
  `name` varchar(100) NOT NULL,
  `middlename` varchar(100) NOT NULL,
  `rank` varchar(200) DEFAULT NULL COMMENT 'должность',
  `phone` varchar(15) DEFAULT NULL,
  `address` varchar(200) DEFAULT NULL,
  `password` varchar(32) DEFAULT NULL,
  `login` varchar(20) DEFAULT NULL,
  `date_reg` datetime DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `level` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Инина','Инна','','администратор','79152014540','','admin','admin',NULL,NULL,10),(11,'Курникова','Лариса','Ивановна','','79562305454','','kor','kor',NULL,NULL,1),(12,'2234','','','','2234','2234','2234','2234',NULL,NULL,0),(14,'Медведчук','Игнат','','','','','let','let',NULL,NULL,2),(19,'Дорина','Ирина','','','','','dod','dod',NULL,NULL,1),(20,'Сурин','Евгений','Анатольевич','','74165415444','','gug','gug',NULL,NULL,1),(21,'Цганчук','Олег','','','','','666','666',NULL,NULL,1),(22,'Амфин','Игорь','Игоревич','','79463163141','','kool','kool',NULL,NULL,1);
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

-- Dump completed on 2024-01-11 12:54:22
