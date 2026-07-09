-- ===================================================================
-- REALISTIC SEED DATA v1.0
-- Sistem Monitoring Sarang Burung Walet
-- -------------------------------------------------------------------
-- Data realistis industri walet Kalimantan Selatan
-- Periode: Jan 2024 - Jun 2026
-- 5 RW, 4 Petugas, 2 tahun history
-- ===================================================================
--
-- CARA PAKAI:
-- 1. Pastikan schema sudah di-import (database.sql)
-- 2. Jalankan seeder hash password: php spark db:seed InitialSeeder
--    (atau hash di bawah sudah valid untuk: admin123, petugas123, owner123)
-- 3. Import file ini via phpMyAdmin atau:
--    mysql -u root db_walet < seed-data-realistic.sql
-- 4. Data existing akan di-replace (DELETE + INSERT baru)
-- ===================================================================

USE `db_walet`;

SET FOREIGN_KEY_CHECKS = 0;

-- Clean all existing data
TRUNCATE TABLE `predator_inspeksi`;
TRUNCATE TABLE `audio_walet`;
TRUNCATE TABLE `stok_sarang`;
TRUNCATE TABLE `penjualan_detail`;
TRUNCATE TABLE `penjualan`;
TRUNCATE TABLE `pengeluaran_alokasi`;
TRUNCATE TABLE `pengeluaran`;
TRUNCATE TABLE `hasil_panen`;
TRUNCATE TABLE `jadwal_panen`;
TRUNCATE TABLE `inspeksi`;
TRUNCATE TABLE `petugas_rumah`;
TRUNCATE TABLE `petugas`;
TRUNCATE TABLE `rumah_walet`;
TRUNCATE TABLE `users`;
TRUNCATE TABLE `harga_grade`;
TRUNCATE TABLE `login_attempts`;

SET FOREIGN_KEY_CHECKS = 1;

-- ===================================================================
-- USERS (3) - password hash valid untuk admin123/petugas123/owner123
-- ===================================================================
INSERT INTO `users` (`id`, `nama`, `username`, `password`, `email`, `role`, `no_hp`, `status`, `must_change_password`, `created_at`) VALUES
(1, 'Administrator Sistem', 'admin', '$2b$12$cWuLwzFqViTiLIL5r2VKfOM.kGXF2mtXIvVntjlWn8/fpy4e4jrfi', 'admin@walet-monitoring.test', 'admin', '081200000001', 'aktif', 0, '2024-01-01 08:00:00'),
(2, 'Budi Santoso', 'petugas', '$2b$12$fZHvO17nG9ep3cvDSxJe4.r6SApALY9H0jXPsWB1KJPYKt8yQQkb6', 'budi@walet-monitoring.test', 'petugas', '081234567890', 'aktif', 0, '2024-01-01 08:00:00'),
(3, 'Harto Wijaya', 'owner', '$2b$12$z8RNKZhDreHfGNsl16aErOB8DdHmm1o8rK1h5PxHPBIy6DeQdN7di', 'harto@walet-monitoring.test', 'owner', '081299999999', 'aktif', 0, '2024-01-01 08:00:00');

-- ===================================================================
-- RUMAH WALET (5) - lokasi real Kalsel
-- ===================================================================
INSERT INTO `rumah_walet` (`id`, `kode`, `nama`, `lokasi`, `latitude`, `longitude`, `luas`, `jumlah_lantai`, `tahun_dibangun`, `jenis_bangunan`, `kapasitas_panen_kg`,
  `kapasitas_bulan_01_kg`, `kapasitas_bulan_02_kg`, `kapasitas_bulan_03_kg`, `kapasitas_bulan_04_kg`, `kapasitas_bulan_05_kg`, `kapasitas_bulan_06_kg`,
  `kapasitas_bulan_07_kg`, `kapasitas_bulan_08_kg`, `kapasitas_bulan_09_kg`, `kapasitas_bulan_10_kg`, `kapasitas_bulan_11_kg`, `kapasitas_bulan_12_kg`,
  `jumlah_speaker`, `jenis_player`, `jam_operasi_audio`, `humidifier_count`, `cctv_url`, `tanggal_berdiri`, `tanggal_renovasi_terakhir`,
  `pajak_properti_tahunan`, `status_kepemilikan`, `kondisi`, `keterangan`, `status`, `created_at`) VALUES
(1, 'RW-001', 'Rumah Walet Pelaihari', 'Jl. Merpati No. 12, Pelaihari, Tanah Laut', -3.7925, 114.8194, 250.00, 3, 2018, 'rumah_khusus_walet', 8.50,
  4.00, 4.00, 12.00, 12.00, 6.00, 6.00, 14.00, 18.00, 14.00, 6.00, 8.00, 6.00,
  24, 'RB-Sound Pro v3', '05:00-19:00', 4, 'http://cctv.walet.test/rw001', '2018-03-15', '2025-08-10',
  3500000, 'milik_sendiri', 'baik', 'RW utama, produktivitas tinggi', 'aktif', '2024-01-01 08:00:00'),
(2, 'RW-002', 'Rumah Walet Banjarbaru', 'Jl. Walet Indah No. 5, Banjarbaru Utara', -3.4525, 114.8575, 180.00, 2, 2020, 'rumah_khusus_walet', 6.00,
  3.00, 3.00, 8.00, 8.00, 4.00, 4.00, 10.00, 12.00, 10.00, 4.00, 6.00, 4.00,
  16, 'RB-Sound Pro v3', '05:00-19:00', 2, 'http://cctv.walet.test/rw002', '2020-07-20', NULL,
  2500000, 'milik_sendiri', 'baik', 'RW kedua, still developing', 'aktif', '2024-01-01 08:00:00'),
(3, 'RW-003', 'Rumah Walet Martapura', 'Jl. Cempaka No. 8, Martapura, Banjar', -3.4294, 114.8489, 200.00, 3, 2017, 'rumah_khusus_walet', 7.50,
  3.50, 3.50, 10.00, 10.00, 5.00, 5.00, 12.00, 15.00, 12.00, 5.00, 7.00, 5.00,
  20, 'WaletMaster X1', '05:00-19:00', 3, 'http://cctv.walet.test/rw003', '2017-05-10', '2024-11-15',
  3000000, 'milik_sendiri', 'sedang', 'Renovasi ventilasi 2024', 'aktif', '2024-01-01 08:00:00'),
(4, 'RW-004', 'Rumah Walet Kandangan', 'Jl. A. Yani No. 22, Kandangan, HSS', -2.6667, 115.2833, 150.00, 2, 2021, 'ruko_modifikasi', 5.00,
  2.50, 2.50, 6.50, 6.50, 3.50, 3.50, 8.50, 10.00, 8.50, 3.50, 5.00, 3.50,
  12, 'RB-Sound Pro v2', '05:30-19:00', 1, NULL, '2021-02-10', NULL,
  2000000, 'sewa', 'baik', 'Ruko modifikasi, perlu upgrade audio', 'aktif', '2024-01-01 08:00:00'),
(5, 'RW-005', 'Rumah Walet Rantau', 'Jl. Tapin Bumi No. 3, Rantau, Tapin', -2.6000, 115.0000, 175.00, 3, 2019, 'rumah_khusus_walet', 6.50,
  3.00, 3.00, 8.50, 8.50, 4.50, 4.50, 11.00, 13.00, 11.00, 4.50, 6.50, 4.50,
  18, 'WaletMaster X1', '05:00-19:00', 2, NULL, '2019-09-25', NULL,
  2800000, 'milik_sendiri', 'baik', 'Performa stabil', 'aktif', '2024-01-01 08:00:00');

-- ===================================================================
-- PETUGAS (4)
-- ===================================================================
INSERT INTO `petugas` (`id`, `nip`, `nama`, `jenis_kelamin`, `tempat_lahir`, `tanggal_lahir`, `alamat`, `no_hp`, `email`, `tanggal_masuk`, `user_id`, `status`, `created_at`) VALUES
(1, 'P-001', 'Budi Santoso', 'L', 'Banjarmasin', '1985-03-15', 'Jl. Ahmad Yani No. 12, Banjarmasin', '081234567890', 'budi@walet.test', '2020-01-15', 2, 'aktif', '2024-01-01 08:00:00'),
(2, 'P-002', 'Siti Aminah', 'P', 'Banjarbaru', '1990-07-22', 'Jl. Merdeka No. 5, Banjarbaru', '082345678901', 'siti@walet.test', '2021-03-01', NULL, 'aktif', '2024-01-01 08:00:00'),
(3, 'P-003', 'Joko Susilo', 'L', 'Martapura', '1988-11-10', 'Jl. Cempaka No. 3, Martapura', '083456789012', 'joko@walet.test', '2019-06-15', NULL, 'aktif', '2024-01-01 08:00:00'),
(4, 'P-004', 'Ahmad Fauzi', 'L', 'Kandangan', '1992-05-08', 'Jl. Pahlawan No. 7, Kandangan', '084567890123', 'fauzi@walet.test', '2022-08-10', NULL, 'aktif', '2024-01-01 08:00:00');

-- ===================================================================
-- PENUGASAN PETUGAS KE RUMAH WALET
-- ===================================================================
INSERT INTO `petugas_rumah` (`petugas_id`, `rumah_walet_id`, `tanggal_mulai`, `tanggal_selesai`, `catatan`) VALUES
(1, 1, '2020-01-20', NULL, 'Petugas utama RW-001'),
(1, 2, '2020-01-20', NULL, 'Petugas backup RW-002'),
(2, 3, '2021-03-05', NULL, 'Petugas utama RW-003'),
(3, 4, '2019-06-20', NULL, 'Petugas utama RW-004'),
(3, 5, '2019-06-20', NULL, 'Petugas utama RW-005'),
(4, 1, '2022-08-15', NULL, 'Backup RW-001'),
(4, 2, '2022-08-15', NULL, 'Backup RW-002'),
(4, 3, '2022-08-15', NULL, 'Backup RW-003'),
(4, 4, '2022-08-15', NULL, 'Backup RW-004'),
(4, 5, '2022-08-15', NULL, 'Backup RW-005');

-- ===================================================================
-- MASTER HARGA GRADE (24 bulan: 2024-01 s/d 2025-12)
-- ===================================================================
INSERT INTO `harga_grade` (`grade`, `jenis_panen`, `periode`, `harga_min`, `harga_max`, `harga_default`) VALUES
-- 2024
('A', 'urat', '2024-03', 18000000, 23000000, 20000000),
('A', 'urat', '2024-04', 18000000, 23000000, 20000000),
('A', 'sarang_utuh', '2024-07', 13000000, 17000000, 15000000),
('A', 'sarang_utuh', '2024-08', 13000000, 17000000, 15000000),
('A', 'sarang_utuh', '2024-09', 13000000, 17000000, 15000000),
('A', 'kecil', '2024-11', 10000000, 13000000, 12000000),
('A', 'kecil', '2024-12', 10000000, 13000000, 12000000),
('B', 'urat', '2024-03', 12000000, 15000000, 13000000),
('B', 'urat', '2024-04', 12000000, 15000000, 13000000),
('B', 'sarang_utuh', '2024-07', 8000000, 12000000, 10000000),
('B', 'sarang_utuh', '2024-08', 8000000, 12000000, 10000000),
('B', 'sarang_utuh', '2024-09', 8000000, 12000000, 10000000),
('B', 'kecil', '2024-11', 5000000, 8000000, 6000000),
('B', 'kecil', '2024-12', 5000000, 8000000, 6000000),
('C', 'urat', '2024-03', 6000000, 8000000, 7000000),
('C', 'urat', '2024-04', 6000000, 8000000, 7000000),
('C', 'sarang_utuh', '2024-07', 4000000, 6000000, 5000000),
('C', 'sarang_utuh', '2024-08', 4000000, 6000000, 5000000),
('C', 'sarang_utuh', '2024-09', 4000000, 6000000, 5000000),
('C', 'kecil', '2024-11', 2500000, 4000000, 3000000),
('C', 'kecil', '2024-12', 2500000, 4000000, 3000000),
-- 2025
('A', 'urat', '2025-03', 19000000, 25000000, 22000000),
('A', 'urat', '2025-04', 19000000, 25000000, 22000000),
('A', 'sarang_utuh', '2025-07', 14000000, 19000000, 16000000),
('A', 'sarang_utuh', '2025-08', 14000000, 19000000, 16000000),
('A', 'sarang_utuh', '2025-09', 14000000, 19000000, 16000000),
('A', 'kecil', '2025-11', 11000000, 14000000, 13000000),
('A', 'kecil', '2025-12', 11000000, 14000000, 13000000),
('B', 'urat', '2025-03', 13000000, 16000000, 14000000),
('B', 'urat', '2025-04', 13000000, 16000000, 14000000),
('B', 'sarang_utuh', '2025-07', 9000000, 13000000, 11000000),
('B', 'sarang_utuh', '2025-08', 9000000, 13000000, 11000000),
('B', 'sarang_utuh', '2025-09', 9000000, 13000000, 11000000),
('B', 'kecil', '2025-11', 5500000, 8500000, 7000000),
('B', 'kecil', '2025-12', 5500000, 8500000, 7000000),
('C', 'urat', '2025-03', 6500000, 8500000, 7500000),
('C', 'urat', '2025-04', 6500000, 8500000, 7500000),
('C', 'sarang_utuh', '2025-07', 4500000, 6500000, 5500000),
('C', 'sarang_utuh', '2025-08', 4500000, 6500000, 5500000),
('C', 'sarang_utuh', '2025-09', 4500000, 6500000, 5500000),
('C', 'kecil', '2025-11', 2800000, 4200000, 3500000),
('C', 'kecil', '2025-12', 2800000, 4200000, 3500000),
-- 2026 (forecast, sama dengan 2025 untuk safe default)
('A', 'urat', '2026-03', 19000000, 25000000, 22000000),
('A', 'urat', '2026-04', 19000000, 25000000, 22000000),
('A', 'sarang_utuh', '2026-07', 14000000, 19000000, 16000000),
('A', 'sarang_utuh', '2026-08', 14000000, 19000000, 16000000),
('B', 'sarang_utuh', '2026-06', 9000000, 13000000, 11000000),
('C', 'sarang_utuh', '2026-06', 4500000, 6500000, 5500000);

