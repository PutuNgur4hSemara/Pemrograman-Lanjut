<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Latihan 1 - Refreshment HTML & CSS Dasar</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f1f5f9;
            color: #334155;
            padding: 40px 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: #ffffff;
            padding: 24px;
            text-align: center;
        }
        .content {
            padding: 24px;
        }
        .badge {
            display: inline-block;
            background: #dbeafe;
            color: #1e40af;
            font-size: 12px;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 9999px;
            margin-bottom: 12px;
        }
        .info-group {
            margin-bottom: 16px;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 12px;
        }
        .info-label {
            font-size: 12px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .info-value {
            font-size: 16px;
            font-weight: 600;
            color: #1e293b;
        }
    </style>
</head>
<body>

<?php
// Data dinamis PHP yang disajikan ke dalam HTML & CSS
$namaMahasiswa = "Aditya Pratama";
$nim = "10231088";
$prodi = "Sistem Informasi";
$mataKuliah = "Pemrograman Lanjut (3 SKS)";
$statusAkademik = "Aktif";
?>

<div class="container">
    <div class="header">
        <h2>Profil Mahasiswa</h2>
        <p>Praktikum Pemrograman Lanjut — Minggu 1</p>
    </div>
    <div class="content">
        <span class="badge"><?= htmlspecialchars($statusAkademik) ?></span>

        <div class="info-group">
            <div class="info-label">Nama Lengkap</div>
            <div class="info-value"><?= htmlspecialchars($namaMahasiswa) ?></div>
        </div>

        <div class="info-group">
            <div class="info-label">NIM</div>
            <div class="info-value"><?= htmlspecialchars($nim) ?></div>
        </div>

        <div class="info-group">
            <div class="info-label">Program Studi</div>
            <div class="info-value"><?= htmlspecialchars($prodi) ?></div>
        </div>

        <div class="info-group" style="border: none;">
            <div class="info-label">Mata Kuliah</div>
            <div class="info-value"><?= htmlspecialchars($mataKuliah) ?></div>
        </div>
    </div>
</div>

</body>
</html>
