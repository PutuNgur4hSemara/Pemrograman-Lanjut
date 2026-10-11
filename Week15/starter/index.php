<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Starter Code - Minggu 15: Validasi Lanjutan & Integrasi</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f8fafc; color: #1e293b; padding: 40px 20px; }
        .container { max-width: 800px; margin: 0 auto; }
        h1 { color: #0f172a; margin-bottom: 8px; }
        p.subtitle { color: #64748b; margin-bottom: 24px; }
        .list { display: grid; gap: 16px; }
        .card { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 4px rgba(0,0,0,0.06); text-decoration: none; color: inherit; display: block; border-left: 5px solid #ec4899; transition: transform 0.2s, box-shadow 0.2s; }
        .card:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .card h3 { margin-bottom: 6px; color: #be185d; }
        .card p { color: #64748b; font-size: 14px; margin: 0; }
    </style>
</head>
<body>

<div class="container">
    <h1>Modul Praktikum Pemrograman Lanjut — Minggu 15</h1>
    <p class="subtitle">Daftar file demonstrasi materi & latihan terarah</p>

    <div class="list">
        <a href="01_cross_validation.php" class="card">
            <h3>01. Validasi Lintas-Field (Cross-Field)</h3>
            <p>Memvalidasi konfirmasi email (<code>same</code>) dan opsi pilihan terdaftar (<code>in</code>).</p>
        </a>

        <a href="02_sticky_form_components.php" class="card">
            <h3>02. Komponen Formulir Lengkap & Sticky Form</h3>
            <p>Menerapkan repopulasi nilai pada input teks, radio button, dan checkbox array.</p>
        </a>

        <a href="03_integrasi_database.php" class="card">
            <h3>03. Integrasi End-to-End dengan Basis Data</h3>
            <p>Alur penuh: Form Request &rarr; Input &rarr; Validator &rarr; Domain Model &rarr; Database Repository.</p>
        </a>
    </div>
</div>

</body>
</html>