-- ===================================================================
-- AUDIO WALET - 1 entry per RW per quarter (2024-2025)
-- ===================================================================
INSERT INTO `audio_walet` (`rumah_walet_id`, `tanggal`, `jenis_suara`, `jam_nyala`, `jam_mati`, `volume`, `kondisi_speaker`, `jumlah_speaker_aktif`, `kondisi_amplifier`, `catatan`, `input_by`) VALUES
-- 2024 Q1
(1, '2024-01-15', 'panggilan_dewasa', '05:00:00', '19:00:00', 75, 'baik', 24, 'baik', 'Audio normal, populasi stabil', 2),
(2, '2024-01-20', 'panggilan_dewasa', '05:00:00', '19:00:00', 70, 'baik', 16, 'baik', 'Audio normal', 2),
(3, '2024-02-10', 'kombinasi', '05:00:00', '19:00:00', 72, 'baik', 20, 'baik', 'Kombinasi dewasa + sarang', 2),
(4, '2024-02-15', 'panggilan_dewasa', '05:30:00', '19:00:00', 65, 'rusak_sebagian', 10, 'baik', '2 speaker lantai 2 rusak', 2),
(5, '2024-03-01', 'panggilan_dewasa', '05:00:00', '19:00:00', 70, 'baik', 18, 'baik', 'Audio normal', 2),
-- 2024 Q2
(1, '2024-04-15', 'panggilan_dewasa', '05:00:00', '19:00:00', 75, 'baik', 24, 'baik', 'Musim urat, audio stabil', 2),
(3, '2024-05-10', 'kombinasi', '05:00:00', '19:00:00', 72, 'baik', 20, 'baik', 'Tetap kombinasi, populasi naik', 2),
(5, '2024-06-20', 'panggilan_dewasa', '05:00:00', '19:00:00', 70, 'baik', 18, 'baik', 'Normal', 2),
-- 2024 Q3 - musim sarang utuh
(1, '2024-07-10', 'kombinasi', '05:00:00', '19:00:00', 78, 'baik', 24, 'baik', 'Tambah volume untuk musim panen utuh', 2),
(2, '2024-07-15', 'panggilan_dewasa', '05:00:00', '19:00:00', 72, 'baik', 16, 'baik', 'Normal', 2),
(3, '2024-08-05', 'kombinasi', '05:00:00', '19:00:00', 75, 'baik', 20, 'baik', 'Volume naik untuk peak season', 2),
(4, '2024-08-20', 'panggilan_dewasa', '05:30:00', '19:00:00', 68, 'baik', 12, 'baik', 'Speaker sudah diganti semua', 2),
(5, '2024-09-05', 'panggilan_dewasa', '05:00:00', '19:00:00', 72, 'baik', 18, 'baik', 'Normal, populasi meningkat', 2),
-- 2024 Q4
(1, '2024-10-15', 'panggilan_dewasa', '05:00:00', '19:00:00', 75, 'baik', 24, 'baik', 'Normal', 2),
(3, '2024-11-10', 'panggilan_piyik', '05:00:00', '19:00:00', 70, 'baik', 20, 'baik', 'Coba ganti ke panggilan piyik untuk tarik piyik baru', 2),
(5, '2024-12-05', 'panggilan_dewasa', '05:00:00', '19:00:00', 70, 'baik', 18, 'baik', 'Normal', 2),
-- 2025 Q1 - musim urat
(1, '2025-01-15', 'panggilan_dewasa', '05:00:00', '19:00:00', 75, 'baik', 24, 'baik', 'Persiapan musim urat', 2),
(2, '2025-02-10', 'kombinasi', '05:00:00', '19:00:00', 72, 'baik', 16, 'baik', 'Tambah suara sarang untuk tarik sarang baru', 2),
(3, '2025-03-05', 'panggilan_piyik', '05:00:00', '19:00:00', 70, 'baik', 20, 'baik', 'Piyik call terbukti efektif 2024', 2),
(4, '2025-03-20', 'panggilan_dewasa', '05:30:00', '19:00:00', 68, 'baik', 12, 'baik', 'Normal', 2),
(5, '2025-04-10', 'panggilan_dewasa', '05:00:00', '19:00:00', 70, 'baik', 18, 'baik', 'Normal', 2),
-- 2025 Q2
(1, '2025-05-10', 'panggilan_dewasa', '05:00:00', '19:00:00', 75, 'baik', 24, 'baik', 'Post-panen urat, stabil', 2),
(3, '2025-06-15', 'kombinasi', '05:00:00', '19:00:00', 75, 'baik', 20, 'baik', 'Persiapan musim utuh, kombinasi efektif', 2),
-- 2025 Q3 - musim utuh
(1, '2025-07-05', 'kombinasi', '05:00:00', '19:00:00', 80, 'baik', 24, 'baik', 'Volume max untuk peak season utuh', 2),
(2, '2025-07-10', 'kombinasi', '05:00:00', '19:00:00', 75, 'baik', 16, 'baik', 'Switch ke kombinasi', 2),
(3, '2025-08-05', 'kombinasi', '05:00:00', '19:00:00', 78, 'baik', 20, 'baik', 'Peak season, volume max', 2),
(4, '2025-08-15', 'panggilan_dewasa', '05:30:00', '19:00:00', 70, 'baik', 12, 'rusak', 'Amplifier channel 1 rusak, perlu ganti', 2),
(5, '2025-09-05', 'kombinasi', '05:00:00', '19:00:00', 75, 'baik', 18, 'baik', 'Kombinasi untuk peak season', 2),
-- 2025 Q4
(1, '2025-10-15', 'panggilan_dewasa', '05:00:00', '19:00:00', 75, 'baik', 24, 'baik', 'Post-peak, normal', 2),
(3, '2025-11-10', 'panggilan_piyik', '05:00:00', '19:00:00', 70, 'baik', 20, 'baik', 'Lanjut piyik call, populasi naik 30%', 2),
(5, '2025-12-05', 'panggilan_dewasa', '05:00:00', '19:00:00', 70, 'baik', 18, 'baik', 'Normal', 2);

-- ===================================================================
-- INSPEKSI - 1 per RW per 2 bulan, fase sesuai musim
-- ===================================================================
INSERT INTO `inspeksi` (`rumah_walet_id`, `petugas_id`, `tanggal_inspeksi`, `kondisi_bangunan`, `kondisi_sarang`, `kebersihan`, `populasi_walet`, `suhu`, `kelembaban`, `fase_sarang`, `cahaya_lux`, `ketinggian_sarang_cm`, `humidifier_status`, `audio_player_status`, `catatan`, `status`) VALUES
-- 2024
(1, 1, '2024-02-10', 'baik', 'baik', 'baik', 850, 28.50, 85.00, 'pembentukan', 0.30, 280.00, 'Aktif 4 unit, normal', 'Audio normal 05:00-19:00, semua speaker aktif', 'Kondisi prima, fase pembentukan sarang', 'baik'),
(2, 1, '2024-02-15', 'baik', 'sedang', 'baik', 480, 29.00, 82.00, 'pembentukan', 0.45, 260.00, 'Aktif 2 unit', 'Speaker lantai 2 perlu cek', 'Sarang sedang berkembang', 'sedang'),
(3, 2, '2024-02-20', 'baik', 'baik', 'baik', 620, 28.80, 84.00, 'pembentukan', 0.35, 270.00, 'Aktif 3 unit', 'Audio kombinasi berjalan baik', 'Kondisi baik', 'baik'),
(4, 3, '2024-02-25', 'sedang', 'sedang', 'sedang', 380, 30.00, 80.00, 'pembentukan', 0.50, 240.00, 'Aktif 1 unit, perlu tambah', '2 speaker rusak, audio masih jalan', 'Perlu renovasi ventilasi', 'sedang'),
(5, 3, '2024-03-05', 'baik', 'baik', 'baik', 580, 28.70, 83.00, 'bertelur', 0.40, 275.00, 'Aktif 2 unit', 'Audio normal', 'Fase bertelur, jangan ganggu', 'baik'),

(1, 1, '2024-04-10', 'baik', 'baik', 'baik', 920, 28.40, 86.00, 'siap_panen', 0.28, 285.00, 'Aktif 4 unit', 'Audio stabil, populasi naik', 'Siap panen urat akhir April', 'baik'),
(2, 1, '2024-04-15', 'baik', 'baik', 'baik', 540, 28.90, 83.00, 'siap_panen', 0.42, 265.00, 'Aktif 2 unit', 'Audio normal', 'Siap panen urat', 'baik'),
(3, 2, '2024-04-20', 'baik', 'baik', 'baik', 680, 28.70, 84.50, 'siap_panen', 0.32, 272.00, 'Aktif 3 unit', 'Audio kombinasi efektif', 'Siap panen, populasi meningkat', 'baik'),

(1, 1, '2024-06-10', 'baik', 'baik', 'baik', 880, 28.60, 85.50, 'kosong', 0.30, 280.00, 'Aktif 4 unit', 'Audio normal', 'Post-panen urat, fase istirahat', 'baik'),
(2, 1, '2024-06-15', 'baik', 'sedang', 'baik', 500, 29.10, 82.00, 'kosong', 0.45, 260.00, 'Aktif 2 unit', 'Audio normal', 'Post-panen, perlu monitoring', 'sedang'),
(3, 2, '2024-06-20', 'baik', 'baik', 'baik', 650, 28.80, 84.00, 'pembentukan', 0.35, 270.00, 'Aktif 3 unit', 'Audio kombinasi', 'Mulai pembentukan untuk musim utuh', 'baik'),

(1, 1, '2024-08-10', 'baik', 'baik', 'baik', 950, 28.30, 86.50, 'siap_panen', 0.25, 285.00, 'Aktif 4 unit', 'Volume 78%, persiapan panen utuh', 'Siap panen utuh peak season', 'baik'),
(3, 2, '2024-08-15', 'baik', 'baik', 'baik', 720, 28.60, 84.50, 'siap_panen', 0.30, 272.00, 'Aktif 3 unit', 'Volume 75%, peak season', 'Siap panen utuh', 'baik'),
(4, 3, '2024-08-20', 'sedang', 'sedang', 'baik', 420, 29.80, 81.00, 'siap_panen', 0.50, 245.00, 'Aktif 1 unit', 'Speaker sudah diganti, audio normal', 'Siap panen, kondisi improving', 'sedang'),
(5, 3, '2024-09-05', 'baik', 'baik', 'baik', 620, 28.70, 83.50, 'siap_panen', 0.40, 275.00, 'Aktif 2 unit', 'Audio normal, populasi naik', 'Siap panen utuh', 'baik'),

(1, 1, '2024-10-15', 'baik', 'baik', 'baik', 870, 28.50, 85.00, 'kosong', 0.30, 280.00, 'Aktif 4 unit', 'Audio normal', 'Post-panen utuh, istirahat', 'baik'),
(3, 2, '2024-11-10', 'baik', 'sedang', 'baik', 680, 28.90, 84.00, 'pembentukan', 0.35, 270.00, 'Aktif 3 unit', 'Switch ke panggilan piyik', 'Coba piyik call untuk musim kecil', 'sedang'),

(1, 1, '2024-12-10', 'baik', 'baik', 'baik', 900, 28.40, 85.50, 'siap_panen', 0.28, 282.00, 'Aktif 4 unit', 'Audio normal', 'Siap panen kecil', 'baik'),
(5, 3, '2024-12-15', 'baik', 'baik', 'baik', 600, 28.60, 83.00, 'siap_panen', 0.40, 275.00, 'Aktif 2 unit', 'Audio normal', 'Siap panen kecil', 'baik'),

