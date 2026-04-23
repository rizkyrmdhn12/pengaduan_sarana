-- ============================================
-- Database: Pengaduan Sarana SMK Sangkuriang 1
-- Updated: 2026-04-23 (v2 - dengan foto & multi-role)
-- ============================================

CREATE TABLE IF NOT EXISTS `admins` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(255) NOT NULL UNIQUE,
  `nama` VARCHAR(255) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('kepala_sekolah','guru','sapras','kesiswaan','guru_bk') NOT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL
);

CREATE TABLE IF NOT EXISTS `siswas` (
  `nis` BIGINT UNSIGNED PRIMARY KEY,
  `nama_siswa` VARCHAR(255) NOT NULL,
  `kelas` VARCHAR(10) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL
);

CREATE TABLE IF NOT EXISTS `kategoris` (
  `id_kategori` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `ket_kategori` VARCHAR(50) NOT NULL,
  `jenis` ENUM('sarana_prasarana','kesejahteraan_siswa') NOT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL
);

CREATE TABLE IF NOT EXISTS `aspirasis` (
  `id_aspirasi` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `status` ENUM('Menunggu','Proses','Selesai') NOT NULL DEFAULT 'Menunggu',
  `id_kategori` BIGINT UNSIGNED NOT NULL,
  `feedback` TEXT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  FOREIGN KEY (`id_kategori`) REFERENCES `kategoris`(`id_kategori`) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS `input_aspirasis` (
  `id_pelaporan` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `nis` BIGINT UNSIGNED NOT NULL,
  `id_aspirasi` BIGINT UNSIGNED NOT NULL,
  `id_kategori` BIGINT UNSIGNED NOT NULL,
  `lokasi` VARCHAR(100) NOT NULL,
  `ket` TEXT NOT NULL,
  `foto` VARCHAR(255) NULL,
  `anonim` TINYINT(1) DEFAULT 0,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  FOREIGN KEY (`nis`) REFERENCES `siswas`(`nis`) ON DELETE CASCADE,
  FOREIGN KEY (`id_aspirasi`) REFERENCES `aspirasis`(`id_aspirasi`) ON DELETE CASCADE,
  FOREIGN KEY (`id_kategori`) REFERENCES `kategoris`(`id_kategori`) ON DELETE CASCADE
);

-- ============================================
-- Akses Role (routing laporan):
-- sarana_prasarana  → sapras, guru, guru_bk, kepala_sekolah
-- kesejahteraan_siswa → kesiswaan, guru, guru_bk, kepala_sekolah
-- ============================================
