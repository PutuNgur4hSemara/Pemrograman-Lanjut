-- Template SQL Week5: skema opsional studi kasus reservasi tiket.
-- Import melalui phpMyAdmin sebelum mengaktifkan koneksi MySQLi di template.php.
-- Kredensial pada template.php menggunakan database pemrograman_lanjut.
CREATE DATABASE IF NOT EXISTS `pemrograman_lanjut`
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;
USE `pemrograman_lanjut`;

CREATE TABLE IF NOT EXISTS `kategori_tiket` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nama_kategori` VARCHAR(50) NOT NULL UNIQUE,
    `stok_tersedia` INT UNSIGNED NOT NULL DEFAULT 0,
    `harga_satuan` DECIMAL(12,2) UNSIGNED NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `pemesanan_tiket` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nama_pemesan` VARCHAR(100) NOT NULL,
    `kategori_id` INT NOT NULL,
    `jumlah_tiket` TINYINT UNSIGNED NOT NULL,
    `total_harga` DECIMAL(12,2) UNSIGNED NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_pemesanan_kategori`
        FOREIGN KEY (`kategori_id`) REFERENCES `kategori_tiket` (`id`),
    CONSTRAINT `chk_jumlah_tiket` CHECK (`jumlah_tiket` BETWEEN 1 AND 4)
) ENGINE=InnoDB;

INSERT INTO `kategori_tiket` (`nama_kategori`, `stok_tersedia`, `harga_satuan`)
VALUES ('Festival', 50, 250000.00), ('VIP', 20, 750000.00), ('VVIP', 8, 1500000.00)
ON DUPLICATE KEY UPDATE `harga_satuan` = VALUES(`harga_satuan`);
