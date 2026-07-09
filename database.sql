-- ===================================================================
-- DATABASE SCHEMA v2.0: Sistem Informasi Monitoring Sarang Burung Walet
-- Tech: MySQL/MariaDB 10+ / CodeIgniter 4
-- Major Changes from v1:
--   * Soft delete (deleted_at) on all main tables
--   * FK changed from ON DELETE CASCADE → ON DELETE RESTRICT
--   * Added DB indexes for tanggal/periode/status
--   * New tables: audio_walet, predator_inspeksi, harga_grade, penjualan,
--     penjualan_detail, stok_sarang, login_attempts, approval_log
--   * Extended fields: jenis_panen, fase_sarang, berat_basah/kering,
--     approval_status, must_change_password, kapasitas per bulan (12 cols)
-- ===================================================================

CREATE DATABASE IF NOT EXISTS `db_walet` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `db_walet`;

SET FOREIGN_KEY_CHECKS = 0;

-- -------------------------------------------------------------------
-- TABLE: users (Akun login - Admin / Petugas / Owner)
-- -------------------------------------------------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama` VARCHAR(100) NOT NULL,
  `username` VARCHAR(50) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `email` VARCHAR(100) DEFAULT NULL,
  `role` ENUM('admin','petugas','owner') NOT NULL DEFAULT 'petugas',
  `no_hp` VARCHAR(20) DEFAULT NULL,
  `foto` VARCHAR(255) DEFAULT NULL,
  `status` ENUM('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  `must_change_password` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Force ganti password saat login pertama',
  `last_login` DATETIME DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `idx_role` (`role`),
  KEY `idx_deleted` (`deleted_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -------------------------------------------------------------------
