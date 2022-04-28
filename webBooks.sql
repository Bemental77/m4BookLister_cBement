CREATE DATABASE  IF NOT EXISTS `webbooks` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */;
USE `webbooks`;
-- MySQL dump 10.13  Distrib 8.0.12, for Win64 (x86_64)
--
-- Host: localhost    Database: webbooks
-- ------------------------------------------------------
-- Server version	8.0.12

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
 SET NAMES utf8 ;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `authors`
--

DROP TABLE IF EXISTS `authors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `authors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `authorName` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `authors`
--

LOCK TABLES `authors` WRITE;
/*!40000 ALTER TABLE `authors` DISABLE KEYS */;
INSERT INTO `authors` VALUES (1,'Stephen Lawhead'),(2,'Winston Churchill'),(3,'Michael Howard'),(4,'Joe Smith'),(5,'Louis Lamour'),(6,'Arthur Conan Doyle'),(7,'Billy \"the Kid\" Hintz'),(8,'Mr C'),(9,'Sir Hacksalot'),(10,'Stephen King'),(11,'Alfred Hitchkok'),(12,'Marie Antoinette'),(13,'Frank Castle - The Punisher');
/*!40000 ALTER TABLE `authors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bookstuff`
--

DROP TABLE IF EXISTS `bookstuff`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `bookstuff` (
  `bookCode` int(11) NOT NULL AUTO_INCREMENT,
  `bookTitle` varchar(255) NOT NULL,
  `catId` int(11) NOT NULL,
  `authorId` int(11) NOT NULL,
  PRIMARY KEY (`bookCode`)
) ENGINE=MyISAM AUTO_INCREMENT=33 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bookstuff`
--

LOCK TABLES `bookstuff` WRITE;
/*!40000 ALTER TABLE `bookstuff` DISABLE KEYS */;
INSERT INTO `bookstuff` VALUES (1,'Taliesin',1,1),(2,'Grail',1,1),(3,'Merlin',1,1),(4,'Arthur',1,1),(5,'Pendragon',1,1),(6,'History of the Ancient World',2,2),(7,'The Franco-Prussian War of 1870',2,3),(8,'Cherynobyl',2,4),(9,'Shalako',3,5),(10,'Guns of the Timberlands',3,5),(11,'Under the Sweetwater Rim',3,5),(12,'Sackett',3,5),(13,'Bendigo Shafter',3,5),(14,'O\' Reilly\'s Luck',3,5),(15,'Last Stand at Papago Wells',3,5),(16,'Hondo',3,5),(17,'The Hound of the Baskervilles',4,6),(18,'A Study in Scarlet',4,6),(19,'The Sign of Four',4,6),(20,'The Valley of Fear',4,6),(21,'The Six Napoleons',4,6),(22,'Five Orange Pips',4,6),(23,'Warsaw',5,7),(24,'The Man Who Knew too Little',5,8),(25,'The Gates of Stalingrad',5,9),(26,'The Gunslinger',6,10),(27,'The Birds',6,11),(28,'Children of the Corn',6,10),(29,'Vertigo',6,11),(30,'Rear Window',6,11),(31,'Midnight Grafitti',6,12),(32,'Lonesome Dove',3,13);
/*!40000 ALTER TABLE `bookstuff` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Celtic Novel'),(2,'History'),(3,'Western'),(4,'Mystery'),(5,'Historical Novel'),(6,'Horror');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-04-05 11:01:55

CREATE USER IF NOT EXISTS
 'itsd'@'localhost' IDENTIFIED WITH mysql_native_password BY 'mysql';
 
GRANT ALL ON webbooks.* TO 'itsd'@'localhost';
