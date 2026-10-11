-- Database Setup untuk Praktikum Minggu 6 & 7
CREATE DATABASE IF NOT EXISTS `pemrograman_lanjut` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `pemrograman_lanjut`;

-- Tabel Mahasiswa
CREATE TABLE IF NOT EXISTS `mahasiswa` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nim` VARCHAR(20) NOT NULL UNIQUE,
    `nama` VARCHAR(100) NOT NULL,
    `program_studi` VARCHAR(50) NOT NULL,
    `ipk` DECIMAL(3,2) NOT NULL DEFAULT 0.00,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Data Awal Mahasiswa
INSERT INTO `mahasiswa` (`nim`, `nama`, `program_studi`, `ipk`) VALUES
('10231001', 'Ahmad Dahlan', 'Sistem Informasi', 3.85),
('10231002', 'Siti Aminah', 'Sistem Informasi', 3.45),
('10231003', 'Budi Santoso', 'Informatika', 3.20),
('10231004', 'Citra Lestari', 'Informatika', 3.90),
('10231005', 'Dimas Prasetyo', 'Sains Data', 2.85),
('10231006', 'Eka Rahmawati', 'Sains Data', 3.70)
ON DUPLICATE KEY UPDATE `nama` = VALUES(`nama`);

-- Tabel Rekening Bank untuk Praktikum Transaksi
CREATE TABLE IF NOT EXISTS `rekening` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `no_rek` VARCHAR(30) NOT NULL UNIQUE,
    `nama_nasabah` VARCHAR(100) NOT NULL,
    `saldo` DECIMAL(15,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB;

INSERT INTO `rekening` (`no_rek`, `nama_nasabah`, `saldo`) VALUES
('REK-001', 'Fajar Pratama', 2500000.00),
('REK-002', 'Gita Gutawa', 500000.00)
ON DUPLICATE KEY UPDATE `saldo` = VALUES(`saldo`);
