<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proyek Akhir UAS - Pemrograman Lanjut</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #0f172a; color: #f8fafc; padding: 40px 20px; }
        .container { max-width: 800px; margin: 0 auto; background: #1e293b; border-radius: 16px; padding: 36px; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.3); border: 1px solid #334155; }
        h1 { color: #38bdf8; margin-bottom: 8px; font-size: 28px; }
        p.subtitle { color: #94a3b8; font-size: 15px; margin-bottom: 24px; }
        .badge-case { display: inline-block; background: #0369a1; color: white; padding: 4px 12px; border-radius: 9999px; font-size: 12px; font-weight: bold; margin-bottom: 16px; }
        .card-topic { background: #0f172a; border-radius: 10px; padding: 20px; margin-bottom: 16px; border-left: 4px solid #38bdf8; }
        .card-topic h3 { color: #f1f5f9; margin-top: 0; margin-bottom: 6px; font-size: 16px; }
        .card-topic p { color: #94a3b8; font-size: 13px; margin: 0; }
        .checklist-box { background: #1e1b4b; border: 1px solid #4338ca; border-radius: 10px; padding: 20px; margin-top: 24px; }
        .checklist-box h4 { color: #a5b4fc; margin-top: 0; }
        ul { padding-left: 20px; color: #cbd5e1; font-size: 14px; }
        li { margin-bottom: 6px; }
    </style>
</head>
<body>

<div class="container">
    <span class="badge-case">CASE METHOD FINAL PROJECT</span>
    <h1>Evaluasi Akhir Semester (UAS)</h1>
    <p class="subtitle">Mata Kuliah Pemrograman Lanjut — Dosen: Arif Wicaksono Septyanto, S.Kom., M.Kom.</p>

    <div class="card-topic">
        <h3>1. Opsi A: Event & Ticketing Platform</h3>
        <p>Pemesanan tiket seminar/konser dengan transaksi database ACID dan validasi data peserta.</p>
    </div>

    <div class="card-topic" style="border-left-color: #a855f7;">
        <h3>2. Opsi B: Lab Asset & Inventory Management</h3>
        <p>Pengelolaan hirarki aset laboratorium, peminjaman barang mahasiswa, dan query filter bertingkat.</p>
    </div>

    <div class="card-topic" style="border-left-color: #22c55e;">
        <h3>3. Opsi C: Auto Repair & Sparepart System</h3>
        <p>Layanan bengkel dan inventaris suku cadang dengan kalkulasi diskon tagihan polimorfis.</p>
    </div>

    <div class="checklist-box">
        <h4>Komponen Arsitektur Wajib yang Diintegrasikan:</h4>
        <ul>
            <li>Model Hirarki OOP (Abstract Class & Interface dengan Encapsulation ketat).</li>
            <li>Koneksi Multi-Database PDO dengan Error Mode Exception.</li>
            <li>Custom Database Query Builder dengan Method Chaining & Reset State.</li>
            <li>Class Input & Class Validator Mandiri (Rule Engine & Sticky Form).</li>
            <li>Proteksi Penuh terhadap SQL Injection dan Cross-Site Scripting (XSS).</li>
        </ul>
    </div>
</div>

</body>
</html>
