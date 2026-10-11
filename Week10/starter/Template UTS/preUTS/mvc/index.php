<?php
// ================================================
// File: index.php
// Fungsi: router utama untuk aplikasi MVC.
// ================================================

require_once __DIR__ . '/controllers/HewanController.php';

// Instansiasi controller.
$controller = new HewanController();

// Ambil parameter halaman dari query string.
$halaman = $_GET['halaman'] ?? 'index';

// Routing sederhana.
switch ($halaman) {
    case 'tambah':
        $controller->tambah();
        break;
    case 'edit':
        $controller->edit();
        break;
    case 'hapus':
        $controller->hapus();
        break;
    case 'index':
    default:
        $controller->index();
        break;
    catch (mysqli_sql_exception $e) {
        // Menangani misalnya kode hewan yang sudah digunakan.
        $error = 'Data gagal diproses. Pastikan kode hewan belum digunakan.';
    }
}
