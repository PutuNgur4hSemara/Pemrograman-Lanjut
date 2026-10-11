-- Database Setup untuk UTS Pemrograman Lanjut
CREATE DATABASE IF NOT EXISTS `pemrograman_lanjut` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `pemrograman_lanjut`;

CREATE TABLE IF NOT EXISTS `kendaraan` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `plat_nomor` VARCHAR(20) NOT NULL UNIQUE,
    `merk` VARCHAR(100) NOT NULL,
    `tipe` ENUM('Mobil', 'Motor') NOT NULL,
    `harga_sewa_per_hari` DECIMAL(12,2) NOT NULL,
    `spesifikasi` VARCHAR(100) NOT NULL, -- Contoh: "Kapasitas 7 Penumpang" atau "150 cc"
    `status` ENUM('Tersedia', 'Disewa') DEFAULT 'Tersedia',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO `kendaraan` (`plat_nomor`, `merk`, `tipe`, `harga_sewa_per_hari`, `spesifikasi`, `status`) VALUES
('KT 1234 AB', 'Toyota Avanza Veloz', 'Mobil', 450000.00, '7 Penumpang', 'Tersedia'),
('KT 5678 CD', 'Honda HR-V', 'Mobil', 650000.00, '5 Penumpang', 'Tersedia'),
('KT 9988 EF', 'Yamaha NMAX 155', 'Motor', 150000.00, '155 cc', 'Tersedia'),
('KT 7711 GH', 'Honda PCX 160', 'Motor', 160000.00, '160 cc', 'Disewa')
ON DUPLICATE KEY UPDATE `merk` = VALUES(`merk`);
