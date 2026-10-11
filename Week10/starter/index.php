<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Starter Code - Minggu 10: Prepared Statement & CRUD PDO</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f8fafc; color: #1e293b; padding: 40px 20px; }
        .container { max-width: 800px; margin: 0 auto; }
        h1 { color: #0f172a; margin-bottom: 8px; }
        p.subtitle { color: #64748b; margin-bottom: 24px; }
        .list { display: grid; gap: 16px; }
        .card { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 4px rgba(0,0,0,0.06); text-decoration: none; color: inherit; display: block; border-left: 5px solid #059669; transition: transform 0.2s, box-shadow 0.2s; }
        .card:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .card h3 { margin-bottom: 6px; color: #047857; }
        .card p { color: #64748b; font-size: 14px; margin: 0; }
    </style>
</head>
<body>

<div class="container">
    <h1>Modul Praktikum Pemrograman Lanjut — Minggu 10</h1>
    <p class="subtitle">Daftar file demonstrasi materi & latihan terarah</p>

    <div class="list">
        <a href="01_binding_parameters.php" class="card">
            <h3>01. Parameter Binding: Named Parameters</h3>
            <p>Mencegah SQL Injection dengan named parameter placeholder (<code>:nama_param</code>) pada PDO.</p>
        </a>

        <a href="02_crud_buku_pdo.php" class="card">
            <h3>02. Operasi CRUD Lengkap Berbasis PDO</h3>
            <p>Mengimplementasikan INSERT, SELECT, dan DELETE pada tabel buku dengan penanganan prepared statement.</p>
        </a>

        <a href="03_transaksi_peminjaman.php" class="card">
            <h3>03. Transaksi Atomik Database (ACID) pada PDO</h3>
            <p>Mengelola transaksi multi-query dengan <code>beginTransaction</code>, <code>commit</code>, dan <code>rollBack</code>.</p>
        </a>
    </div>
</div>

</body>
</html>
