-- ============================================
-- DATABASE: pengaduan_sarana
-- SMK Sangkuriang 1 Cimahi
-- Versi: 3.0 (2026) - MySQL
-- ============================================

CREATE DATABASE IF NOT EXISTS `pengaduan_sarana`
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE `pengaduan_sarana`;

-- ============================================
-- TABEL: admins
-- Menyimpan data seluruh staff/guru
-- Role: kepala_sekolah | guru | sapras | kesiswaan | guru_bk
-- ============================================
CREATE TABLE `admins` (
    `id`         BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `username`   VARCHAR(255) NOT NULL UNIQUE,
    `nama`       VARCHAR(255) NOT NULL,
    `password`   VARCHAR(255) NOT NULL,
    `role`       ENUM('kepala_sekolah','guru','sapras','kesiswaan','guru_bk') NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- TABEL: siswas
-- Menyimpan data siswa yang bisa login
-- ============================================
CREATE TABLE `siswas` (
    `nis`        BIGINT UNSIGNED PRIMARY KEY,
    `nama_siswa` VARCHAR(255) NOT NULL,
    `kelas`      VARCHAR(10) NOT NULL,
    `password`   VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- TABEL: kategoris
-- Menyimpan kategori laporan
-- Jenis: sarana_prasarana | kesejahteraan_siswa
-- ============================================
CREATE TABLE `kategoris` (
    `id_kategori`  BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `ket_kategori` VARCHAR(50) NOT NULL,
    `jenis`        ENUM('sarana_prasarana','kesejahteraan_siswa') NOT NULL,
    `created_at`   TIMESTAMP NULL DEFAULT NULL,
    `updated_at`   TIMESTAMP NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- TABEL: aspirasis
-- Menyimpan status & feedback setiap laporan
-- Status: Menunggu | Proses | Selesai
-- ============================================
CREATE TABLE `aspirasis` (
    `id_aspirasi` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `status`      ENUM('Menunggu','Proses','Selesai') NOT NULL DEFAULT 'Menunggu',
    `id_kategori` BIGINT UNSIGNED NOT NULL,
    `feedback`    TEXT NULL,
    `created_at`  TIMESTAMP NULL DEFAULT NULL,
    `updated_at`  TIMESTAMP NULL DEFAULT NULL,
    CONSTRAINT `fk_aspirasi_kategori`
        FOREIGN KEY (`id_kategori`) REFERENCES `kategoris`(`id_kategori`)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- TABEL: input_aspirasis
-- Menyimpan isi laporan dari siswa
-- ============================================
CREATE TABLE `input_aspirasis` (
    `id_pelaporan` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `nis`          BIGINT UNSIGNED NOT NULL,
    `id_aspirasi`  BIGINT UNSIGNED NOT NULL,
    `id_kategori`  BIGINT UNSIGNED NOT NULL,
    `lokasi`       VARCHAR(100) NOT NULL,
    `ket`          TEXT NOT NULL,
    `foto`         VARCHAR(255) NULL,
    `anonim`       TINYINT(1) NOT NULL DEFAULT 0,
    `created_at`   TIMESTAMP NULL DEFAULT NULL,
    `updated_at`   TIMESTAMP NULL DEFAULT NULL,
    CONSTRAINT `fk_input_siswa`
        FOREIGN KEY (`nis`) REFERENCES `siswas`(`nis`)
        ON DELETE CASCADE,
    CONSTRAINT `fk_input_aspirasi`
        FOREIGN KEY (`id_aspirasi`) REFERENCES `aspirasis`(`id_aspirasi`)
        ON DELETE CASCADE,
    CONSTRAINT `fk_input_kategori`
        FOREIGN KEY (`id_kategori`) REFERENCES `kategoris`(`id_kategori`)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- DATA AWAL: Admin / Staff
-- (password di-hash bcrypt, jalankan seeder untuk generate)
-- ============================================
INSERT INTO `admins` (`username`, `nama`, `role`, `password`, `created_at`, `updated_at`) VALUES
('kepsek',    'Drs. H. Ahmad Subarnas, M.Pd', 'kepala_sekolah', '$2y$12$HASH_KEPSEK',    NOW(), NOW()),
('guru1',     'Bpk. Deni Kurniawan, S.Kom',   'guru',           '$2y$12$HASH_GURU',      NOW(), NOW()),
('sapras',    'Bpk. Rudi Hartono, S.T',        'sapras',         '$2y$12$HASH_SAPRAS',    NOW(), NOW()),
('kesiswaan', 'Ibu Sari Dewi, S.Pd',           'kesiswaan',      '$2y$12$HASH_KESISWAAN', NOW(), NOW()),
('gurubk',    'Ibu Rina Fitriani, S.Psi',      'guru_bk',        '$2y$12$HASH_GURUBK',   NOW(), NOW());

-- ============================================
-- DATA AWAL: Kategori
-- ============================================
INSERT INTO `kategoris` (`ket_kategori`, `jenis`, `created_at`, `updated_at`) VALUES
('Toilet & Kamar Mandi',  'sarana_prasarana',    NOW(), NOW()),
('Ruang Kelas',           'sarana_prasarana',    NOW(), NOW()),
('Laboratorium',          'sarana_prasarana',    NOW(), NOW()),
('Perpustakaan',          'sarana_prasarana',    NOW(), NOW()),
('Lapangan & Olahraga',   'sarana_prasarana',    NOW(), NOW()),
('Kantin Sekolah',        'sarana_prasarana',    NOW(), NOW()),
('Area Parkir',           'sarana_prasarana',    NOW(), NOW()),
('Listrik & Elektronik',  'sarana_prasarana',    NOW(), NOW()),
('Bullying / Perundungan','kesejahteraan_siswa', NOW(), NOW()),
('Kekerasan Fisik',       'kesejahteraan_siswa', NOW(), NOW()),
('Masalah Psikologis',    'kesejahteraan_siswa', NOW(), NOW()),
('Masalah Akademik',      'kesejahteraan_siswa', NOW(), NOW()),
('Permasalahan Sosial',   'kesejahteraan_siswa', NOW(), NOW()),
('Saran & Masukan Lainnya','kesejahteraan_siswa',NOW(), NOW());

-- ============================================
-- DATA AWAL: Siswa Demo
-- ============================================
INSERT INTO `siswas` (`nis`, `nama_siswa`, `kelas`, `password`, `created_at`, `updated_at`) VALUES
(2024001, 'Budi Santoso',  'XII RPL 1', '$2y$12$HASH_SISWA', NOW(), NOW()),
(2024002, 'Siti Rahayu',   'XII RPL 2', '$2y$12$HASH_SISWA', NOW(), NOW()),
(2024003, 'Ahmad Fauzi',   'XI RPL 1',  '$2y$12$HASH_SISWA', NOW(), NOW()),
(2024004, 'Dewi Lestari',  'XI RPL 2',  '$2y$12$HASH_SISWA', NOW(), NOW()),
(2024005, 'Rizki Pratama', 'X RPL 1',   '$2y$12$HASH_SISWA', NOW(), NOW());

-- ============================================
-- CATATAN PENGGUNAAN MYSQL:
-- 1. Jalankan file ini di phpMyAdmin atau MySQL CLI
-- 2. Lalu update .env:
--    DB_CONNECTION=mysql
--    DB_HOST=127.0.0.1
--    DB_PORT=3306
--    DB_DATABASE=pengaduan_sarana
--    DB_USERNAME=root
--    DB_PASSWORD=
-- 3. Jalankan: php artisan db:seed
--    (untuk generate hash password yang benar)
-- ============================================
