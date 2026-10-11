<?php
// ================================================
// File: hapus.php
// Fungsi: menghapus data hewan berdasarkan id lalu redirect ke index.
// ================================================

require_once __DIR__ . '/db.php';

// Ambil id dari URL. Jika id tidak valid, kembalikan ke index.
$id = (int)($_GET['id'] ?? 0);

if ($id > 0) {
    // Buat koneksi ke database.
    $db = connectDatabase();

    // Gunakan prepared statement agar aman saat delete.
    $stmt = $db->prepare("DELETE FROM hewan WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();
}

// Redirect kembali ke daftar utama dengan pesan sukses.
header('Location: index.php?pesan=' . urlencode('Data hewan berhasil dihapus.'));
exit;
