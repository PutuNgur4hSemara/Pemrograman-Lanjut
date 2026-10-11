<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Starter Code - Minggu 9: Pengantar PDO</title>
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
    <h1>Modul Praktikum Pemrograman Lanjut — Minggu 9</h1>
    <p class="subtitle">Daftar file demonstrasi materi & latihan terarah</p>

    <div class="list">
        <a href="01_koneksi_pdo.php" class="card">
            <h3>01. Koneksi PDO & Konfigurasi DSN</h3>
            <p>Membentuk koneksi PDO multi-driver, konfigurasi DSN, dan pengaturan error mode exception.</p>
        </a>

        <a href="02_exec_dml.php" class="card">
            <h3>02. Eksekusi Perintah DML dengan exec()</h3>
            <p>Menjalankan perintah SQL non-SELECT dan membaca nilai kembalian affected rows.</p>
        </a>

        <a href="03_query_fetch.php" class="card">
            <h3>03. Query & Perbandingan Mode Fetching</h3>
            <p>Mengambil data hasil query menggunakan metode <code>FETCH_ASSOC</code> dan <code>FETCH_OBJ</code>.</p>
        </a>
    </div>
</div>

</body>
</html>
