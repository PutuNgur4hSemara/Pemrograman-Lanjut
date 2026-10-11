<?php
// =====================================================
// MODUL 8 — DASHBOARD
// File: dashboard.php
// =====================================================
require_once __DIR__ . '/middleware.php';

// Middleware: halaman ini hanya untuk yang sudah login
harus_login();

// Ambil data user yang login
$user = user_login();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — Sistem Informasi Akademik</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f4f6f9; }
        
        /* Navbar */
        nav { background: #2c3e50; padding: 0 30px; display: flex; align-items: center; justify-content: space-between; height: 60px; }
        nav .brand { color: white; font-weight: bold; font-size: 16px; }
        nav .nav-links a { color: rgba(255,255,255,.8); text-decoration: none; margin-left: 20px; font-size: 14px; }
        nav .nav-links a:hover { color: white; }
        nav .logout { color: #e74c3c; text-decoration: none; margin-left: 20px; font-size: 14px; font-weight: bold; }
        
        /* Konten */
        .container { max-width: 900px; margin: 30px auto; padding: 0 20px; }
        
        /* Selamat datang */
        .welcome { background: white; padding: 25px 30px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,.1); margin-bottom: 25px; }
        .welcome h1 { color: #2c3e50; font-size: 22px; margin-bottom: 5px; }
        .welcome p  { color: #777; font-size: 14px; }
        .badge-role { display: inline-block; padding: 3px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; margin-top: 8px; }
        .badge-admin { background: #d5f5e3; color: #1e8449; }
        .badge-mahasiswa { background: #d6eaf8; color: #1a5276; }
        
        /* Grid menu */
        .menu-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 15px; }
        .menu-card { background: white; padding: 25px 20px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,.1); text-align: center; text-decoration: none; color: #2c3e50; transition: all 0.2s; border-top: 4px solid #3498db; }
        .menu-card:hover { transform: translateY(-3px); box-shadow: 0 6px 20px rgba(0,0,0,.15); }
        .menu-card .ikon { font-size: 36px; margin-bottom: 10px; }
        .menu-card .judul { font-weight: bold; font-size: 15px; }
        .menu-card .keterangan { font-size: 12px; color: #999; margin-top: 5px; }
        .menu-card.admin-only { border-top-color: #e74c3c; }
        
        h2 { color: #2c3e50; margin-bottom: 15px; font-size: 16px; }
        .section { margin-bottom: 30px; }
    </style>
</head>
<body>

<nav>
    <span class="brand">🏫 Sistem Informasi Akademik</span>
    <div class="nav-links">
        <a href="dashboard.php">Dashboard</a>
        <a href="mahasiswa.php">Daftar Mahasiswa</a>
        <!-- Logout: hapus session dan redirect ke login -->
        <a href="logout.php" class="logout">Logout →</a>
    </div>
</nav>

<div class="container">

    <!-- SELAMAT DATANG -->
    <div class="welcome">
        <h1>👋 Selamat Datang, <?= htmlspecialchars($user['nama']) ?>!</h1>
        <p>Kamu sedang mengakses Sistem Informasi Akademik Jurusan Sistem Informasi.</p>
        <span class="badge-role badge-<?= htmlspecialchars($user['role']) ?>">
            Role: <?= ucfirst(htmlspecialchars($user['role'])) ?>
        </span>
    </div>

    <!-- MENU SEMUA USER -->
    <div class="section">
        <h2>📋 Menu Utama</h2>
        <div class="menu-grid">
            <a href="mahasiswa.php" class="menu-card">
                <div class="ikon">👨‍🎓</div>
                <div class="judul">Daftar Mahasiswa</div>
                <div class="keterangan">Lihat semua data mahasiswa</div>
            </a>

            <?php if (adalah_admin()): ?>
                <!-- Menu khusus Admin -->
                <a href="../praktikum_7/?halaman=tambah" class="menu-card admin-only">
                    <div class="ikon">➕</div>
                    <div class="judul">Tambah Mahasiswa</div>
                    <div class="keterangan">Tambah data mahasiswa baru</div>
                </a>

                <a href="kelola_user.php" class="menu-card admin-only">
                    <div class="ikon">👥</div>
                    <div class="judul">Kelola Pengguna</div>
                    <div class="keterangan">Admin: Kelola akun user</div>
                </a>
            <?php endif; ?>
        </div>
    </div>

    <?php if (adalah_admin()): ?>
        <!-- Pesan khusus admin -->
        <div style="background: #fff3cd; border: 1px solid #ffc107; padding: 12px 18px; border-radius: 6px; font-size: 14px;">
            <strong>ℹ️ Info Admin:</strong> Kamu memiliki akses penuh. 
            Menu bertanda merah hanya bisa diakses oleh admin.
        </div>
    <?php else: ?>
        <!-- Pesan untuk mahasiswa -->
        <div style="background: #d6eaf8; border: 1px solid #3498db; padding: 12px 18px; border-radius: 6px; font-size: 14px;">
            <strong>ℹ️ Info:</strong> Kamu login sebagai mahasiswa. 
            Kamu hanya bisa melihat data, bukan mengubah atau menghapus.
        </div>
    <?php endif; ?>

</div>
</body>
</html>
