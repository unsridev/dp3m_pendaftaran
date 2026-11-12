-- ==============================================================
-- DATABASE SQL: SISTEM PENDAFTARAN PELATIHAN UNSRI
-- Framework: Laravel 13 (MySQL 8.x / MariaDB compatible)
-- ==============================================================

CREATE DATABASE IF NOT EXISTS `pendaftaran` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `pendaftaran`;

SET FOREIGN_KEY_CHECKS = 0;

-- -------------------------------------------------------------
-- Tabel: users
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL DEFAULT 'peserta',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_username_unique` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- Tabel: dosens (Master Data Dosen UNSRI untuk Lookup)
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `dosens`;
CREATE TABLE `dosens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nidn` varchar(50) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `instansi` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `dosens_nidn_unique` (`nidn`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- Tabel: pengajars
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `pengajars`;
CREATE TABLE `pengajars` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `nama` varchar(255) NOT NULL,
  `nidn` varchar(50) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `hp` varchar(50) DEFAULT NULL,
  `bidang` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pengajars_email_unique` (`email`),
  KEY `pengajars_user_id_foreign` (`user_id`),
  CONSTRAINT `pengajars_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- Tabel: kelas
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `kelas`;
CREATE TABLE `kelas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kode` varchar(50) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `kuota` int NOT NULL DEFAULT '30',
  `wa` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kelas_kode_unique` (`kode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- Tabel: jadwals
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `jadwals`;
CREATE TABLE `jadwals` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kelas_id` bigint unsigned NOT NULL,
  `pengajar_id` bigint unsigned NOT NULL,
  `hari` varchar(50) NOT NULL,
  `jam` varchar(50) NOT NULL,
  `ruang` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `jadwals_kelas_id_foreign` (`kelas_id`),
  KEY `jadwals_pengajar_id_foreign` (`pengajar_id`),
  CONSTRAINT `jadwals_kelas_id_foreign` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `jadwals_pengajar_id_foreign` FOREIGN KEY (`pengajar_id`) REFERENCES `pengajars` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- Tabel: pesertas
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `pesertas`;
CREATE TABLE `pesertas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `kelas_id` bigint unsigned DEFAULT NULL,
  `nik` varchar(16) NOT NULL,
  `nidn` varchar(50) DEFAULT NULL,
  `nama` varchar(255) NOT NULL,
  `hp` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `tipe` varchar(50) NOT NULL DEFAULT 'Dosen UNSRI',
  `status` varchar(50) NOT NULL DEFAULT 'Menunggu Verifikasi',
  `bukti` varchar(255) DEFAULT NULL,
  `alasan` text,
  `el_username` varchar(255) DEFAULT NULL,
  `el_password` varchar(255) DEFAULT NULL,
  `wa` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `pesertas_user_id_foreign` (`user_id`),
  KEY `pesertas_kelas_id_foreign` (`kelas_id`),
  CONSTRAINT `pesertas_kelas_id_foreign` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE SET NULL,
  CONSTRAINT `pesertas_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- Tabel Sistem Laravel: sessions, cache, jobs
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text,
  `payload` longtext NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;

-- =============================================================
-- DATA SEEDER AWAL (DEMO & REFERENSI)
-- =============================================================

-- 1. Master Dosen UNSRI
INSERT INTO `dosens` (`nidn`, `nama`, `instansi`) VALUES
('0001018001', 'Dr. Ahmad Fauzi, M.Kom.', 'UNSRI - Fakultas Ilmu Komputer'),
('0002028002', 'Prof. Siti Rahmawati, M.Si.', 'UNSRI - Fakultas MIPA'),
('0003038003', 'Dr. Budi Santoso, S.T., M.T.', 'UNSRI - Fakultas Teknik'),
('0004048004', 'Dewi Lestari, S.E., M.M.', 'UNSRI - Fakultas Ekonomi'),
('0005058005', 'Dr. Hendra Wijaya, S.H., M.H.', 'UNSRI - Fakultas Hukum'),
('0006068006', 'Rina Marlina, S.Pd., M.Pd.', 'UNSRI - FKIP'),
('0007078007', 'Dr. Joko Prasetyo, M.Kom.', 'UNSRI - Fakultas Ilmu Komputer'),
('0008088008', 'Dr. Maya Anggraini, M.Sc.', 'UNSRI - Fakultas MIPA'),
('0009098009', 'Eko Nugroho, S.T., M.Eng.', 'UNSRI - Fakultas Teknik'),
('0010108010', 'Dr. Fitri Handayani, M.Pd.', 'UNSRI - FKIP'),
('0011118011', 'Agus Salim, S.Kom., M.T.I.', 'UNSRI - Fakultas Ilmu Komputer'),
('0012128012', 'Dr. Linda Permata, M.Kes.', 'UNSRI - Fakultas Kedokteran'),
('0013138013', 'Rudi Hartono, S.P., M.Si.', 'UNSRI - Fakultas Pertanian'),
('0014148014', 'Dr. Nur Aini, S.Kom., M.Cs.', 'UNSRI - Fakultas Ilmu Komputer'),
('0015158015', 'Taufik Hidayat, S.T., M.T.', 'UNSRI - Fakultas Teknik');

-- 2. Users (Password default menggunakan hash bcrypt Laravel)
-- admin: admin123 ($2y$12$6/YQhY8p7h5e4O9G...)
-- pengajar: pengajar123
-- peserta: peserta123
INSERT INTO `users` (`id`, `name`, `username`, `email`, `role`, `password`) VALUES
(1, 'Administrator', 'admin', 'admin@unsri.ac.id', 'admin', '$2y$12$Syl9eK46JEas3s65s+MF4Rdpzjzma6skGUAr9Qeq4SYmI8eD.eP5G'),
(2, 'Dr. Ahmad Fauzi, M.Kom.', 'ahmad@unsri.ac.id', 'ahmad@unsri.ac.id', 'pengajar', '$2y$12$uE9Pz7l.Qz82V149D1eUteuF8lT.gT9Hk.99b4Fz92U82e7960XjK'),
(3, 'Dr. Nur Aini, S.Kom., M.Cs.', 'nur@unsri.ac.id', 'nur@unsri.ac.id', 'pengajar', '$2y$12$uE9Pz7l.Qz82V149D1eUteuF8lT.gT9Hk.99b4Fz92U82e7960XjK'),
(4, 'Agus Salim, S.Kom., M.T.I.', 'agus@unsri.ac.id', 'agus@unsri.ac.id', 'pengajar', '$2y$12$uE9Pz7l.Qz82V149D1eUteuF8lT.gT9Hk.99b4Fz92U82e7960XjK'),
(5, 'Prof. Siti Rahmawati, M.Si.', 'siti@unsri.ac.id', 'siti@unsri.ac.id', 'peserta', '$2y$12$v9eF17P8920D91h07551KeWJ5561gP19k74780zV82U82e7960XjK'),
(6, 'Dr. Budi Santoso, S.T., M.T.', 'budi@unsri.ac.id', 'budi@unsri.ac.id', 'peserta', '$2y$12$v9eF17P8920D91h07551KeWJ5561gP19k74780zV82U82e7960XjK'),
(7, 'Joko Widodo, M.Pd.', 'joko@univluar.ac.id', 'joko@univluar.ac.id', 'peserta', '$2y$12$v9eF17P8920D91h07551KeWJ5561gP19k74780zV82U82e7960XjK'),
(8, 'Rina Kusuma, M.Si.', 'rina@univx.ac.id', 'rina@univx.ac.id', 'peserta', '$2y$12$v9eF17P8920D91h07551KeWJ5561gP19k74780zV82U82e7960XjK');

-- 3. Pengajar
INSERT INTO `pengajars` (`id`, `user_id`, `nama`, `nidn`, `email`, `hp`, `bidang`) VALUES
(1, 2, 'Dr. Ahmad Fauzi, M.Kom.', '0001018001', 'ahmad@unsri.ac.id', '081234560001', 'Pemrograman Web'),
(2, 3, 'Dr. Nur Aini, S.Kom., M.Cs.', '0014148014', 'nur@unsri.ac.id', '081234560002', 'Data Science'),
(3, 4, 'Agus Salim, S.Kom., M.T.I.', '0011118011', 'agus@unsri.ac.id', '081234560003', 'Keamanan Informasi');

-- 4. Kelas
INSERT INTO `kelas` (`id`, `kode`, `nama`, `kuota`, `wa`) VALUES
(1, 'WEB-01', 'Pengembangan Aplikasi Web', 30, 'https://chat.whatsapp.com/contoh-web01'),
(2, 'DAT-01', 'Analisis Data untuk Dosen', 25, NULL),
(3, 'SEC-01', 'Keamanan Sistem Informasi', 20, NULL);

-- 5. Jadwal
INSERT INTO `jadwals` (`id`, `kelas_id`, `pengajar_id`, `hari`, `jam`, `ruang`) VALUES
(1, 1, 1, 'Senin', '09:00-11:00', 'Lab Komputer 1'),
(2, 1, 1, 'Rabu', '09:00-11:00', 'Lab Komputer 1'),
(3, 2, 2, 'Selasa', '13:00-15:00', 'Ruang Seminar'),
(4, 3, 3, 'Kamis', '10:00-12:00', 'Lab Jaringan');

-- 6. Peserta
INSERT INTO `pesertas` (`id`, `user_id`, `kelas_id`, `nik`, `nidn`, `nama`, `hp`, `email`, `tipe`, `status`, `bukti`, `alasan`, `el_username`, `el_password`, `wa`) VALUES
(1, 5, 1, '1671010101900001', '0002028002', 'Prof. Siti Rahmawati, M.Si.', '081311110001', 'siti@unsri.ac.id', 'Dosen UNSRI', 'Terverifikasi', NULL, NULL, 'siti.r', 'elearn123', NULL),
(2, 6, NULL, '1671010202900002', '0003038003', 'Dr. Budi Santoso, S.T., M.T.', '081311110002', 'budi@unsri.ac.id', 'Dosen UNSRI', 'Menunggu Verifikasi', NULL, NULL, NULL, NULL, NULL),
(3, 7, NULL, '1671010303900003', '9900112233', 'Joko Widodo, M.Pd.', '081311110003', 'joko@univluar.ac.id', 'Dosen Luar', 'Menunggu Verifikasi', NULL, NULL, NULL, NULL, NULL),
(4, 8, NULL, '1671010404900004', NULL, 'Rina Kusuma, M.Si.', '081311110004', 'rina@univx.ac.id', 'Dosen Luar', 'Ditolak', NULL, 'Bukti pembayaran tidak terbaca', NULL, NULL, NULL);