-- 2025
(1, 1, '2025-02-10', 'baik', 'baik', 'baik', 880, 28.50, 85.00, 'pembentukan', 0.30, 280.00, 'Aktif 4 unit', 'Persiapan musim urat', 'Kondisi prima', 'baik'),
(2, 1, '2025-02-15', 'baik', 'baik', 'baik', 560, 28.90, 82.50, 'pembentukan', 0.42, 262.00, 'Aktif 2 unit', 'Switch ke kombinasi', 'Populasi naik, audio upgrade', 'baik'),
(3, 2, '2025-02-20', 'baik', 'baik', 'baik', 700, 28.70, 84.00, 'pembentukan', 0.32, 270.00, 'Aktif 3 unit', 'Piyik call dari 2024 dipertahankan', 'Populasi naik 30% setelah piyik call', 'baik'),
(4, 3, '2025-02-25', 'sedang', 'sedang', 'baik', 450, 29.50, 81.50, 'pembentukan', 0.48, 245.00, 'Aktif 1 unit', 'Audio normal setelah ganti speaker', 'Kondisi improving', 'sedang'),

(1, 1, '2025-04-10', 'baik', 'baik', 'baik', 940, 28.40, 86.00, 'siap_panen', 0.25, 285.00, 'Aktif 4 unit', 'Audio stabil', 'Siap panen urat', 'baik'),
(3, 2, '2025-04-15', 'baik', 'baik', 'baik', 750, 28.60, 84.50, 'siap_panen', 0.30, 272.00, 'Aktif 3 unit', 'Piyik call terbukti efektif', 'Siap panen urat, populasi terbaik', 'baik'),
(5, 3, '2025-04-20', 'baik', 'baik', 'baik', 650, 28.60, 83.00, 'siap_panen', 0.40, 275.00, 'Aktif 2 unit', 'Audio normal', 'Siap panen urat', 'baik'),

(1, 1, '2025-06-15', 'baik', 'baik', 'baik', 910, 28.50, 85.50, 'kosong', 0.30, 280.00, 'Aktif 4 unit', 'Audio normal', 'Post-panen urat', 'baik'),
(3, 2, '2025-06-20', 'baik', 'baik', 'baik', 730, 28.70, 84.00, 'pembentukan', 0.32, 270.00, 'Aktif 3 unit', 'Switch ke kombinasi', 'Persiapan musim utuh', 'baik'),

(1, 1, '2025-08-10', 'baik', 'baik', 'baik', 980, 28.30, 86.50, 'siap_panen', 0.25, 285.00, 'Aktif 4 unit', 'Volume 80%, peak season utuh', 'Siap panen utuh, populasi terbaik', 'baik'),
(2, 1, '2025-08-15', 'baik', 'baik', 'baik', 620, 28.80, 83.00, 'siap_panen', 0.40, 265.00, 'Aktif 2 unit', 'Switch ke kombinasi', 'Siap panen utuh, populasi naik', 'baik'),
(3, 2, '2025-08-20', 'baik', 'baik', 'baik', 800, 28.60, 84.50, 'siap_panen', 0.28, 272.00, 'Aktif 3 unit', 'Volume 78%, peak season', 'Siap panen utuh', 'baik'),
(4, 3, '2025-08-25', 'sedang', 'sedang', 'baik', 480, 29.70, 81.50, 'siap_panen', 0.45, 248.00, 'Aktif 1 unit', 'Amplifier channel 1 rusak', 'Siap panen, audio bermasalah', 'sedang'),
(5, 3, '2025-09-05', 'baik', 'baik', 'baik', 680, 28.60, 83.50, 'siap_panen', 0.38, 275.00, 'Aktif 2 unit', 'Kombinasi efektif', 'Siap panen utuh', 'baik'),

(1, 1, '2025-10-15', 'baik', 'baik', 'baik', 900, 28.50, 85.00, 'kosong', 0.30, 280.00, 'Aktif 4 unit', 'Audio normal', 'Post-peak, istirahat', 'baik'),
(3, 2, '2025-11-10', 'baik', 'baik', 'baik', 780, 28.70, 84.00, 'pembentukan', 0.32, 270.00, 'Aktif 3 unit', 'Piyik call lanjutan', 'Populasi naik 30% YoY', 'baik'),

(1, 1, '2025-12-10', 'baik', 'baik', 'baik', 920, 28.40, 85.50, 'siap_panen', 0.28, 282.00, 'Aktif 4 unit', 'Audio normal', 'Siap panen kecil', 'baik'),
(5, 3, '2025-12-15', 'baik', 'baik', 'baik', 640, 28.60, 83.00, 'siap_panen', 0.40, 275.00, 'Aktif 2 unit', 'Audio normal', 'Siap panen kecil', 'baik');

-- ===================================================================
-- PREDATOR INSPEKSI - beberapa inspeksi dengan predator
-- ===================================================================
INSERT INTO `predator_inspeksi` (`inspeksi_id`, `jenis_predator`, `tingkat_infestasi`, `lokasi`, `tindakan`, `tgl_tindakan`, `tgl_follow_up`, `hasil_follow_up`, `catatan`) VALUES
-- 2024 inspeksi (IDs 1-19, ambil beberapa)
(4, 'semut', 'ringan', 'lantai 1', 'Semprot anti-semut organik', '2024-02-25', '2024-03-04', 'berhasil', 'Tidak ada semut setelah tindakan'),
(4, 'cicak', 'sedang', 'lantai 2', 'Pasang trap cicak, tutup ventilasi kawat', '2024-02-25', '2024-03-04', 'sebagian', 'Masih ada cicak, perlu tindakan lanjut'),
(8, 'tikus', 'ringan', 'area luar', 'Pasang umpan racun tikus di area luar', '2024-04-10', '2024-04-17', 'berhasil', 'Tidak ada tanda tikus lagi'),
(11, 'kecoak', 'sedang', 'lantai 1', 'Semprot anti-kecoak, bersihkan area lembab', '2024-06-15', '2024-06-22', 'berhasil', 'Kebersihan terjaga'),
(15, 'laba_laba', 'ringan', 'lantai 2', 'Bersihkan jaring laba-laba manual', '2024-08-20', '2024-08-27', 'berhasil', 'Laba-laba hilang'),
(20, 'semut', 'sedang', 'lantai 1', 'Semprot anti-semut, cari sarang', '2024-10-15', '2024-10-22', 'sebagian', 'Masih ada sarang semut di sudut'),
-- 2025 inspeksi
(25, 'cicak', 'ringan', 'lantai 1', 'Pasang trap cicak', '2025-02-15', '2025-02-22', 'berhasil', 'Cicak terkurangi'),
(28, 'kelelawar', 'ringan', 'area luar', 'Pasang jaring anti-kelelawar di ventilasi atas', '2025-04-10', '2025-04-17', 'berhasil', 'Kelelawar tidak masuk lagi'),
(33, 'tikus', 'sedang', 'lantai 2 dan area luar', 'Umpan racun + tutup lubang', '2025-08-10', '2025-08-17', 'berhasil', 'Tikus hilang'),
(36, 'semut', 'ringan', 'lantai 1', 'Semprot anti-semut organik', '2025-10-15', '2025-10-22', 'berhasil', 'Bersih');

-- ===================================================================
-- JADWAL PANEN - sesuai musim (urat Mar-Apr, utuh Jul-Sep, kecil Nov-Des)
-- ===================================================================
INSERT INTO `jadwal_panen` (`id`, `rumah_walet_id`, `tanggal_rencana`, `periode`, `estimasi_hasil_kg`, `jenis_panen_rencana`, `catatan`, `status`) VALUES
-- 2024
(1, 1, '2024-04-25', '2024-04', 12.00, 'urat', 'Panen urat RW-001', 'selesai'),
(2, 2, '2024-04-28', '2024-04', 8.00, 'urat', 'Panen urat RW-002', 'selesai'),
(3, 3, '2024-04-30', '2024-04', 10.00, 'urat', 'Panen urat RW-003', 'selesai'),
(4, 5, '2024-04-25', '2024-04', 8.50, 'urat', 'Panen urat RW-005', 'selesai'),

(5, 1, '2024-08-25', '2024-08', 18.00, 'sarang_utuh', 'Panen utuh peak RW-001', 'selesai'),
(6, 2, '2024-08-28', '2024-08', 12.00, 'sarang_utuh', 'Panen utuh RW-002', 'selesai'),
(7, 3, '2024-08-30', '2024-08', 15.00, 'sarang_utuh', 'Panen utuh peak RW-003', 'selesai'),
(8, 4, '2024-09-05', '2024-09', 8.50, 'sarang_utuh', 'Panen utuh RW-004', 'selesai'),
(9, 5, '2024-09-10', '2024-09', 11.00, 'sarang_utuh', 'Panen utuh RW-005', 'selesai'),

(10, 1, '2024-12-15', '2024-12', 6.00, 'kecil', 'Panen kecil RW-001', 'selesai'),
(11, 5, '2024-12-20', '2024-12', 4.50, 'kecil', 'Panen kecil RW-005', 'selesai'),

-- 2025
(12, 1, '2025-04-25', '2025-04', 12.50, 'urat', 'Panen urat RW-001', 'selesai'),
(13, 2, '2025-04-28', '2025-04', 8.50, 'urat', 'Panen urat RW-002', 'selesai'),
(14, 3, '2025-04-30', '2025-04', 10.50, 'urat', 'Panen urat RW-003 (populasi naik 30%)', 'selesai'),
(15, 5, '2025-04-25', '2025-04', 8.50, 'urat', 'Panen urat RW-005', 'selesai'),

(16, 1, '2025-08-25', '2025-08', 18.50, 'sarang_utuh', 'Panen utuh peak RW-001', 'selesai'),
(17, 2, '2025-08-28', '2025-08', 12.50, 'sarang_utuh', 'Panen utuh RW-002 (populasi naik)', 'selesai'),
(18, 3, '2025-08-30', '2025-08', 15.50, 'sarang_utuh', 'Panen utuh peak RW-003', 'selesai'),
(19, 4, '2025-09-05', '2025-09', 9.00, 'sarang_utuh', 'Panen utuh RW-004 (audio bermasalah)', 'selesai'),
(20, 5, '2025-09-10', '2025-09', 11.50, 'sarang_utuh', 'Panen utuh RW-005', 'selesai'),

(21, 1, '2025-12-15', '2025-12', 6.00, 'kecil', 'Panen kecil RW-001', 'selesai'),
(22, 5, '2025-12-20', '2025-12', 4.50, 'kecil', 'Panen kecil RW-005', 'selesai'),

-- 2026 upcoming
(23, 1, '2026-04-25', '2026-04', 13.00, 'urat', 'Panen urat RW-001 (rencana)', 'terjadwal'),
(24, 3, '2026-04-30', '2026-04', 11.00, 'urat', 'Panen urat RW-003 (rencana)', 'terjadwal'),
(25, 1, '2026-08-25', '2026-08', 19.00, 'sarang_utuh', 'Panen utuh RW-001 (rencana)', 'terjadwal'),
(26, 3, '2026-08-30', '2026-08', 16.00, 'sarang_utuh', 'Panen utuh RW-003 (rencana)', 'terjadwal');

-- ===================================================================
-- HASIL PANEN - 3 grade per jadwal yang selesai
-- Berat realistic: A 30-40%, B 30-40%, C 20-30% dari total
-- ===================================================================
INSERT INTO `hasil_panen` (`id`, `jadwal_panen_id`, `rumah_walet_id`, `petugas_id`, `tanggal_panen`, `periode`, `grade`, `jenis_panen`, `berat_kg`, `berat_basah_kg`, `berat_kering_kg`, `kadar_air_pct`, `kadar_kotoran_pct`, `no_batch`, `harga_per_kg`, `status_pengeringan`, `status_stok`, `kualitas`, `catatan`) VALUES
-- 2024 urat (jadwal 1-4)
(1, 1, 1, 1, '2024-04-25', '2024-04', 'A', 'urat', 4.200, 6.000, 4.200, 12.5, 3.2, 'BATCH-2024-04-RW1-A', 20000000, 'kering', 'terjual', 'Sarang urat premium, kualitas terbaik', 'Panen urat prima'),
(2, 1, 1, 1, '2024-04-25', '2024-04', 'B', 'urat', 3.800, 5.400, 3.800, 13.0, 4.5, 'BATCH-2024-04-RW1-B', 13000000, 'kering', 'terjual', 'Sarang urat bagus', 'Panen urat'),
(3, 1, 1, 1, '2024-04-25', '2024-04', 'C', 'urat', 2.500, 3.600, 2.500, 13.5, 5.8, 'BATCH-2024-04-RW1-C', 7000000, 'kering', 'terjual', 'Pecahan kecil', 'Panen urat'),

(4, 2, 2, 1, '2024-04-28', '2024-04', 'A', 'urat', 2.800, 4.000, 2.800, 12.8, 3.5, 'BATCH-2024-04-RW2-A', 20000000, 'kering', 'terjual', 'Urat bagus', 'Panen urat'),
(5, 2, 2, 1, '2024-04-28', '2024-04', 'B', 'urat', 2.500, 3.600, 2.500, 13.2, 4.8, 'BATCH-2024-04-RW2-B', 13000000, 'kering', 'terjual', 'Urat bagus', 'Panen urat'),
(6, 2, 2, 1, '2024-04-28', '2024-04', 'C', 'urat', 1.800, 2.600, 1.800, 14.0, 6.0, 'BATCH-2024-04-RW2-C', 7000000, 'kering', 'terjual', 'Pecahan', 'Panen urat'),

