-- Database Setup Lokakarya untuk Praktikum Validasi Lanjutan (Minggu 15)
CREATE DATABASE IF NOT EXISTS `pemrograman_lanjut` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `pemrograman_lanjut`;

CREATE TABLE IF NOT EXISTS `peserta_lokakarya` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nama` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `level` ENUM('dasar', 'menengah', 'lanjut') NOT NULL,
    `sesi` ENUM('Pagi', 'Siang') NOT NULL,
    `topik_minat` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
