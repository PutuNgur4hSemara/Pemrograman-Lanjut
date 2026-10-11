-- Database template Week7: CRUD Mahasiswa via Repository Pattern
-- Import file ini terlebih dahulu di phpMyAdmin/MySQL CLI.
CREATE DATABASE IF NOT EXISTS `pemrograman_lanjut`
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;
USE `pemrograman_lanjut`;

CREATE TABLE IF NOT EXISTS `mahasiswa` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nim` VARCHAR(20) NOT NULL UNIQUE,
    `nama` VARCHAR(100) NOT NULL,
    `program_studi` VARCHAR(50) NOT NULL,
    `ipk` DECIMAL(3,2) NOT NULL DEFAULT 0.00,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO `mahasiswa` (`nim`, `nama`, `program_studi`, `ipk`) VALUES
('10231001', 'Ahmad Dahlan', 'Sistem Informasi', 3.85),
('10231002', 'Siti Aminah', 'Sistem Informasi', 3.45),
('10231003', 'Budi Santoso', 'Informatika', 3.20),
('10231004', 'Citra Lestari', 'Informatika', 3.90)
ON DUPLICATE KEY UPDATE `nama` = VALUES(`nama`);
