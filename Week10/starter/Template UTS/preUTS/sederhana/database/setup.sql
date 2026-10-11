-- ===============================
-- Pre-UTS: Database Setup Hewan
-- ===============================
-- Database ini dibuat khusus untuk tugas CRUD hewan.
-- Data yang dibuat digunakan pada versi sederhana dan versi MVC.

CREATE DATABASE IF NOT EXISTS `uts_prek_2025` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `uts_prek_2025`;

-- Tabel hewan: kolom utama sesuai spesifikasi pre-UTS
CREATE TABLE IF NOT EXISTS `hewan` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `kode_hewan` VARCHAR(10) NOT NULL UNIQUE,
    `nama` VARCHAR(100) NOT NULL,
    `spesies` VARCHAR(50) NOT NULL,
    `jumlah` INT NOT NULL DEFAULT 0,
    `status` VARCHAR(50) NOT NULL,
    `umur` INT NOT NULL DEFAULT 0
) ENGINE=InnoDB;

-- Data awal agar tabel langsung bisa dipakai saat dibuka
INSERT INTO `hewan` (`kode_hewan`, `nama`, `spesies`, `jumlah`, `status`, `umur`) VALUES
('H001', 'Gajah', 'Mamalia', 1, 'Dilindungi', 12),
('H002', 'Harimau', 'Mamalia', 2, 'Dilindungi', 8),
('H003', 'Ikan', 'Ikan', 5, 'Umum', 2)
ON DUPLICATE KEY UPDATE
    `nama` = VALUES(`nama`),
    `spesies` = VALUES(`spesies`),
    `jumlah` = VALUES(`jumlah`),
    `status` = VALUES(`status`),
    `umur` = VALUES(`umur`);
