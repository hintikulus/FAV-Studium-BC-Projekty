CREATE DATABASE  IF NOT EXISTS `hra` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_czech_ci */;
USE `hra`;
-- MySQL dump 10.13  Distrib 8.0.22, for Win64 (x86_64)
--
-- Host: vpn.hintik.cz    Database: hra
-- ------------------------------------------------------
-- Server version	5.5.5-10.3.27-MariaDB-0+deb10u1

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
-- Table structure for table `ALIANCE`
--

DROP TABLE IF EXISTS `ALIANCE`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ALIANCE` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `nazev` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `zkratka` varchar(45) COLLATE utf8_czech_ci NOT NULL,
  `info` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `id_zakladatel` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_CECHY_HRACI1_idx` (`id_zakladatel`),
  CONSTRAINT `fk_CECHY_HRACI1` FOREIGN KEY (`id_zakladatel`) REFERENCES `HRACI` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ALIANCE`
--

LOCK TABLES `ALIANCE` WRITE;
/*!40000 ALTER TABLE `ALIANCE` DISABLE KEYS */;
INSERT INTO `ALIANCE` VALUES (1,'Velkoobchodní Aliance','VOA','Aliance pro obchodníky z celého světa',1),(2,'Aliance Spojených Sil','ASS','Vítězství je naše',4);
/*!40000 ALTER TABLE `ALIANCE` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ALIENCNI_FORA`
--