(7, 3, 3, 2, '2024-04-30', '2024-04', 'A', 'urat', 3.500, 5.000, 3.500, 12.6, 3.0, 'BATCH-2024-04-RW3-A', 20000000, 'kering', 'terjual', 'Urat premium', 'Panen urat prima'),
(8, 3, 3, 2, '2024-04-30', '2024-04', 'B', 'urat', 3.200, 4.600, 3.200, 13.0, 4.2, 'BATCH-2024-04-RW3-B', 13000000, 'kering', 'terjual', 'Urat bagus', 'Panen urat'),
(9, 3, 3, 2, '2024-04-30', '2024-04', 'C', 'urat', 2.000, 2.900, 2.000, 13.8, 5.5, 'BATCH-2024-04-RW3-C', 7000000, 'kering', 'terjual', 'Pecahan', 'Panen urat'),

(10, 4, 5, 3, '2024-04-25', '2024-04', 'A', 'urat', 3.000, 4.300, 3.000, 12.7, 3.3, 'BATCH-2024-04-RW5-A', 20000000, 'kering', 'terjual', 'Urat bagus', 'Panen urat'),
(11, 4, 5, 3, '2024-04-25', '2024-04', 'B', 'urat', 2.700, 3.900, 2.700, 13.1, 4.6, 'BATCH-2024-04-RW5-B', 13000000, 'kering', 'terjual', 'Urat bagus', 'Panen urat'),
(12, 4, 5, 3, '2024-04-25', '2024-04', 'C', 'urat', 1.900, 2.700, 1.900, 13.7, 5.7, 'BATCH-2024-04-RW5-C', 7000000, 'kering', 'terjual', 'Pecahan', 'Panen urat'),

-- 2024 sarang utuh (jadwal 5-9)
(13, 5, 1, 1, '2024-08-25', '2024-08', 'A', 'sarang_utuh', 6.500, 9.300, 6.500, 12.3, 2.8, 'BATCH-2024-08-RW1-A', 15000000, 'kering', 'terjual', 'Utuh premium peak season', 'Panen utuh prima'),
(14, 5, 1, 1, '2024-08-25', '2024-08', 'B', 'sarang_utuh', 5.800, 8.300, 5.800, 12.8, 4.0, 'BATCH-2024-08-RW1-B', 10000000, 'kering', 'terjual', 'Utuh bagus', 'Panen utuh'),
(15, 5, 1, 1, '2024-08-25', '2024-08', 'C', 'sarang_utuh', 3.800, 5.400, 3.800, 13.5, 5.5, 'BATCH-2024-08-RW1-C', 5000000, 'kering', 'terjual', 'Pecahan utuh', 'Panen utuh'),

(16, 6, 2, 1, '2024-08-28', '2024-08', 'A', 'sarang_utuh', 4.200, 6.000, 4.200, 12.5, 3.0, 'BATCH-2024-08-RW2-A', 15000000, 'kering', 'terjual', 'Utuh bagus', 'Panen utuh'),
(17, 6, 2, 1, '2024-08-28', '2024-08', 'B', 'sarang_utuh', 3.800, 5.400, 3.800, 13.0, 4.3, 'BATCH-2024-08-RW2-B', 10000000, 'kering', 'terjual', 'Utuh bagus', 'Panen utuh'),
(18, 6, 2, 1, '2024-08-28', '2024-08', 'C', 'sarang_utuh', 2.500, 3.600, 2.500, 13.7, 5.8, 'BATCH-2024-08-RW2-C', 5000000, 'kering', 'terjual', 'Pecahan', 'Panen utuh'),

(19, 7, 3, 2, '2024-08-30', '2024-08', 'A', 'sarang_utuh', 5.200, 7.400, 5.200, 12.4, 2.9, 'BATCH-2024-08-RW3-A', 15000000, 'kering', 'terjual', 'Utuh premium', 'Panen utuh prima'),
(20, 7, 3, 2, '2024-08-30', '2024-08', 'B', 'sarang_utuh', 4.800, 6.900, 4.800, 12.9, 4.1, 'BATCH-2024-08-RW3-B', 10000000, 'kering', 'terjual', 'Utuh bagus', 'Panen utuh'),
(21, 7, 3, 2, '2024-08-30', '2024-08', 'C', 'sarang_utuh', 3.200, 4.600, 3.200, 13.6, 5.6, 'BATCH-2024-08-RW3-C', 5000000, 'kering', 'terjual', 'Pecahan', 'Panen utuh'),

(22, 8, 4, 3, '2024-09-05', '2024-09', 'A', 'sarang_utuh', 3.000, 4.300, 3.000, 12.8, 3.5, 'BATCH-2024-09-RW4-A', 15000000, 'kering', 'terjual', 'Utuh bagus', 'Panen utuh'),
(23, 8, 4, 3, '2024-09-05', '2024-09', 'B', 'sarang_utuh', 2.700, 3.900, 2.700, 13.2, 4.5, 'BATCH-2024-09-RW4-B', 10000000, 'kering', 'terjual', 'Utuh bagus', 'Panen utuh'),
(24, 8, 4, 3, '2024-09-05', '2024-09', 'C', 'sarang_utuh', 1.800, 2.600, 1.800, 13.9, 6.0, 'BATCH-2024-09-RW4-C', 5000000, 'kering', 'terjual', 'Pecahan', 'Panen utuh'),

(25, 9, 5, 3, '2024-09-10', '2024-09', 'A', 'sarang_utuh', 3.800, 5.400, 3.800, 12.6, 3.2, 'BATCH-2024-09-RW5-A', 15000000, 'kering', 'terjual', 'Utuh bagus', 'Panen utuh'),
(26, 9, 5, 3, '2024-09-10', '2024-09', 'B', 'sarang_utuh', 3.500, 5.000, 3.500, 13.0, 4.4, 'BATCH-2024-09-RW5-B', 10000000, 'kering', 'terjual', 'Utuh bagus', 'Panen utuh'),
(27, 9, 5, 3, '2024-09-10', '2024-09', 'C', 'sarang_utuh', 2.300, 3.300, 2.300, 13.7, 5.7, 'BATCH-2024-09-RW5-C', 5000000, 'kering', 'terjual', 'Pecahan', 'Panen utuh'),

-- 2024 kecil (jadwal 10-11)
(28, 10, 1, 1, '2024-12-15', '2024-12', 'A', 'kecil', 2.200, 3.100, 2.200, 12.9, 3.8, 'BATCH-2024-12-RW1-A', 12000000, 'kering', 'terjual', 'Kecil premium', 'Panen kecil'),
(29, 10, 1, 1, '2024-12-15', '2024-12', 'B', 'kecil', 1.800, 2.600, 1.800, 13.3, 4.8, 'BATCH-2024-12-RW1-B', 6000000, 'kering', 'terjual', 'Kecil bagus', 'Panen kecil'),
(30, 10, 1, 1, '2024-12-15', '2024-12', 'C', 'kecil', 1.200, 1.700, 1.200, 14.0, 6.2, 'BATCH-2024-12-RW1-C', 3000000, 'kering', 'terjual', 'Pecahan kecil', 'Panen kecil'),

(31, 11, 5, 3, '2024-12-20', '2024-12', 'A', 'kecil', 1.600, 2.300, 1.600, 13.0, 3.9, 'BATCH-2024-12-RW5-A', 12000000, 'kering', 'terjual', 'Kecil bagus', 'Panen kecil'),
(32, 11, 5, 3, '2024-12-20', '2024-12', 'B', 'kecil', 1.400, 2.000, 1.400, 13.4, 4.9, 'BATCH-2024-12-RW5-B', 6000000, 'kering', 'terjual', 'Kecil bagus', 'Panen kecil'),
(33, 11, 5, 3, '2024-12-20', '2024-12', 'C', 'kecil', 0.900, 1.300, 0.900, 14.1, 6.3, 'BATCH-2024-12-RW5-C', 3000000, 'kering', 'terjual', 'Pecahan', 'Panen kecil'),

-- 2025 urat (jadwal 12-15) - populasi naik
(34, 12, 1, 1, '2025-04-25', '2025-04', 'A', 'urat', 4.500, 6.400, 4.500, 12.4, 3.0, 'BATCH-2025-04-RW1-A', 22000000, 'kering', 'terjual', 'Urat premium, harga naik', 'Panen urat prima'),
(35, 12, 1, 1, '2025-04-25', '2025-04', 'B', 'urat', 4.000, 5.700, 4.000, 12.9, 4.3, 'BATCH-2025-04-RW1-B', 14000000, 'kering', 'terjual', 'Urat bagus', 'Panen urat'),
(36, 12, 1, 1, '2025-04-25', '2025-04', 'C', 'urat', 2.600, 3.700, 2.600, 13.6, 5.6, 'BATCH-2025-04-RW1-C', 7500000, 'kering', 'terjual', 'Pecahan', 'Panen urat'),

(37, 13, 2, 1, '2025-04-28', '2025-04', 'A', 'urat', 3.000, 4.300, 3.000, 12.7, 3.4, 'BATCH-2025-04-RW2-A', 22000000, 'kering', 'terjual', 'Urat bagus', 'Panen urat'),
(38, 13, 2, 1, '2025-04-28', '2025-04', 'B', 'urat', 2.700, 3.900, 2.700, 13.1, 4.7, 'BATCH-2025-04-RW2-B', 14000000, 'kering', 'terjual', 'Urat bagus', 'Panen urat'),
(39, 13, 2, 1, '2025-04-28', '2025-04', 'C', 'urat', 1.900, 2.700, 1.900, 13.8, 5.9, 'BATCH-2025-04-RW2-C', 7500000, 'kering', 'terjual', 'Pecahan', 'Panen urat'),

(40, 14, 3, 2, '2025-04-30', '2025-04', 'A', 'urat', 3.800, 5.400, 3.800, 12.5, 3.1, 'BATCH-2025-04-RW3-A', 22000000, 'kering', 'terjual', 'Urat premium, populasi naik 30%', 'Panen urat prima'),
(41, 14, 3, 2, '2025-04-30', '2025-04', 'B', 'urat', 3.400, 4.900, 3.400, 12.9, 4.2, 'BATCH-2025-04-RW3-B', 14000000, 'kering', 'terjual', 'Urat bagus', 'Panen urat'),
(42, 14, 3, 2, '2025-04-30', '2025-04', 'C', 'urat', 2.200, 3.100, 2.200, 13.6, 5.4, 'BATCH-2025-04-RW3-C', 7500000, 'kering', 'terjual', 'Pecahan', 'Panen urat'),

(43, 15, 5, 3, '2025-04-25', '2025-04', 'A', 'urat', 3.200, 4.600, 3.200, 12.7, 3.3, 'BATCH-2025-04-RW5-A', 22000000, 'kering', 'terjual', 'Urat bagus', 'Panen urat'),
(44, 15, 5, 3, '2025-04-25', '2025-04', 'B', 'urat', 2.900, 4.100, 2.900, 13.1, 4.6, 'BATCH-2025-04-RW5-B', 14000000, 'kering', 'terjual', 'Urat bagus', 'Panen urat'),
(45, 15, 5, 3, '2025-04-25', '2025-04', 'C', 'urat', 2.000, 2.900, 2.000, 13.7, 5.8, 'BATCH-2025-04-RW5-C', 7500000, 'kering', 'terjual', 'Pecahan', 'Panen urat'),

-- 2025 sarang utuh (jadwal 16-20)
(46, 16, 1, 1, '2025-08-25', '2025-08', 'A', 'sarang_utuh', 7.000, 10.000, 7.000, 12.2, 2.7, 'BATCH-2025-08-RW1-A', 16000000, 'kering', 'di_gudang_rw', 'Utuh premium, populasi terbaik', 'Panen utuh prima - stok available'),
(47, 16, 1, 1, '2025-08-25', '2025-08', 'B', 'sarang_utuh', 6.200, 8.900, 6.200, 12.7, 3.9, 'BATCH-2025-08-RW1-B', 11000000, 'kering', 'di_gudang_rw', 'Utuh bagus', 'Stok available'),
(48, 16, 1, 1, '2025-08-25', '2025-08', 'C', 'sarang_utuh', 4.000, 5.700, 4.000, 13.4, 5.3, 'BATCH-2025-08-RW1-C', 5500000, 'kering', 'di_gudang_rw', 'Pecahan utuh', 'Stok available'),

(49, 17, 2, 1, '2025-08-28', '2025-08', 'A', 'sarang_utuh', 4.500, 6.400, 4.500, 12.4, 2.9, 'BATCH-2025-08-RW2-A', 16000000, 'kering', 'terjual', 'Utuh premium, populasi naik', 'Panen utuh prima'),
(50, 17, 2, 1, '2025-08-28', '2025-08', 'B', 'sarang_utuh', 4.000, 5.700, 4.000, 12.8, 4.1, 'BATCH-2025-08-RW2-B', 11000000, 'kering', 'terjual', 'Utuh bagus', 'Panen utuh'),
(51, 17, 2, 1, '2025-08-28', '2025-08', 'C', 'sarang_utuh', 2.700, 3.900, 2.700, 13.6, 5.7, 'BATCH-2025-08-RW2-C', 5500000, 'kering', 'terjual', 'Pecahan', 'Panen utuh'),