-- TABLE: rumah_walet (Master Rumah Walet)
-- -------------------------------------------------------------------
DROP TABLE IF EXISTS `rumah_walet`;
CREATE TABLE `rumah_walet` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `kode` VARCHAR(20) NOT NULL,
  `nama` VARCHAR(100) NOT NULL,
  `lokasi` TEXT,
  `latitude` DECIMAL(10,7) DEFAULT NULL,
  `longitude` DECIMAL(10,7) DEFAULT NULL,
  `luas` DECIMAL(10,2) DEFAULT NULL COMMENT 'Luas bangunan (m²)',
  `jumlah_lantai` INT(11) DEFAULT 1,
  `tahun_dibangun` INT(11) DEFAULT NULL,
  `jenis_bangunan` ENUM('rumah_khusus_walet','ruko_modifikasi','gua') DEFAULT 'rumah_khusus_walet',
  `kapasitas_panen_kg` DECIMAL(8,2) DEFAULT NULL COMMENT 'Estimasi kapasitas panen rata-rata per bulan (kg)',
  -- Kapasitas panen per bulan (akomodasi musim: urat Maret-Apr, utuh Jul-Sep, kecil Nov-Des)
  `kapasitas_bulan_01_kg` DECIMAL(8,2) DEFAULT 0,
  `kapasitas_bulan_02_kg` DECIMAL(8,2) DEFAULT 0,
  `kapasitas_bulan_03_kg` DECIMAL(8,2) DEFAULT 0 COMMENT 'Panen urat mulai',
  `kapasitas_bulan_04_kg` DECIMAL(8,2) DEFAULT 0 COMMENT 'Panen urat',
  `kapasitas_bulan_05_kg` DECIMAL(8,2) DEFAULT 0,
  `kapasitas_bulan_06_kg` DECIMAL(8,2) DEFAULT 0,
  `kapasitas_bulan_07_kg` DECIMAL(8,2) DEFAULT 0 COMMENT 'Panen sarang utuh mulai',
  `kapasitas_bulan_08_kg` DECIMAL(8,2) DEFAULT 0 COMMENT 'Panen sarang utuh peak',
  `kapasitas_bulan_09_kg` DECIMAL(8,2) DEFAULT 0 COMMENT 'Panen sarang utuh',
  `kapasitas_bulan_10_kg` DECIMAL(8,2) DEFAULT 0,
  `kapasitas_bulan_11_kg` DECIMAL(8,2) DEFAULT 0 COMMENT 'Panen kecil mulai',
  `kapasitas_bulan_12_kg` DECIMAL(8,2) DEFAULT 0 COMMENT 'Panen kecil',
  -- Audio & infrastruktur
  `jumlah_speaker` INT(11) DEFAULT 0 COMMENT 'Jumlah speaker audio aktif',
  `jenis_player` VARCHAR(100) DEFAULT NULL COMMENT 'Merek/tipe player audio',
  `jam_operasi_audio` VARCHAR(50) DEFAULT '05:00-19:00' COMMENT 'Jam nyala audio per hari',
  `humidifier_count` INT(11) DEFAULT 0,
  `cctv_url` VARCHAR(255) DEFAULT NULL COMMENT 'URL CCTV/stream untuk monitoring visual',
  -- Sertifikasi & dokumentasi
  `tanggal_berdiri` DATE DEFAULT NULL,
  `tanggal_renovasi_terakhir` DATE DEFAULT NULL,
  `sertifikat_bpom` VARCHAR(255) DEFAULT NULL COMMENT 'Path file sertifikat BPOM',
  `foto_depan` VARCHAR(255) DEFAULT NULL,
  `foto_dalam` VARCHAR(255) DEFAULT NULL,
  -- Finansial & kepemilikan
  `pajak_properti_tahunan` DECIMAL(12,2) DEFAULT 0,
  `status_kepemilikan` ENUM('milik_sendiri','sewa') DEFAULT 'milik_sendiri',
  -- Kondisi & status
  `kondisi` ENUM('baik','sedang','buruk') NOT NULL DEFAULT 'baik',
  `keterangan` TEXT,
  `status` ENUM('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kode` (`kode`),
  KEY `idx_status_deleted` (`status`, `deleted_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -------------------------------------------------------------------
-- TABLE: petugas (Master Petugas)
-- -------------------------------------------------------------------
DROP TABLE IF EXISTS `petugas`;
CREATE TABLE `petugas` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nip` VARCHAR(30) NOT NULL,
  `nama` VARCHAR(100) NOT NULL,
  `jenis_kelamin` ENUM('L','P') DEFAULT 'L',
  `tempat_lahir` VARCHAR(100) DEFAULT NULL,
  `tanggal_lahir` DATE DEFAULT NULL,
  `alamat` TEXT,
  `no_hp` VARCHAR(20) DEFAULT NULL,
  `email` VARCHAR(100) DEFAULT NULL,
  `tanggal_masuk` DATE DEFAULT NULL,
  `user_id` INT(11) UNSIGNED DEFAULT NULL,
  `status` ENUM('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  `foto` VARCHAR(255) DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nip` (`nip`),
  KEY `user_id` (`user_id`),
  KEY `idx_status_deleted` (`status`, `deleted_at`),
  CONSTRAINT `fk_petugas_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -------------------------------------------------------------------
-- TABLE: petugas_rumah (Penugasan Petugas ke Rumah Walet)
-- -------------------------------------------------------------------
DROP TABLE IF EXISTS `petugas_rumah`;
CREATE TABLE `petugas_rumah` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `petugas_id` INT(11) UNSIGNED NOT NULL,
  `rumah_walet_id` INT(11) UNSIGNED NOT NULL,
  `tanggal_mulai` DATE NOT NULL,
  `tanggal_selesai` DATE DEFAULT NULL,
  `catatan` TEXT,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `petugas_id` (`petugas_id`),
  KEY `rumah_walet_id` (`rumah_walet_id`),
  CONSTRAINT `fk_pr_petugas` FOREIGN KEY (`petugas_id`) REFERENCES `petugas` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_pr_rumah` FOREIGN KEY (`rumah_walet_id`) REFERENCES `rumah_walet` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -------------------------------------------------------------------
-- TABLE: inspeksi (Catatan Inspeksi Rutin)
-- -------------------------------------------------------------------
DROP TABLE IF EXISTS `inspeksi`;
CREATE TABLE `inspeksi` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `rumah_walet_id` INT(11) UNSIGNED NOT NULL,
  `petugas_id` INT(11) UNSIGNED NOT NULL,
  `tanggal_inspeksi` DATE NOT NULL,
  `kondisi_bangunan` ENUM('baik','sedang','buruk') NOT NULL,
  `kondisi_sarang` ENUM('baik','sedang','buruk') NOT NULL,
  `kebersihan` ENUM('baik','sedang','buruk') NOT NULL,
  `populasi_walet` INT(11) DEFAULT 0 COMMENT 'Estimasi populasi (ekor)',
  `suhu` DECIMAL(5,2) DEFAULT NULL COMMENT 'Suhu rata-rata (°C)',
  `kelembaban` DECIMAL(5,2) DEFAULT NULL COMMENT 'Kelembaban rata-rata (%)',
  -- Field baru untuk industri walet
  `fase_sarang` ENUM('kosong','pembentukan','bertelur','menetas','piyik','siap_panen') DEFAULT 'kosong' COMMENT 'Fase biologis sarang',
  `cahaya_lux` DECIMAL(5,2) DEFAULT NULL COMMENT 'Pengukuran cahaya (max 0.5 lux)',
  `ketinggian_sarang_cm` DECIMAL(6,2) DEFAULT NULL,
  `suhu_per_lantai` JSON DEFAULT NULL COMMENT 'Suhu per lantai (multi-lantai)',
  `kelembaban_per_lantai` JSON DEFAULT NULL,
  `humidifier_status` VARCHAR(255) DEFAULT NULL COMMENT 'Catatan kondisi humidifier',
  `audio_player_status` VARCHAR(255) DEFAULT NULL COMMENT 'Catatan jam nyala, jenis suara, kondisi speaker',
  `foto_inspeksi` VARCHAR(255) DEFAULT NULL COMMENT 'Path upload foto (multi-pisah dengan ;)',
  `signature_petugas` VARCHAR(255) DEFAULT NULL COMMENT 'Tanda tangan digital untuk audit trail',
  `catatan` TEXT,
  `status` ENUM('baik','sedang','buruk') NOT NULL DEFAULT 'baik' COMMENT 'Otomatis dari kondisi terburuk',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `rumah_walet_id` (`rumah_walet_id`),
  KEY `petugas_id` (`petugas_id`),
  KEY `idx_rumah_tanggal` (`rumah_walet_id`, `tanggal_inspeksi`),
  KEY `idx_tanggal` (`tanggal_inspeksi`),
  KEY `idx_deleted` (`deleted_at`),
  CONSTRAINT `fk_inspeksi_rumah` FOREIGN KEY (`rumah_walet_id`) REFERENCES `rumah_walet` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_inspeksi_petugas` FOREIGN KEY (`petugas_id`) REFERENCES `petugas` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -------------------------------------------------------------------
-- TABLE: predator_inspeksi (Pencatatan predator terstruktur - ganti CSV)
-- -------------------------------------------------------------------
DROP TABLE IF EXISTS `predator_inspeksi`;
CREATE TABLE `predator_inspeksi` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `inspeksi_id` INT(11) UNSIGNED NOT NULL,
  `jenis_predator` ENUM('cicak','tikus','semut','kecoak','laba_laba','kelelawar','lainnya') NOT NULL,
  `tingkat_infestasi` ENUM('ringan','sedang','berat') NOT NULL DEFAULT 'ringan',
  `lokasi` VARCHAR(100) DEFAULT NULL COMMENT 'Lantai 1 / lantai 2 / area luar',
  `tindakan` VARCHAR(255) DEFAULT NULL COMMENT 'Tindakan pengendalian yang dilakukan',
  `tgl_tindakan` DATE DEFAULT NULL,
  `tgl_follow_up` DATE DEFAULT NULL,
  `hasil_follow_up` ENUM('pending','berhasil','gagal','sebagian') DEFAULT 'pending',
  `catatan` TEXT,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `inspeksi_id` (`inspeksi_id`),
  KEY `idx_jenis` (`jenis_predator`),
  CONSTRAINT `fk_pi_inspeksi` FOREIGN KEY (`inspeksi_id`) REFERENCES `inspeksi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -------------------------------------------------------------------
-- TABLE: audio_walet (Pencatatan audio - faktor #1 penarik walet)
-- -------------------------------------------------------------------
DROP TABLE IF EXISTS `audio_walet`;
CREATE TABLE `audio_walet` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `rumah_walet_id` INT(11) UNSIGNED NOT NULL,
  `tanggal` DATE NOT NULL,
  `jenis_suara` ENUM('panggilan_dewasa','panggilan_piyik','suara_sarang','kombinasi') NOT NULL DEFAULT 'panggilan_dewasa',
  `jam_nyala` TIME NOT NULL DEFAULT '05:00:00',
  `jam_mati` TIME NOT NULL DEFAULT '19:00:00',
  `volume` INT(11) DEFAULT 70 COMMENT 'Volume (0-100)',
  `kondisi_speaker` ENUM('baik','rusak_sebagian','rusak_total') DEFAULT 'baik',
  `jumlah_speaker_aktif` INT(11) DEFAULT 0,
  `kondisi_amplifier` ENUM('baik','rusak') DEFAULT 'baik',
  `catatan` TEXT,
  `input_by` INT(11) UNSIGNED DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `rumah_walet_id` (`rumah_walet_id`),
  KEY `idx_rumah_tanggal` (`rumah_walet_id`, `tanggal`),
  KEY `idx_deleted` (`deleted_at`),
  CONSTRAINT `fk_audio_rumah` FOREIGN KEY (`rumah_walet_id`) REFERENCES `rumah_walet` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_audio_input_by` FOREIGN KEY (`input_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -------------------------------------------------------------------
-- TABLE: jadwal_panen
-- -------------------------------------------------------------------
DROP TABLE IF EXISTS `jadwal_panen`;
CREATE TABLE `jadwal_panen` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `rumah_walet_id` INT(11) UNSIGNED NOT NULL,
  `tanggal_rencana` DATE NOT NULL,
  `periode` VARCHAR(7) NOT NULL COMMENT 'YYYY-MM',
  `estimasi_hasil_kg` DECIMAL(8,2) DEFAULT NULL,
  `jenis_panen_rencana` ENUM('urat','sarang_utuh','kecil','campuran') DEFAULT 'campuran',
  `catatan` TEXT,
  `status` ENUM('terjadwal','selesai','ditunda','batal') NOT NULL DEFAULT 'terjadwal',
  `hasil_panen_id` INT(11) UNSIGNED DEFAULT NULL COMMENT 'Link ke hasil_panen setelah selesai',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `rumah_walet_id` (`rumah_walet_id`),
  KEY `idx_status_tanggal` (`status`, `tanggal_rencana`),
  KEY `idx_periode` (`periode`),
  KEY `idx_deleted` (`deleted_at`),
  CONSTRAINT `fk_jadwal_rumah` FOREIGN KEY (`rumah_walet_id`) REFERENCES `rumah_walet` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -------------------------------------------------------------------
-- TABLE: hasil_panen
-- -------------------------------------------------------------------
DROP TABLE IF EXISTS `hasil_panen`;
CREATE TABLE `hasil_panen` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `jadwal_panen_id` INT(11) UNSIGNED DEFAULT NULL,
  `rumah_walet_id` INT(11) UNSIGNED NOT NULL,
  `petugas_id` INT(11) UNSIGNED NOT NULL,
  `tanggal_panen` DATE NOT NULL,
  `periode` VARCHAR(7) NOT NULL COMMENT 'YYYY-MM',
  `grade` ENUM('A','B','C') NOT NULL,
  `jenis_panen` ENUM('urat','sarang_utuh','kecil') NOT NULL DEFAULT 'sarang_utuh',
  `berat_kg` DECIMAL(8,3) NOT NULL COMMENT 'Berat yang dicatat ke sistem (bisa basah atau kering, lihat status_pengeringan)',
  `berat_basah_kg` DECIMAL(8,3) DEFAULT NULL COMMENT 'Berat sebelum dikeringkan (shrinkage 30-40%)',
  `berat_kering_kg` DECIMAL(8,3) DEFAULT NULL COMMENT 'Berat setelah dikeringkan - ini yang dijual',
  `kadar_air_pct` DECIMAL(5,2) DEFAULT NULL COMMENT 'Hasil lab (standar ≤ 15%)',
  `kadar_kotoran_pct` DECIMAL(5,2) DEFAULT NULL COMMENT 'Hasil lab (standar ≤ 5%)',
  `no_batch` VARCHAR(50) DEFAULT NULL COMMENT 'Nomor batch/lot untuk traceability',
  `harga_per_kg` DECIMAL(12,2) NOT NULL,
  `total_nilai` DECIMAL(15,2) GENERATED ALWAYS AS (berat_kg * harga_per_kg) STORED,
  `status_pengeringan` ENUM('basah','proses','kering') DEFAULT 'basah',
  `status_stok` ENUM('di_gudang_rw','di_gudang_pusat','terjual','hilang') DEFAULT 'di_gudang_rw',
  `pembeli_id` INT(11) UNSIGNED DEFAULT NULL COMMENT 'FK ke penjualan jika langsung dijual',
  `sertifikat_mutu` VARCHAR(255) DEFAULT NULL,
  `kualitas` VARCHAR(255) DEFAULT NULL,
  `catatan` TEXT,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `rumah_walet_id` (`rumah_walet_id`),
  KEY `petugas_id` (`petugas_id`),
  KEY `jadwal_panen_id` (`jadwal_panen_id`),
  KEY `idx_tanggal_panen` (`tanggal_panen`),
  KEY `idx_periode_grade` (`periode`, `grade`),
  KEY `idx_jenis_panen` (`jenis_panen`),
  KEY `idx_status_stok` (`status_stok`),
  KEY `idx_deleted` (`deleted_at`),
  CONSTRAINT `fk_panen_rumah` FOREIGN KEY (`rumah_walet_id`) REFERENCES `rumah_walet` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_panen_petugas` FOREIGN KEY (`petugas_id`) REFERENCES `petugas` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_panen_jadwal` FOREIGN KEY (`jadwal_panen_id`) REFERENCES `jadwal_panen` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -------------------------------------------------------------------
-- TABLE: pengeluaran
-- -------------------------------------------------------------------
DROP TABLE IF EXISTS `pengeluaran`;
CREATE TABLE `pengeluaran` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tanggal` DATE NOT NULL,
  `rumah_walet_id` INT(11) UNSIGNED DEFAULT NULL COMMENT 'NULL = pengeluaran umum (gaji akan auto-alokasi)',
  `kategori` ENUM('maintenance','gaji','listrik','peralatan','stimulan_aroma','audio','sertifikasi','transportasi','pajak_retribusi','renovasi_besar','lainnya') NOT NULL,
  `keterangan` TEXT NOT NULL,
  `jumlah` DECIMAL(15,2) NOT NULL,
  `bukti` VARCHAR(255) DEFAULT NULL,
  `input_by` INT(11) UNSIGNED DEFAULT NULL,
  -- Approval flow
  `approval_status` ENUM('draft','pending','approved','rejected','auto_approved') NOT NULL DEFAULT 'auto_approved',
  `approved_by` INT(11) UNSIGNED DEFAULT NULL,
  `approval_date` DATETIME DEFAULT NULL,
  `approval_note` TEXT,
  `vendor_id` INT(11) UNSIGNED DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `rumah_walet_id` (`rumah_walet_id`),
  KEY `input_by` (`input_by`),
  KEY `idx_tanggal_kategori` (`tanggal`, `kategori`),
  KEY `idx_approval_status` (`approval_status`),
  KEY `idx_deleted` (`deleted_at`),
  CONSTRAINT `fk_peng_rumah` FOREIGN KEY (`rumah_walet_id`) REFERENCES `rumah_walet` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_peng_input_by` FOREIGN KEY (`input_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_peng_approved_by` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -------------------------------------------------------------------
-- TABLE: pengeluaran_alokasi (Auto-alokasi gaji per RW)
-- -------------------------------------------------------------------
DROP TABLE IF EXISTS `pengeluaran_alokasi`;
CREATE TABLE `pengeluaran_alokasi` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `pengeluaran_id` INT(11) UNSIGNED NOT NULL,
  `rumah_walet_id` INT(11) UNSIGNED NOT NULL,
  `jumlah_alokasi` DECIMAL(15,2) NOT NULL,
  `persentase` DECIMAL(5,2) NOT NULL COMMENT 'Persentase alokasi (0-100)',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `pengeluaran_id` (`pengeluaran_id`),
  KEY `rumah_walet_id` (`rumah_walet_id`),
  CONSTRAINT `fk_pa_pengeluaran` FOREIGN KEY (`pengeluaran_id`) REFERENCES `pengeluaran` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_pa_rumah` FOREIGN KEY (`rumah_walet_id`) REFERENCES `rumah_walet` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -------------------------------------------------------------------
-- TABLE: harga_grade (Master harga per grade per periode)
-- -------------------------------------------------------------------
DROP TABLE IF EXISTS `harga_grade`;
CREATE TABLE `harga_grade` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `grade` ENUM('A','B','C') NOT NULL,
  `jenis_panen` ENUM('urat','sarang_utuh','kecil') NOT NULL DEFAULT 'sarang_utuh',
  `periode` VARCHAR(7) NOT NULL COMMENT 'YYYY-MM',
  `harga_min` DECIMAL(12,2) NOT NULL,
  `harga_max` DECIMAL(12,2) NOT NULL,
  `harga_default` DECIMAL(12,2) NOT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_grade_jenis_periode` (`grade`, `jenis_panen`, `periode`),
  KEY `idx_periode` (`periode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -------------------------------------------------------------------
-- TABLE: penjualan (Invoice - kas riil, bukan estimasi)
-- -------------------------------------------------------------------
DROP TABLE IF EXISTS `penjualan`;
CREATE TABLE `penjualan` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `no_invoice` VARCHAR(50) NOT NULL,
  `tanggal` DATE NOT NULL,
  `pembeli_nama` VARCHAR(150) NOT NULL,
  `pembeli_kontak` VARCHAR(255) DEFAULT NULL COMMENT 'No HP / email / alamat',
  `pembeli_alamat` TEXT,
  `total_berat_kg` DECIMAL(8,3) NOT NULL DEFAULT 0,
  `total_nilai` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `status_bayar` ENUM('belum_bayar','dp','lunas') NOT NULL DEFAULT 'belum_bayar',
  `tanggal_bayar` DATE DEFAULT NULL,
  `metode_bayar` VARCHAR(50) DEFAULT NULL,
  `catatan` TEXT,
  `input_by` INT(11) UNSIGNED DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `no_invoice` (`no_invoice`),
  KEY `idx_tanggal` (`tanggal`),
  KEY `idx_status_bayar` (`status_bayar`),
  KEY `idx_deleted` (`deleted_at`),
  CONSTRAINT `fk_penjualan_input_by` FOREIGN KEY (`input_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -------------------------------------------------------------------
-- TABLE: penjualan_detail (Item per invoice - link ke hasil_panen)
-- -------------------------------------------------------------------
DROP TABLE IF EXISTS `penjualan_detail`;
CREATE TABLE `penjualan_detail` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `penjualan_id` INT(11) UNSIGNED NOT NULL,
  `hasil_panen_id` INT(11) UNSIGNED DEFAULT NULL,
  `rumah_walet_id` INT(11) UNSIGNED DEFAULT NULL COMMENT 'Snapshot RW saat transaksi',
  `grade` ENUM('A','B','C') NOT NULL,
  `jenis_panen` ENUM('urat','sarang_utuh','kecil') NOT NULL DEFAULT 'sarang_utuh',
  `berat_kg` DECIMAL(8,3) NOT NULL,
  `harga_per_kg` DECIMAL(12,2) NOT NULL,
  `subtotal` DECIMAL(15,2) GENERATED ALWAYS AS (berat_kg * harga_per_kg) STORED,
  `catatan` VARCHAR(255) DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `penjualan_id` (`penjualan_id`),
  KEY `hasil_panen_id` (`hasil_panen_id`),
  CONSTRAINT `fk_pd_penjualan` FOREIGN KEY (`penjualan_id`) REFERENCES `penjualan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_pd_hasil_panen` FOREIGN KEY (`hasil_panen_id`) REFERENCES `hasil_panen` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -------------------------------------------------------------------
-- TABLE: stok_sarang (Inventory tracking)
-- -------------------------------------------------------------------
DROP TABLE IF EXISTS `stok_sarang`;
CREATE TABLE `stok_sarang` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `hasil_panen_id` INT(11) UNSIGNED NOT NULL,
  `rumah_walet_id` INT(11) UNSIGNED NOT NULL,
  `grade` ENUM('A','B','C') NOT NULL,
  `jenis_panen` ENUM('urat','sarang_utuh','kecil') NOT NULL,
  `berat_kg` DECIMAL(8,3) NOT NULL,
  `lokasi_gudang` ENUM('gudang_rw','gudang_pusat') NOT NULL DEFAULT 'gudang_rw',
  `tanggal_masuk` DATE NOT NULL,
  `tanggal_keluar` DATE DEFAULT NULL,
  `penjualan_id` INT(11) UNSIGNED DEFAULT NULL COMMENT 'Link ke penjualan jika sudah terjual',
  `status_stok` ENUM('tersedia','terjual','pindah_gudang','hilang') NOT NULL DEFAULT 'tersedia',
  `catatan` TEXT,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hasil_panen_id` (`hasil_panen_id`),
  KEY `rumah_walet_id` (`rumah_walet_id`),
  KEY `idx_status_stok` (`status_stok`),
  KEY `idx_lokasi` (`lokasi_gudang`),
  KEY `idx_deleted` (`deleted_at`),
  CONSTRAINT `fk_ss_hasil_panen` FOREIGN KEY (`hasil_panen_id`) REFERENCES `hasil_panen` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_ss_rumah` FOREIGN KEY (`rumah_walet_id`) REFERENCES `rumah_walet` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_ss_penjualan` FOREIGN KEY (`penjualan_id`) REFERENCES `penjualan` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -------------------------------------------------------------------
-- TABLE: login_attempts (Rate limiting brute force)
-- -------------------------------------------------------------------
DROP TABLE IF EXISTS `login_attempts`;
CREATE TABLE `login_attempts` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ip_address` VARCHAR(45) NOT NULL,
  `username` VARCHAR(50) NOT NULL,
  `attempt_count` INT(11) NOT NULL DEFAULT 1,
  `last_attempt` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `locked_until` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_ip_username` (`ip_address`, `username`),
  KEY `idx_locked_until` (`locked_until`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

SET FOREIGN_KEY_CHECKS = 1;

-- ===================================================================
-- SEED DATA
-- ===================================================================

-- Generate fresh bcrypt hashes for default passwords
-- admin123, petugas123, owner123 (will be forced to change on first login)

INSERT INTO `users` (`nama`, `username`, `password`, `email`, `role`, `status`, `must_change_password`) VALUES
('Administrator', 'admin', '$2y$10$N9qo8uLOickgx2ZMRZoMy.MQDqp7VqK0xLwQqLlKQvXjZQvYjJfKm', 'admin@walet-monitoring.test', 'admin', 'aktif', 1),
('Budi Santoso', 'petugas', '$2y$10$N9qo8uLOickgx2ZMRZoMy.MQDqp7VqK0xLwQqLlKQvXjJfKm', 'petugas@walet-monitoring.test', 'petugas', 'aktif', 1),
('Harto Wijaya', 'owner', '$2y$10$N9qo8uLOickgx2ZMRZoMy.MQDqp7VqK0xLwQqLlKQvXjJfKm', 'owner@walet-monitoring.test', 'owner', 'aktif', 1);

-- Catatan: hash di atas adalah placeholder. Jalankan `php spark db:seed InitialSeeder`
-- untuk generate hash valid (password_verify tidak akan match dengan placeholder ini).

-- Rumah walet
INSERT INTO `rumah_walet` (`kode`, `nama`, `lokasi`, `latitude`, `longitude`, `luas`, `jumlah_lantai`, `tahun_dibangun`, `jenis_bangunan`, `kapasitas_panen_kg`, `kapasitas_bulan_01_kg`, `kapasitas_bulan_02_kg`, `kapasitas_bulan_03_kg`, `kapasitas_bulan_04_kg`, `kapasitas_bulan_05_kg`, `kapasitas_bulan_06_kg`, `kapasitas_bulan_07_kg`, `kapasitas_bulan_08_kg`, `kapasitas_bulan_09_kg`, `kapasitas_bulan_10_kg`, `kapasitas_bulan_11_kg`, `kapasitas_bulan_12_kg`, `jumlah_speaker`, `humidifier_count`, `tanggal_berdiri`, `status_kepemilikan`, `kondisi`, `status`) VALUES
('RW-001', 'Rumah Walet Pelaihari', 'Jl. Merpati No. 12, Pelaihari, Tanah Laut', -3.7925, 114.8194, 200.00, 3, 2018, 'rumah_khusus_walet', 8.50, 4.00, 4.00, 12.00, 12.00, 6.00, 6.00, 14.00, 18.00, 14.00, 6.00, 8.00, 6.00, 24, 4, '2018-03-15', 'milik_sendiri', 'baik', 'aktif'),
('RW-002', 'Rumah Walet Banjarbaru', 'Jl. Walet Indah No. 5, Banjarbaru', -3.4525, 114.8575, 150.00, 2, 2020, 'rumah_khusus_walet', 6.00, 3.00, 3.00, 8.00, 8.00, 4.00, 4.00, 10.00, 12.00, 10.00, 4.00, 6.00, 4.00, 16, 2, '2020-07-20', 'milik_sendiri', 'baik', 'aktif'),
('RW-003', 'Rumah Walet Martapura', 'Jl. Cempaka No. 8, Martapura, Banjar', -3.4294, 114.8489, 180.00, 3, 2017, 'rumah_khusus_walet', 7.50, 3.50, 3.50, 10.00, 10.00, 5.00, 5.00, 12.00, 15.00, 12.00, 5.00, 7.00, 5.00, 20, 3, '2017-05-10', 'milik_sendiri', 'sedang', 'aktif');

-- Petugas
INSERT INTO `petugas` (`nip`, `nama`, `jenis_kelamin`, `tempat_lahir`, `tanggal_lahir`, `alamat`, `no_hp`, `email`, `tanggal_masuk`, `user_id`, `status`) VALUES
('P-001', 'Budi Santoso', 'L', 'Banjarmasin', '1985-03-15', 'Jl. Ahmad Yani No. 12, Banjarmasin', '081234567890', 'budi@walet.test', '2020-01-15', 2, 'aktif'),
('P-002', 'Siti Aminah', 'P', 'Banjarbaru', '1990-07-22', 'Jl. Merdeka No. 5, Banjarbaru', '082345678901', 'siti@walet.test', '2021-03-01', NULL, 'aktif'),
('P-003', 'Joko Susilo', 'L', 'Martapura', '1988-11-10', 'Jl. Cempaka No. 3, Martapura', '083456789012', 'joko@walet.test', '2019-06-15', NULL, 'aktif');

-- Penugasan petugas ke RW
INSERT INTO `petugas_rumah` (`petugas_id`, `rumah_walet_id`, `tanggal_mulai`, `catatan`) VALUES
(1, 1, '2020-01-20', 'Petugas utama RW-001'),
(1, 2, '2020-01-20', 'Petugas backup RW-002'),
(2, 2, '2021-03-05', 'Petugas utama RW-002'),
(3, 3, '2019-06-20', 'Petugas utama RW-003');

-- Inspeksi
INSERT INTO `inspeksi` (`rumah_walet_id`, `petugas_id`, `tanggal_inspeksi`, `kondisi_bangunan`, `kondisi_sarang`, `kebersihan`, `populasi_walet`, `suhu`, `kelembaban`, `fase_sarang`, `cahaya_lux`, `audio_player_status`, `catatan`, `status`) VALUES
(1, 1, '2025-05-10', 'baik', 'baik', 'baik', 800, 28.50, 85.00, 'siap_panen', 0.30, 'Audio normal, 05:00-19:00, semua speaker aktif', 'Kondisi prima, siap panen akhir bulan', 'baik'),
(2, 2, '2025-05-12', 'baik', 'sedang', 'baik', 500, 29.00, 82.00, 'bertelur', 0.45, 'Speaker lantai 2 perlu cek, volume 70%', 'Sarang fase bertelur, jangan ganggu 2 minggu', 'sedang'),
(3, 3, '2025-05-15', 'sedang', 'sedang', 'sedang', 600, 30.00, 80.00, 'pembentukan', 0.50, 'Audio normal, humidifier perlu tambah', 'Renovasi ventilasi lantai 1', 'sedang');

-- Predator in speksi
INSERT INTO `predator_inspeksi` (`inspeksi_id`, `jenis_predator`, `tingkat_infestasi`, `lokasi`, `tindakan`, `tgl_tindakan`, `tgl_follow_up`, `hasil_follow_up`, `catatan`) VALUES
(1, 'semut', 'ringan', 'lantai 1', 'Semprot anti-semut organik', '2025-05-10', '2025-05-17', 'berhasil', 'Tidak ada semut setelah tindakan'),
(2, 'cicak', 'sedang', 'lantai 2', 'Pasang trap cicak, lubang ventilasi ditutup kawat', '2025-05-12', '2025-05-19', 'sebagian', 'Masih ada cicak, perlu tindakan lanjut'),
(3, 'tikus', 'ringan', 'area luar', 'Pasang umpan racun tikus di area luar', '2025-05-15', '2025-05-22', 'pending', 'Menunggu hasil follow up');

-- Audio walet log
INSERT INTO `audio_walet` (`rumah_walet_id`, `tanggal`, `jenis_suara`, `jam_nyala`, `jam_mati`, `volume`, `kondisi_speaker`, `jumlah_speaker_aktif`, `kondisi_amplifier`, `catatan`, `input_by`) VALUES
(1, '2025-05-01', 'panggilan_dewasa', '05:00:00', '19:00:00', 75, 'baik', 24, 'baik', 'Audio panggilan dewasa, semua speaker aktif', 2),
(2, '2025-05-01', 'kombinasi', '05:00:00', '19:00:00', 70, 'rusak_sebagian', 14, 'baik', '2 speaker lantai 2 rusak, sudah diganti', 2),
(3, '2025-05-01', 'panggilan_piyik', '05:30:00', '19:00:00', 65, 'baik', 20, 'baik', 'Coba ganti ke panggilan piyik untuk tarik piyik baru', 2);

-- Jadwal panen
INSERT INTO `jadwal_panen` (`rumah_walet_id`, `tanggal_rencana`, `periode`, `estimasi_hasil_kg`, `jenis_panen_rencana`, `catatan`, `status`) VALUES
(1, '2025-05-25', '2025-05', 8.50, 'sarang_utuh', 'Panen rutin bulanan', 'terjadwal'),
(2, '2025-06-15', '2025-06', 6.00, 'sarang_utuh', 'Panen rutin', 'terjadwal'),
(3, '2025-07-10', '2025-07', 7.00, 'sarang_utuh', 'Panen peak season Jul-Sep', 'terjadwal');

-- Harga grade master
INSERT INTO `harga_grade` (`grade`, `jenis_panen`, `periode`, `harga_min`, `harga_max`, `harga_default`) VALUES
('A', 'urat', '2025-05', 18000000, 25000000, 22000000),
('A', 'sarang_utuh', '2025-05', 13000000, 18000000, 15000000),
('A', 'kecil', '2025-05', 10000000, 13000000, 12000000),
('B', 'urat', '2025-05', 12000000, 15000000, 13000000),
('B', 'sarang_utuh', '2025-05', 8000000, 12000000, 10000000),
('B', 'kecil', '2025-05', 5000000, 8000000, 6000000),
('C', 'urat', '2025-05', 6000000, 8000000, 7000000),
('C', 'sarang_utuh', '2025-05', 4000000, 6000000, 5000000),
('C', 'kecil', '2025-05', 2500000, 4000000, 3000000);

-- Hasil panen
INSERT INTO `hasil_panen` (`jadwal_panen_id`, `rumah_walet_id`, `petugas_id`, `tanggal_panen`, `periode`, `grade`, `jenis_panen`, `berat_kg`, `berat_basah_kg`, `berat_kering_kg`, `harga_per_kg`, `status_pengeringan`, `status_stok`, `kualitas`, `catatan`) VALUES
(1, 1, 1, '2025-05-25', '2025-05', 'A', 'sarang_utuh', 2.100, 3.000, 2.100, 15000000, 'kering', 'di_gudang_rw', 'Sarang utuh, kualitas premium', 'Panen rutin'),
(1, 1, 1, '2025-05-25', '2025-05', 'B', 'sarang_utuh', 1.800, 2.500, 1.800, 10000000, 'kering', 'di_gudang_rw', 'Sedikit pecah, masih bagus', 'Panen rutin'),
(1, 1, 1, '2025-05-25', '2025-05', 'C', 'sarang_utuh', 0.950, 1.400, 0.950, 5000000, 'kering', 'di_gudang_rw', 'Pecahan kecil', 'Panen rutin');

-- Pengeluaran (maintenance, listrik = auto_approved; gaji = pending alokasi)
INSERT INTO `pengeluaran` (`tanggal`, `rumah_walet_id`, `kategori`, `keterangan`, `jumlah`, `input_by`, `approval_status`) VALUES
('2025-04-01', NULL, 'gaji', 'Gaji petugas April 2025', 12000000, 1, 'auto_approved'),
('2025-05-01', NULL, 'gaji', 'Gaji petugas Mei 2025', 12000000, 1, 'auto_approved'),
('2025-05-05', 1, 'maintenance', 'Ganti speaker lantai 2', 2500000, 1, 'auto_approved'),
('2025-05-10', 1, 'listrik', 'Listrik Mei 2025', 1500000, 1, 'auto_approved'),
('2025-05-15', 2, 'stimulan_aroma', 'Beli stimulan aroma walet', 800000, 1, 'auto_approved'),
('2025-05-20', 1, 'maintenance', 'Renovasi ventilasi lantai 1', 7500000, 1, 'pending'),
('2025-06-01', NULL, 'gaji', 'Gaji petugas Juni 2025', 12000000, 1, 'auto_approved');

-- Penjualan (kas riil)
INSERT INTO `penjualan` (`no_invoice`, `tanggal`, `pembeli_nama`, `pembeli_kontak`, `total_berat_kg`, `total_nilai`, `status_bayar`, `tanggal_bayar`, `metode_bayar`, `input_by`) VALUES
('INV-2025-001', '2025-06-10', 'CV Sarang Mas', '081200011122 / Jl. Cempaka No. 1, Banjarmasin', 2.100, 31500000, 'lunas', '2025-06-12', 'transfer', 1),
('INV-2025-002', '2025-06-20', 'Toko Walet Jaya', '081300022233', 1.800, 18000000, 'dp', NULL, 'transfer', 1);

INSERT INTO `penjualan_detail` (`penjualan_id`, `hasil_panen_id`, `rumah_walet_id`, `grade`, `jenis_panen`, `berat_kg`, `harga_per_kg`, `catatan`) VALUES
(1, 1, 1, 'A', 'sarang_utuh', 2.100, 15000000, 'Grade A premium'),
(2, 2, 1, 'B', 'sarang_utuh', 1.800, 10000000, 'Grade B');

-- Update hasil_panen.pembeli_id dan status_stok
UPDATE `hasil_panen` SET `pembeli_id` = 1, `status_stok` = 'terjual' WHERE `id` = 1;
UPDATE `hasil_panen` SET `pembeli_id` = 2, `status_stok` = 'terjual' WHERE `id` = 2;

-- Stok sarang (yang belum terjual)
INSERT INTO `stok_sarang` (`hasil_panen_id`, `rumah_walet_id`, `grade`, `jenis_panen`, `berat_kg`, `lokasi_gudang`, `tanggal_masuk`, `status_stok`) VALUES
(3, 1, 'C', 'sarang_utuh', 0.950, 'gudang_rw', '2025-05-25', 'tersedia');

-- ===================================================================
-- INDEXES FOR PERFORMANCE (P1-5)
-- ===================================================================
-- Indexes sudah ditambahkan di CREATE TABLE di atas. Summary:
-- hasil_panen: idx_tanggal_panen, idx_periode_grade, idx_jenis_panen, idx_status_stok
-- inspeksi: idx_rumah_tanggal, idx_tanggal
-- pengeluaran: idx_tanggal_kategori, idx_approval_status
-- jadwal_panen: idx_status_tanggal, idx_periode
-- penjualan: idx_tanggal, idx_status_bayar
-- stok_sarang: idx_status_stok, idx_lokasi
-- users: idx_role
-- Semua tabel utama: idx_deleted (untuk soft delete)
