<?php
/**
 * Latihan 1: Memahami Bahaya Akses Public Tanpa Enkapsulasi
 * Modul Praktikum Minggu 3
 */

class ProdukTanpaEnkapsulasi
{
    public string $kode;
    public string $nama;
    public float $harga;
    public int $stok;
}

$produk = new ProdukTanpaEnkapsulasi();
$produk->kode = "PRD-001";
$produk->nama = "Monitor LED 24 Inch";
$produk->harga = 1800000;
$produk->stok = 10;

// BAHAYA: Kode dari luar dapat merusak nilai properti secara tidak logis!
$produk->harga = -500000; // Harga negatif!
$produk->stok = -99;       // Stok minus!
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Latihan 1 - Bahaya Public Property</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #fef2f2; padding: 30px; }
        .danger-card { max-width: 500px; margin: 0 auto; background: white; border-radius: 10px; padding: 24px; border-left: 5px solid #ef4444; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
        .alert-box { background: #fee2e2; color: #991b1b; padding: 12px; border-radius: 6px; margin-top: 16px; font-size: 14px; }
    </style>
</head>
<body>

    <div class="danger-card">
        <h3 style="color: #991b1b; margin-top: 0;">Peringatan Integritas Data Rusak!</h3>
        <p>Data objek <code>$produk</code> dapat diisi nilai sembarangan dari luar:</p>
        <ul>
            <li>Kode: <?= htmlspecialchars($produk->kode) ?></li>
            <li>Nama: <?= htmlspecialchars($produk->nama) ?></li>
            <li>Harga: <strong style="color: red;">Rp <?= number_format($produk->harga, 0, ',', '.') ?></strong> (TIDAK MASUK AKAL)</li>
            <li>Stok: <strong style="color: red;"><?= $produk->stok ?> unit</strong> (TIDAK MASUK AKAL)</li>
        </ul>
        <div class="alert-box">
            Inilah alasan mengapa seluruh properti data wajib dibuat <code>private</code> dan hanya boleh dimutasi melalui method terkontrol (Enkapsulasi).
        </div>
    </div>

</body>
</html>