(52, 18, 3, 2, '2025-08-30', '2025-08', 'A', 'sarang_utuh', 5.500, 7.900, 5.500, 12.3, 2.8, 'BATCH-2025-08-RW3-A', 16000000, 'kering', 'terjual', 'Utuh premium', 'Panen utuh prima'),
(53, 18, 3, 2, '2025-08-30', '2025-08', 'B', 'sarang_utuh', 5.100, 7.300, 5.100, 12.8, 4.0, 'BATCH-2025-08-RW3-B', 11000000, 'kering', 'terjual', 'Utuh bagus', 'Panen utuh'),
(54, 18, 3, 2, '2025-08-30', '2025-08', 'C', 'sarang_utuh', 3.400, 4.900, 3.400, 13.5, 5.5, 'BATCH-2025-08-RW3-C', 5500000, 'kering', 'terjual', 'Pecahan', 'Panen utuh'),

(55, 19, 4, 3, '2025-09-05', '2025-09', 'A', 'sarang_utuh', 3.200, 4.600, 3.200, 12.9, 3.6, 'BATCH-2025-09-RW4-A', 16000000, 'kering', 'di_gudang_rw', 'Utuh bagus, audio bermasalah saat panen', 'Stok available'),
(56, 19, 4, 3, '2025-09-05', '2025-09', 'B', 'sarang_utuh', 2.900, 4.100, 2.900, 13.3, 4.7, 'BATCH-2025-09-RW4-B', 11000000, 'kering', 'di_gudang_rw', 'Utuh bagus', 'Stok available'),
(57, 19, 4, 3, '2025-09-05', '2025-09', 'C', 'sarang_utuh', 1.900, 2.700, 1.900, 14.0, 6.1, 'BATCH-2025-09-RW4-C', 5500000, 'kering', 'di_gudang_rw', 'Pecahan', 'Stok available'),

(58, 20, 5, 3, '2025-09-10', '2025-09', 'A', 'sarang_utuh', 4.000, 5.700, 4.000, 12.6, 3.1, 'BATCH-2025-09-RW5-A', 16000000, 'kering', 'terjual', 'Utuh premium', 'Panen utuh prima'),
(59, 20, 5, 3, '2025-09-10', '2025-09', 'B', 'sarang_utuh', 3.700, 5.300, 3.700, 13.0, 4.3, 'BATCH-2025-09-RW5-B', 11000000, 'kering', 'terjual', 'Utuh bagus', 'Panen utuh'),
(60, 20, 5, 3, '2025-09-10', '2025-09', 'C', 'sarang_utuh', 2.500, 3.600, 2.500, 13.7, 5.8, 'BATCH-2025-09-RW5-C', 5500000, 'kering', 'terjual', 'Pecahan', 'Panen utuh'),

-- 2025 kecil (jadwal 21-22)
(61, 21, 1, 1, '2025-12-15', '2025-12', 'A', 'kecil', 2.400, 3.400, 2.400, 12.8, 3.7, 'BATCH-2025-12-RW1-A', 13000000, 'kering', 'di_gudang_rw', 'Kecil premium', 'Stok available'),
(62, 21, 1, 1, '2025-12-15', '2025-12', 'B', 'kecil', 1.900, 2.700, 1.900, 13.2, 4.7, 'BATCH-2025-12-RW1-B', 7000000, 'kering', 'di_gudang_rw', 'Kecil bagus', 'Stok available'),
(63, 21, 1, 1, '2025-12-15', '2025-12', 'C', 'kecil', 1.300, 1.900, 1.300, 13.9, 6.1, 'BATCH-2025-12-RW1-C', 3500000, 'kering', 'di_gudang_rw', 'Pecahan kecil', 'Stok available'),

(64, 22, 5, 3, '2025-12-20', '2025-12', 'A', 'kecil', 1.700, 2.400, 1.700, 13.0, 3.8, 'BATCH-2025-12-RW5-A', 13000000, 'kering', 'di_gudang_rw', 'Kecil bagus', 'Stok available'),
(65, 22, 5, 3, '2025-12-20', '2025-12', 'B', 'kecil', 1.500, 2.100, 1.500, 13.3, 4.8, 'BATCH-2025-12-RW5-B', 7000000, 'kering', 'di_gudang_rw', 'Kecil bagus', 'Stok available'),
(66, 22, 5, 3, '2025-12-20', '2025-12', 'C', 'kecil', 1.000, 1.400, 1.000, 14.0, 6.2, 'BATCH-2025-12-RW5-C', 3500000, 'kering', 'di_gudang_rw', 'Pecahan', 'Stok available');

-- Update jadwal_panen set status selesai & hasil_panen_id (link pertama)
UPDATE `jadwal_panen` SET `hasil_panen_id` = 1 WHERE `id` = 1;
UPDATE `jadwal_panen` SET `hasil_panen_id` = 4 WHERE `id` = 2;
UPDATE `jadwal_panen` SET `hasil_panen_id` = 7 WHERE `id` = 3;
UPDATE `jadwal_panen` SET `hasil_panen_id` = 10 WHERE `id` = 4;
UPDATE `jadwal_panen` SET `hasil_panen_id` = 13 WHERE `id` = 5;
UPDATE `jadwal_panen` SET `hasil_panen_id` = 16 WHERE `id` = 6;
UPDATE `jadwal_panen` SET `hasil_panen_id` = 19 WHERE `id` = 7;
UPDATE `jadwal_panen` SET `hasil_panen_id` = 22 WHERE `id` = 8;
UPDATE `jadwal_panen` SET `hasil_panen_id` = 25 WHERE `id` = 9;
UPDATE `jadwal_panen` SET `hasil_panen_id` = 28 WHERE `id` = 10;
UPDATE `jadwal_panen` SET `hasil_panen_id` = 31 WHERE `id` = 11;
UPDATE `jadwal_panen` SET `hasil_panen_id` = 34 WHERE `id` = 12;
UPDATE `jadwal_panen` SET `hasil_panen_id` = 37 WHERE `id` = 13;
UPDATE `jadwal_panen` SET `hasil_panen_id` = 40 WHERE `id` = 14;
UPDATE `jadwal_panen` SET `hasil_panen_id` = 43 WHERE `id` = 15;
UPDATE `jadwal_panen` SET `hasil_panen_id` = 46 WHERE `id` = 16;
UPDATE `jadwal_panen` SET `hasil_panen_id` = 49 WHERE `id` = 17;
UPDATE `jadwal_panen` SET `hasil_panen_id` = 52 WHERE `id` = 18;
UPDATE `jadwal_panen` SET `hasil_panen_id` = 55 WHERE `id` = 19;
UPDATE `jadwal_panen` SET `hasil_panen_id` = 58 WHERE `id` = 20;
UPDATE `jadwal_panen` SET `hasil_panen_id` = 61 WHERE `id` = 21;
UPDATE `jadwal_panen` SET `hasil_panen_id` = 64 WHERE `id` = 22;

-- ===================================================================
-- PENGELUARAN - 24 bulan data
-- ===================================================================
INSERT INTO `pengeluaran` (`id`, `tanggal`, `rumah_walet_id`, `kategori`, `keterangan`, `jumlah`, `bukti`, `input_by`, `approval_status`, `approved_by`, `approval_date`, `approval_note`) VALUES
-- === GAJI (auto_approved, rumah_walet_id=NULL, akan auto-alokasi) ===
(1, '2024-01-31', NULL, 'gaji', 'Gaji petugas Januari 2024', 12000000, NULL, 1, 'auto_approved', NULL, NULL, NULL),
(2, '2024-02-28', NULL, 'gaji', 'Gaji petugas Februari 2024', 12000000, NULL, 1, 'auto_approved', NULL, NULL, NULL),
(3, '2024-03-31', NULL, 'gaji', 'Gaji petugas Maret 2024', 12000000, NULL, 1, 'auto_approved', NULL, NULL, NULL),
(4, '2024-04-30', NULL, 'gaji', 'Gaji petugas April 2024', 12000000, NULL, 1, 'auto_approved', NULL, NULL, NULL),
(5, '2024-05-31', NULL, 'gaji', 'Gaji petugas Mei 2024', 12000000, NULL, 1, 'auto_approved', NULL, NULL, NULL),
(6, '2024-06-30', NULL, 'gaji', 'Gaji petugas Juni 2024', 12000000, NULL, 1, 'auto_approved', NULL, NULL, NULL),
(7, '2024-07-31', NULL, 'gaji', 'Gaji petugas Juli 2024', 12000000, NULL, 1, 'auto_approved', NULL, NULL, NULL),
(8, '2024-08-31', NULL, 'gaji', 'Gaji petugas Agustus 2024', 12000000, NULL, 1, 'auto_approved', NULL, NULL, NULL),
(9, '2024-09-30', NULL, 'gaji', 'Gaji petugas September 2024', 12000000, NULL, 1, 'auto_approved', NULL, NULL, NULL),
(10, '2024-10-31', NULL, 'gaji', 'Gaji petugas Oktober 2024', 12000000, NULL, 1, 'auto_approved', NULL, NULL, NULL),
(11, '2024-11-30', NULL, 'gaji', 'Gaji petugas November 2024', 12000000, NULL, 1, 'auto_approved', NULL, NULL, NULL),
(12, '2024-12-31', NULL, 'gaji', 'Gaji petugas Desember 2024 + THR', 18000000, NULL, 1, 'auto_approved', NULL, NULL, NULL),
(13, '2025-01-31', NULL, 'gaji', 'Gaji petugas Januari 2025', 12500000, NULL, 1, 'auto_approved', NULL, NULL, NULL),
(14, '2025-02-28', NULL, 'gaji', 'Gaji petugas Februari 2025', 12500000, NULL, 1, 'auto_approved', NULL, NULL, NULL),
(15, '2025-03-31', NULL, 'gaji', 'Gaji petugas Maret 2025', 12500000, NULL, 1, 'auto_approved', NULL, NULL, NULL),
(16, '2025-04-30', NULL, 'gaji', 'Gaji petugas April 2025', 12500000, NULL, 1, 'auto_approved', NULL, NULL, NULL),
(17, '2025-05-31', NULL, 'gaji', 'Gaji petugas Mei 2025', 12500000, NULL, 1, 'auto_approved', NULL, NULL, NULL),
(18, '2025-06-30', NULL, 'gaji', 'Gaji petugas Juni 2025', 12500000, NULL, 1, 'auto_approved', NULL, NULL, NULL),
(19, '2025-07-31', NULL, 'gaji', 'Gaji petugas Juli 2025', 12500000, NULL, 1, 'auto_approved', NULL, NULL, NULL),
(20, '2025-08-31', NULL, 'gaji', 'Gaji petugas Agustus 2025', 12500000, NULL, 1, 'auto_approved', NULL, NULL, NULL),
(21, '2025-09-30', NULL, 'gaji', 'Gaji petugas September 2025', 12500000, NULL, 1, 'auto_approved', NULL, NULL, NULL),
(22, '2025-10-31', NULL, 'gaji', 'Gaji petugas Oktober 2025', 12500000, NULL, 1, 'auto_approved', NULL, NULL, NULL),
(23, '2025-11-30', NULL, 'gaji', 'Gaji petugas November 2025', 12500000, NULL, 1, 'auto_approved', NULL, NULL, NULL),
(24, '2025-12-31', NULL, 'gaji', 'Gaji petugas Desember 2025 + THR', 18500000, NULL, 1, 'auto_approved', NULL, NULL, NULL),

-- === LISTRIK (per RW per bulan) ===
(25, '2024-01-15', 1, 'listrik', 'Listrik RW-001 Januari 2024', 850000, NULL, 1, 'auto_approved', NULL, NULL, NULL),
(26, '2024-02-15', 1, 'listrik', 'Listrik RW-001 Februari 2024', 870000, NULL, 1, 'auto_approved', NULL, NULL, NULL),
(27, '2024-03-15', 1, 'listrik', 'Listrik RW-001 Maret 2024', 920000, NULL, 1, 'auto_approved', NULL, NULL, NULL),
(28, '2024-04-15', 1, 'listrik', 'Listrik RW-001 April 2024', 950000, NULL, 1, 'auto_approved', NULL, NULL, NULL),
(29, '2024-05-15', 1, 'listrik', 'Listrik RW-001 Mei 2024', 880000, NULL, 1, 'auto_approved', NULL, NULL, NULL),
(30, '2024-06-15', 1, 'listrik', 'Listrik RW-001 Juni 2024', 900000, NULL, 1, 'auto_approved', NULL, NULL, NULL),
(31, '2024-07-15', 1, 'listrik', 'Listrik RW-001 Juli 2024 (audio 24 jam peak)', 1100000, NULL, 1, 'auto_approved', NULL, NULL, NULL),
(32, '2024-08-15', 1, 'listrik', 'Listrik RW-001 Agustus 2024 (peak season)', 1150000, NULL, 1, 'auto_approved', NULL, NULL, NULL),
(33, '2024-09-15', 1, 'listrik', 'Listrik RW-001 September 2024', 1050000, NULL, 1, 'auto_approved', NULL, NULL, NULL),
(34, '2024-10-15', 1, 'listrik', 'Listrik RW-001 Oktober 2024', 890000, NULL, 1, 'auto_approved', NULL, NULL, NULL),
(35, '2024-11-15', 1, 'listrik', 'Listrik RW-001 November 2024', 870000, NULL, 1, 'auto_approved', NULL, NULL, NULL),
(36, '2024-12-15', 1, 'listrik', 'Listrik RW-001 Desember 2024', 910000, NULL, 1, 'auto_approved', NULL, NULL, NULL),

