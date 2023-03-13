-- MySQL dump 10.13  Distrib 8.0.31, for Win64 (x86_64)
--
-- Host: localhost    Database: callcenter
-- ------------------------------------------------------
-- Server version	8.0.31

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
-- Table structure for table `fila`
--

DROP TABLE IF EXISTS `fila`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `fila` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nomeRamal` int NOT NULL,
  `chamadas` int NOT NULL DEFAULT (0),
  `agente` varchar(250) NOT NULL,
  `status` varchar(45) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status_updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`nomeRamal`) REFERENCES `ramais` (`nome`)
);

--
-- Dumping data for table `fila`
--

LOCK TABLES `fila` WRITE;
/*!40000 ALTER TABLE `fila` DISABLE KEYS */;
INSERT INTO `fila` VALUES (161,7000,0,'Chaves','disponivel','2023-02-20 09:57:20','2023-02-21 07:22:10','2023-02-21 10:02:19'),(162,7001,0,'Kiko','chamando','2023-02-20 09:57:20','2023-02-20 06:57:20',NULL),(163,7002,3,'Chiquinha','indisponivel','2023-02-20 09:57:20','2023-02-20 06:57:20',NULL),(164,7003,48,'Nhonho','indisponivel','2023-02-20 09:57:20','2023-02-20 06:57:20',NULL),(165,7004,0,'Godines','pausado','2023-02-20 09:57:20','2023-02-20 06:57:20',NULL);
/*!40000 ALTER TABLE `fila` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ramais`
--

DROP TABLE IF EXISTS `ramais`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ramais` (
  `nome` int NOT NULL,
  `ramal` int NOT NULL,
  `IP` varchar(45) DEFAULT NULL,
  `online` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`nome`)
);

--
-- Dumping data for table `ramais`
--

LOCK TABLES `ramais` WRITE;
/*!40000 ALTER TABLE `ramais` DISABLE KEYS */;
INSERT INTO `ramais` VALUES (7000,7000,'181.219.125.7',1,'2023-02-20 09:57:20','2023-02-20 06:57:20'),(7001,7001,'181.219.125.7',1,'2023-02-20 09:57:20','2023-02-20 06:57:20'),(7002,7004,'181.219.125.7',1,'2023-02-20 09:57:20','2023-02-20 06:57:20'),(7003,7003,'(Unspecified)',0,'2023-02-20 09:57:20','2023-02-20 06:57:20'),(7004,7002,'(Unspecified)',0,'2023-02-20 09:57:20','2023-02-20 06:57:20');
/*!40000 ALTER TABLE `ramais` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha` text NOT NULL,
  PRIMARY KEY (`id`)
);

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (1,'Leonardo Vitor Sitnievski','leonardositnievski@gmail.com','49b20d204f98c04201ff65a6a64e7690');
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-03-05 13:50:51
