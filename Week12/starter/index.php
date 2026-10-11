<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Starter Code - Minggu 12: SELECT Query Builder</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f8fafc; color: #1e293b; padding: 40px 20px; }
        .container { max-width: 800px; margin: 0 auto; }
        h1 { color: #0f172a; margin-bottom: 8px; }
        p.subtitle { color: #64748b; margin-bottom: 24px; }
        .list { display: grid; gap: 16px; }
        .card { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 4px rgba(0,0,0,0.06); text-decoration: none; color: inherit; display: block; border-left: 5px solid #d97706; transition: transform 0.2s, box-shadow 0.2s; }
        .card:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .card h3 { margin-bottom: 6px; color: #b45309; }
        .card p { color: #64748b; font-size: 14px; margin: 0; }
    </style>
</head>
<body>

<div class="container">
    <h1>Modul Praktikum Pemrograman Lanjut — Minggu 12</h1>
    <p class="subtitle">Daftar file demonstrasi materi & latihan terarah</p>

    <div class="list">
        <a href="01_select_builder.php" class="card">
            <h3>01. SELECT Builder & Method Chaining</h3>
            <p>Membangun query SELECT dasar secara berantai dengan method <code>table()</code> dan <code>select()</code>.</p>
        </a>

        <a href="02_where_chaining.php" class="card">
            <h3>02. WHERE Chaining & Allowlist Identifier</h3>
            <p>Menambahkan klausul filter dinamis dan mengamankan identifier kolom dengan allowlist.</p>
        </a>

        <a href="03_order_and_execution.php" class="card">
            <h3>03. Pengurutan ORDER BY & Eksekusi Dataset</h3>
            <p>Mengintegrasikan sorting dan mengeksekusi query melalui method <code>get()</code>.</p>
        </a>
    </div>
</div>

</body>
</html>
