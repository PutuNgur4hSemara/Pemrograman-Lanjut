<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Starter Code - Minggu 5: Exception & MySQLi Object</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f8fafc; color: #1e293b; padding: 40px 20px; }
        .container { max-width: 800px; margin: 0 auto; }
        h1 { color: #0f172a; margin-bottom: 8px; }
        p.subtitle { color: #64748b; margin-bottom: 24px; }
        .list { display: grid; gap: 16px; }
        .card { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 4px rgba(0,0,0,0.06); text-decoration: none; color: inherit; display: block; border-left: 5px solid #0284c7; transition: transform 0.2s, box-shadow 0.2s; }
        .card:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .card h3 { margin-bottom: 6px; color: #0369a1; }
        .card p { color: #64748b; font-size: 14px; margin: 0; }
    </style>
</head>
<body>

<div class="container">
    <h1>Modul Praktikum Pemrograman Lanjut — Minggu 5</h1>
    <p class="subtitle">Daftar file demonstrasi materi & latihan terarah</p>

    <div class="list">
        <a href="01_try_catch_finally.php" class="card">
            <h3>01. Exception Handling Dasar & Blok Finally</h3>
            <p>Mengendalikan error program secara elegan menggunakan blok try, catch, dan finally.</p>
        </a>

        <a href="02_custom_exception.php" class="card">
            <h3>02. Custom Exception Domain</h3>
            <p>Membuat exception class sendiri untuk membedakan kesalahan bisnis dari kesalahan teknis.</p>
        </a>

        <a href="03_koneksi_mysqli.php" class="card">
            <h3>03. Pengantar Koneksi MySQLi Object & Strict Exception</h3>
            <p>Membuka koneksi database dengan <code>new mysqli()</code> dan menangani <code>mysqli_sql_exception</code>.</p>
        </a>
    </div>
</div>

</body>
</html>