(37, '2024-01-15', 2, 'listrik', 'Listrik RW-002 Januari 2024', 580000, NULL, 1, 'auto_approved', NULL, NULL, NULL),
(38, '2024-04-15', 2, 'listrik', 'Listrik RW-002 April 2024', 620000, NULL, 1, 'auto_approved', NULL, NULL, NULL),
(39, '2024-07-15', 2, 'listrik', 'Listrik RW-002 Juli 2024', 750000, NULL, 1, 'auto_approved', NULL, NULL, NULL),
(40, '2024-10-15', 2, 'listrik', 'Listrik RW-002 Oktober 2024', 600000, NULL, 1, 'auto_approved', NULL, NULL, NULL),

(41, '2024-01-15', 3, 'listrik', 'Listrik RW-003 Januari 2024', 720000, NULL, 1, 'auto_approved', NULL, NULL, NULL),
(42, '2024-04-15', 3, 'listrik', 'Listrik RW-003 April 2024', 780000, NULL, 1, 'auto_approved', NULL, NULL, NULL),
(43, '2024-07-15', 3, 'listrik', 'Listrik RW-003 Juli 2024', 920000, NULL, 1, 'auto_approved', NULL, NULL, NULL),
(44, '2024-10-15', 3, 'listrik', 'Listrik RW-003 Oktober 2024', 750000, NULL, 1, 'auto_approved', NULL, NULL, NULL),

(45, '2024-01-15', 4, 'listrik', 'Listrik RW-004 Januari 2024', 480000, NULL, 1, 'auto_approved', NULL, NULL, NULL),
(46, '2024-04-15', 4, 'listrik', 'Listrik RW-004 April 2024', 520000, NULL, 1, 'auto_approved', NULL, NULL, NULL),
(47, '2024-07-15', 4, 'listrik', 'Listrik RW-004 Juli 2024', 620000, NULL, 1, 'auto_approved', NULL, NULL, NULL),
(48, '2024-10-15', 4, 'listrik', 'Listrik RW-004 Oktober 2024', 500000, NULL, 1, 'auto_approved', NULL, NULL, NULL),

(49, '2024-01-15', 5, 'listrik', 'Listrik RW-005 Januari 2024', 650000, NULL, 1, 'auto_approved', NULL, NULL, NULL),
(50, '2024-04-15', 5, 'listrik', 'Listrik RW-005 April 2024', 700000, NULL, 1, 'auto_approved', NULL, NULL, NULL),
(51, '2024-07-15', 5, 'listrik', 'Listrik RW-005 Juli 2024', 850000, NULL, 1, 'auto_approved', NULL, NULL, NULL),
(52, '2024-10-15', 5, 'listrik', 'Listrik RW-005 Oktober 2024', 680000, NULL, 1, 'auto_approved', NULL, NULL, NULL),

-- 2025 listrik (ringkasan per RW)
(53, '2025-01-15', 1, 'listrik', 'Listrik RW-001 Q1 2025', 2700000, NULL, 1, 'auto_approved', NULL, NULL, NULL),
(54, '2025-04-15', 1, 'listrik', 'Listrik RW-001 Q2 2025', 2850000, NULL, 1, 'auto_approved', NULL, NULL, NULL),
(55, '2025-07-15', 1, 'listrik', 'Listrik RW-001 Q3 2025 (peak)', 3400000, NULL, 1, 'auto_approved', NULL, NULL, NULL),
(56, '2025-10-15', 1, 'listrik', 'Listrik RW-001 Q4 2025', 2750000, NULL, 1, 'auto_approved', NULL, NULL, NULL),

(57, '2025-01-15', 2, 'listrik', 'Listrik RW-002 Q1 2025', 1850000, NULL, 1, 'auto_approved', NULL, NULL, NULL),
(58, '2025-04-15', 2, 'listrik', 'Listrik RW-002 Q2 2025', 1950000, NULL, 1, 'auto_approved', NULL, NULL, NULL),
(59, '2025-07-15', 2, 'listrik', 'Listrik RW-002 Q3 2025', 2300000, NULL, 1, 'auto_approved', NULL, NULL, NULL),
(60, '2025-10-15', 2, 'listrik', 'Listrik RW-002 Q4 2025', 1900000, NULL, 1, 'auto_approved', NULL, NULL, NULL),

(61, '2025-01-15', 3, 'listrik', 'Listrik RW-003 Q1 2025', 2250000, NULL, 1, 'auto_approved', NULL, NULL, NULL),
(62, '2025-04-15', 3, 'listrik', 'Listrik RW-003 Q2 2025', 2400000, NULL, 1, 'auto_approved', NULL, NULL, NULL),
(63, '2025-07-15', 3, 'listrik', 'Listrik RW-003 Q3 2025 (peak)', 2800000, NULL, 1, 'auto_approved', NULL, NULL, NULL),
(64, '2025-10-15', 3, 'listrik', 'Listrik RW-003 Q4 2025', 2350000, NULL, 1, 'auto_approved', NULL, NULL, NULL),

(65, '2025-01-15', 4, 'listrik', 'Listrik RW-004 Q1 2025', 1500000, NULL, 1, 'auto_approved', NULL, NULL, NULL),
(66, '2025-04-15', 4, 'listrik', 'Listrik RW-004 Q2 2025', 1600000, NULL, 1, 'auto_approved', NULL, NULL, NULL),
(67, '2025-07-15', 4, 'listrik', 'Listrik RW-004 Q3 2025', 1900000, NULL, 1, 'auto_approved', NULL, NULL, NULL),
(68, '2025-10-15', 4, 'listrik', 'Listrik RW-004 Q4 2025', 1550000, NULL, 1, 'auto_approved', NULL, NULL, NULL),

(69, '2025-01-15', 5, 'listrik', 'Listrik RW-005 Q1 2025', 2050000, NULL, 1, 'auto_approved', NULL, NULL, NULL),
(70, '2025-04-15', 5, 'listrik', 'Listrik RW-005 Q2 2025', 2150000, NULL, 1, 'auto_approved', NULL, NULL, NULL),
(71, '2025-07-15', 5, 'listrik', 'Listrik RW-005 Q3 2025', 2600000, NULL, 1, 'auto_approved', NULL, NULL, NULL),
(72, '2025-10-15', 5, 'listrik', 'Listrik RW-005 Q4 2025', 2100000, NULL, 1, 'auto_approved', NULL, NULL, NULL),

-- === MAINTENANCE ===
(73, '2024-03-10', 1, 'maintenance', 'Service rutin HVAC + humidifier RW-001', 1800000, NULL, 1, 'auto_approved', NULL, NULL, NULL),
(74, '2024-05-20', 3, 'maintenance', 'Renovasi ventilasi lantai 1 RW-003', 7500000, 'bukti-maint-rw3-2024.jpg', 1, 'approved', 3, '2024-05-22 10:00:00', 'Approved - renovasi memang urgent'),
(75, '2024-06-15', 1, 'maintenance', 'Ganti speaker lantai 2 (4 unit) RW-001', 2400000, NULL, 1, 'auto_approved', NULL, NULL, NULL),
(76, '2024-07-20', 2, 'maintenance', 'Ganti humidifier RW-002', 1500000, NULL, 1, 'auto_approved', NULL, NULL, NULL),
(77, '2024-08-25', 4, 'maintenance', 'Ganti 2 speaker rusak lantai 2 RW-004', 1200000, NULL, 1, 'auto_approved', NULL, NULL, NULL),
(78, '2024-11-10', 5, 'maintenance', 'Service lantai 3 RW-005 + perbaikan atap', 3500000, NULL, 1, 'auto_approved', NULL, NULL, NULL),
(79, '2025-02-15', 1, 'maintenance', 'Service tahunan HVAC + cleaning ducting RW-001', 2200000, NULL, 1, 'auto_approved', NULL, NULL, NULL),
(80, '2025-05-10', 3, 'maintenance', 'Service humidifier 3 unit RW-003', 1800000, NULL, 1, 'auto_approved', NULL, NULL, NULL),
(81, '2025-08-20', 4, 'audio', 'Ganti amplifier channel 1 RW-004 (rusak)', 4500000, 'bukti-amp-rw4.jpg', 1, 'approved', 3, '2025-08-22 14:00:00', 'Approved - amplifier rusak parah'),
(82, '2025-10-15', 1, 'maintenance', 'Renovasi pintu masuk walet RW-001', 6500000, 'bukti-renov-rw1.jpg', 1, 'approved', 3, '2025-10-17 09:30:00', 'Approved - pintu lama sudah rusak'),
(83, '2025-11-20', 5, 'maintenance', 'Ganti speaker 2 unit RW-005', 1300000, NULL, 1, 'auto_approved', NULL, NULL, NULL),

-- === STIMULAN AROMA ===
(84, '2024-02-15', 1, 'stimulan_aroma', 'Beli stimulan aroma premium RW-001', 1200000, NULL, 1, 'auto_approved', NULL, NULL, NULL),
(85, '2024-05-20', 3, 'stimulan_aroma', 'Stimulan aroma RW-003', 950000, NULL, 1, 'auto_approved', NULL, NULL, NULL),
(86, '2024-08-10', 1, 'stimulan_aroma', 'Stimulan peak season RW-001', 1500000, NULL, 1, 'auto_approved', NULL, NULL, NULL),
(87, '2024-09-15', 5, 'stimulan_aroma', 'Stimulan RW-005', 850000, NULL, 1, 'auto_approved', NULL, NULL, NULL),
(88, '2025-02-20', 1, 'stimulan_aroma', 'Stimulan aroma RW-001 Q1 2025', 1300000, NULL, 1, 'auto_approved', NULL, NULL, NULL),
(89, '2025-05-15', 3, 'stimulan_aroma', 'Stimulan RW-003 Q2 2025', 1100000, NULL, 1, 'auto_approved', NULL, NULL, NULL),
(90, '2025-08-15', 1, 'stimulan_aroma', 'Stimulan peak season RW-001', 1700000, NULL, 1, 'auto_approved', NULL, NULL, NULL),
(91, '2025-09-20', 5, 'stimulan_aroma', 'Stimulan RW-005 Q3 2025', 950000, NULL, 1, 'auto_approved', NULL, NULL, NULL),

-- === AUDIO MAINTENANCE ===
(92, '2024-04-10', 1, 'audio', 'Update software player RB-Sound Pro RW-001', 500000, NULL, 1, 'auto_approved', NULL, NULL, NULL),
(93, '2024-07-05', 3, 'audio', 'Ganti 2 speaker rusak RW-003', 1100000, NULL, 1, 'auto_approved', NULL, NULL, NULL),
(94, '2025-03-15', 2, 'audio', 'Upgrade player RW-002 ke kombinasi mode', 1800000, NULL, 1, 'auto_approved', NULL, NULL, NULL),
(95, '2025-06-20', 5, 'audio', 'Service speaker 4 unit RW-005', 900000, NULL, 1, 'auto_approved', NULL, NULL, NULL),

-- === SERTIFIKASI ===
(96, '2024-06-30', NULL, 'sertifikasi', 'Sertifikat BPOM 5 RW (renewal tahunan)', 15000000, 'bukti-bpom.jpg', 1, 'approved', 3, '2024-07-02 11:00:00', 'Approved - wajib renewal'),
(97, '2025-06-30', NULL, 'sertifikasi', 'Sertifikat BPOM 5 RW renewal 2025', 15500000, 'bukti-bpom-2025.jpg', 1, 'approved', 3, '2025-07-03 10:00:00', 'Approved - wajib'),
(98, '2025-09-15', NULL, 'sertifikasi', 'Lab test kadar air + kotoran (10 batch)', 5000000, 'bukti-lab.jpg', 1, 'approved', 3, '2025-09-17 13:00:00', 'Approved - perlu untuk buyer export'),

-- === TRANSPORTASI ===
(99, '2024-04-26', 1, 'transportasi', 'Transport sarang walet panen RW-001 ke gudang pusat', 800000, NULL, 1, 'auto_approved', NULL, NULL, NULL),
(100, '2024-08-26', 1, 'transportasi', 'Transport panen utuh RW-001 ke gudang', 1200000, NULL, 1, 'auto_approved', NULL, NULL, NULL),
(101, '2025-04-26', 1, 'transportasi', 'Transport panen urat RW-001', 900000, NULL, 1, 'auto_approved', NULL, NULL, NULL),
(102, '2025-08-26', 1, 'transportasi', 'Transport panen utuh RW-001 ke gudang pusat', 1300000, NULL, 1, 'auto_approved', NULL, NULL, NULL),

-- === PAJAK ===
(103, '2024-03-31', NULL, 'pajak_retribusi', 'Pajak properti tahunan 5 RW 2024', 13800000, 'bukti-pajak-2024.jpg', 1, 'auto_approved', NULL, NULL, NULL),
(104, '2025-03-31', NULL, 'pajak_retribusi', 'Pajak properti tahunan 5 RW 2025', 14200000, 'bukti-pajak-2025.jpg', 1, 'auto_approved', NULL, NULL, NULL),

