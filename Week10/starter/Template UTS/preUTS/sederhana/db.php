<?php
// ================================================
// File: db.php
// Fungsi: menghubungkan PHP ke database MySQLi dan
// memastikan tabel hewan sudah tersedia.
// ================================================

// Menggunakan koneksi lokal default Laragon.
// root tanpa password sesuai konfigurasi lokal.
function connectDatabase(): mysqli
{
    // Menampilkan error MySQLi dalam bentuk exception agar lebih mudah dideteksi.
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    // Parameter koneksi database.
    $host = '127.0.0.1';
    $user = 'root';
    $pass = '';
    $dbName = 'uts_prek_2025';

    try {
        // Membuat objek mysqli baru.
        $db = new mysqli($host, $user, $pass, $dbName);

        // Mengatur charset agar huruf dan karakter khusus tampil benar.
        $db->set_charset('utf8mb4');

        // Memastikan database benar-benar dibuat.
        // Query CREATE DATABASE ini dijalankan saat koneksi dibuat.
        $db->query("CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

        // Setelah database dibuat, select database supaya query berikutnya masuk ke database yang benar.
        $db->select_db($dbName);

        // Membuat tabel hewan jika belum ada.
        $db->query("
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

        // Menyisipkan data awal agar tabel tidak kosong saat pertama dibuka.
        $db->query("
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

        // Mengembalikan objek koneksi agar file lain dapat menggunakannya.
        return $db;
    } catch (mysqli_sql_exception $e) {
        // Jika koneksi gagal, kita hentikan proses dan tampilkan pesan singkat.
        die('Koneksi database gagal: ' . $e->getMessage());
    }
}
