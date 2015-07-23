-- MySQL dump 10.15  Distrib 10.0.20-MariaDB, for debian-linux-gnu (i686)
--
-- Host: localhost    Database: gaseame
-- ------------------------------------------------------
-- Server version	10.0.20-MariaDB-1~wheezy-log

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
-- Current Database: `gaseamap`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `gaseamap` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `gaseamap`;

--
-- Table structure for table `cuantitativos`
--

DROP TABLE IF EXISTS `cuantitativos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cuantitativos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `estacion` varchar(255) DEFAULT NULL,
  `hora` varchar(255) DEFAULT NULL,
  `estado` int(11) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10251 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cuantitativos`
--

LOCK TABLES `cuantitativos` WRITE;
/*!40000 ALTER TABLE `cuantitativos` DISABLE KEYS */;
/*!40000 ALTER TABLE `cuantitativos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `estaciones`
--

DROP TABLE IF EXISTS `estaciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `estaciones` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) DEFAULT NULL,
  `longitud` varchar(255) DEFAULT NULL,
  `latitud` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `estaciones`
--

LOCK TABLES `estaciones` WRITE;
/*!40000 ALTER TABLE `estaciones` DISABLE KEYS */;
INSERT INTO `estaciones` VALUES (1,'La Rabida','-6.92026798998562','37.1997646111733'),(2,'Marismas del Titan','-6.94218747450492','37.2525815986743'),(3,'Pozo Dulce','-6.93513952108221','37.2533569113105'),(4,'Los Rosales','-6.92546310245881','37.2601556171702'),(5,'La Orden','-6.93807633408643','37.2796354461791'),(6,'Campus del Carmen','-6.9246203280391','37.2714800687201'),(7,'Palos','-6.88857735350462','37.2192510920051'),(8,'Punta Umbria','-6.96413653775016','37.1856818840077'),(9,'San Juan del Puerto','-6.84462674008048','37.315395192284');
/*!40000 ALTER TABLE `estaciones` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-07-23 23:11:23