DROP TABLE IF EXISTS `ALIENCNI_FORA`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ALIENCNI_FORA` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `nazev` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `id_aliance` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_CECHOVNI_FORA_CECHY1_idx` (`id_aliance`),
  CONSTRAINT `fk_CECHOVNI_FORA_CECHY1` FOREIGN KEY (`id_aliance`) REFERENCES `ALIANCE` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ALIENCNI_FORA`
--

LOCK TABLES `ALIENCNI_FORA` WRITE;
/*!40000 ALTER TABLE `ALIENCNI_FORA` DISABLE KEYS */;
INSERT INTO `ALIENCNI_FORA` VALUES (1,'Útoky',1),(2,'Podpora',1),(3,'Směna surovin',1),(4,'Plánování útoků',2),(5,'Pokec',2);
/*!40000 ALTER TABLE `ALIENCNI_FORA` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `HRACI`
--

DROP TABLE IF EXISTS `HRACI`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `HRACI` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `jmeno` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `zkusenosti` int(11) NOT NULL DEFAULT 0,
  `popis` text COLLATE utf8_czech_ci DEFAULT NULL,
  `id_aliance` bigint(20) DEFAULT NULL,
  `id_uzivatel` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_HRACI_CECHY1_idx` (`id_aliance`),
  KEY `fk_HRACI_UZIVATELE1_idx` (`id_uzivatel`),
  CONSTRAINT `fk_HRACI_CECHY1` FOREIGN KEY (`id_aliance`) REFERENCES `ALIANCE` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_HRACI_UZIVATELE1` FOREIGN KEY (`id_uzivatel`) REFERENCES `UZIVATELE` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `HRACI`
--

LOCK TABLES `HRACI` WRITE;
/*!40000 ALTER TABLE `HRACI` DISABLE KEYS */;
INSERT INTO `HRACI` VALUES (1,'hintik',30056,NULL,1,1),(2,'PepaZDepa',1052,'Depo číslo 64',2,2),(3,'Bojovný Jouda',563,NULL,1,3),(4,'Alžběta I.',18623,'Královna svého království',NULL,4),(5,'Královský šašek',12069,NULL,2,5);
/*!40000 ALTER TABLE `HRACI` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `IP_ADRESY`
--

DROP TABLE IF EXISTS `IP_ADRESY`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `IP_ADRESY` (
  `id_ip` bigint(20) NOT NULL AUTO_INCREMENT,
  `ip` varchar(15) COLLATE utf8_czech_ci NOT NULL,
  `blokovani` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id_ip`),
  UNIQUE KEY `ip_UNIQUE` (`ip`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `IP_ADRESY`
--

LOCK TABLES `IP_ADRESY` WRITE;
/*!40000 ALTER TABLE `IP_ADRESY` DISABLE KEYS */;
INSERT INTO `IP_ADRESY` VALUES (1,'192.168.0.1',0),(2,'192.168.5.3',0),(3,'10.0.0.5',0),(4,'10.0.0.10',0),(5,'10.0.5.3',0),(6,'192.168.100.123',0),(7,'10.10.10.10',0),(8,'10.10.23.2',0),(9,'192.168.4.32',0),(13,'192.168.255.1',0);
/*!40000 ALTER TABLE `IP_ADRESY` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `MESTA`
--

DROP TABLE IF EXISTS `MESTA`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `MESTA` (
  `pozice_x` int(11) NOT NULL,
  `pozice_y` int(11) NOT NULL,
  `nazev` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `p_obyvatel` int(11) NOT NULL,
  `u_radnice` smallint(6) NOT NULL DEFAULT 0,
  `u_kostel` smallint(6) NOT NULL DEFAULT 0,
  `u_fabriky` smallint(6) NOT NULL DEFAULT 0,
  `u_hradeb` smallint(6) NOT NULL DEFAULT 0,
  `u_kasaren` smallint(6) NOT NULL DEFAULT 0,
  `p_vojaku` int(11) NOT NULL DEFAULT 0,
  `id_hrac` bigint(20) NOT NULL,
  PRIMARY KEY (`pozice_x`,`pozice_y`),
  KEY `fk_MESTO_HRACI1_idx` (`id_hrac`),
  CONSTRAINT `fk_MESTO_HRACI1` FOREIGN KEY (`id_hrac`) REFERENCES `HRACI` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `MESTA`
--

LOCK TABLES `MESTA` WRITE;
/*!40000 ALTER TABLE `MESTA` DISABLE KEYS */;
INSERT INTO `MESTA` VALUES (0,1,'Town',1253,6,5,4,3,3,158,3),(1,0,'Velkoměsto',1002,5,6,2,4,3,105,1),(1,1,'Maloměsto',302,1,2,1,0,1,15,1),(2,2,'ForceVillage',3250,10,7,7,6,5,320,4),(5,2,'Blank Name (5,2)',429,2,1,1,2,1,35,2),(6,5,'Brno',60,1,0,1,1,1,45,5),(7,4,'NevimNazev',1364,6,2,2,2,3,103,3);
/*!40000 ALTER TABLE `MESTA` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PREDMETY`
--

DROP TABLE IF EXISTS `PREDMETY`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `PREDMETY` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `nazev` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `popis` text COLLATE utf8_czech_ci NOT NULL,
  `cesta_k_ikonce` text COLLATE utf8_czech_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PREDMETY`
--

LOCK TABLES `PREDMETY` WRITE;
/*!40000 ALTER TABLE `PREDMETY` DISABLE KEYS */;
INSERT INTO `PREDMETY` VALUES (1,'Registrační bonus','Registrační bonus pro všechny uživatele','imgs/reg.png'),(2,'Meč pána z Griffindoru ','Meč slavného zakladatele bradavické školy čar a kouzel.','imgs/sword.png'),(3,'Prsten moci','Jeden prsten vládne všem, jeden jim všem káže, jeden všechny přivede, do temnoty sváže.','imgs/ring.png'),(4,'Balíček potravin','Jednorázový balíček pro doplnění potravin v jednom tvém městě','imgs/food.png'),(5,'Dračí vejce','Slouží k vytvoření draka.','imgs/dragon_egg.png'),(6,'Balíček surovin','Jednorázový balíček pro doplnění surovin.','imgs/materials.png');
/*!40000 ALTER TABLE `PREDMETY` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PRIHLASIL`
--

DROP TABLE IF EXISTS `PRIHLASIL`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `PRIHLASIL` (
  `d_prihlaseni` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_uzivatel` bigint(20) NOT NULL,
  `id_adresa` bigint(20) NOT NULL,
  KEY `fk_PRIHLASIL_IP_ADRESY1_idx` (`id_adresa`),
  KEY `fk_PRIHLASIL_UZIVATELE` (`id_uzivatel`),
  CONSTRAINT `fk_PRIHLASIL_IP_ADRESY1` FOREIGN KEY (`id_adresa`) REFERENCES `IP_ADRESY` (`id_ip`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_PRIHLASIL_UZIVATELE` FOREIGN KEY (`id_uzivatel`) REFERENCES `UZIVATELE` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PRIHLASIL`
--

LOCK TABLES `PRIHLASIL` WRITE;
/*!40000 ALTER TABLE `PRIHLASIL` DISABLE KEYS */;
INSERT INTO `PRIHLASIL` VALUES ('2021-01-17 10:19:42',1,1),('2021-01-17 10:21:51',1,2),('2021-01-17 10:21:51',2,3),('2021-01-17 10:21:51',1,1),('2021-01-17 10:21:51',3,4),('2021-01-17 10:21:51',3,5),('2021-01-17 10:21:51',2,6),('2021-01-17 10:21:51',1,7),('2021-01-17 10:21:51',2,3),('2021-01-17 10:21:51',4,8),('2021-01-17 10:21:51',3,6),('2021-01-17 10:21:51',5,9),('2021-01-17 10:21:51',4,3),('2021-01-17 10:21:51',5,1),('2021-01-17 10:21:51',3,5),('2021-01-17 10:21:51',1,7),('2021-01-18 00:20:20',1,9),('2021-01-18 02:46:47',1,9);
/*!40000 ALTER TABLE `PRIHLASIL` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PRISPEVKY`
--

DROP TABLE IF EXISTS `PRISPEVKY`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `PRISPEVKY` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `zprava` text COLLATE utf8_czech_ci NOT NULL,
  `d_vytvoreni` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_autor` bigint(20) NOT NULL,
  `id_vlakno` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_PRISPEVKY_HRACI1_idx` (`id_autor`),
  KEY `fk_PRISPEVKY_VLAKNA_FORA1_idx` (`id_vlakno`),
  CONSTRAINT `fk_PRISPEVKY_HRACI1` FOREIGN KEY (`id_autor`) REFERENCES `HRACI` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_PRISPEVKY_VLAKNA_FORA1` FOREIGN KEY (`id_vlakno`) REFERENCES `VLAKNA_FORA` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PRISPEVKY`
--

LOCK TABLES `PRISPEVKY` WRITE;
/*!40000 ALTER TABLE `PRISPEVKY` DISABLE KEYS */;
INSERT INTO `PRISPEVKY` VALUES (1,'Jak jsou na tom naše vojska?','2021-01-17 16:24:25',2,3),(2,'No celkem nic moc. Jenom pár jednotek.','2021-01-17 17:03:13',5,3),(3,'Aha... no nevadí... verbuj!','2021-01-17 17:03:13',2,3),(4,'Zdravím všechny, potřeboval bych podporu. Útočí na mě','2021-01-17 17:03:13',3,1),(5,'Už posílám své podpory. Měly by dorazit večer.','2021-01-17 17:03:13',1,1),(6,'Děkuji moc. Vážím si toho.','2021-01-17 17:03:13',3,1),(7,'Auto','2021-01-17 17:03:13',5,5),(8,'Obelisk','2021-01-17 17:03:13',2,5),(9,'Kafe','2021-01-17 17:03:13',5,5),(10,'Energie','2021-01-17 17:03:13',2,5),(11,'Efektivita','2021-01-17 17:03:13',5,5),(12,'Útok dle domluvy provedeme zítra večer.','2021-01-17 17:10:37',1,2);
/*!40000 ALTER TABLE `PRISPEVKY` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `UZIVATELE`
--

DROP TABLE IF EXISTS `UZIVATELE`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `UZIVATELE` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `login` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `jmeno` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `prijmeni` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  `heslo` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  `d_registrace` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `UZIVATELE`
--

LOCK TABLES `UZIVATELE` WRITE;
/*!40000 ALTER TABLE `UZIVATELE` DISABLE KEYS */;
INSERT INTO `UZIVATELE` VALUES (1,'hintik','Jan','Hinterholzinger','hintik@hintik.cz','heslo123','2021-01-17 01:32:31'),(2,'peter','Petr','Mánička','peter@example.com','heslo123','2021-01-17 01:33:11'),(3,'alzbeta','Alžběta','Nováková','alzbeta@example.com','heslo123','2021-01-17 01:33:39'),(4,'krojan','Jan','Křovinořez','krojan@example.com','heslo123','2021-01-17 01:35:00'),(5,'janka','Jana','Štědrá','janka@example.com','heslo123','2021-01-17 01:35:35');
/*!40000 ALTER TABLE `UZIVATELE` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `VLAKNA_FORA`
--

DROP TABLE IF EXISTS `VLAKNA_FORA`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `VLAKNA_FORA` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `nazev` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `id_forum` bigint(20) NOT NULL,
  `id_autor` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_VLAKNA_FORA_CECHOVNI_FORA1_idx` (`id_forum`),
  KEY `fk_VLAKNA_FORA_HRACI1_idx` (`id_autor`),
  CONSTRAINT `fk_VLAKNA_FORA_CECHOVNI_FORA1` FOREIGN KEY (`id_forum`) REFERENCES `ALIENCNI_FORA` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_VLAKNA_FORA_HRACI1` FOREIGN KEY (`id_autor`) REFERENCES `HRACI` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `VLAKNA_FORA`
--

LOCK TABLES `VLAKNA_FORA` WRITE;
/*!40000 ALTER TABLE `VLAKNA_FORA` DISABLE KEYS */;
INSERT INTO `VLAKNA_FORA` VALUES (1,'Pomozte mi prosim',2,3),(2,'Útok na barbarskou vesnici',1,1),(3,'První krok k ovládnutí lidstva',4,2),(4,'Vyprávění o vládnutí',5,2),(5,'Slovní forbal',5,5);
/*!40000 ALTER TABLE `VLAKNA_FORA` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `VLASTNI`
--

DROP TABLE IF EXISTS `VLASTNI`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `VLASTNI` (
  `pocet` smallint(6) NOT NULL DEFAULT 1,
  `id_hrace` bigint(20) NOT NULL,
  `id_predmetu` bigint(20) NOT NULL,
  PRIMARY KEY (`id_hrace`,`id_predmetu`),
  KEY `fk_VLASTNI_PREDMETY1_idx` (`id_predmetu`),
  CONSTRAINT `fk_VLASTNI_HRACI1` FOREIGN KEY (`id_hrace`) REFERENCES `HRACI` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_VLASTNI_PREDMETY1` FOREIGN KEY (`id_predmetu`) REFERENCES `PREDMETY` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `VLASTNI`
--

LOCK TABLES `VLASTNI` WRITE;
/*!40000 ALTER TABLE `VLASTNI` DISABLE KEYS */;
INSERT INTO `VLASTNI` VALUES (3,1,1),(2,1,3),(1,1,5),(1,2,1),(1,2,4),(2,3,1),(1,3,2),(1,4,3),(3,4,4),(1,5,3);
/*!40000 ALTER TABLE `VLASTNI` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-01-18  4:00:49