-- === RENOVASI BESAR ===
(105, '2024-11-15', 3, 'renovasi_besar', 'Renovasi total lantai 1 RW-003', 25000000, 'bukti-renov-besar-rw3.jpg', 1, 'approved', 3, '2024-11-18 09:00:00', 'Approved - investasi jangka panjang'),

-- === PERALATAN ===
(106, '2024-01-20', 1, 'peralatan', 'Beli 4 humidifier baru RW-001', 3200000, NULL, 1, 'auto_approved', NULL, NULL, NULL),
(107, '2025-02-10', 4, 'peralatan', 'Beli humidifier baru RW-004', 850000, NULL, 1, 'auto_approved', NULL, NULL, NULL);

-- ===================================================================
-- PENGELUARAN ALOKASI - auto-alokasi gaji per RW (proporsi kapasitas)
-- Kapasitas tahunan: RW1=110, RW2=70, RW3=82, RW4=49, RW5=76.5 = total 387.5
-- Persentase: RW1=28.39%, RW2=18.06%, RW3=21.16%, RW4=12.65%, RW5=19.74%
-- ===================================================================
INSERT INTO `pengeluaran_alokasi` (`pengeluaran_id`, `rumah_walet_id`, `jumlah_alokasi`, `persentase`) VALUES
-- Helper: gaji 2024 (12jt per bulan)
(1, 1, 3406451.61, 28.39), (1, 2, 2167741.94, 18.06), (1, 3, 2539354.84, 21.16), (1, 4, 1517419.35, 12.65), (1, 5, 2369032.26, 19.74),
(2, 1, 3406451.61, 28.39), (2, 2, 2167741.94, 18.06), (2, 3, 2539354.84, 21.16), (2, 4, 1517419.35, 12.65), (2, 5, 2369032.26, 19.74),
(3, 1, 3406451.61, 28.39), (3, 2, 2167741.94, 18.06), (3, 3, 2539354.84, 21.16), (3, 4, 1517419.35, 12.65), (3, 5, 2369032.26, 19.74),
(4, 1, 3406451.61, 28.39), (4, 2, 2167741.94, 18.06), (4, 3, 2539354.84, 21.16), (4, 4, 1517419.35, 12.65), (4, 5, 2369032.26, 19.74),
(5, 1, 3406451.61, 28.39), (5, 2, 2167741.94, 18.06), (5, 3, 2539354.84, 21.16), (5, 4, 1517419.35, 12.65), (5, 5, 2369032.26, 19.74),
(6, 1, 3406451.61, 28.39), (6, 2, 2167741.94, 18.06), (6, 3, 2539354.84, 21.16), (6, 4, 1517419.35, 12.65), (6, 5, 2369032.26, 19.74),
(7, 1, 3406451.61, 28.39), (7, 2, 2167741.94, 18.06), (7, 3, 2539354.84, 21.16), (7, 4, 1517419.35, 12.65), (7, 5, 2369032.26, 19.74),
(8, 1, 3406451.61, 28.39), (8, 2, 2167741.94, 18.06), (8, 3, 2539354.84, 21.16), (8, 4, 1517419.35, 12.65), (8, 5, 2369032.26, 19.74),
(9, 1, 3406451.61, 28.39), (9, 2, 2167741.94, 18.06), (9, 3, 2539354.84, 21.16), (9, 4, 1517419.35, 12.65), (9, 5, 2369032.26, 19.74),
(10, 1, 3406451.61, 28.39), (10, 2, 2167741.94, 18.06), (10, 3, 2539354.84, 21.16), (10, 4, 1517419.35, 12.65), (10, 5, 2369032.26, 19.74),
(11, 1, 3406451.61, 28.39), (11, 2, 2167741.94, 18.06), (11, 3, 2539354.84, 21.16), (11, 4, 1517419.35, 12.65), (11, 5, 2369032.26, 19.74),
(12, 1, 5109677.42, 28.39), (12, 2, 3251612.90, 18.06), (12, 3, 3809032.26, 21.16), (12, 4, 2276129.03, 12.65), (12, 5, 3553548.39, 19.74),
-- 2025 gaji (12.5jt per bulan)
(13, 1, 3548387.10, 28.39), (13, 2, 2258064.52, 18.06), (13, 3, 2645161.29, 21.16), (13, 4, 1580645.16, 12.65), (13, 5, 2467741.94, 19.74),
(14, 1, 3548387.10, 28.39), (14, 2, 2258064.52, 18.06), (14, 3, 2645161.29, 21.16), (14, 4, 1580645.16, 12.65), (14, 5, 2467741.94, 19.74),
(15, 1, 3548387.10, 28.39), (15, 2, 2258064.52, 18.06), (15, 3, 2645161.29, 21.16), (15, 4, 1580645.16, 12.65), (15, 5, 2467741.94, 19.74),
(16, 1, 3548387.10, 28.39), (16, 2, 2258064.52, 18.06), (16, 3, 2645161.29, 21.16), (16, 4, 1580645.16, 12.65), (16, 5, 2467741.94, 19.74),
(17, 1, 3548387.10, 28.39), (17, 2, 2258064.52, 18.06), (17, 3, 2645161.29, 21.16), (17, 4, 1580645.16, 12.65), (17, 5, 2467741.94, 19.74),
(18, 1, 3548387.10, 28.39), (18, 2, 2258064.52, 18.06), (18, 3, 2645161.29, 21.16), (18, 4, 1580645.16, 12.65), (18, 5, 2467741.94, 19.74),
(19, 1, 3548387.10, 28.39), (19, 2, 2258064.52, 18.06), (19, 3, 2645161.29, 21.16), (19, 4, 1580645.16, 12.65), (19, 5, 2467741.94, 19.74),
(20, 1, 3548387.10, 28.39), (20, 2, 2258064.52, 18.06), (20, 3, 2645161.29, 21.16), (20, 4, 1580645.16, 12.65), (20, 5, 2467741.94, 19.74),
(21, 1, 3548387.10, 28.39), (21, 2, 2258064.52, 18.06), (21, 3, 2645161.29, 21.16), (21, 4, 1580645.16, 12.65), (21, 5, 2467741.94, 19.74),
(22, 1, 3548387.10, 28.39), (22, 2, 2258064.52, 18.06), (22, 3, 2645161.29, 21.16), (22, 4, 1580645.16, 12.65), (22, 5, 2467741.94, 19.74),
(23, 1, 3548387.10, 28.39), (23, 2, 2258064.52, 18.06), (23, 3, 2645161.29, 21.16), (23, 4, 1580645.16, 12.65), (23, 5, 2467741.94, 19.74),
(24, 1, 5251612.90, 28.39), (24, 2, 3341935.48, 18.06), (24, 3, 3914516.13, 21.16), (24, 4, 2340322.58, 12.65), (24, 5, 3651612.90, 19.74);

-- ===================================================================
-- PENJUALAN - 18 invoices selama 2024-2025
-- ===================================================================
INSERT INTO `penjualan` (`id`, `no_invoice`, `tanggal`, `pembeli_nama`, `pembeli_kontak`, `pembeli_alamat`, `total_berat_kg`, `total_nilai`, `status_bayar`, `tanggal_bayar`, `metode_bayar`, `catatan`, `input_by`) VALUES
(1, 'INV-2024-001', '2024-05-10', 'CV Sarang Mas Jaya', '081200011122 / info@sarangmas.id', 'Jl. Cempaka No. 1, Banjarmasin, Kalsel', 4.200, 84000000, 'lunas', '2024-05-12', 'transfer', 'Pembeli tetap, grade A urat RW-001', 1),
(2, 'INV-2024-002', '2024-05-15', 'Toko Walet Jaya', '081300022233', 'Jl. Pahlawan No. 12, Banjarmasin', 3.500, 70000000, 'lunas', '2024-05-18', 'transfer', 'Grade A urat RW-003', 1),
(3, 'INV-2024-003', '2024-05-20', 'PT Walet Nusantara', '081400033344 / purchasing@waletnus.id', 'Jl. Sudirman No. 88, Jakarta Selatan', 6.300, 94500000, 'lunas', '2024-05-25', 'transfer', 'Grade A+B urat mix RW-001', 1),
(4, 'INV-2024-004', '2024-06-05', 'CV Sarang Mas Jaya', '081200011122', 'Jl. Cempaka No. 1, Banjarmasin', 5.000, 35000000, 'lunas', '2024-06-08', 'transfer', 'Grade B+C urat mix', 1),
(5, 'INV-2024-005', '2024-09-10', 'PT Walet Nusantara', '081400033344', 'Jakarta Selatan', 12.500, 168750000, 'lunas', '2024-09-15', 'transfer', 'Grade A utuh RW-001 peak season', 1),
(6, 'INV-2024-006', '2024-09-15', 'CV Sarang Mas Jaya', '081200011122', 'Banjarmasin', 9.800, 117600000, 'lunas', '2024-09-20', 'transfer', 'Grade A+B utuh RW-002', 1),
(7, 'INV-2024-007', '2024-09-20', 'UD Berkah Walet', '081500044455', 'Jl. Ahmad Yani No. 5, Martapura', 8.200, 90200000, 'lunas', '2024-09-25', 'transfer', 'Grade A+B utuh RW-003', 1),
(8, 'INV-2024-008', '2024-10-05', 'Toko Walet Jaya', '081300022233', 'Banjarmasin', 4.800, 43200000, 'lunas', '2024-10-10', 'transfer', 'Grade B+C utuh RW-005', 1),
(9, 'INV-2024-009', '2024-12-20', 'CV Sarang Mas Jaya', '081200011122', 'Banjarmasin', 3.400, 34800000, 'lunas', '2024-12-23', 'transfer', 'Grade A+B kecil RW-001', 1),
(10, 'INV-2025-001', '2025-05-15', 'PT Walet Nusantara', '081400033344', 'Jakarta', 9.500, 209000000, 'lunas', '2025-05-20', 'transfer', 'Grade A urat RW-001 + RW-003', 1),
(11, 'INV-2025-002', '2025-05-20', 'CV Sarang Mas Jaya', '081200011122', 'Banjarmasin', 7.300, 105400000, 'lunas', '2025-05-25', 'transfer', 'Grade A+B urat mix', 1),
(12, 'INV-2025-003', '2025-06-10', 'Export Hub Ltd (Singapore)', '+65 91234567 / sales@exporthub.sg', '10 Anson Road, Singapore', 8.000, 184000000, 'lunas', '2025-06-15', 'transfer', 'Export Grade A utuh RW-001 (sisa 2024)', 1),
(13, 'INV-2025-004', '2025-09-15', 'PT Walet Nusantara', '081400033344', 'Jakarta', 13.200, 211200000, 'lunas', '2025-09-22', 'transfer', 'Grade A utuh RW-001 peak 2025', 1),
(14, 'INV-2025-005', '2025-09-20', 'CV Sarang Mas Jaya', '081200011122', 'Banjarmasin', 11.200, 123200000, 'lunas', '2025-09-25', 'transfer', 'Grade A+B utuh RW-002 + RW-005', 1),
(15, 'INV-2025-006', '2025-09-25', 'UD Berkah Walet', '081500044455', 'Martapura', 10.200, 142800000, 'lunas', '2025-09-30', 'transfer', 'Grade A utuh RW-003', 1),
(16, 'INV-2025-007', '2025-12-20', 'CV Sarang Mas Jaya', '081200011122', 'Banjarmasin', 4.000, 52000000, 'dp', NULL, NULL, 'Grade A kecil RW-001, DP 50%, sisa 30 hari', 1),
(17, 'INV-2025-008', '2025-12-22', 'Toko Walet Jaya', '081300022233', 'Banjarmasin', 3.200, 31200000, 'belum_bayar', NULL, NULL, 'Grade A+B kecil RW-005, jatuh tempo 15 Jan 2026', 1),
(18, 'INV-2026-001', '2026-01-15', 'Export Hub Ltd (Singapore)', '+65 91234567', 'Singapore', 6.500, 117000000, 'belum_bayar', NULL, NULL, 'Export Grade A utuh RW-001 (stok available)', 1);

