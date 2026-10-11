<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Starter Code - Minggu 6: MySQLi & Prepared Statement</title>
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
    <h1>Modul Praktikum Pemrograman Lanjut — Minggu 6</h1>
    <p class="subtitle">Daftar file demonstrasi materi & latihan terarah</p>

    <div class="list">
        <a href="01_sqli_vs_prepared.php" class="card">
            <h3>01. Prepared Statement vs SQL Injection</h3>
            <p>Membuktikan keunggulan prepared statement dalam menangkal manipulasi input berbahaya.</p>
        </a>

        <a href="02_filter_mahasiswa.php" class="card">
            <h3>02. Multi-Parameter Binding & Result Set Fetching</h3>
            <p>Mengikat berbagai tipe data parameter (<code>bind_param</code>) dan menampilkan data ke tabel HTML.</p>
        </a>

        <a href="03_transaksi_mysqli.php" class="card">
            <h3>03. Transaksi Database MySQLi (ACID)</h3>
            <p>Menjamin konsistensi eksekusi multi-query dengan <code>begin_transaction</code>, <code>commit</code>, dan <code>rollback</code>.</p>
        </a>
    </div>
</div>

</body>
</html>
