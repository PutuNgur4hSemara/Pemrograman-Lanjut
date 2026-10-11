<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Starter Code - Minggu 1: Konsep Dasar PBO</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f8fafc; color: #1e293b; padding: 40px 20px; }
        .container { max-width: 800px; margin: 0 auto; }
        h1 { color: #0f172a; margin-bottom: 8px; }
        p.subtitle { color: #64748b; margin-bottom: 24px; }
        .list { display: grid; gap: 16px; }
        .card { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 4px rgba(0,0,0,0.06); text-decoration: none; color: inherit; display: block; border-left: 5px solid #3b82f6; transition: transform 0.2s, box-shadow 0.2s; }
        .card:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .card h3 { margin-bottom: 6px; color: #1d4ed8; }
        .card p { color: #64748b; font-size: 14px; margin: 0; }
    </style>
</head>
<body>

<div class="container">
    <h1>Modul Praktikum Pemrograman Lanjut — Minggu 1</h1>
    <p class="subtitle">Daftar file demonstrasi materi & latihan terarah</p>

    <div class="list">
        <a href="01_html_css_quickstart.php" class="card">
            <h3>01. Refreshment HTML & CSS Dasar</h3>
            <p>Penyegaran struktur HTML semantik, container CSS, dan rendering data dinamis PHP.</p>
        </a>

        <a href="02_class_dan_object.php" class="card">
            <h3>02. Class & Instansiasi Object</h3>
            <p>Mendefinisikan class cetak biru dan membuat object instance menggunakan operator <code>new</code>.</p>
        </a>

        <a href="03_property_dan_method.php" class="card">
            <h3>03. Property, Method & Pseudo-Variable $this</h3>
            <p>Mengakses data internal objek dan mengoperasikan method dengan konteks <code>$this</code>.</p>
        </a>

        <a href="04_argument_method.php" class="card">
            <h3>04. Method dengan Parameter & Return Value</h3>
            <p>Mengirim argumen nilai ke method dan mengolah hasil kalkulasi untuk disajikan ke antarmuka.</p>
        </a>
    </div>
</div>

</body>
</html>
