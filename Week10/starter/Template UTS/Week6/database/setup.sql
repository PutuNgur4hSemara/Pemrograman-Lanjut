-- Skema database latihan Week6: prepared statement, binding, result set, transaksi.
-- Import file ini melalui phpMyAdmin atau MySQL CLI sebelum menjalankan template.php.
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
('10231004', 'Citra Lestari', 'Informatika', 3.90),
('10231005', 'Dimas Prasetyo', 'Sains Data', 2.85),
('10231006', 'Eka Rahmawati', 'Sains Data', 3.70)
ON DUPLICATE KEY UPDATE `nama` = VALUES(`nama`);

CREATE TABLE IF NOT EXISTS `rekening` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `no_rek` VARCHAR(30) NOT NULL UNIQUE,
    `nama_nasabah` VARCHAR(100) NOT NULL,
    `saldo` DECIMAL(15,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB;

INSERT INTO `rekening` (`no_rek`, `nama_nasabah`, `saldo`) VALUES
('REK-001', 'Fajar Pratama', 2500000.00),
('REK-002', 'Gita Gutawa', 500000.00)
ON DUPLICATE KEY UPDATE `nama_nasabah` = VALUES(`nama_nasabah`);

CREATE TABLE IF NOT EXISTS `katalog_buku` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `isbn` VARCHAR(20) NOT NULL UNIQUE,
    `judul` VARCHAR(150) NOT NULL,
    `penulis` VARCHAR(100) NOT NULL,
    `kategori` VARCHAR(50) NOT NULL,
    `harga` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    `stok` INT UNSIGNED NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO `katalog_buku` (`isbn`, `judul`, `penulis`, `kategori`, `harga`, `stok`) VALUES
('9786020324784', 'Laskar Pelangi', 'Andrea Hirata', 'Novel', 95000.00, 12),
('9786022912829', 'Bumi', 'Tere Liye', 'Novel', 89000.00, 8),
('9780132350884', 'Clean Code', 'Robert C. Martin', 'Teknologi', 185000.00, 5),
('9781491950296', 'Learning PHP, MySQL & JavaScript', 'Robin Nixon', 'Teknologi', 220000.00, 7),
('9786020649322', 'Filosofi Teras', 'Henry Manampiring', 'Pengembangan Diri', 98000.00, 15),
('9786230012345', 'Pengantar Basis Data', 'Abdul Kadir', 'Pendidikan', 125000.00, 10)
ON DUPLICATE KEY UPDATE
    `judul` = VALUES(`judul`), `penulis` = VALUES(`penulis`),
    `kategori` = VALUES(`kategori`), `harga` = VALUES(`harga`), `stok` = VALUES(`stok`);
