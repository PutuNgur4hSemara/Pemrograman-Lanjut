-- Database Setup Perpustakaan untuk Praktikum PDO (Minggu 9 & 10)
CREATE DATABASE IF NOT EXISTS `pemrograman_lanjut` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `pemrograman_lanjut`;

-- Tabel Koleksi Buku
CREATE TABLE IF NOT EXISTS `koleksi_buku` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `kode_buku` VARCHAR(20) NOT NULL UNIQUE,
    `judul` VARCHAR(150) NOT NULL,
    `penulis` VARCHAR(100) NOT NULL,
    `kategori` VARCHAR(50) NOT NULL,
    `stok` INT NOT NULL DEFAULT 0,
    `tahun_terbit` INT NOT NULL
) ENGINE=InnoDB;

-- Tabel Peminjaman Transaksi
CREATE TABLE IF NOT EXISTS `peminjaman_buku` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `id_buku` INT NOT NULL,
    `nama_peminjam` VARCHAR(100) NOT NULL,
    `nim` VARCHAR(20) NOT NULL,
    `tanggal_pinjam` DATE NOT NULL,
    `status` ENUM('Dipinjam', 'Kembali') DEFAULT 'Dipinjam',
    FOREIGN KEY (`id_buku`) REFERENCES `koleksi_buku`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Data Awal Buku
INSERT INTO `koleksi_buku` (`kode_buku`, `judul`, `penulis`, `kategori`, `stok`, `tahun_terbit`) VALUES
('BK-001', 'Clean Code: A Handbook of Agile Software Craftsmanship', 'Robert C. Martin', 'Software Engineering', 5, 2008),
('BK-002', 'Design Patterns: Elements of Reusable Object-Oriented Software', 'Erich Gamma et al.', 'Software Architecture', 3, 1994),
('BK-003', 'PHP 8 Objects, Patterns, and Practice', 'Matt Zandstra', 'Programming', 4, 2021),
('BK-004', 'Refactoring: Improving the Design of Existing Code', 'Martin Fowler', 'Software Engineering', 2, 2018),
('BK-005', 'Domain-Driven Design: Tackling Complexity', 'Eric Evans', 'Software Architecture', 0, 2003)
ON DUPLICATE KEY UPDATE `judul` = VALUES(`judul`);
