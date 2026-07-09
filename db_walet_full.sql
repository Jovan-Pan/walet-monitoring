/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19-11.8.6-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: db_walet
-- ------------------------------------------------------
-- Server version	11.8.6-MariaDB-0+deb13u1 from Debian

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;

--
-- Current Database: `db_walet`
--

/*!40000 DROP DATABASE IF EXISTS `db_walet`*/;

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `db_walet` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;

USE `db_walet`;

--
-- Table structure for table `audio_walet`
--

DROP TABLE IF EXISTS `audio_walet`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `audio_walet` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `rumah_walet_id` int(11) unsigned NOT NULL,
  `tanggal` date NOT NULL,
  `jenis_suara` enum('panggilan_dewasa','panggilan_piyik','suara_sarang','kombinasi') NOT NULL DEFAULT 'panggilan_dewasa',
  `jam_nyala` time NOT NULL DEFAULT '05:00:00',
  `jam_mati` time NOT NULL DEFAULT '19:00:00',
  `volume` int(11) DEFAULT 70 COMMENT 'Volume (0-100)',
  `kondisi_speaker` enum('baik','rusak_sebagian','rusak_total') DEFAULT 'baik',
  `jumlah_speaker_aktif` int(11) DEFAULT 0,
  `kondisi_amplifier` enum('baik','rusak') DEFAULT 'baik',
  `catatan` text DEFAULT NULL,
  `input_by` int(11) unsigned DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `rumah_walet_id` (`rumah_walet_id`),
  KEY `idx_rumah_tanggal` (`rumah_walet_id`,`tanggal`),
  KEY `idx_deleted` (`deleted_at`),
  KEY `fk_audio_input_by` (`input_by`),
  CONSTRAINT `fk_audio_input_by` FOREIGN KEY (`input_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_audio_rumah` FOREIGN KEY (`rumah_walet_id`) REFERENCES `rumah_walet` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `audio_walet`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `audio_walet` WRITE;
/*!40000 ALTER TABLE `audio_walet` DISABLE KEYS */;
INSERT INTO `audio_walet` VALUES
(1,1,'2024-01-15','panggilan_dewasa','05:00:00','19:00:00',75,'baik',24,'baik','Audio normal, populasi stabil',2,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(2,2,'2024-01-20','panggilan_dewasa','05:00:00','19:00:00',70,'baik',16,'baik','Audio normal',2,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(3,3,'2024-02-10','kombinasi','05:00:00','19:00:00',72,'baik',20,'baik','Kombinasi dewasa + sarang',2,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(4,4,'2024-02-15','panggilan_dewasa','05:30:00','19:00:00',65,'rusak_sebagian',10,'baik','2 speaker lantai 2 rusak',2,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(5,5,'2024-03-01','panggilan_dewasa','05:00:00','19:00:00',70,'baik',18,'baik','Audio normal',2,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(6,1,'2024-04-15','panggilan_dewasa','05:00:00','19:00:00',75,'baik',24,'baik','Musim urat, audio stabil',2,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(7,3,'2024-05-10','kombinasi','05:00:00','19:00:00',72,'baik',20,'baik','Tetap kombinasi, populasi naik',2,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(8,5,'2024-06-20','panggilan_dewasa','05:00:00','19:00:00',70,'baik',18,'baik','Normal',2,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(9,1,'2024-07-10','kombinasi','05:00:00','19:00:00',78,'baik',24,'baik','Tambah volume untuk musim panen utuh',2,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(10,2,'2024-07-15','panggilan_dewasa','05:00:00','19:00:00',72,'baik',16,'baik','Normal',2,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(11,3,'2024-08-05','kombinasi','05:00:00','19:00:00',75,'baik',20,'baik','Volume naik untuk peak season',2,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(12,4,'2024-08-20','panggilan_dewasa','05:30:00','19:00:00',68,'baik',12,'baik','Speaker sudah diganti semua',2,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(13,5,'2024-09-05','panggilan_dewasa','05:00:00','19:00:00',72,'baik',18,'baik','Normal, populasi meningkat',2,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(14,1,'2024-10-15','panggilan_dewasa','05:00:00','19:00:00',75,'baik',24,'baik','Normal',2,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(15,3,'2024-11-10','panggilan_piyik','05:00:00','19:00:00',70,'baik',20,'baik','Coba ganti ke panggilan piyik untuk tarik piyik baru',2,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(16,5,'2024-12-05','panggilan_dewasa','05:00:00','19:00:00',70,'baik',18,'baik','Normal',2,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(17,1,'2025-01-15','panggilan_dewasa','05:00:00','19:00:00',75,'baik',24,'baik','Persiapan musim urat',2,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(18,2,'2025-02-10','kombinasi','05:00:00','19:00:00',72,'baik',16,'baik','Tambah suara sarang untuk tarik sarang baru',2,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(19,3,'2025-03-05','panggilan_piyik','05:00:00','19:00:00',70,'baik',20,'baik','Piyik call terbukti efektif 2024',2,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(20,4,'2025-03-20','panggilan_dewasa','05:30:00','19:00:00',68,'baik',12,'baik','Normal',2,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(21,5,'2025-04-10','panggilan_dewasa','05:00:00','19:00:00',70,'baik',18,'baik','Normal',2,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(22,1,'2025-05-10','panggilan_dewasa','05:00:00','19:00:00',75,'baik',24,'baik','Post-panen urat, stabil',2,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(23,3,'2025-06-15','kombinasi','05:00:00','19:00:00',75,'baik',20,'baik','Persiapan musim utuh, kombinasi efektif',2,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(24,1,'2025-07-05','kombinasi','05:00:00','19:00:00',80,'baik',24,'baik','Volume max untuk peak season utuh',2,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(25,2,'2025-07-10','kombinasi','05:00:00','19:00:00',75,'baik',16,'baik','Switch ke kombinasi',2,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(26,3,'2025-08-05','kombinasi','05:00:00','19:00:00',78,'baik',20,'baik','Peak season, volume max',2,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(27,4,'2025-08-15','panggilan_dewasa','05:30:00','19:00:00',70,'baik',12,'rusak','Amplifier channel 1 rusak, perlu ganti',2,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(28,5,'2025-09-05','kombinasi','05:00:00','19:00:00',75,'baik',18,'baik','Kombinasi untuk peak season',2,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(29,1,'2025-10-15','panggilan_dewasa','05:00:00','19:00:00',75,'baik',24,'baik','Post-peak, normal',2,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(30,3,'2025-11-10','panggilan_piyik','05:00:00','19:00:00',70,'baik',20,'baik','Lanjut piyik call, populasi naik 30%',2,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(31,5,'2025-12-05','panggilan_dewasa','05:00:00','19:00:00',70,'baik',18,'baik','Normal',2,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL);
/*!40000 ALTER TABLE `audio_walet` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `harga_grade`
--

DROP TABLE IF EXISTS `harga_grade`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `harga_grade` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `grade` enum('A','B','C') NOT NULL,
  `jenis_panen` enum('urat','sarang_utuh','kecil') NOT NULL DEFAULT 'sarang_utuh',
  `periode` varchar(7) NOT NULL COMMENT 'YYYY-MM',
  `harga_min` decimal(12,2) NOT NULL,
  `harga_max` decimal(12,2) NOT NULL,
  `harga_default` decimal(12,2) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_grade_jenis_periode` (`grade`,`jenis_panen`,`periode`),
  KEY `idx_periode` (`periode`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `harga_grade`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `harga_grade` WRITE;
/*!40000 ALTER TABLE `harga_grade` DISABLE KEYS */;
INSERT INTO `harga_grade` VALUES
(1,'A','urat','2026-07',18000000.00,25000000.00,22000000.00,'2026-07-04 17:22:26','2026-07-04 17:22:26'),
(2,'A','sarang_utuh','2026-07',13000000.00,18000000.00,15000000.00,'2026-07-04 17:22:26','2026-07-04 17:22:26'),
(3,'A','kecil','2026-07',10000000.00,13000000.00,12000000.00,'2026-07-04 17:22:26','2026-07-04 17:22:26'),
(4,'B','urat','2026-07',12000000.00,15000000.00,13000000.00,'2026-07-04 17:22:26','2026-07-04 17:22:26'),
(5,'B','sarang_utuh','2026-07',8000000.00,12000000.00,10000000.00,'2026-07-04 17:22:26','2026-07-04 17:22:26'),
(6,'B','kecil','2026-07',5000000.00,8000000.00,6000000.00,'2026-07-04 17:22:26','2026-07-04 17:22:26'),
(7,'C','urat','2026-07',6000000.00,8000000.00,7000000.00,'2026-07-04 17:22:26','2026-07-04 17:22:26'),
(8,'C','sarang_utuh','2026-07',4000000.00,6000000.00,5000000.00,'2026-07-04 17:22:26','2026-07-04 17:22:26'),
(9,'C','kecil','2026-07',2500000.00,4000000.00,3000000.00,'2026-07-04 17:22:26','2026-07-04 17:22:26'),
(10,'A','urat','2026-08',18000000.00,25000000.00,22000000.00,'2026-07-04 17:22:26','2026-07-04 17:22:26'),
(11,'A','sarang_utuh','2026-08',13000000.00,18000000.00,15000000.00,'2026-07-04 17:22:26','2026-07-04 17:22:26'),
(12,'A','kecil','2026-08',10000000.00,13000000.00,12000000.00,'2026-07-04 17:22:26','2026-07-04 17:22:26'),
(13,'B','urat','2026-08',12000000.00,15000000.00,13000000.00,'2026-07-04 17:22:26','2026-07-04 17:22:26'),
(14,'B','sarang_utuh','2026-08',8000000.00,12000000.00,10000000.00,'2026-07-04 17:22:26','2026-07-04 17:22:26'),
(15,'B','kecil','2026-08',5000000.00,8000000.00,6000000.00,'2026-07-04 17:22:26','2026-07-04 17:22:26'),
(16,'C','urat','2026-08',6000000.00,8000000.00,7000000.00,'2026-07-04 17:22:26','2026-07-04 17:22:26'),
(17,'C','sarang_utuh','2026-08',4000000.00,6000000.00,5000000.00,'2026-07-04 17:22:26','2026-07-04 17:22:26'),
(18,'C','kecil','2026-08',2500000.00,4000000.00,3000000.00,'2026-07-04 17:22:26','2026-07-04 17:22:26');
/*!40000 ALTER TABLE `harga_grade` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `hasil_panen`
--

DROP TABLE IF EXISTS `hasil_panen`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `hasil_panen` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `jadwal_panen_id` int(11) unsigned DEFAULT NULL,
  `rumah_walet_id` int(11) unsigned NOT NULL,
  `petugas_id` int(11) unsigned NOT NULL,
  `tanggal_panen` date NOT NULL,
  `periode` varchar(7) NOT NULL COMMENT 'YYYY-MM',
  `grade` enum('A','B','C') NOT NULL,
  `jenis_panen` enum('urat','sarang_utuh','kecil') NOT NULL DEFAULT 'sarang_utuh',
  `berat_kg` decimal(8,3) NOT NULL COMMENT 'Berat yang dicatat ke sistem (bisa basah atau kering, lihat status_pengeringan)',
  `berat_basah_kg` decimal(8,3) DEFAULT NULL COMMENT 'Berat sebelum dikeringkan (shrinkage 30-40%)',
  `berat_kering_kg` decimal(8,3) DEFAULT NULL COMMENT 'Berat setelah dikeringkan - ini yang dijual',
  `kadar_air_pct` decimal(5,2) DEFAULT NULL COMMENT 'Hasil lab (standar ≤ 15%)',
  `kadar_kotoran_pct` decimal(5,2) DEFAULT NULL COMMENT 'Hasil lab (standar ≤ 5%)',
  `no_batch` varchar(50) DEFAULT NULL COMMENT 'Nomor batch/lot untuk traceability',
  `harga_per_kg` decimal(12,2) NOT NULL,
  `total_nilai` decimal(15,2) GENERATED ALWAYS AS (`berat_kg` * `harga_per_kg`) STORED,
  `status_pengeringan` enum('basah','proses','kering') DEFAULT 'basah',
  `status_stok` enum('di_gudang_rw','di_gudang_pusat','terjual','hilang') DEFAULT 'di_gudang_rw',
  `pembeli_id` int(11) unsigned DEFAULT NULL COMMENT 'FK ke penjualan jika langsung dijual',
  `sertifikat_mutu` varchar(255) DEFAULT NULL,
  `kualitas` varchar(255) DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `rumah_walet_id` (`rumah_walet_id`),
  KEY `petugas_id` (`petugas_id`),
  KEY `jadwal_panen_id` (`jadwal_panen_id`),
  KEY `idx_tanggal_panen` (`tanggal_panen`),
  KEY `idx_periode_grade` (`periode`,`grade`),
  KEY `idx_jenis_panen` (`jenis_panen`),
  KEY `idx_status_stok` (`status_stok`),
  KEY `idx_deleted` (`deleted_at`),
  CONSTRAINT `fk_panen_jadwal` FOREIGN KEY (`jadwal_panen_id`) REFERENCES `jadwal_panen` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_panen_petugas` FOREIGN KEY (`petugas_id`) REFERENCES `petugas` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_panen_rumah` FOREIGN KEY (`rumah_walet_id`) REFERENCES `rumah_walet` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hasil_panen`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `hasil_panen` WRITE;
/*!40000 ALTER TABLE `hasil_panen` DISABLE KEYS */;
INSERT INTO `hasil_panen` VALUES
(1,1,1,1,'2024-04-25','2024-04','A','urat',4.200,6.000,4.200,12.50,3.20,'BATCH-2024-04-RW1-A',20000000.00,84000000.00,'kering','terjual',3,NULL,'Sarang urat premium, kualitas terbaik','Panen urat prima','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(2,1,1,1,'2024-04-25','2024-04','B','urat',3.800,5.400,3.800,13.00,4.50,'BATCH-2024-04-RW1-B',13000000.00,49400000.00,'kering','terjual',3,NULL,'Sarang urat bagus','Panen urat','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(3,1,1,1,'2024-04-25','2024-04','C','urat',2.500,3.600,2.500,13.50,5.80,'BATCH-2024-04-RW1-C',7000000.00,17500000.00,'kering','terjual',4,NULL,'Pecahan kecil','Panen urat','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(4,2,2,1,'2024-04-28','2024-04','A','urat',2.800,4.000,2.800,12.80,3.50,'BATCH-2024-04-RW2-A',20000000.00,56000000.00,'kering','terjual',NULL,NULL,'Urat bagus','Panen urat','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(5,2,2,1,'2024-04-28','2024-04','B','urat',2.500,3.600,2.500,13.20,4.80,'BATCH-2024-04-RW2-B',13000000.00,32500000.00,'kering','terjual',3,NULL,'Urat bagus','Panen urat','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(6,2,2,1,'2024-04-28','2024-04','C','urat',1.800,2.600,1.800,14.00,6.00,'BATCH-2024-04-RW2-C',7000000.00,12600000.00,'kering','terjual',4,NULL,'Pecahan','Panen urat','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(7,3,3,2,'2024-04-30','2024-04','A','urat',3.500,5.000,3.500,12.60,3.00,'BATCH-2024-04-RW3-A',20000000.00,70000000.00,'kering','terjual',2,NULL,'Urat premium','Panen urat prima','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(8,3,3,2,'2024-04-30','2024-04','B','urat',3.200,4.600,3.200,13.00,4.20,'BATCH-2024-04-RW3-B',13000000.00,41600000.00,'kering','terjual',NULL,NULL,'Urat bagus','Panen urat','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(9,3,3,2,'2024-04-30','2024-04','C','urat',2.000,2.900,2.000,13.80,5.50,'BATCH-2024-04-RW3-C',7000000.00,14000000.00,'kering','terjual',4,NULL,'Pecahan','Panen urat','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(10,4,5,3,'2024-04-25','2024-04','A','urat',3.000,4.300,3.000,12.70,3.30,'BATCH-2024-04-RW5-A',20000000.00,60000000.00,'kering','terjual',NULL,NULL,'Urat bagus','Panen urat','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(11,4,5,3,'2024-04-25','2024-04','B','urat',2.700,3.900,2.700,13.10,4.60,'BATCH-2024-04-RW5-B',13000000.00,35100000.00,'kering','terjual',NULL,NULL,'Urat bagus','Panen urat','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(12,4,5,3,'2024-04-25','2024-04','C','urat',1.900,2.700,1.900,13.70,5.70,'BATCH-2024-04-RW5-C',7000000.00,13300000.00,'kering','terjual',NULL,NULL,'Pecahan','Panen urat','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(13,5,1,1,'2024-08-25','2024-08','A','sarang_utuh',6.500,9.300,6.500,12.30,2.80,'BATCH-2024-08-RW1-A',15000000.00,97500000.00,'kering','terjual',12,NULL,'Utuh premium peak season','Panen utuh prima','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(14,5,1,1,'2024-08-25','2024-08','B','sarang_utuh',5.800,8.300,5.800,12.80,4.00,'BATCH-2024-08-RW1-B',10000000.00,58000000.00,'kering','terjual',NULL,NULL,'Utuh bagus','Panen utuh','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(15,5,1,1,'2024-08-25','2024-08','C','sarang_utuh',3.800,5.400,3.800,13.50,5.50,'BATCH-2024-08-RW1-C',5000000.00,19000000.00,'kering','terjual',NULL,NULL,'Pecahan utuh','Panen utuh','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(16,6,2,1,'2024-08-28','2024-08','A','sarang_utuh',4.200,6.000,4.200,12.50,3.00,'BATCH-2024-08-RW2-A',15000000.00,63000000.00,'kering','terjual',6,NULL,'Utuh bagus','Panen utuh','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(17,6,2,1,'2024-08-28','2024-08','B','sarang_utuh',3.800,5.400,3.800,13.00,4.30,'BATCH-2024-08-RW2-B',10000000.00,38000000.00,'kering','terjual',6,NULL,'Utuh bagus','Panen utuh','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(18,6,2,1,'2024-08-28','2024-08','C','sarang_utuh',2.500,3.600,2.500,13.70,5.80,'BATCH-2024-08-RW2-C',5000000.00,12500000.00,'kering','terjual',NULL,NULL,'Pecahan','Panen utuh','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(19,7,3,2,'2024-08-30','2024-08','A','sarang_utuh',5.200,7.400,5.200,12.40,2.90,'BATCH-2024-08-RW3-A',15000000.00,78000000.00,'kering','terjual',12,NULL,'Utuh premium','Panen utuh prima','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(20,7,3,2,'2024-08-30','2024-08','B','sarang_utuh',4.800,6.900,4.800,12.90,4.10,'BATCH-2024-08-RW3-B',10000000.00,48000000.00,'kering','terjual',7,NULL,'Utuh bagus','Panen utuh','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(21,7,3,2,'2024-08-30','2024-08','C','sarang_utuh',3.200,4.600,3.200,13.60,5.60,'BATCH-2024-08-RW3-C',5000000.00,16000000.00,'kering','terjual',NULL,NULL,'Pecahan','Panen utuh','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(22,8,4,3,'2024-09-05','2024-09','A','sarang_utuh',3.000,4.300,3.000,12.80,3.50,'BATCH-2024-09-RW4-A',15000000.00,45000000.00,'kering','terjual',6,NULL,'Utuh bagus','Panen utuh','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(23,8,4,3,'2024-09-05','2024-09','B','sarang_utuh',2.700,3.900,2.700,13.20,4.50,'BATCH-2024-09-RW4-B',10000000.00,27000000.00,'kering','terjual',6,NULL,'Utuh bagus','Panen utuh','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(24,8,4,3,'2024-09-05','2024-09','C','sarang_utuh',1.800,2.600,1.800,13.90,6.00,'BATCH-2024-09-RW4-C',5000000.00,9000000.00,'kering','terjual',NULL,NULL,'Pecahan','Panen utuh','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(25,9,5,3,'2024-09-10','2024-09','A','sarang_utuh',3.800,5.400,3.800,12.60,3.20,'BATCH-2024-09-RW5-A',15000000.00,57000000.00,'kering','terjual',NULL,NULL,'Utuh bagus','Panen utuh','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(26,9,5,3,'2024-09-10','2024-09','B','sarang_utuh',3.500,5.000,3.500,13.00,4.40,'BATCH-2024-09-RW5-B',10000000.00,35000000.00,'kering','terjual',8,NULL,'Utuh bagus','Panen utuh','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(27,9,5,3,'2024-09-10','2024-09','C','sarang_utuh',2.300,3.300,2.300,13.70,5.70,'BATCH-2024-09-RW5-C',5000000.00,11500000.00,'kering','terjual',8,NULL,'Pecahan','Panen utuh','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(28,10,1,1,'2024-12-15','2024-12','A','kecil',2.200,3.100,2.200,12.90,3.80,'BATCH-2024-12-RW1-A',12000000.00,26400000.00,'kering','terjual',9,NULL,'Kecil premium','Panen kecil','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(29,10,1,1,'2024-12-15','2024-12','B','kecil',1.800,2.600,1.800,13.30,4.80,'BATCH-2024-12-RW1-B',6000000.00,10800000.00,'kering','terjual',9,NULL,'Kecil bagus','Panen kecil','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(30,10,1,1,'2024-12-15','2024-12','C','kecil',1.200,1.700,1.200,14.00,6.20,'BATCH-2024-12-RW1-C',3000000.00,3600000.00,'kering','terjual',NULL,NULL,'Pecahan kecil','Panen kecil','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(31,11,5,3,'2024-12-20','2024-12','A','kecil',1.600,2.300,1.600,13.00,3.90,'BATCH-2024-12-RW5-A',12000000.00,19200000.00,'kering','terjual',NULL,NULL,'Kecil bagus','Panen kecil','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(32,11,5,3,'2024-12-20','2024-12','B','kecil',1.400,2.000,1.400,13.40,4.90,'BATCH-2024-12-RW5-B',6000000.00,8400000.00,'kering','terjual',NULL,NULL,'Kecil bagus','Panen kecil','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(33,11,5,3,'2024-12-20','2024-12','C','kecil',0.900,1.300,0.900,14.10,6.30,'BATCH-2024-12-RW5-C',3000000.00,2700000.00,'kering','terjual',NULL,NULL,'Pecahan','Panen kecil','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(34,12,1,1,'2025-04-25','2025-04','A','urat',4.500,6.400,4.500,12.40,3.00,'BATCH-2025-04-RW1-A',22000000.00,99000000.00,'kering','terjual',11,NULL,'Urat premium, harga naik','Panen urat prima','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(35,12,1,1,'2025-04-25','2025-04','B','urat',4.000,5.700,4.000,12.90,4.30,'BATCH-2025-04-RW1-B',14000000.00,56000000.00,'kering','terjual',11,NULL,'Urat bagus','Panen urat','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(36,12,1,1,'2025-04-25','2025-04','C','urat',2.600,3.700,2.600,13.60,5.60,'BATCH-2025-04-RW1-C',7500000.00,19500000.00,'kering','terjual',NULL,NULL,'Pecahan','Panen urat','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(37,13,2,1,'2025-04-28','2025-04','A','urat',3.000,4.300,3.000,12.70,3.40,'BATCH-2025-04-RW2-A',22000000.00,66000000.00,'kering','terjual',NULL,NULL,'Urat bagus','Panen urat','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(38,13,2,1,'2025-04-28','2025-04','B','urat',2.700,3.900,2.700,13.10,4.70,'BATCH-2025-04-RW2-B',14000000.00,37800000.00,'kering','terjual',NULL,NULL,'Urat bagus','Panen urat','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(39,13,2,1,'2025-04-28','2025-04','C','urat',1.900,2.700,1.900,13.80,5.90,'BATCH-2025-04-RW2-C',7500000.00,14250000.00,'kering','terjual',NULL,NULL,'Pecahan','Panen urat','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(40,14,3,2,'2025-04-30','2025-04','A','urat',3.800,5.400,3.800,12.50,3.10,'BATCH-2025-04-RW3-A',22000000.00,83600000.00,'kering','terjual',11,NULL,'Urat premium, populasi naik 30%','Panen urat prima','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(41,14,3,2,'2025-04-30','2025-04','B','urat',3.400,4.900,3.400,12.90,4.20,'BATCH-2025-04-RW3-B',14000000.00,47600000.00,'kering','terjual',11,NULL,'Urat bagus','Panen urat','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(42,14,3,2,'2025-04-30','2025-04','C','urat',2.200,3.100,2.200,13.60,5.40,'BATCH-2025-04-RW3-C',7500000.00,16500000.00,'kering','terjual',NULL,NULL,'Pecahan','Panen urat','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(43,15,5,3,'2025-04-25','2025-04','A','urat',3.200,4.600,3.200,12.70,3.30,'BATCH-2025-04-RW5-A',22000000.00,70400000.00,'kering','terjual',10,NULL,'Urat bagus','Panen urat','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(44,15,5,3,'2025-04-25','2025-04','B','urat',2.900,4.100,2.900,13.10,4.60,'BATCH-2025-04-RW5-B',14000000.00,40600000.00,'kering','terjual',NULL,NULL,'Urat bagus','Panen urat','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(45,15,5,3,'2025-04-25','2025-04','C','urat',2.000,2.900,2.000,13.70,5.80,'BATCH-2025-04-RW5-C',7500000.00,15000000.00,'kering','terjual',NULL,NULL,'Pecahan','Panen urat','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(46,16,1,1,'2025-08-25','2025-08','A','sarang_utuh',7.000,10.000,7.000,12.20,2.70,'BATCH-2025-08-RW1-A',16000000.00,112000000.00,'kering','terjual',13,NULL,'Utuh premium, populasi terbaik','Panen utuh prima - stok available','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(47,16,1,1,'2025-08-25','2025-08','B','sarang_utuh',6.200,8.900,6.200,12.70,3.90,'BATCH-2025-08-RW1-B',11000000.00,68200000.00,'kering','di_gudang_rw',NULL,NULL,'Utuh bagus','Stok available','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(48,16,1,1,'2025-08-25','2025-08','C','sarang_utuh',4.000,5.700,4.000,13.40,5.30,'BATCH-2025-08-RW1-C',5500000.00,22000000.00,'kering','di_gudang_rw',NULL,NULL,'Pecahan utuh','Stok available','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(49,17,2,1,'2025-08-28','2025-08','A','sarang_utuh',4.500,6.400,4.500,12.40,2.90,'BATCH-2025-08-RW2-A',16000000.00,72000000.00,'kering','terjual',14,NULL,'Utuh premium, populasi naik','Panen utuh prima','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(50,17,2,1,'2025-08-28','2025-08','B','sarang_utuh',4.000,5.700,4.000,12.80,4.10,'BATCH-2025-08-RW2-B',11000000.00,44000000.00,'kering','terjual',14,NULL,'Utuh bagus','Panen utuh','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(51,17,2,1,'2025-08-28','2025-08','C','sarang_utuh',2.700,3.900,2.700,13.60,5.70,'BATCH-2025-08-RW2-C',5500000.00,14850000.00,'kering','terjual',NULL,NULL,'Pecahan','Panen utuh','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(52,18,3,2,'2025-08-30','2025-08','A','sarang_utuh',5.500,7.900,5.500,12.30,2.80,'BATCH-2025-08-RW3-A',16000000.00,88000000.00,'kering','terjual',15,NULL,'Utuh premium','Panen utuh prima','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(53,18,3,2,'2025-08-30','2025-08','B','sarang_utuh',5.100,7.300,5.100,12.80,4.00,'BATCH-2025-08-RW3-B',11000000.00,56100000.00,'kering','terjual',NULL,NULL,'Utuh bagus','Panen utuh','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(54,18,3,2,'2025-08-30','2025-08','C','sarang_utuh',3.400,4.900,3.400,13.50,5.50,'BATCH-2025-08-RW3-C',5500000.00,18700000.00,'kering','terjual',NULL,NULL,'Pecahan','Panen utuh','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(55,19,4,3,'2025-09-05','2025-09','A','sarang_utuh',3.200,4.600,3.200,12.90,3.60,'BATCH-2025-09-RW4-A',16000000.00,51200000.00,'kering','di_gudang_rw',NULL,NULL,'Utuh bagus, audio bermasalah saat panen','Stok available','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(56,19,4,3,'2025-09-05','2025-09','B','sarang_utuh',2.900,4.100,2.900,13.30,4.70,'BATCH-2025-09-RW4-B',11000000.00,31900000.00,'kering','di_gudang_rw',NULL,NULL,'Utuh bagus','Stok available','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(57,19,4,3,'2025-09-05','2025-09','C','sarang_utuh',1.900,2.700,1.900,14.00,6.10,'BATCH-2025-09-RW4-C',5500000.00,10450000.00,'kering','di_gudang_rw',NULL,NULL,'Pecahan','Stok available','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(58,20,5,3,'2025-09-10','2025-09','A','sarang_utuh',4.000,5.700,4.000,12.60,3.10,'BATCH-2025-09-RW5-A',16000000.00,64000000.00,'kering','terjual',15,NULL,'Utuh premium','Panen utuh prima','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(59,20,5,3,'2025-09-10','2025-09','B','sarang_utuh',3.700,5.300,3.700,13.00,4.30,'BATCH-2025-09-RW5-B',11000000.00,40700000.00,'kering','terjual',14,NULL,'Utuh bagus','Panen utuh','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(60,20,5,3,'2025-09-10','2025-09','C','sarang_utuh',2.500,3.600,2.500,13.70,5.80,'BATCH-2025-09-RW5-C',5500000.00,13750000.00,'kering','terjual',NULL,NULL,'Pecahan','Panen utuh','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(61,21,1,1,'2025-12-15','2025-12','A','kecil',2.400,3.400,2.400,12.80,3.70,'BATCH-2025-12-RW1-A',13000000.00,31200000.00,'kering','terjual',16,NULL,'Kecil premium','Stok available','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(62,21,1,1,'2025-12-15','2025-12','B','kecil',1.900,2.700,1.900,13.20,4.70,'BATCH-2025-12-RW1-B',7000000.00,13300000.00,'kering','terjual',17,NULL,'Kecil bagus','Stok available','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(63,21,1,1,'2025-12-15','2025-12','C','kecil',1.300,1.900,1.300,13.90,6.10,'BATCH-2025-12-RW1-C',3500000.00,4550000.00,'kering','di_gudang_rw',NULL,NULL,'Pecahan kecil','Stok available','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(64,22,5,3,'2025-12-20','2025-12','A','kecil',1.700,2.400,1.700,13.00,3.80,'BATCH-2025-12-RW5-A',13000000.00,22100000.00,'kering','terjual',16,NULL,'Kecil bagus','Stok available','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(65,22,5,3,'2025-12-20','2025-12','B','kecil',1.500,2.100,1.500,13.30,4.80,'BATCH-2025-12-RW5-B',7000000.00,10500000.00,'kering','terjual',17,NULL,'Kecil bagus','Stok available','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(66,22,5,3,'2025-12-20','2025-12','C','kecil',1.000,1.400,1.000,14.00,6.20,'BATCH-2025-12-RW5-C',3500000.00,3500000.00,'kering','terjual',17,NULL,'Pecahan','Stok available','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL);
/*!40000 ALTER TABLE `hasil_panen` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `inspeksi`
--

DROP TABLE IF EXISTS `inspeksi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `inspeksi` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `rumah_walet_id` int(11) unsigned NOT NULL,
  `petugas_id` int(11) unsigned NOT NULL,
  `tanggal_inspeksi` date NOT NULL,
  `kondisi_bangunan` enum('baik','sedang','buruk') NOT NULL,
  `kondisi_sarang` enum('baik','sedang','buruk') NOT NULL,
  `kebersihan` enum('baik','sedang','buruk') NOT NULL,
  `populasi_walet` int(11) DEFAULT 0 COMMENT 'Estimasi populasi (ekor)',
  `suhu` decimal(5,2) DEFAULT NULL COMMENT 'Suhu rata-rata (°C)',
  `kelembaban` decimal(5,2) DEFAULT NULL COMMENT 'Kelembaban rata-rata (%)',
  `fase_sarang` enum('kosong','pembentukan','bertelur','menetas','piyik','siap_panen') DEFAULT 'kosong' COMMENT 'Fase biologis sarang',
  `cahaya_lux` decimal(5,2) DEFAULT NULL COMMENT 'Pengukuran cahaya (max 0.5 lux)',
  `ketinggian_sarang_cm` decimal(6,2) DEFAULT NULL,
  `suhu_per_lantai` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Suhu per lantai (multi-lantai)' CHECK (json_valid(`suhu_per_lantai`)),
  `kelembaban_per_lantai` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`kelembaban_per_lantai`)),
  `humidifier_status` varchar(255) DEFAULT NULL COMMENT 'Catatan kondisi humidifier',
  `audio_player_status` varchar(255) DEFAULT NULL COMMENT 'Catatan jam nyala, jenis suara, kondisi speaker',
  `foto_inspeksi` varchar(255) DEFAULT NULL COMMENT 'Path upload foto (multi-pisah dengan ;)',
  `signature_petugas` varchar(255) DEFAULT NULL COMMENT 'Tanda tangan digital untuk audit trail',
  `catatan` text DEFAULT NULL,
  `status` enum('baik','sedang','buruk') NOT NULL DEFAULT 'baik' COMMENT 'Otomatis dari kondisi terburuk',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `rumah_walet_id` (`rumah_walet_id`),
  KEY `petugas_id` (`petugas_id`),
  KEY `idx_rumah_tanggal` (`rumah_walet_id`,`tanggal_inspeksi`),
  KEY `idx_tanggal` (`tanggal_inspeksi`),
  KEY `idx_deleted` (`deleted_at`),
  CONSTRAINT `fk_inspeksi_petugas` FOREIGN KEY (`petugas_id`) REFERENCES `petugas` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_inspeksi_rumah` FOREIGN KEY (`rumah_walet_id`) REFERENCES `rumah_walet` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inspeksi`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `inspeksi` WRITE;
/*!40000 ALTER TABLE `inspeksi` DISABLE KEYS */;
INSERT INTO `inspeksi` VALUES
(1,1,1,'2024-02-10','baik','baik','baik',850,28.50,85.00,'pembentukan',0.30,280.00,NULL,NULL,'Aktif 4 unit, normal','Audio normal 05:00-19:00, semua speaker aktif',NULL,NULL,'Kondisi prima, fase pembentukan sarang','baik','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(2,2,1,'2024-02-15','baik','sedang','baik',480,29.00,82.00,'pembentukan',0.45,260.00,NULL,NULL,'Aktif 2 unit','Speaker lantai 2 perlu cek',NULL,NULL,'Sarang sedang berkembang','sedang','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(3,3,2,'2024-02-20','baik','baik','baik',620,28.80,84.00,'pembentukan',0.35,270.00,NULL,NULL,'Aktif 3 unit','Audio kombinasi berjalan baik',NULL,NULL,'Kondisi baik','baik','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(4,4,3,'2024-02-25','sedang','sedang','sedang',380,30.00,80.00,'pembentukan',0.50,240.00,NULL,NULL,'Aktif 1 unit, perlu tambah','2 speaker rusak, audio masih jalan',NULL,NULL,'Perlu renovasi ventilasi','sedang','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(5,5,3,'2024-03-05','baik','baik','baik',580,28.70,83.00,'bertelur',0.40,275.00,NULL,NULL,'Aktif 2 unit','Audio normal',NULL,NULL,'Fase bertelur, jangan ganggu','baik','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(6,1,1,'2024-04-10','baik','baik','baik',920,28.40,86.00,'siap_panen',0.28,285.00,NULL,NULL,'Aktif 4 unit','Audio stabil, populasi naik',NULL,NULL,'Siap panen urat akhir April','baik','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(7,2,1,'2024-04-15','baik','baik','baik',540,28.90,83.00,'siap_panen',0.42,265.00,NULL,NULL,'Aktif 2 unit','Audio normal',NULL,NULL,'Siap panen urat','baik','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(8,3,2,'2024-04-20','baik','baik','baik',680,28.70,84.50,'siap_panen',0.32,272.00,NULL,NULL,'Aktif 3 unit','Audio kombinasi efektif',NULL,NULL,'Siap panen, populasi meningkat','baik','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(9,1,1,'2024-06-10','baik','baik','baik',880,28.60,85.50,'kosong',0.30,280.00,NULL,NULL,'Aktif 4 unit','Audio normal',NULL,NULL,'Post-panen urat, fase istirahat','baik','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(10,2,1,'2024-06-15','baik','sedang','baik',500,29.10,82.00,'kosong',0.45,260.00,NULL,NULL,'Aktif 2 unit','Audio normal',NULL,NULL,'Post-panen, perlu monitoring','sedang','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(11,3,2,'2024-06-20','baik','baik','baik',650,28.80,84.00,'pembentukan',0.35,270.00,NULL,NULL,'Aktif 3 unit','Audio kombinasi',NULL,NULL,'Mulai pembentukan untuk musim utuh','baik','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(12,1,1,'2024-08-10','baik','baik','baik',950,28.30,86.50,'siap_panen',0.25,285.00,NULL,NULL,'Aktif 4 unit','Volume 78%, persiapan panen utuh',NULL,NULL,'Siap panen utuh peak season','baik','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(13,3,2,'2024-08-15','baik','baik','baik',720,28.60,84.50,'siap_panen',0.30,272.00,NULL,NULL,'Aktif 3 unit','Volume 75%, peak season',NULL,NULL,'Siap panen utuh','baik','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(14,4,3,'2024-08-20','sedang','sedang','baik',420,29.80,81.00,'siap_panen',0.50,245.00,NULL,NULL,'Aktif 1 unit','Speaker sudah diganti, audio normal',NULL,NULL,'Siap panen, kondisi improving','sedang','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(15,5,3,'2024-09-05','baik','baik','baik',620,28.70,83.50,'siap_panen',0.40,275.00,NULL,NULL,'Aktif 2 unit','Audio normal, populasi naik',NULL,NULL,'Siap panen utuh','baik','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(16,1,1,'2024-10-15','baik','baik','baik',870,28.50,85.00,'kosong',0.30,280.00,NULL,NULL,'Aktif 4 unit','Audio normal',NULL,NULL,'Post-panen utuh, istirahat','baik','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(17,3,2,'2024-11-10','baik','sedang','baik',680,28.90,84.00,'pembentukan',0.35,270.00,NULL,NULL,'Aktif 3 unit','Switch ke panggilan piyik',NULL,NULL,'Coba piyik call untuk musim kecil','sedang','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(18,1,1,'2024-12-10','baik','baik','baik',900,28.40,85.50,'siap_panen',0.28,282.00,NULL,NULL,'Aktif 4 unit','Audio normal',NULL,NULL,'Siap panen kecil','baik','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(19,5,3,'2024-12-15','baik','baik','baik',600,28.60,83.00,'siap_panen',0.40,275.00,NULL,NULL,'Aktif 2 unit','Audio normal',NULL,NULL,'Siap panen kecil','baik','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(20,1,1,'2025-02-10','baik','baik','baik',880,28.50,85.00,'pembentukan',0.30,280.00,NULL,NULL,'Aktif 4 unit','Persiapan musim urat',NULL,NULL,'Kondisi prima','baik','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(21,2,1,'2025-02-15','baik','baik','baik',560,28.90,82.50,'pembentukan',0.42,262.00,NULL,NULL,'Aktif 2 unit','Switch ke kombinasi',NULL,NULL,'Populasi naik, audio upgrade','baik','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(22,3,2,'2025-02-20','baik','baik','baik',700,28.70,84.00,'pembentukan',0.32,270.00,NULL,NULL,'Aktif 3 unit','Piyik call dari 2024 dipertahankan',NULL,NULL,'Populasi naik 30% setelah piyik call','baik','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(23,4,3,'2025-02-25','sedang','sedang','baik',450,29.50,81.50,'pembentukan',0.48,245.00,NULL,NULL,'Aktif 1 unit','Audio normal setelah ganti speaker',NULL,NULL,'Kondisi improving','sedang','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(24,1,1,'2025-04-10','baik','baik','baik',940,28.40,86.00,'siap_panen',0.25,285.00,NULL,NULL,'Aktif 4 unit','Audio stabil',NULL,NULL,'Siap panen urat','baik','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(25,3,2,'2025-04-15','baik','baik','baik',750,28.60,84.50,'siap_panen',0.30,272.00,NULL,NULL,'Aktif 3 unit','Piyik call terbukti efektif',NULL,NULL,'Siap panen urat, populasi terbaik','baik','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(26,5,3,'2025-04-20','baik','baik','baik',650,28.60,83.00,'siap_panen',0.40,275.00,NULL,NULL,'Aktif 2 unit','Audio normal',NULL,NULL,'Siap panen urat','baik','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(27,1,1,'2025-06-15','baik','baik','baik',910,28.50,85.50,'kosong',0.30,280.00,NULL,NULL,'Aktif 4 unit','Audio normal',NULL,NULL,'Post-panen urat','baik','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(28,3,2,'2025-06-20','baik','baik','baik',730,28.70,84.00,'pembentukan',0.32,270.00,NULL,NULL,'Aktif 3 unit','Switch ke kombinasi',NULL,NULL,'Persiapan musim utuh','baik','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(29,1,1,'2025-08-10','baik','baik','baik',980,28.30,86.50,'siap_panen',0.25,285.00,NULL,NULL,'Aktif 4 unit','Volume 80%, peak season utuh',NULL,NULL,'Siap panen utuh, populasi terbaik','baik','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(30,2,1,'2025-08-15','baik','baik','baik',620,28.80,83.00,'siap_panen',0.40,265.00,NULL,NULL,'Aktif 2 unit','Switch ke kombinasi',NULL,NULL,'Siap panen utuh, populasi naik','baik','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(31,3,2,'2025-08-20','baik','baik','baik',800,28.60,84.50,'siap_panen',0.28,272.00,NULL,NULL,'Aktif 3 unit','Volume 78%, peak season',NULL,NULL,'Siap panen utuh','baik','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(32,4,3,'2025-08-25','sedang','sedang','baik',480,29.70,81.50,'siap_panen',0.45,248.00,NULL,NULL,'Aktif 1 unit','Amplifier channel 1 rusak',NULL,NULL,'Siap panen, audio bermasalah','sedang','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(33,5,3,'2025-09-05','baik','baik','baik',680,28.60,83.50,'siap_panen',0.38,275.00,NULL,NULL,'Aktif 2 unit','Kombinasi efektif',NULL,NULL,'Siap panen utuh','baik','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(34,1,1,'2025-10-15','baik','baik','baik',900,28.50,85.00,'kosong',0.30,280.00,NULL,NULL,'Aktif 4 unit','Audio normal',NULL,NULL,'Post-peak, istirahat','baik','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(35,3,2,'2025-11-10','baik','baik','baik',780,28.70,84.00,'pembentukan',0.32,270.00,NULL,NULL,'Aktif 3 unit','Piyik call lanjutan',NULL,NULL,'Populasi naik 30% YoY','baik','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(36,1,1,'2025-12-10','baik','baik','baik',920,28.40,85.50,'siap_panen',0.28,282.00,NULL,NULL,'Aktif 4 unit','Audio normal',NULL,NULL,'Siap panen kecil','baik','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(37,5,3,'2025-12-15','baik','baik','baik',640,28.60,83.00,'siap_panen',0.40,275.00,NULL,NULL,'Aktif 2 unit','Audio normal',NULL,NULL,'Siap panen kecil','baik','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL);
/*!40000 ALTER TABLE `inspeksi` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `jadwal_panen`
--

DROP TABLE IF EXISTS `jadwal_panen`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `jadwal_panen` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `rumah_walet_id` int(11) unsigned NOT NULL,
  `tanggal_rencana` date NOT NULL,
  `periode` varchar(7) NOT NULL COMMENT 'YYYY-MM',
  `estimasi_hasil_kg` decimal(8,2) DEFAULT NULL,
  `jenis_panen_rencana` enum('urat','sarang_utuh','kecil','campuran') DEFAULT 'campuran',
  `catatan` text DEFAULT NULL,
  `status` enum('terjadwal','selesai','ditunda','batal') NOT NULL DEFAULT 'terjadwal',
  `hasil_panen_id` int(11) unsigned DEFAULT NULL COMMENT 'Link ke hasil_panen setelah selesai',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `rumah_walet_id` (`rumah_walet_id`),
  KEY `idx_status_tanggal` (`status`,`tanggal_rencana`),
  KEY `idx_periode` (`periode`),
  KEY `idx_deleted` (`deleted_at`),
  CONSTRAINT `fk_jadwal_rumah` FOREIGN KEY (`rumah_walet_id`) REFERENCES `rumah_walet` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jadwal_panen`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `jadwal_panen` WRITE;
/*!40000 ALTER TABLE `jadwal_panen` DISABLE KEYS */;
INSERT INTO `jadwal_panen` VALUES
(1,1,'2024-04-25','2024-04',12.00,'urat','Panen urat RW-001','selesai',1,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(2,2,'2024-04-28','2024-04',8.00,'urat','Panen urat RW-002','selesai',4,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(3,3,'2024-04-30','2024-04',10.00,'urat','Panen urat RW-003','selesai',7,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(4,5,'2024-04-25','2024-04',8.50,'urat','Panen urat RW-005','selesai',10,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(5,1,'2024-08-25','2024-08',18.00,'sarang_utuh','Panen utuh peak RW-001','selesai',13,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(6,2,'2024-08-28','2024-08',12.00,'sarang_utuh','Panen utuh RW-002','selesai',16,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(7,3,'2024-08-30','2024-08',15.00,'sarang_utuh','Panen utuh peak RW-003','selesai',19,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(8,4,'2024-09-05','2024-09',8.50,'sarang_utuh','Panen utuh RW-004','selesai',22,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(9,5,'2024-09-10','2024-09',11.00,'sarang_utuh','Panen utuh RW-005','selesai',25,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(10,1,'2024-12-15','2024-12',6.00,'kecil','Panen kecil RW-001','selesai',28,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(11,5,'2024-12-20','2024-12',4.50,'kecil','Panen kecil RW-005','selesai',31,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(12,1,'2025-04-25','2025-04',12.50,'urat','Panen urat RW-001','selesai',34,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(13,2,'2025-04-28','2025-04',8.50,'urat','Panen urat RW-002','selesai',37,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(14,3,'2025-04-30','2025-04',10.50,'urat','Panen urat RW-003 (populasi naik 30%)','selesai',40,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(15,5,'2025-04-25','2025-04',8.50,'urat','Panen urat RW-005','selesai',43,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(16,1,'2025-08-25','2025-08',18.50,'sarang_utuh','Panen utuh peak RW-001','selesai',46,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(17,2,'2025-08-28','2025-08',12.50,'sarang_utuh','Panen utuh RW-002 (populasi naik)','selesai',49,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(18,3,'2025-08-30','2025-08',15.50,'sarang_utuh','Panen utuh peak RW-003','selesai',52,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(19,4,'2025-09-05','2025-09',9.00,'sarang_utuh','Panen utuh RW-004 (audio bermasalah)','selesai',55,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(20,5,'2025-09-10','2025-09',11.50,'sarang_utuh','Panen utuh RW-005','selesai',58,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(21,1,'2025-12-15','2025-12',6.00,'kecil','Panen kecil RW-001','selesai',61,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(22,5,'2025-12-20','2025-12',4.50,'kecil','Panen kecil RW-005','selesai',64,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(23,1,'2026-04-25','2026-04',13.00,'urat','Panen urat RW-001 (rencana)','terjadwal',NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(24,3,'2026-04-30','2026-04',11.00,'urat','Panen urat RW-003 (rencana)','terjadwal',NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(25,1,'2026-08-25','2026-08',19.00,'sarang_utuh','Panen utuh RW-001 (rencana)','terjadwal',NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(26,3,'2026-08-30','2026-08',16.00,'sarang_utuh','Panen utuh RW-003 (rencana)','terjadwal',NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL);
/*!40000 ALTER TABLE `jadwal_panen` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `login_attempts`
--

DROP TABLE IF EXISTS `login_attempts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `login_attempts` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(45) NOT NULL,
  `username` varchar(50) NOT NULL,
  `attempt_count` int(11) NOT NULL DEFAULT 1,
  `last_attempt` datetime NOT NULL DEFAULT current_timestamp(),
  `locked_until` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_ip_username` (`ip_address`,`username`),
  KEY `idx_locked_until` (`locked_until`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `login_attempts`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `login_attempts` WRITE;
/*!40000 ALTER TABLE `login_attempts` DISABLE KEYS */;
/*!40000 ALTER TABLE `login_attempts` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `pengeluaran`
--

DROP TABLE IF EXISTS `pengeluaran`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `pengeluaran` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tanggal` date NOT NULL,
  `rumah_walet_id` int(11) unsigned DEFAULT NULL COMMENT 'NULL = pengeluaran umum (gaji akan auto-alokasi)',
  `kategori` enum('maintenance','gaji','listrik','peralatan','stimulan_aroma','audio','sertifikasi','transportasi','pajak_retribusi','renovasi_besar','lainnya') NOT NULL,
  `keterangan` text NOT NULL,
  `jumlah` decimal(15,2) NOT NULL,
  `bukti` varchar(255) DEFAULT NULL,
  `input_by` int(11) unsigned DEFAULT NULL,
  `approval_status` enum('draft','pending','approved','rejected','auto_approved') NOT NULL DEFAULT 'auto_approved',
  `approved_by` int(11) unsigned DEFAULT NULL,
  `approval_date` datetime DEFAULT NULL,
  `approval_note` text DEFAULT NULL,
  `vendor_id` int(11) unsigned DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `rumah_walet_id` (`rumah_walet_id`),
  KEY `input_by` (`input_by`),
  KEY `idx_tanggal_kategori` (`tanggal`,`kategori`),
  KEY `idx_approval_status` (`approval_status`),
  KEY `idx_deleted` (`deleted_at`),
  KEY `fk_peng_approved_by` (`approved_by`),
  CONSTRAINT `fk_peng_approved_by` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_peng_input_by` FOREIGN KEY (`input_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_peng_rumah` FOREIGN KEY (`rumah_walet_id`) REFERENCES `rumah_walet` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=108 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pengeluaran`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `pengeluaran` WRITE;
/*!40000 ALTER TABLE `pengeluaran` DISABLE KEYS */;
INSERT INTO `pengeluaran` VALUES
(1,'2024-01-31',NULL,'gaji','Gaji petugas Januari 2024',12000000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(2,'2024-02-28',NULL,'gaji','Gaji petugas Februari 2024',12000000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(3,'2024-03-31',NULL,'gaji','Gaji petugas Maret 2024',12000000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(4,'2024-04-30',NULL,'gaji','Gaji petugas April 2024',12000000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(5,'2024-05-31',NULL,'gaji','Gaji petugas Mei 2024',12000000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(6,'2024-06-30',NULL,'gaji','Gaji petugas Juni 2024',12000000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(7,'2024-07-31',NULL,'gaji','Gaji petugas Juli 2024',12000000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(8,'2024-08-31',NULL,'gaji','Gaji petugas Agustus 2024',12000000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(9,'2024-09-30',NULL,'gaji','Gaji petugas September 2024',12000000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(10,'2024-10-31',NULL,'gaji','Gaji petugas Oktober 2024',12000000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(11,'2024-11-30',NULL,'gaji','Gaji petugas November 2024',12000000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(12,'2024-12-31',NULL,'gaji','Gaji petugas Desember 2024 + THR',18000000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(13,'2025-01-31',NULL,'gaji','Gaji petugas Januari 2025',12500000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(14,'2025-02-28',NULL,'gaji','Gaji petugas Februari 2025',12500000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(15,'2025-03-31',NULL,'gaji','Gaji petugas Maret 2025',12500000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(16,'2025-04-30',NULL,'gaji','Gaji petugas April 2025',12500000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(17,'2025-05-31',NULL,'gaji','Gaji petugas Mei 2025',12500000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(18,'2025-06-30',NULL,'gaji','Gaji petugas Juni 2025',12500000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(19,'2025-07-31',NULL,'gaji','Gaji petugas Juli 2025',12500000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(20,'2025-08-31',NULL,'gaji','Gaji petugas Agustus 2025',12500000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(21,'2025-09-30',NULL,'gaji','Gaji petugas September 2025',12500000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(22,'2025-10-31',NULL,'gaji','Gaji petugas Oktober 2025',12500000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(23,'2025-11-30',NULL,'gaji','Gaji petugas November 2025',12500000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(24,'2025-12-31',NULL,'gaji','Gaji petugas Desember 2025 + THR',18500000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(25,'2024-01-15',1,'listrik','Listrik RW-001 Januari 2024',850000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(26,'2024-02-15',1,'listrik','Listrik RW-001 Februari 2024',870000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(27,'2024-03-15',1,'listrik','Listrik RW-001 Maret 2024',920000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(28,'2024-04-15',1,'listrik','Listrik RW-001 April 2024',950000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(29,'2024-05-15',1,'listrik','Listrik RW-001 Mei 2024',880000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(30,'2024-06-15',1,'listrik','Listrik RW-001 Juni 2024',900000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(31,'2024-07-15',1,'listrik','Listrik RW-001 Juli 2024 (audio 24 jam peak)',1100000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(32,'2024-08-15',1,'listrik','Listrik RW-001 Agustus 2024 (peak season)',1150000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(33,'2024-09-15',1,'listrik','Listrik RW-001 September 2024',1050000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(34,'2024-10-15',1,'listrik','Listrik RW-001 Oktober 2024',890000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(35,'2024-11-15',1,'listrik','Listrik RW-001 November 2024',870000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(36,'2024-12-15',1,'listrik','Listrik RW-001 Desember 2024',910000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(37,'2024-01-15',2,'listrik','Listrik RW-002 Januari 2024',580000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(38,'2024-04-15',2,'listrik','Listrik RW-002 April 2024',620000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(39,'2024-07-15',2,'listrik','Listrik RW-002 Juli 2024',750000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(40,'2024-10-15',2,'listrik','Listrik RW-002 Oktober 2024',600000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(41,'2024-01-15',3,'listrik','Listrik RW-003 Januari 2024',720000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(42,'2024-04-15',3,'listrik','Listrik RW-003 April 2024',780000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(43,'2024-07-15',3,'listrik','Listrik RW-003 Juli 2024',920000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(44,'2024-10-15',3,'listrik','Listrik RW-003 Oktober 2024',750000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(45,'2024-01-15',4,'listrik','Listrik RW-004 Januari 2024',480000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(46,'2024-04-15',4,'listrik','Listrik RW-004 April 2024',520000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(47,'2024-07-15',4,'listrik','Listrik RW-004 Juli 2024',620000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(48,'2024-10-15',4,'listrik','Listrik RW-004 Oktober 2024',500000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(49,'2024-01-15',5,'listrik','Listrik RW-005 Januari 2024',650000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(50,'2024-04-15',5,'listrik','Listrik RW-005 April 2024',700000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(51,'2024-07-15',5,'listrik','Listrik RW-005 Juli 2024',850000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(52,'2024-10-15',5,'listrik','Listrik RW-005 Oktober 2024',680000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(53,'2025-01-15',1,'listrik','Listrik RW-001 Q1 2025',2700000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(54,'2025-04-15',1,'listrik','Listrik RW-001 Q2 2025',2850000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(55,'2025-07-15',1,'listrik','Listrik RW-001 Q3 2025 (peak)',3400000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(56,'2025-10-15',1,'listrik','Listrik RW-001 Q4 2025',2750000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(57,'2025-01-15',2,'listrik','Listrik RW-002 Q1 2025',1850000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(58,'2025-04-15',2,'listrik','Listrik RW-002 Q2 2025',1950000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(59,'2025-07-15',2,'listrik','Listrik RW-002 Q3 2025',2300000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(60,'2025-10-15',2,'listrik','Listrik RW-002 Q4 2025',1900000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(61,'2025-01-15',3,'listrik','Listrik RW-003 Q1 2025',2250000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(62,'2025-04-15',3,'listrik','Listrik RW-003 Q2 2025',2400000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(63,'2025-07-15',3,'listrik','Listrik RW-003 Q3 2025 (peak)',2800000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(64,'2025-10-15',3,'listrik','Listrik RW-003 Q4 2025',2350000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(65,'2025-01-15',4,'listrik','Listrik RW-004 Q1 2025',1500000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(66,'2025-04-15',4,'listrik','Listrik RW-004 Q2 2025',1600000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(67,'2025-07-15',4,'listrik','Listrik RW-004 Q3 2025',1900000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(68,'2025-10-15',4,'listrik','Listrik RW-004 Q4 2025',1550000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(69,'2025-01-15',5,'listrik','Listrik RW-005 Q1 2025',2050000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(70,'2025-04-15',5,'listrik','Listrik RW-005 Q2 2025',2150000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(71,'2025-07-15',5,'listrik','Listrik RW-005 Q3 2025',2600000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(72,'2025-10-15',5,'listrik','Listrik RW-005 Q4 2025',2100000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(73,'2024-03-10',1,'maintenance','Service rutin HVAC + humidifier RW-001',1800000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(74,'2024-05-20',3,'maintenance','Renovasi ventilasi lantai 1 RW-003',7500000.00,'bukti-maint-rw3-2024.jpg',1,'approved',3,'2024-05-22 10:00:00','Approved - renovasi memang urgent',NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(75,'2024-06-15',1,'maintenance','Ganti speaker lantai 2 (4 unit) RW-001',2400000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(76,'2024-07-20',2,'maintenance','Ganti humidifier RW-002',1500000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(77,'2024-08-25',4,'maintenance','Ganti 2 speaker rusak lantai 2 RW-004',1200000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(78,'2024-11-10',5,'maintenance','Service lantai 3 RW-005 + perbaikan atap',3500000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(79,'2025-02-15',1,'maintenance','Service tahunan HVAC + cleaning ducting RW-001',2200000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(80,'2025-05-10',3,'maintenance','Service humidifier 3 unit RW-003',1800000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(81,'2025-08-20',4,'audio','Ganti amplifier channel 1 RW-004 (rusak)',4500000.00,'bukti-amp-rw4.jpg',1,'approved',3,'2025-08-22 14:00:00','Approved - amplifier rusak parah',NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(82,'2025-10-15',1,'maintenance','Renovasi pintu masuk walet RW-001',6500000.00,'bukti-renov-rw1.jpg',1,'approved',3,'2025-10-17 09:30:00','Approved - pintu lama sudah rusak',NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(83,'2025-11-20',5,'maintenance','Ganti speaker 2 unit RW-005',1300000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(84,'2024-02-15',1,'stimulan_aroma','Beli stimulan aroma premium RW-001',1200000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(85,'2024-05-20',3,'stimulan_aroma','Stimulan aroma RW-003',950000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(86,'2024-08-10',1,'stimulan_aroma','Stimulan peak season RW-001',1500000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(87,'2024-09-15',5,'stimulan_aroma','Stimulan RW-005',850000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(88,'2025-02-20',1,'stimulan_aroma','Stimulan aroma RW-001 Q1 2025',1300000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(89,'2025-05-15',3,'stimulan_aroma','Stimulan RW-003 Q2 2025',1100000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(90,'2025-08-15',1,'stimulan_aroma','Stimulan peak season RW-001',1700000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(91,'2025-09-20',5,'stimulan_aroma','Stimulan RW-005 Q3 2025',950000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(92,'2024-04-10',1,'audio','Update software player RB-Sound Pro RW-001',500000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(93,'2024-07-05',3,'audio','Ganti 2 speaker rusak RW-003',1100000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(94,'2025-03-15',2,'audio','Upgrade player RW-002 ke kombinasi mode',1800000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(95,'2025-06-20',5,'audio','Service speaker 4 unit RW-005',900000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(96,'2024-06-30',NULL,'sertifikasi','Sertifikat BPOM 5 RW (renewal tahunan)',15000000.00,'bukti-bpom.jpg',1,'approved',3,'2024-07-02 11:00:00','Approved - wajib renewal',NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(97,'2025-06-30',NULL,'sertifikasi','Sertifikat BPOM 5 RW renewal 2025',15500000.00,'bukti-bpom-2025.jpg',1,'approved',3,'2025-07-03 10:00:00','Approved - wajib',NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(98,'2025-09-15',NULL,'sertifikasi','Lab test kadar air + kotoran (10 batch)',5000000.00,'bukti-lab.jpg',1,'approved',3,'2025-09-17 13:00:00','Approved - perlu untuk buyer export',NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(99,'2024-04-26',1,'transportasi','Transport sarang walet panen RW-001 ke gudang pusat',800000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(100,'2024-08-26',1,'transportasi','Transport panen utuh RW-001 ke gudang',1200000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(101,'2025-04-26',1,'transportasi','Transport panen urat RW-001',900000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(102,'2025-08-26',1,'transportasi','Transport panen utuh RW-001 ke gudang pusat',1300000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(103,'2024-03-31',NULL,'pajak_retribusi','Pajak properti tahunan 5 RW 2024',13800000.00,'bukti-pajak-2024.jpg',1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(104,'2025-03-31',NULL,'pajak_retribusi','Pajak properti tahunan 5 RW 2025',14200000.00,'bukti-pajak-2025.jpg',1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(105,'2024-11-15',3,'renovasi_besar','Renovasi total lantai 1 RW-003',25000000.00,'bukti-renov-besar-rw3.jpg',1,'approved',3,'2024-11-18 09:00:00','Approved - investasi jangka panjang',NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(106,'2024-01-20',1,'peralatan','Beli 4 humidifier baru RW-001',3200000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(107,'2025-02-10',4,'peralatan','Beli humidifier baru RW-004',850000.00,NULL,1,'auto_approved',NULL,NULL,NULL,NULL,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL);
/*!40000 ALTER TABLE `pengeluaran` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `pengeluaran_alokasi`
--

DROP TABLE IF EXISTS `pengeluaran_alokasi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `pengeluaran_alokasi` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pengeluaran_id` int(11) unsigned NOT NULL,
  `rumah_walet_id` int(11) unsigned NOT NULL,
  `jumlah_alokasi` decimal(15,2) NOT NULL,
  `persentase` decimal(5,2) NOT NULL COMMENT 'Persentase alokasi (0-100)',
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `pengeluaran_id` (`pengeluaran_id`),
  KEY `rumah_walet_id` (`rumah_walet_id`),
  CONSTRAINT `fk_pa_pengeluaran` FOREIGN KEY (`pengeluaran_id`) REFERENCES `pengeluaran` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_pa_rumah` FOREIGN KEY (`rumah_walet_id`) REFERENCES `rumah_walet` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=121 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pengeluaran_alokasi`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `pengeluaran_alokasi` WRITE;
/*!40000 ALTER TABLE `pengeluaran_alokasi` DISABLE KEYS */;
INSERT INTO `pengeluaran_alokasi` VALUES
(1,1,1,3406451.61,28.39,'2026-07-04 10:18:50'),
(2,1,2,2167741.94,18.06,'2026-07-04 10:18:50'),
(3,1,3,2539354.84,21.16,'2026-07-04 10:18:50'),
(4,1,4,1517419.35,12.65,'2026-07-04 10:18:50'),
(5,1,5,2369032.26,19.74,'2026-07-04 10:18:50'),
(6,2,1,3406451.61,28.39,'2026-07-04 10:18:50'),
(7,2,2,2167741.94,18.06,'2026-07-04 10:18:50'),
(8,2,3,2539354.84,21.16,'2026-07-04 10:18:50'),
(9,2,4,1517419.35,12.65,'2026-07-04 10:18:50'),
(10,2,5,2369032.26,19.74,'2026-07-04 10:18:50'),
(11,3,1,3406451.61,28.39,'2026-07-04 10:18:50'),
(12,3,2,2167741.94,18.06,'2026-07-04 10:18:50'),
(13,3,3,2539354.84,21.16,'2026-07-04 10:18:50'),
(14,3,4,1517419.35,12.65,'2026-07-04 10:18:50'),
(15,3,5,2369032.26,19.74,'2026-07-04 10:18:50'),
(16,4,1,3406451.61,28.39,'2026-07-04 10:18:50'),
(17,4,2,2167741.94,18.06,'2026-07-04 10:18:50'),
(18,4,3,2539354.84,21.16,'2026-07-04 10:18:50'),
(19,4,4,1517419.35,12.65,'2026-07-04 10:18:50'),
(20,4,5,2369032.26,19.74,'2026-07-04 10:18:50'),
(21,5,1,3406451.61,28.39,'2026-07-04 10:18:50'),
(22,5,2,2167741.94,18.06,'2026-07-04 10:18:50'),
(23,5,3,2539354.84,21.16,'2026-07-04 10:18:50'),
(24,5,4,1517419.35,12.65,'2026-07-04 10:18:50'),
(25,5,5,2369032.26,19.74,'2026-07-04 10:18:50'),
(26,6,1,3406451.61,28.39,'2026-07-04 10:18:50'),
(27,6,2,2167741.94,18.06,'2026-07-04 10:18:50'),
(28,6,3,2539354.84,21.16,'2026-07-04 10:18:50'),
(29,6,4,1517419.35,12.65,'2026-07-04 10:18:50'),
(30,6,5,2369032.26,19.74,'2026-07-04 10:18:50'),
(31,7,1,3406451.61,28.39,'2026-07-04 10:18:50'),
(32,7,2,2167741.94,18.06,'2026-07-04 10:18:50'),
(33,7,3,2539354.84,21.16,'2026-07-04 10:18:50'),
(34,7,4,1517419.35,12.65,'2026-07-04 10:18:50'),
(35,7,5,2369032.26,19.74,'2026-07-04 10:18:50'),
(36,8,1,3406451.61,28.39,'2026-07-04 10:18:50'),
(37,8,2,2167741.94,18.06,'2026-07-04 10:18:50'),
(38,8,3,2539354.84,21.16,'2026-07-04 10:18:50'),
(39,8,4,1517419.35,12.65,'2026-07-04 10:18:50'),
(40,8,5,2369032.26,19.74,'2026-07-04 10:18:50'),
(41,9,1,3406451.61,28.39,'2026-07-04 10:18:50'),
(42,9,2,2167741.94,18.06,'2026-07-04 10:18:50'),
(43,9,3,2539354.84,21.16,'2026-07-04 10:18:50'),
(44,9,4,1517419.35,12.65,'2026-07-04 10:18:50'),
(45,9,5,2369032.26,19.74,'2026-07-04 10:18:50'),
(46,10,1,3406451.61,28.39,'2026-07-04 10:18:50'),
(47,10,2,2167741.94,18.06,'2026-07-04 10:18:50'),
(48,10,3,2539354.84,21.16,'2026-07-04 10:18:50'),
(49,10,4,1517419.35,12.65,'2026-07-04 10:18:50'),
(50,10,5,2369032.26,19.74,'2026-07-04 10:18:50'),
(51,11,1,3406451.61,28.39,'2026-07-04 10:18:50'),
(52,11,2,2167741.94,18.06,'2026-07-04 10:18:50'),
(53,11,3,2539354.84,21.16,'2026-07-04 10:18:50'),
(54,11,4,1517419.35,12.65,'2026-07-04 10:18:50'),
(55,11,5,2369032.26,19.74,'2026-07-04 10:18:50'),
(56,12,1,5109677.42,28.39,'2026-07-04 10:18:50'),
(57,12,2,3251612.90,18.06,'2026-07-04 10:18:50'),
(58,12,3,3809032.26,21.16,'2026-07-04 10:18:50'),
(59,12,4,2276129.03,12.65,'2026-07-04 10:18:50'),
(60,12,5,3553548.39,19.74,'2026-07-04 10:18:50'),
(61,13,1,3548387.10,28.39,'2026-07-04 10:18:50'),
(62,13,2,2258064.52,18.06,'2026-07-04 10:18:50'),
(63,13,3,2645161.29,21.16,'2026-07-04 10:18:50'),
(64,13,4,1580645.16,12.65,'2026-07-04 10:18:50'),
(65,13,5,2467741.94,19.74,'2026-07-04 10:18:50'),
(66,14,1,3548387.10,28.39,'2026-07-04 10:18:50'),
(67,14,2,2258064.52,18.06,'2026-07-04 10:18:50'),
(68,14,3,2645161.29,21.16,'2026-07-04 10:18:50'),
(69,14,4,1580645.16,12.65,'2026-07-04 10:18:50'),
(70,14,5,2467741.94,19.74,'2026-07-04 10:18:50'),
(71,15,1,3548387.10,28.39,'2026-07-04 10:18:50'),
(72,15,2,2258064.52,18.06,'2026-07-04 10:18:50'),
(73,15,3,2645161.29,21.16,'2026-07-04 10:18:50'),
(74,15,4,1580645.16,12.65,'2026-07-04 10:18:50'),
(75,15,5,2467741.94,19.74,'2026-07-04 10:18:50'),
(76,16,1,3548387.10,28.39,'2026-07-04 10:18:50'),
(77,16,2,2258064.52,18.06,'2026-07-04 10:18:50'),
(78,16,3,2645161.29,21.16,'2026-07-04 10:18:50'),
(79,16,4,1580645.16,12.65,'2026-07-04 10:18:50'),
(80,16,5,2467741.94,19.74,'2026-07-04 10:18:50'),
(81,17,1,3548387.10,28.39,'2026-07-04 10:18:50'),
(82,17,2,2258064.52,18.06,'2026-07-04 10:18:50'),
(83,17,3,2645161.29,21.16,'2026-07-04 10:18:50'),
(84,17,4,1580645.16,12.65,'2026-07-04 10:18:50'),
(85,17,5,2467741.94,19.74,'2026-07-04 10:18:50'),
(86,18,1,3548387.10,28.39,'2026-07-04 10:18:50'),
(87,18,2,2258064.52,18.06,'2026-07-04 10:18:50'),
(88,18,3,2645161.29,21.16,'2026-07-04 10:18:50'),
(89,18,4,1580645.16,12.65,'2026-07-04 10:18:50'),
(90,18,5,2467741.94,19.74,'2026-07-04 10:18:50'),
(91,19,1,3548387.10,28.39,'2026-07-04 10:18:50'),
(92,19,2,2258064.52,18.06,'2026-07-04 10:18:50'),
(93,19,3,2645161.29,21.16,'2026-07-04 10:18:50'),
(94,19,4,1580645.16,12.65,'2026-07-04 10:18:50'),
(95,19,5,2467741.94,19.74,'2026-07-04 10:18:50'),
(96,20,1,3548387.10,28.39,'2026-07-04 10:18:50'),
(97,20,2,2258064.52,18.06,'2026-07-04 10:18:50'),
(98,20,3,2645161.29,21.16,'2026-07-04 10:18:50'),
(99,20,4,1580645.16,12.65,'2026-07-04 10:18:50'),
(100,20,5,2467741.94,19.74,'2026-07-04 10:18:50'),
(101,21,1,3548387.10,28.39,'2026-07-04 10:18:50'),
(102,21,2,2258064.52,18.06,'2026-07-04 10:18:50'),
(103,21,3,2645161.29,21.16,'2026-07-04 10:18:50'),
(104,21,4,1580645.16,12.65,'2026-07-04 10:18:50'),
(105,21,5,2467741.94,19.74,'2026-07-04 10:18:50'),
(106,22,1,3548387.10,28.39,'2026-07-04 10:18:50'),
(107,22,2,2258064.52,18.06,'2026-07-04 10:18:50'),
(108,22,3,2645161.29,21.16,'2026-07-04 10:18:50'),
(109,22,4,1580645.16,12.65,'2026-07-04 10:18:50'),
(110,22,5,2467741.94,19.74,'2026-07-04 10:18:50'),
(111,23,1,3548387.10,28.39,'2026-07-04 10:18:50'),
(112,23,2,2258064.52,18.06,'2026-07-04 10:18:50'),
(113,23,3,2645161.29,21.16,'2026-07-04 10:18:50'),
(114,23,4,1580645.16,12.65,'2026-07-04 10:18:50'),
(115,23,5,2467741.94,19.74,'2026-07-04 10:18:50'),
(116,24,1,5251612.90,28.39,'2026-07-04 10:18:50'),
(117,24,2,3341935.48,18.06,'2026-07-04 10:18:50'),
(118,24,3,3914516.13,21.16,'2026-07-04 10:18:50'),
(119,24,4,2340322.58,12.65,'2026-07-04 10:18:50'),
(120,24,5,3651612.90,19.74,'2026-07-04 10:18:50');
/*!40000 ALTER TABLE `pengeluaran_alokasi` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `penjualan`
--

DROP TABLE IF EXISTS `penjualan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `penjualan` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `no_invoice` varchar(50) NOT NULL,
  `tanggal` date NOT NULL,
  `pembeli_nama` varchar(150) NOT NULL,
  `pembeli_kontak` varchar(255) DEFAULT NULL COMMENT 'No HP / email / alamat',
  `pembeli_alamat` text DEFAULT NULL,
  `total_berat_kg` decimal(8,3) NOT NULL DEFAULT 0.000,
  `total_nilai` decimal(15,2) NOT NULL DEFAULT 0.00,
  `status_bayar` enum('belum_bayar','dp','lunas') NOT NULL DEFAULT 'belum_bayar',
  `tanggal_bayar` date DEFAULT NULL,
  `metode_bayar` varchar(50) DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `input_by` int(11) unsigned DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `no_invoice` (`no_invoice`),
  KEY `idx_tanggal` (`tanggal`),
  KEY `idx_status_bayar` (`status_bayar`),
  KEY `idx_deleted` (`deleted_at`),
  KEY `fk_penjualan_input_by` (`input_by`),
  CONSTRAINT `fk_penjualan_input_by` FOREIGN KEY (`input_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `penjualan`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `penjualan` WRITE;
/*!40000 ALTER TABLE `penjualan` DISABLE KEYS */;
INSERT INTO `penjualan` VALUES
(1,'INV-2024-001','2024-05-10','CV Sarang Mas Jaya','081200011122 / info@sarangmas.id','Jl. Cempaka No. 1, Banjarmasin, Kalsel',4.200,84000000.00,'lunas','2024-05-12','transfer','Pembeli tetap, grade A urat RW-001',1,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(2,'INV-2024-002','2024-05-15','Toko Walet Jaya','081300022233','Jl. Pahlawan No. 12, Banjarmasin',3.500,70000000.00,'lunas','2024-05-18','transfer','Grade A urat RW-003',1,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(3,'INV-2024-003','2024-05-20','PT Walet Nusantara','081400033344 / purchasing@waletnus.id','Jl. Sudirman No. 88, Jakarta Selatan',6.300,94500000.00,'lunas','2024-05-25','transfer','Grade A+B urat mix RW-001',1,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(4,'INV-2024-004','2024-06-05','CV Sarang Mas Jaya','081200011122','Jl. Cempaka No. 1, Banjarmasin',5.000,35000000.00,'lunas','2024-06-08','transfer','Grade B+C urat mix',1,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(5,'INV-2024-005','2024-09-10','PT Walet Nusantara','081400033344','Jakarta Selatan',12.500,168750000.00,'lunas','2024-09-15','transfer','Grade A utuh RW-001 peak season',1,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(6,'INV-2024-006','2024-09-15','CV Sarang Mas Jaya','081200011122','Banjarmasin',9.800,117600000.00,'lunas','2024-09-20','transfer','Grade A+B utuh RW-002',1,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(7,'INV-2024-007','2024-09-20','UD Berkah Walet','081500044455','Jl. Ahmad Yani No. 5, Martapura',8.200,90200000.00,'lunas','2024-09-25','transfer','Grade A+B utuh RW-003',1,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(8,'INV-2024-008','2024-10-05','Toko Walet Jaya','081300022233','Banjarmasin',4.800,43200000.00,'lunas','2024-10-10','transfer','Grade B+C utuh RW-005',1,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(9,'INV-2024-009','2024-12-20','CV Sarang Mas Jaya','081200011122','Banjarmasin',3.400,34800000.00,'lunas','2024-12-23','transfer','Grade A+B kecil RW-001',1,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(10,'INV-2025-001','2025-05-15','PT Walet Nusantara','081400033344','Jakarta',9.500,209000000.00,'lunas','2025-05-20','transfer','Grade A urat RW-001 + RW-003',1,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(11,'INV-2025-002','2025-05-20','CV Sarang Mas Jaya','081200011122','Banjarmasin',7.300,105400000.00,'lunas','2025-05-25','transfer','Grade A+B urat mix',1,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(12,'INV-2025-003','2025-06-10','Export Hub Ltd (Singapore)','+65 91234567 / sales@exporthub.sg','10 Anson Road, Singapore',8.000,184000000.00,'lunas','2025-06-15','transfer','Export Grade A utuh RW-001 (sisa 2024)',1,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(13,'INV-2025-004','2025-09-15','PT Walet Nusantara','081400033344','Jakarta',13.200,211200000.00,'lunas','2025-09-22','transfer','Grade A utuh RW-001 peak 2025',1,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(14,'INV-2025-005','2025-09-20','CV Sarang Mas Jaya','081200011122','Banjarmasin',11.200,123200000.00,'lunas','2025-09-25','transfer','Grade A+B utuh RW-002 + RW-005',1,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(15,'INV-2025-006','2025-09-25','UD Berkah Walet','081500044455','Martapura',10.200,142800000.00,'lunas','2025-09-30','transfer','Grade A utuh RW-003',1,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(16,'INV-2025-007','2025-12-20','CV Sarang Mas Jaya','081200011122','Banjarmasin',4.000,52000000.00,'dp',NULL,NULL,'Grade A kecil RW-001, DP 50%, sisa 30 hari',1,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(17,'INV-2025-008','2025-12-22','Toko Walet Jaya','081300022233','Banjarmasin',3.200,31200000.00,'belum_bayar',NULL,NULL,'Grade A+B kecil RW-005, jatuh tempo 15 Jan 2026',1,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(18,'INV-2026-001','2026-01-15','Export Hub Ltd (Singapore)','+65 91234567','Singapore',6.500,117000000.00,'belum_bayar',NULL,NULL,'Export Grade A utuh RW-001 (stok available)',1,'2026-07-04 10:18:50','2026-07-04 10:18:50',NULL);
/*!40000 ALTER TABLE `penjualan` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `penjualan_detail`
--

DROP TABLE IF EXISTS `penjualan_detail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `penjualan_detail` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `penjualan_id` int(11) unsigned NOT NULL,
  `hasil_panen_id` int(11) unsigned DEFAULT NULL,
  `rumah_walet_id` int(11) unsigned DEFAULT NULL COMMENT 'Snapshot RW saat transaksi',
  `grade` enum('A','B','C') NOT NULL,
  `jenis_panen` enum('urat','sarang_utuh','kecil') NOT NULL DEFAULT 'sarang_utuh',
  `berat_kg` decimal(8,3) NOT NULL,
  `harga_per_kg` decimal(12,2) NOT NULL,
  `subtotal` decimal(15,2) GENERATED ALWAYS AS (`berat_kg` * `harga_per_kg`) STORED,
  `catatan` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `penjualan_id` (`penjualan_id`),
  KEY `hasil_panen_id` (`hasil_panen_id`),
  CONSTRAINT `fk_pd_hasil_panen` FOREIGN KEY (`hasil_panen_id`) REFERENCES `hasil_panen` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_pd_penjualan` FOREIGN KEY (`penjualan_id`) REFERENCES `penjualan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `penjualan_detail`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `penjualan_detail` WRITE;
/*!40000 ALTER TABLE `penjualan_detail` DISABLE KEYS */;
INSERT INTO `penjualan_detail` VALUES
(1,1,1,1,'A','urat',4.200,20000000.00,84000000.00,'Grade A urat premium','2026-07-04 10:18:50'),
(2,2,7,3,'A','urat',3.500,20000000.00,70000000.00,'Grade A urat','2026-07-04 10:18:50'),
(3,3,2,1,'B','urat',3.000,13000000.00,39000000.00,'B urat RW-001','2026-07-04 10:18:50'),
(4,3,5,2,'B','urat',1.800,13000000.00,23400000.00,'B urat RW-002','2026-07-04 10:18:50'),
(5,3,1,1,'A','urat',1.500,20000000.00,30000000.00,'A urat RW-001 (sisa)','2026-07-04 10:18:50'),
(6,4,3,1,'C','urat',2.500,7000000.00,17500000.00,'C urat RW-001','2026-07-04 10:18:50'),
(7,4,6,2,'C','urat',1.800,7000000.00,12600000.00,'C urat RW-002','2026-07-04 10:18:50'),
(8,4,9,3,'C','urat',0.700,7000000.00,4900000.00,'C urat RW-003','2026-07-04 10:18:50'),
(9,5,13,1,'A','sarang_utuh',6.500,15000000.00,97500000.00,'A utuh peak','2026-07-04 10:18:50'),
(10,5,19,3,'A','sarang_utuh',5.200,15000000.00,78000000.00,'A utuh RW-003','2026-07-04 10:18:50'),
(11,5,16,2,'A','sarang_utuh',0.800,15000000.00,12000000.00,'A utuh RW-002','2026-07-04 10:18:50'),
(12,6,16,2,'A','sarang_utuh',3.400,15000000.00,51000000.00,'A utuh RW-002','2026-07-04 10:18:50'),
(13,6,17,2,'B','sarang_utuh',3.800,10000000.00,38000000.00,'B utuh RW-002','2026-07-04 10:18:50'),
(14,6,22,4,'A','sarang_utuh',1.500,15000000.00,22500000.00,'A utuh RW-004','2026-07-04 10:18:50'),
(15,6,23,4,'B','sarang_utuh',1.100,10000000.00,11000000.00,'B utuh RW-004','2026-07-04 10:18:50'),
(16,7,19,3,'A','sarang_utuh',5.200,15000000.00,78000000.00,'A utuh RW-003','2026-07-04 10:18:50'),
(17,7,20,3,'B','sarang_utuh',3.000,10000000.00,30000000.00,'B utuh RW-003','2026-07-04 10:18:50'),
(18,8,26,5,'B','sarang_utuh',3.000,10000000.00,30000000.00,'B utuh RW-005','2026-07-04 10:18:50'),
(19,8,27,5,'C','sarang_utuh',1.800,5000000.00,9000000.00,'C utuh RW-005','2026-07-04 10:18:50'),
(20,9,28,1,'A','kecil',2.200,12000000.00,26400000.00,'A kecil RW-001','2026-07-04 10:18:50'),
(21,9,29,1,'B','kecil',1.200,6000000.00,7200000.00,'B kecil RW-001','2026-07-04 10:18:50'),
(22,10,34,1,'A','urat',4.500,22000000.00,99000000.00,'A urat RW-001','2026-07-04 10:18:50'),
(23,10,40,3,'A','urat',3.800,22000000.00,83600000.00,'A urat RW-003','2026-07-04 10:18:50'),
(24,10,43,5,'A','urat',1.200,22000000.00,26400000.00,'A urat RW-005','2026-07-04 10:18:50'),
(25,11,34,1,'B','urat',2.500,14000000.00,35000000.00,'B urat RW-001','2026-07-04 10:18:50'),
(26,11,40,3,'B','urat',2.200,14000000.00,30800000.00,'B urat RW-003','2026-07-04 10:18:50'),
(27,11,35,1,'A','urat',1.500,22000000.00,33000000.00,'A urat RW-001','2026-07-04 10:18:50'),
(28,11,41,3,'A','urat',1.100,22000000.00,24200000.00,'A urat RW-003','2026-07-04 10:18:50'),
(29,12,13,1,'A','sarang_utuh',4.000,23000000.00,92000000.00,'A utuh premium export','2026-07-04 10:18:50'),
(30,12,19,3,'A','sarang_utuh',4.000,23000000.00,92000000.00,'A utuh RW-003 export','2026-07-04 10:18:50'),
(31,13,46,1,'A','sarang_utuh',7.000,16000000.00,112000000.00,'A utuh RW-001 2025','2026-07-04 10:18:50'),
(32,13,49,2,'A','sarang_utuh',4.500,16000000.00,72000000.00,'A utuh RW-002 2025','2026-07-04 10:18:50'),
(33,13,52,3,'A','sarang_utuh',1.700,16000000.00,27200000.00,'A utuh RW-003 2025','2026-07-04 10:18:50'),
(34,14,49,2,'B','sarang_utuh',4.000,11000000.00,44000000.00,'B utuh RW-002','2026-07-04 10:18:50'),
(35,14,50,2,'A','sarang_utuh',0.500,16000000.00,8000000.00,'A utuh RW-002 sisa','2026-07-04 10:18:50'),
(36,14,58,5,'A','sarang_utuh',4.000,16000000.00,64000000.00,'A utuh RW-005','2026-07-04 10:18:50'),
(37,14,59,5,'B','sarang_utuh',2.700,11000000.00,29700000.00,'B utuh RW-005','2026-07-04 10:18:50'),
(38,15,52,3,'A','sarang_utuh',5.500,16000000.00,88000000.00,'A utuh RW-003','2026-07-04 10:18:50'),
(39,15,58,5,'A','sarang_utuh',4.700,16000000.00,75200000.00,'A utuh RW-005','2026-07-04 10:18:50'),
(40,16,61,1,'A','kecil',2.400,13000000.00,31200000.00,'A kecil RW-001 (DP 50%)','2026-07-04 10:18:50'),
(41,16,64,5,'A','kecil',1.600,13000000.00,20800000.00,'A kecil RW-005 (DP 50%)','2026-07-04 10:18:50'),
(42,17,65,5,'A','kecil',1.500,13000000.00,19500000.00,'A kecil RW-005','2026-07-04 10:18:50'),
(43,17,62,1,'B','kecil',1.000,7000000.00,7000000.00,'B kecil RW-001','2026-07-04 10:18:50'),
(44,17,66,5,'C','kecil',0.700,3500000.00,2450000.00,'C kecil RW-005','2026-07-04 10:18:50'),
(45,18,46,1,'A','sarang_utuh',6.500,18000000.00,117000000.00,'A utuh RW-001 (stok tersedia)','2026-07-04 10:18:50');
/*!40000 ALTER TABLE `penjualan_detail` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `petugas`
--

DROP TABLE IF EXISTS `petugas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `petugas` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nip` varchar(30) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `jenis_kelamin` enum('L','P') DEFAULT 'L',
  `tempat_lahir` varchar(100) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `tanggal_masuk` date DEFAULT NULL,
  `user_id` int(11) unsigned DEFAULT NULL,
  `status` enum('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  `foto` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nip` (`nip`),
  KEY `user_id` (`user_id`),
  KEY `idx_status_deleted` (`status`,`deleted_at`),
  CONSTRAINT `fk_petugas_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `petugas`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `petugas` WRITE;
/*!40000 ALTER TABLE `petugas` DISABLE KEYS */;
INSERT INTO `petugas` VALUES
(1,'P-001','Budi Santoso','L','Banjarmasin','1985-03-15','Jl. Ahmad Yani No. 12, Banjarmasin','081234567890','budi@walet.test','2020-01-15',2,'aktif',NULL,'2024-01-01 08:00:00','2026-07-04 10:18:50',NULL),
(2,'P-002','Siti Aminah','P','Banjarbaru','1990-07-22','Jl. Merdeka No. 5, Banjarbaru','082345678901','siti@walet.test','2021-03-01',NULL,'aktif',NULL,'2024-01-01 08:00:00','2026-07-04 10:18:50',NULL),
(3,'P-003','Joko Susilo','L','Martapura','1988-11-10','Jl. Cempaka No. 3, Martapura','083456789012','joko@walet.test','2019-06-15',NULL,'aktif',NULL,'2024-01-01 08:00:00','2026-07-04 10:18:50',NULL),
(4,'P-004','Ahmad Fauzi','L','Kandangan','1992-05-08','Jl. Pahlawan No. 7, Kandangan','084567890123','fauzi@walet.test','2022-08-10',NULL,'aktif',NULL,'2024-01-01 08:00:00','2026-07-04 10:18:50',NULL);
/*!40000 ALTER TABLE `petugas` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `petugas_rumah`
--

DROP TABLE IF EXISTS `petugas_rumah`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `petugas_rumah` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `petugas_id` int(11) unsigned NOT NULL,
  `rumah_walet_id` int(11) unsigned NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `petugas_id` (`petugas_id`),
  KEY `rumah_walet_id` (`rumah_walet_id`),
  CONSTRAINT `fk_pr_petugas` FOREIGN KEY (`petugas_id`) REFERENCES `petugas` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_pr_rumah` FOREIGN KEY (`rumah_walet_id`) REFERENCES `rumah_walet` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `petugas_rumah`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `petugas_rumah` WRITE;
/*!40000 ALTER TABLE `petugas_rumah` DISABLE KEYS */;
INSERT INTO `petugas_rumah` VALUES
(1,1,1,'2020-01-20',NULL,'Petugas utama RW-001','2026-07-04 10:18:50'),
(2,1,2,'2020-01-20',NULL,'Petugas backup RW-002','2026-07-04 10:18:50'),
(3,2,3,'2021-03-05',NULL,'Petugas utama RW-003','2026-07-04 10:18:50'),
(4,3,4,'2019-06-20',NULL,'Petugas utama RW-004','2026-07-04 10:18:50'),
(5,3,5,'2019-06-20',NULL,'Petugas utama RW-005','2026-07-04 10:18:50'),
(6,4,1,'2022-08-15',NULL,'Backup RW-001','2026-07-04 10:18:50'),
(7,4,2,'2022-08-15',NULL,'Backup RW-002','2026-07-04 10:18:50'),
(8,4,3,'2022-08-15',NULL,'Backup RW-003','2026-07-04 10:18:50'),
(9,4,4,'2022-08-15',NULL,'Backup RW-004','2026-07-04 10:18:50'),
(10,4,5,'2022-08-15',NULL,'Backup RW-005','2026-07-04 10:18:50');
/*!40000 ALTER TABLE `petugas_rumah` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `predator_inspeksi`
--

DROP TABLE IF EXISTS `predator_inspeksi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `predator_inspeksi` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `inspeksi_id` int(11) unsigned NOT NULL,
  `jenis_predator` enum('cicak','tikus','semut','kecoak','laba_laba','kelelawar','lainnya') NOT NULL,
  `tingkat_infestasi` enum('ringan','sedang','berat') NOT NULL DEFAULT 'ringan',
  `lokasi` varchar(100) DEFAULT NULL COMMENT 'Lantai 1 / lantai 2 / area luar',
  `tindakan` varchar(255) DEFAULT NULL COMMENT 'Tindakan pengendalian yang dilakukan',
  `tgl_tindakan` date DEFAULT NULL,
  `tgl_follow_up` date DEFAULT NULL,
  `hasil_follow_up` enum('pending','berhasil','gagal','sebagian') DEFAULT 'pending',
  `catatan` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `inspeksi_id` (`inspeksi_id`),
  KEY `idx_jenis` (`jenis_predator`),
  CONSTRAINT `fk_pi_inspeksi` FOREIGN KEY (`inspeksi_id`) REFERENCES `inspeksi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `predator_inspeksi`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `predator_inspeksi` WRITE;
/*!40000 ALTER TABLE `predator_inspeksi` DISABLE KEYS */;
INSERT INTO `predator_inspeksi` VALUES
(1,4,'semut','ringan','lantai 1','Semprot anti-semut organik','2024-02-25','2024-03-04','berhasil','Tidak ada semut setelah tindakan','2026-07-04 10:18:50'),
(2,4,'cicak','sedang','lantai 2','Pasang trap cicak, tutup ventilasi kawat','2024-02-25','2024-03-04','sebagian','Masih ada cicak, perlu tindakan lanjut','2026-07-04 10:18:50'),
(3,8,'tikus','ringan','area luar','Pasang umpan racun tikus di area luar','2024-04-10','2024-04-17','berhasil','Tidak ada tanda tikus lagi','2026-07-04 10:18:50'),
(4,11,'kecoak','sedang','lantai 1','Semprot anti-kecoak, bersihkan area lembab','2024-06-15','2024-06-22','berhasil','Kebersihan terjaga','2026-07-04 10:18:50'),
(5,15,'laba_laba','ringan','lantai 2','Bersihkan jaring laba-laba manual','2024-08-20','2024-08-27','berhasil','Laba-laba hilang','2026-07-04 10:18:50'),
(6,20,'semut','sedang','lantai 1','Semprot anti-semut, cari sarang','2024-10-15','2024-10-22','sebagian','Masih ada sarang semut di sudut','2026-07-04 10:18:50'),
(7,25,'cicak','ringan','lantai 1','Pasang trap cicak','2025-02-15','2025-02-22','berhasil','Cicak terkurangi','2026-07-04 10:18:50'),
(8,28,'kelelawar','ringan','area luar','Pasang jaring anti-kelelawar di ventilasi atas','2025-04-10','2025-04-17','berhasil','Kelelawar tidak masuk lagi','2026-07-04 10:18:50'),
(9,33,'tikus','sedang','lantai 2 dan area luar','Umpan racun + tutup lubang','2025-08-10','2025-08-17','berhasil','Tikus hilang','2026-07-04 10:18:50'),
(10,36,'semut','ringan','lantai 1','Semprot anti-semut organik','2025-10-15','2025-10-22','berhasil','Bersih','2026-07-04 10:18:50');
/*!40000 ALTER TABLE `predator_inspeksi` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `rumah_walet`
--

DROP TABLE IF EXISTS `rumah_walet`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `rumah_walet` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `kode` varchar(20) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `lokasi` text DEFAULT NULL,
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `luas` decimal(10,2) DEFAULT NULL COMMENT 'Luas bangunan (m²)',
  `jumlah_lantai` int(11) DEFAULT 1,
  `tahun_dibangun` int(11) DEFAULT NULL,
  `jenis_bangunan` enum('rumah_khusus_walet','ruko_modifikasi','gua') DEFAULT 'rumah_khusus_walet',
  `kapasitas_panen_kg` decimal(8,2) DEFAULT NULL COMMENT 'Estimasi kapasitas panen rata-rata per bulan (kg)',
  `kapasitas_bulan_01_kg` decimal(8,2) DEFAULT 0.00,
  `kapasitas_bulan_02_kg` decimal(8,2) DEFAULT 0.00,
  `kapasitas_bulan_03_kg` decimal(8,2) DEFAULT 0.00 COMMENT 'Panen urat mulai',
  `kapasitas_bulan_04_kg` decimal(8,2) DEFAULT 0.00 COMMENT 'Panen urat',
  `kapasitas_bulan_05_kg` decimal(8,2) DEFAULT 0.00,
  `kapasitas_bulan_06_kg` decimal(8,2) DEFAULT 0.00,
  `kapasitas_bulan_07_kg` decimal(8,2) DEFAULT 0.00 COMMENT 'Panen sarang utuh mulai',
  `kapasitas_bulan_08_kg` decimal(8,2) DEFAULT 0.00 COMMENT 'Panen sarang utuh peak',
  `kapasitas_bulan_09_kg` decimal(8,2) DEFAULT 0.00 COMMENT 'Panen sarang utuh',
  `kapasitas_bulan_10_kg` decimal(8,2) DEFAULT 0.00,
  `kapasitas_bulan_11_kg` decimal(8,2) DEFAULT 0.00 COMMENT 'Panen kecil mulai',
  `kapasitas_bulan_12_kg` decimal(8,2) DEFAULT 0.00 COMMENT 'Panen kecil',
  `jumlah_speaker` int(11) DEFAULT 0 COMMENT 'Jumlah speaker audio aktif',
  `jenis_player` varchar(100) DEFAULT NULL COMMENT 'Merek/tipe player audio',
  `jam_operasi_audio` varchar(50) DEFAULT '05:00-19:00' COMMENT 'Jam nyala audio per hari',
  `humidifier_count` int(11) DEFAULT 0,
  `cctv_url` varchar(255) DEFAULT NULL COMMENT 'URL CCTV/stream untuk monitoring visual',
  `tanggal_berdiri` date DEFAULT NULL,
  `tanggal_renovasi_terakhir` date DEFAULT NULL,
  `sertifikat_bpom` varchar(255) DEFAULT NULL COMMENT 'Path file sertifikat BPOM',
  `foto_depan` varchar(255) DEFAULT NULL,
  `foto_dalam` varchar(255) DEFAULT NULL,
  `pajak_properti_tahunan` decimal(12,2) DEFAULT 0.00,
  `status_kepemilikan` enum('milik_sendiri','sewa') DEFAULT 'milik_sendiri',
  `kondisi` enum('baik','sedang','buruk') NOT NULL DEFAULT 'baik',
  `keterangan` text DEFAULT NULL,
  `status` enum('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kode` (`kode`),
  KEY `idx_status_deleted` (`status`,`deleted_at`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rumah_walet`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `rumah_walet` WRITE;
/*!40000 ALTER TABLE `rumah_walet` DISABLE KEYS */;
INSERT INTO `rumah_walet` VALUES
(1,'RW-001','Rumah Walet Pelaihari','Jl. Merpati No. 12, Pelaihari, Tanah Laut',-3.7925000,114.8194000,250.00,3,2018,'rumah_khusus_walet',8.50,4.00,4.00,12.00,12.00,6.00,6.00,14.00,18.00,14.00,6.00,8.00,6.00,24,'RB-Sound Pro v3','05:00-19:00',4,'http://cctv.walet.test/rw001','2018-03-15','2025-08-10',NULL,NULL,NULL,3500000.00,'milik_sendiri','baik','RW utama, produktivitas tinggi','aktif','2024-01-01 08:00:00','2026-07-04 10:18:50',NULL),
(2,'RW-002','Rumah Walet Banjarbaru','Jl. Walet Indah No. 5, Banjarbaru Utara',-3.4525000,114.8575000,180.00,2,2020,'rumah_khusus_walet',6.00,3.00,3.00,8.00,8.00,4.00,4.00,10.00,12.00,10.00,4.00,6.00,4.00,16,'RB-Sound Pro v3','05:00-19:00',2,'http://cctv.walet.test/rw002','2020-07-20',NULL,NULL,NULL,NULL,2500000.00,'milik_sendiri','baik','RW kedua, still developing','aktif','2024-01-01 08:00:00','2026-07-04 10:18:50',NULL),
(3,'RW-003','Rumah Walet Martapura','Jl. Cempaka No. 8, Martapura, Banjar',-3.4294000,114.8489000,200.00,3,2017,'rumah_khusus_walet',7.50,3.50,3.50,10.00,10.00,5.00,5.00,12.00,15.00,12.00,5.00,7.00,5.00,20,'WaletMaster X1','05:00-19:00',3,'http://cctv.walet.test/rw003','2017-05-10','2024-11-15',NULL,NULL,NULL,3000000.00,'milik_sendiri','sedang','Renovasi ventilasi 2024','aktif','2024-01-01 08:00:00','2026-07-04 10:18:50',NULL),
(4,'RW-004','Rumah Walet Kandangan','Jl. A. Yani No. 22, Kandangan, HSS',-2.6667000,115.2833000,150.00,2,2021,'ruko_modifikasi',5.00,2.50,2.50,6.50,6.50,3.50,3.50,8.50,10.00,8.50,3.50,5.00,3.50,12,'RB-Sound Pro v2','05:30-19:00',1,NULL,'2021-02-10',NULL,NULL,NULL,NULL,2000000.00,'sewa','baik','Ruko modifikasi, perlu upgrade audio','aktif','2024-01-01 08:00:00','2026-07-04 10:18:50',NULL),
(5,'RW-005','Rumah Walet Rantau','Jl. Tapin Bumi No. 3, Rantau, Tapin',-2.6000000,115.0000000,175.00,3,2019,'rumah_khusus_walet',6.50,3.00,3.00,8.50,8.50,4.50,4.50,11.00,13.00,11.00,4.50,6.50,4.50,18,'WaletMaster X1','05:00-19:00',2,NULL,'2019-09-25',NULL,NULL,NULL,NULL,2800000.00,'milik_sendiri','baik','Performa stabil','aktif','2024-01-01 08:00:00','2026-07-04 10:18:50',NULL),
(8,'RW-TEST-001','Rumah Walet Test','Lokasi Test',-3.3196938,114.5908241,100.50,2,2020,'rumah_khusus_walet',5.50,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0,NULL,'05:00-19:00',0,NULL,NULL,NULL,NULL,NULL,NULL,0.00,'milik_sendiri','baik',NULL,'aktif','2026-07-04 17:36:31','2026-07-04 17:36:31',NULL);
/*!40000 ALTER TABLE `rumah_walet` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `stok_sarang`
--

DROP TABLE IF EXISTS `stok_sarang`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `stok_sarang` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `hasil_panen_id` int(11) unsigned NOT NULL,
  `rumah_walet_id` int(11) unsigned NOT NULL,
  `grade` enum('A','B','C') NOT NULL,
  `jenis_panen` enum('urat','sarang_utuh','kecil') NOT NULL,
  `berat_kg` decimal(8,3) NOT NULL,
  `lokasi_gudang` enum('gudang_rw','gudang_pusat') NOT NULL DEFAULT 'gudang_rw',
  `tanggal_masuk` date NOT NULL,
  `tanggal_keluar` date DEFAULT NULL,
  `penjualan_id` int(11) unsigned DEFAULT NULL COMMENT 'Link ke penjualan jika sudah terjual',
  `status_stok` enum('tersedia','terjual','pindah_gudang','hilang') NOT NULL DEFAULT 'tersedia',
  `catatan` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hasil_panen_id` (`hasil_panen_id`),
  KEY `rumah_walet_id` (`rumah_walet_id`),
  KEY `idx_status_stok` (`status_stok`),
  KEY `idx_lokasi` (`lokasi_gudang`),
  KEY `idx_deleted` (`deleted_at`),
  KEY `fk_ss_penjualan` (`penjualan_id`),
  CONSTRAINT `fk_ss_hasil_panen` FOREIGN KEY (`hasil_panen_id`) REFERENCES `hasil_panen` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_ss_penjualan` FOREIGN KEY (`penjualan_id`) REFERENCES `penjualan` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_ss_rumah` FOREIGN KEY (`rumah_walet_id`) REFERENCES `rumah_walet` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stok_sarang`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `stok_sarang` WRITE;
/*!40000 ALTER TABLE `stok_sarang` DISABLE KEYS */;
INSERT INTO `stok_sarang` VALUES
(1,47,1,'B','sarang_utuh',6.200,'gudang_rw','2025-08-25',NULL,NULL,'tersedia','Stok available - menunggu pembeli','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(2,48,1,'C','sarang_utuh',4.000,'gudang_rw','2025-08-25',NULL,NULL,'tersedia','Stok available','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(3,55,4,'A','sarang_utuh',3.200,'gudang_rw','2025-09-05',NULL,NULL,'tersedia','Stok RW-004 available','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(4,56,4,'B','sarang_utuh',2.900,'gudang_rw','2025-09-05',NULL,NULL,'tersedia','Stok RW-004 available','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(5,57,4,'C','sarang_utuh',1.900,'gudang_rw','2025-09-05',NULL,NULL,'tersedia','Stok RW-004 available','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(6,63,1,'C','kecil',1.300,'gudang_rw','2025-12-15',NULL,NULL,'tersedia','Stok kecil available','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(7,1,1,'A','urat',4.200,'gudang_pusat','2024-04-25','2024-05-10',1,'terjual','Terjual INV-2024-001','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(8,13,1,'A','sarang_utuh',6.500,'gudang_pusat','2024-08-25','2024-09-10',5,'terjual','Terjual INV-2024-005','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(9,34,1,'A','urat',4.500,'gudang_pusat','2025-04-25','2025-05-15',10,'terjual','Terjual INV-2025-001','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL),
(10,46,1,'A','sarang_utuh',7.000,'gudang_pusat','2025-08-25','2025-09-15',13,'terjual','Terjual INV-2025-004','2026-07-04 10:18:50','2026-07-04 10:18:50',NULL);
/*!40000 ALTER TABLE `stok_sarang` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `role` enum('admin','petugas','owner') NOT NULL DEFAULT 'petugas',
  `no_hp` varchar(20) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `status` enum('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  `must_change_password` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Force ganti password saat login pertama',
  `last_login` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `idx_role` (`role`),
  KEY `idx_deleted` (`deleted_at`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES
(1,'Administrator','admin','$2y$12$VLgUXq9H.6R.26Yhwes8gO3rpaXPmGOIZcDnf3aLm9y8ZVn7a.e2K','admin@walet-monitoring.test','admin',NULL,NULL,'aktif',0,'2026-07-04 17:36:27','2026-07-04 17:22:26','2026-07-04 17:36:27',NULL),
(2,'Budi Santoso','petugas','$2y$12$fYDDTt9nVDfxFnT5VKvzb.JKOwcDdCiXtZQ99CajTrAepDQID//nS','petugas@walet-monitoring.test','petugas',NULL,NULL,'aktif',0,'2026-07-04 17:36:29','2026-07-04 17:22:26','2026-07-04 17:36:29',NULL),
(3,'Harto Wijaya','owner','$2y$12$0HBUd1.jQjoIemULquwS1esq0lGF1VgjkOWgywDTUhpXpCOuobzZS','owner@walet-monitoring.test','owner',NULL,NULL,'aktif',0,'2026-07-04 17:36:30','2026-07-04 17:22:26','2026-07-04 17:36:30',NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Dumping routines for database 'db_walet'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

-- Dump completed on 2026-07-05  8:58:55
