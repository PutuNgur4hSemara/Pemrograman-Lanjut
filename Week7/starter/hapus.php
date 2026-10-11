<?php
/**
 * DELETE Handler: Hapus Mahasiswa
 * Modul Praktikum Minggu 7
 */

require_once __DIR__ . '/config/Database.php';
require_once __DIR__ . '/src/MahasiswaRepository.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    try {
        $db = Database::getConnection();
        $repo = new MahasiswaRepository($db);
        $repo->hapus($id);
        header("Location: index.php?pesan=" . urlencode("Mahasiswa berhasil dihapus."));
        exit;
    } catch (mysqli_sql_exception $e) {
        die("Gagal menghapus data: " . $e->getMessage());
    }
}

header("Location: index.php");
exit;
