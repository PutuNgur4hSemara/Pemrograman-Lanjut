<?php
// ================================================
// File: config/Database.php
// Fungsi: class koneksi database dan setup tabel otomatis.
// ================================================

class Database
{
    // Properti statis untuk menampung koneksi singleton.
    private static ?mysqli $instance = null;

    // Method statis untuk mendapatkan satu koneksi database saja.
    public static function getConnection(): mysqli
    {
        // Jika koneksi belum dibuat, buat baru.
        if (self::$instance === null) {
            // Menyalakan mode exception MySQLi agar error lebih mudah dibaca.
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

            // Parameter koneksi lokal Laragon.
            $host = '127.0.0.1';
            $user = 'root';
            $pass = '';
            $dbName = 'uts_prek_2025';

            try {
                // Membuat koneksi ke MySQL tanpa memilih database dulu.
                $conn = new mysqli($host, $user, $pass);
                $conn->set_charset('utf8mb4');

                // Membuat database jika belum ada.
                $conn->query("CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

                // Memilih database aktif.
                $conn->select_db($dbName);

                // Membuat tabel hewan jika belum ada.
                $conn->query("
                    CREATE TABLE IF NOT EXISTS `hewan` (
                        `id` INT AUTO_INCREMENT PRIMARY KEY,
                        `kode_hewan` VARCHAR(10) NOT NULL UNIQUE,
                        `nama` VARCHAR(100) NOT NULL,
                        `spesies` VARCHAR(50) NOT NULL,
                        `jumlah` INT NOT NULL DEFAULT 0,
                        `status` VARCHAR(50) NOT NULL,
                        `umur` INT NOT NULL DEFAULT 0
                    ) ENGINE=InnoDB
                ");

                // Menambahkan data awal agar tabel tidak kosong.
                $conn->query("
                    INSERT INTO `hewan` (`kode_hewan`, `nama`, `spesies`, `jumlah`, `status`, `umur`) VALUES
                    ('H001', 'Gajah', 'Mamalia', 1, 'Dilindungi', 12),
                    ('H002', 'Harimau', 'Mamalia', 2, 'Dilindungi', 8),
                    ('H003', 'Ikan', 'Ikan', 5, 'Umum', 2)
                    ON DUPLICATE KEY UPDATE
                        `nama` = VALUES(`nama`),
                        `spesies` = VALUES(`spesies`),
                        `jumlah` = VALUES(`jumlah`),
                        `status` = VALUES(`status`),
                        `umur` = VALUES(`umur`)
                ");

                // Menyimpan koneksi dalam properti static agar reuse.
                self::$instance = $conn;
            } catch (mysqli_sql_exception $e) {
                // Jika koneksi gagal, hentikan aplikasi.
                die('Koneksi database gagal: ' . $e->getMessage());
            }
        }

        // Mengembalikan koneksi aktif.
        return self::$instance;
    }
}
