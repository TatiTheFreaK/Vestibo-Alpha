-- MySQL dump 10.13  Distrib 5.1.66, for redhat-linux-gnu (x86_64)
--
-- Host: localhost    Database: u395938104_ves
-- ------------------------------------------------------
-- Server version	5.1.66

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
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
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'auto incrementing user_id of each user, unique index',
  `user_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT 'user''s name, unique',
  `user_password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'user''s password in salted and hashed format',
  `user_email` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT 'user''s email, unique',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_name` (`user_name`),
  UNIQUE KEY `user_email` (`user_email`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='user data';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (7,'dennysuchiha','$2y$10$alQ/6XaYLv8t6KG9hPRsK.zOz5r5vm7sdSzs/Fi/R9kdrqJXqazBK','dennys_spfc@hotmail.com'),(8,'teste','$2y$10$g7.zeFb7O1NUeR8gyAXhCeDtrfL9twHpKct.VftX0faxv3coub7hS','teste@teste.com'),(9,'Vinicius','$2y$10$CP1kh457FDaKvSehVNWtAeorRdSDeD4PEB87qOXqI51wafmJj1fiK','viniciusnm16@hotmail.com'),(10,'william','$2y$10$PU8z9aBaiWydCMWPZgnH4ukdsZsI9c5dXC71wborx5l0Wm3atKOFC','filhodaputa@hotmail.com'),(11,'Gugu','$2y$10$cloeE.w7AYC2bHaEslwR5.N93YRfEl8swJqVknqrFbYaEZTx6da1W','darkflame438@gmail.com'),(12,'will15','$2y$10$56PDJlkpSeloM5vjx0k0IOyP4584PLWlPvGsU3saH.Kh0t8Zgc6F6','william15@hotmail.com'),(13,'caique','$2y$10$zXgRKoSdveaj2OtUVieOQ.IH.sg68LhEB0mzmpUkRfqlRlpajREx6','caiquekoba@ymail.com'),(14,'root','$2y$10$Oa/al/WwDiYB0YvPLI5wj.B48ozsPYZjbK2Xj0afrXI4BVQddYZTu','matheuspbaroni@gmail.com'),(15,'crakudo','$2y$10$qZ8WJ77atjjG86EARtA/beUbu3QoXEQWiRvxhYOfTCVAir2w1kwli','eusonalandroo@outlook.com'),(16,'Telma','$2y$10$icZg1NClEN4GOyS9udWisuTfFCQKhCBGeH7qfjJgQmHTP2bXu8.4C','telmacpbaroni@gmail.com'),(17,'kenji','$2y$10$P9/nXhAJItU9LNx8gNJC8Oi3IPvF1ZxBmUHYz86o4s2ggaWMmnAwG','kenji-kamakura@hotmail.com');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-11-30 17:23:12
