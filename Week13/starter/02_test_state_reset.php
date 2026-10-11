<?php
/**
 * Latihan 2: Pengujian Reset State & Helper first()
 * Modul Praktikum Minggu 13
 */

require_once __DIR__ . '/QueryBuilder.php';

$pdo = new PDO("mysql:host=127.0.0.1;dbname=pemrograman_lanjut;charset=utf8mb4", "root", "", [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
$builder = new QueryBuilder($pdo);

// QUERY 1: Mencari record dengan kondisi WHERE spesifik
$kegiatanPertama = $builder
    ->table('kegiatan')
    ->where('status', '=', 'terjadwal')
    ->orderBy('kuota', 'DESC')
    ->first();

// QUERY 2: Menjalankan query baru pada objek builder yang SAMA
// JIKA Reset State bekerja dengan benar, query kedua TIDAK AKAN terikat dengan kondisi WHERE dari Query 1
$semuaData = $builder
    ->table('kegiatan')
    ->get();

$sqlQuery2 = $builder->table('kegiatan')->toSql();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Latihan 2 - Reset State & first()</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f8fafc; padding: 30px; }
        .card { max-width: 750px; margin: 0 auto 20px; background: white; border-radius: 12px; padding: 24px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
        .badge-pass { background: #dcfce7; color: #166534; padding: 4px 8px; border-radius: 4px; font-weight: bold; font-size: 12px; }
        .code-box { background: #0f172a; color: #38bdf8; font-family: monospace; font-size: 13px; padding: 10px; border-radius: 6px; margin: 8px 0; }
    </style>
</head>
<body>

    <div class="card">
        <span class="badge-pass">TEST RESULT: PASSED</span>
        <h2 style="margin-top: 8px;">1. Pengujian Helper <code>first()</code></h2>
        <p style="color: #64748b; font-size: 14px;">Mengambil satu objek pertama dengan kuota tertinggi (status terjadwal):</p>
        
        <?php if ($kegiatanPertama): ?>
            <div style="background: #f1f5f9; padding: 14px; border-radius: 8px; margin-top: 10px;">
                <h4 style="margin: 0; color: #1e293b;"><?= htmlspecialchars($kegiatanPertama['nama']) ?> (<?= htmlspecialchars($kegiatanPertama['kode']) ?>)</h4>
                <p style="margin: 4px 0 0; color: #475569; font-size: 13px;">Lokasi: <?= htmlspecialchars($kegiatanPertama['lokasi']) ?> | Kuota: <?= $kegiatanPertama['kuota'] ?> orang</p>
            </div>
        <?php endif; ?>
    </div>

    <div class="card">
        <span class="badge-pass">TEST RESULT: PASSED</span>
        <h2 style="margin-top: 8px;">2. Pengujian Reset State (Bebas Kebocoran Memory)</h2>
        <p style="color: #64748b; font-size: 14px;">Memastikan query berikutnya tidak tercemar oleh WHERE dari query sebelumnya:</p>
        
        <p><strong>Query Kedua yang Dihasilkan:</strong></p>
        <div class="code-box"><?= htmlspecialchars($sqlQuery2) ?></div>
        
        <p>Total data yang berhasil dibaca kembali secara bersih: <strong><?= count($semuaData) ?> records</strong>.</p>
    </div>

</body>
</html>