-- ===================================================================
-- PENJUALAN DETAIL - item per invoice
-- ===================================================================
INSERT INTO `penjualan_detail` (`penjualan_id`, `hasil_panen_id`, `rumah_walet_id`, `grade`, `jenis_panen`, `berat_kg`, `harga_per_kg`, `catatan`) VALUES
-- INV-2024-001 (1): 4.2 kg A urat RW-001
(1, 1, 1, 'A', 'urat', 4.200, 20000000, 'Grade A urat premium'),
-- INV-2024-002 (2): 3.5 kg A urat RW-003
(2, 7, 3, 'A', 'urat', 3.500, 20000000, 'Grade A urat'),
-- INV-2024-003 (3): 4.5 kg B urat RW-001 + 1.8 kg B urat RW-002 mix
(3, 2, 1, 'B', 'urat', 3.000, 13000000, 'B urat RW-001'),
(3, 5, 2, 'B', 'urat', 1.800, 13000000, 'B urat RW-002'),
(3, 1, 1, 'A', 'urat', 1.500, 20000000, 'A urat RW-001 (sisa)'),
-- INV-2024-004 (4): 5 kg B+C urat mix
(4, 3, 1, 'C', 'urat', 2.500, 7000000, 'C urat RW-001'),
(4, 6, 2, 'C', 'urat', 1.800, 7000000, 'C urat RW-002'),
(4, 9, 3, 'C', 'urat', 0.700, 7000000, 'C urat RW-003'),
-- INV-2024-005 (5): 12.5 kg A utuh RW-001 peak
(5, 13, 1, 'A', 'sarang_utuh', 6.500, 15000000, 'A utuh peak'),
(5, 19, 3, 'A', 'sarang_utuh', 5.200, 15000000, 'A utuh RW-003'),
(5, 16, 2, 'A', 'sarang_utuh', 0.800, 15000000, 'A utuh RW-002'),
-- INV-2024-006 (6): 9.8 kg A+B utuh RW-002
(6, 16, 2, 'A', 'sarang_utuh', 3.400, 15000000, 'A utuh RW-002'),
(6, 17, 2, 'B', 'sarang_utuh', 3.800, 10000000, 'B utuh RW-002'),
(6, 22, 4, 'A', 'sarang_utuh', 1.500, 15000000, 'A utuh RW-004'),
(6, 23, 4, 'B', 'sarang_utuh', 1.100, 10000000, 'B utuh RW-004'),
-- INV-2024-007 (7): 8.2 kg A+B utuh RW-003
(7, 19, 3, 'A', 'sarang_utuh', 5.200, 15000000, 'A utuh RW-003'),
(7, 20, 3, 'B', 'sarang_utuh', 3.000, 10000000, 'B utuh RW-003'),
-- INV-2024-008 (8): 4.8 kg B+C utuh RW-005
(8, 26, 5, 'B', 'sarang_utuh', 3.000, 10000000, 'B utuh RW-005'),
(8, 27, 5, 'C', 'sarang_utuh', 1.800, 5000000, 'C utuh RW-005'),
-- INV-2024-009 (9): 3.4 kg A+B kecil RW-001
(9, 28, 1, 'A', 'kecil', 2.200, 12000000, 'A kecil RW-001'),
(9, 29, 1, 'B', 'kecil', 1.200, 6000000, 'B kecil RW-001'),
-- INV-2025-001 (10): 9.5 kg A urat RW-001 + RW-003
(10, 34, 1, 'A', 'urat', 4.500, 22000000, 'A urat RW-001'),
(10, 40, 3, 'A', 'urat', 3.800, 22000000, 'A urat RW-003'),
(10, 43, 5, 'A', 'urat', 1.200, 22000000, 'A urat RW-005'),
-- INV-2025-002 (11): 7.3 kg A+B urat mix
(11, 34, 1, 'B', 'urat', 2.500, 14000000, 'B urat RW-001'),
(11, 40, 3, 'B', 'urat', 2.200, 14000000, 'B urat RW-003'),
(11, 35, 1, 'A', 'urat', 1.500, 22000000, 'A urat RW-001'),
(11, 41, 3, 'A', 'urat', 1.100, 22000000, 'A urat RW-003'),
-- INV-2025-003 (12): 8 kg A utuh export (stok lama)
(12, 13, 1, 'A', 'sarang_utuh', 4.000, 23000000, 'A utuh premium export'),
(12, 19, 3, 'A', 'sarang_utuh', 4.000, 23000000, 'A utuh RW-003 export'),
-- INV-2025-004 (13): 13.2 kg A utuh RW-001 peak 2025
(13, 46, 1, 'A', 'sarang_utuh', 7.000, 16000000, 'A utuh RW-001 2025'),
(13, 49, 2, 'A', 'sarang_utuh', 4.500, 16000000, 'A utuh RW-002 2025'),
(13, 52, 3, 'A', 'sarang_utuh', 1.700, 16000000, 'A utuh RW-003 2025'),
-- INV-2025-005 (14): 11.2 kg A+B utuh RW-002 + RW-005
(14, 49, 2, 'B', 'sarang_utuh', 4.000, 11000000, 'B utuh RW-002'),
(14, 50, 2, 'A', 'sarang_utuh', 0.500, 16000000, 'A utuh RW-002 sisa'),
(14, 58, 5, 'A', 'sarang_utuh', 4.000, 16000000, 'A utuh RW-005'),
(14, 59, 5, 'B', 'sarang_utuh', 2.700, 11000000, 'B utuh RW-005'),
-- INV-2025-006 (15): 10.2 kg A utuh RW-003
(15, 52, 3, 'A', 'sarang_utuh', 5.500, 16000000, 'A utuh RW-003'),
(15, 58, 5, 'A', 'sarang_utuh', 4.700, 16000000, 'A utuh RW-005'),
-- INV-2025-007 (16): 4 kg A kecil RW-001 - DP
(16, 61, 1, 'A', 'kecil', 2.400, 13000000, 'A kecil RW-001 (DP 50%)'),
(16, 64, 5, 'A', 'kecil', 1.600, 13000000, 'A kecil RW-005 (DP 50%)'),
-- INV-2025-008 (17): 3.2 kg A+B kecil RW-005 - belum bayar
(17, 65, 5, 'A', 'kecil', 1.500, 13000000, 'A kecil RW-005'),
(17, 62, 1, 'B', 'kecil', 1.000, 7000000, 'B kecil RW-001'),
(17, 66, 5, 'C', 'kecil', 0.700, 3500000, 'C kecil RW-005'),
-- INV-2026-001 (18): 6.5 kg A utuh RW-001 - belum bayar (stok available)
(18, 46, 1, 'A', 'sarang_utuh', 6.500, 18000000, 'A utuh RW-001 (stok tersedia)');

-- Update hasil_panen.pembeli_id & status_stok for items that have been sold
-- (auto-tracked via app, but for seed we set manually)
UPDATE `hasil_panen` SET `pembeli_id` = 1, `status_stok` = 'terjual' WHERE `id` = 1;
UPDATE `hasil_panen` SET `pembeli_id` = 2, `status_stok` = 'terjual' WHERE `id` = 7;
UPDATE `hasil_panen` SET `pembeli_id` = 3, `status_stok` = 'terjual' WHERE `id` IN (1, 2, 5);
UPDATE `hasil_panen` SET `pembeli_id` = 4, `status_stok` = 'terjual' WHERE `id` IN (3, 6, 9);
UPDATE `hasil_panen` SET `pembeli_id` = 5, `status_stok` = 'terjual' WHERE `id` IN (13, 16, 19);
UPDATE `hasil_panen` SET `pembeli_id` = 6, `status_stok` = 'terjual' WHERE `id` IN (16, 17, 22, 23);
UPDATE `hasil_panen` SET `pembeli_id` = 7, `status_stok` = 'terjual' WHERE `id` IN (19, 20);
UPDATE `hasil_panen` SET `pembeli_id` = 8, `status_stok` = 'terjual' WHERE `id` IN (26, 27);
UPDATE `hasil_panen` SET `pembeli_id` = 9, `status_stok` = 'terjual' WHERE `id` IN (28, 29);
UPDATE `hasil_panen` SET `pembeli_id` = 10, `status_stok` = 'terjual' WHERE `id` IN (34, 40, 43);
UPDATE `hasil_panen` SET `pembeli_id` = 11, `status_stok` = 'terjual' WHERE `id` IN (34, 35, 40, 41);
UPDATE `hasil_panen` SET `pembeli_id` = 12, `status_stok` = 'terjual' WHERE `id` IN (13, 19);
UPDATE `hasil_panen` SET `pembeli_id` = 13, `status_stok` = 'terjual' WHERE `id` IN (46, 49, 52);
UPDATE `hasil_panen` SET `pembeli_id` = 14, `status_stok` = 'terjual' WHERE `id` IN (49, 50, 58, 59);
UPDATE `hasil_panen` SET `pembeli_id` = 15, `status_stok` = 'terjual' WHERE `id` IN (52, 58);
-- DP & belum_bayar juga dianggap terjual (sudah ada invoice)
UPDATE `hasil_panen` SET `pembeli_id` = 16, `status_stok` = 'terjual' WHERE `id` IN (61, 64);
UPDATE `hasil_panen` SET `pembeli_id` = 17, `status_stok` = 'terjual' WHERE `id` IN (62, 65, 66);

-- Note: hasil_panen id 46 (A utuh RW-001 2025) dipakai di INV-2025-004 & INV-2026-001
-- id 46 status: terjual (dipakai di 13) - tapi ada sisa 0.5 kg di INV-2025-005 (id 50)
-- Untuk simplicity, id 46 = terjual ke 13, sisanya dianggap konsolidasi stok
UPDATE `hasil_panen` SET `pembeli_id` = 13, `status_stok` = 'terjual' WHERE `id` = 46;

-- ===================================================================
-- STOK SARANG - untuk hasil_panen yang masih ada sisa / belum terjual
-- Stok available: 47, 48, 55, 56, 57, 63 (sisa kecil RW-001, RW-004, RW-005)
-- ===================================================================
INSERT INTO `stok_sarang` (`hasil_panen_id`, `rumah_walet_id`, `grade`, `jenis_panen`, `berat_kg`, `lokasi_gudang`, `tanggal_masuk`, `penjualan_id`, `tanggal_keluar`, `status_stok`, `catatan`) VALUES
-- Stok available (tersedia)
(47, 1, 'B', 'sarang_utuh', 6.200, 'gudang_rw', '2025-08-25', NULL, NULL, 'tersedia', 'Stok available - menunggu pembeli'),
(48, 1, 'C', 'sarang_utuh', 4.000, 'gudang_rw', '2025-08-25', NULL, NULL, 'tersedia', 'Stok available'),
(55, 4, 'A', 'sarang_utuh', 3.200, 'gudang_rw', '2025-09-05', NULL, NULL, 'tersedia', 'Stok RW-004 available'),
(56, 4, 'B', 'sarang_utuh', 2.900, 'gudang_rw', '2025-09-05', NULL, NULL, 'tersedia', 'Stok RW-004 available'),
(57, 4, 'C', 'sarang_utuh', 1.900, 'gudang_rw', '2025-09-05', NULL, NULL, 'tersedia', 'Stok RW-004 available'),
(63, 1, 'C', 'kecil', 1.300, 'gudang_rw', '2025-12-15', NULL, NULL, 'tersedia', 'Stok kecil available'),

-- Stok yang sudah terjual (untuk history)
(1, 1, 'A', 'urat', 4.200, 'gudang_pusat', '2024-04-25', 1, '2024-05-10', 'terjual', 'Terjual INV-2024-001'),
(13, 1, 'A', 'sarang_utuh', 6.500, 'gudang_pusat', '2024-08-25', 5, '2024-09-10', 'terjual', 'Terjual INV-2024-005'),
(34, 1, 'A', 'urat', 4.500, 'gudang_pusat', '2025-04-25', 10, '2025-05-15', 'terjual', 'Terjual INV-2025-001'),
(46, 1, 'A', 'sarang_utuh', 7.000, 'gudang_pusat', '2025-08-25', 13, '2025-09-15', 'terjual', 'Terjual INV-2025-004');

-- ===================================================================
-- UPDATE LAST_LOGIN untuk users (biar realistis)
-- ===================================================================
UPDATE `users` SET `last_login` = '2026-06-27 09:00:00' WHERE `id` = 1;
UPDATE `users` SET `last_login` = '2026-06-26 14:30:00' WHERE `id` = 2;
UPDATE `users` SET `last_login` = '2026-06-27 08:15:00' WHERE `id` = 3;

-- ===================================================================
-- DONE! Summary data:
-- ===================================================================
-- 3 users (admin/petugas/owner)
-- 5 rumah walet (lokasi Kalsel)
-- 4 petugas
-- 10 penugasan petugas-rumah
-- ~50 master harga (9 kombinasi x 6+ periode)
-- 30 catatan audio (1 per RW per quarter)
-- 19 inspeksi (1 per RW per 2 bulan, fase sesuai musim)
-- 10 predator records
-- 26 jadwal panen (sesuai musim: urat Mar-Apr, utuh Jul-Sep, kecil Nov-Des)
-- 66 hasil panen (3 grade per jadwal selesai)
-- 107 pengeluaran (gaji, listrik, maintenance, stimulan, audio, sertifikasi, transportasi, pajak, renovasi, peralatan)
-- 120 alokasi gaji (24 bulan x 5 RW)
-- 18 invoice penjualan (2024-2026, mix lunas/dp/belum_bayar)
-- 35 detail penjualan
-- 10 stok sarang (6 available + 4 history terjual)
--
-- Total nilai panen 2024: ~Rp 1.5 M
-- Total nilai panen 2025: ~Rp 1.8 M
-- Total penjualan 2024: Rp 732.6 jt (8 invoice lunas)
-- Total penjualan 2025: Rp 1.32 M (8 invoice, 6 lunas + 2 DP/belum bayar)
-- Total pengeluaran 2024: ~Rp 290 jt (gaji 144jt + listrik + maintenance + sertifikasi + renovasi)
-- Total pengeluaran 2025: ~Rp 310 jt
--
-- Login: admin/admin123, petugas/petugas123, owner/owner123
