-- Database Setup Agenda Kegiatan untuk Praktikum Query Builder (Minggu 11, 12, 13)
CREATE DATABASE IF NOT EXISTS `pemrograman_lanjut` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `pemrograman_lanjut`;

CREATE TABLE IF NOT EXISTS `kegiatan` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `kode` VARCHAR(20) NOT NULL UNIQUE,
    `nama` VARCHAR(150) NOT NULL,
    `lokasi` VARCHAR(100) NOT NULL,
    `tanggal` DATE NOT NULL,
    `kuota` INT NOT NULL DEFAULT 50,
    `status` ENUM('terjadwal', 'berlangsung', 'selesai', 'dibatalkan') DEFAULT 'terjadwal',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO `kegiatan` (`kode`, `nama`, `lokasi`, `tanggal`, `kuota`, `status`) VALUES
('ACT-01', 'Kuliah Tamu: AI & Machine Learning Architecture', 'Auditorium Lab Terpadu', '2026-09-15', 120, 'terjadwal'),
('ACT-02', 'Workshop PHP 8 & Clean Architecture', 'Lab Komputer 3', '2026-09-20', 40, 'terjadwal'),
('ACT-03', 'Seminar Karir Software Engineer Global', 'Auditorium Utama', '2026-08-25', 200, 'selesai'),
('ACT-04', 'Hackathon Inovasi Kampus Merdeka', 'Gedung Serbaguna', '2026-10-05', 80, 'terjadwal'),
('ACT-05', 'Lokakarya UI/UX Design System', 'Lab Komputer 1', '2026-08-28', 35, 'selesai')
ON DUPLICATE KEY UPDATE `nama` = VALUES(`nama`);
