<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Starter Code - Minggu 3: Encapsulation & Access Modifier</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f8fafc; color: #1e293b; padding: 40px 20px; }
        .container { max-width: 800px; margin: 0 auto; }
        h1 { color: #0f172a; margin-bottom: 8px; }
        p.subtitle { color: #64748b; margin-bottom: 24px; }
        .list { display: grid; gap: 16px; }
        .card { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 4px rgba(0,0,0,0.06); text-decoration: none; color: inherit; display: block; border-left: 5px solid #16a34a; transition: transform 0.2s, box-shadow 0.2s; }
        .card:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .card h3 { margin-bottom: 6px; color: #15803d; }
        .card p { color: #64748b; font-size: 14px; margin: 0; }
    </style>
</head>
<body>

<div class="container">
    <h1>Modul Praktikum Pemrograman Lanjut — Minggu 3</h1>
    <p class="subtitle">Daftar file demonstrasi materi & latihan terarah</p>

    <div class="list">
        <a href="01_masalah_akses_public.php" class="card">
            <h3>01. Bahaya Akses Public Tanpa Enkapsulasi</h3>
            <p>Melihat bagaimana data internal objek dapat rusak jika dibiarkan terbuka secara publik.</p>
        </a>

        <a href="02_getter_setter.php" class="card">
            <h3>02. Implementasi Private, Getter & Setter</h3>
            <p>Melindungi data dengan visibility private dan menyematkan validasi pada setter.</p>
        </a>

        <a href="03_rekening_bank.php" class="card">
            <h3>03. Studi Kasus: Domain Model Rekening Bank</h3>
            <p>Penerapan enkapsulasi menyeluruh pada sistem mutasi perbankan.</p>
        </a>
    </div>
</div>

</body>
</html>
