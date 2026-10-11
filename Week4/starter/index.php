<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Starter Code - Minggu 4: Inheritance & Interface</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f8fafc; color: #1e293b; padding: 40px 20px; }
        .container { max-width: 800px; margin: 0 auto; }
        h1 { color: #0f172a; margin-bottom: 8px; }
        p.subtitle { color: #64748b; margin-bottom: 24px; }
        .list { display: grid; gap: 16px; }
        .card { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 4px rgba(0,0,0,0.06); text-decoration: none; color: inherit; display: block; border-left: 5px solid #8b5cf6; transition: transform 0.2s, box-shadow 0.2s; }
        .card:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .card h3 { margin-bottom: 6px; color: #6d28d9; }
        .card p { color: #64748b; font-size: 14px; margin: 0; }
    </style>
</head>
<body>

<div class="container">
    <h1>Modul Praktikum Pemrograman Lanjut — Minggu 4</h1>
    <p class="subtitle">Daftar file demonstrasi materi & latihan terarah</p>

    <div class="list">
        <a href="01_inheritance_overriding.php" class="card">
            <h3>01. Inheritance & Method Overriding</h3>
            <p>Pewarisan properti dan method dengan <code>extends</code>, visibilitas <code>protected</code>, dan <code>parent::</code>.</p>
        </a>

        <a href="02_abstract_class.php" class="card">
            <h3>02. Abstract Class & Abstract Method</h3>
            <p>Membuat kerangka wajib class induk yang harus diimplementasikan oleh class anak.</p>
        </a>

        <a href="03_interface_polymorphism.php" class="card">
            <h3>03. Interface & Polymorphism</h3>
            <p>Mendefinisikan kontrak antarmuka dan memproses berbagai jenis objek secara polymorphic.</p>
        </a>
    </div>
</div>

</body>
</html>
