<?php
/**
 * Latihan 2: Deklarasi Class dan Pembuatan Object (Instansiasi)
 * Modul Praktikum Minggu 1
 */

// 1. Mendefinisikan Class Mobil
class Mobil
{
    public $merk;
    public $tipe;
    public $warna;
    public $tahunProduksi;
    public $kecepatanMaksimum;
}

// 2. Instansiasi Object Pertama
$mobilA = new Mobil();
$mobilA->merk = "Toyota";
$mobilA->tipe = "Yaris GR";
$mobilA->warna = "Putih Mutiara";
$mobilA->tahunProduksi = 2023;
$mobilA->kecepatanMaksimum = 230;

// 3. Instansiasi Object Kedua
$mobilB = new Mobil();
$mobilB->merk = "Honda";
$mobilB->tipe = "Civic Type R";
$mobilB->warna = "Hitam Metalik";
$mobilB->tahunProduksi = 2024;
$mobilB->kecepatanMaksimum = 275;

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Latihan 2 - Class & Object</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f8fafc; padding: 30px; }
        .grid { display: flex; gap: 20px; }
        .card { background: white; border-radius: 8px; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.08); flex: 1; border-top: 4px solid #3b82f6; }
        h3 { margin-top: 0; color: #1e293b; }
        ul { list-style: none; padding: 0; margin-top: 10px; }
        li { padding: 6px 0; border-bottom: 1px dashed #e2e8f0; font-size: 14px; }
        strong { color: #475569; }
    </style>
</head>
<body>

    <h2>Daftar Objek Mobil (Instansiasi Class Mobil)</h2>
    <br>

    <div class="grid">
        <div class="card">
            <h3>Object: $mobilA</h3>
            <ul>
                <li><strong>Merk:</strong> <?= htmlspecialchars($mobilA->merk) ?></li>
                <li><strong>Tipe:</strong> <?= htmlspecialchars($mobilA->tipe) ?></li>
                <li><strong>Warna:</strong> <?= htmlspecialchars($mobilA->warna) ?></li>
                <li><strong>Tahun:</strong> <?= htmlspecialchars($mobilA->tahunProduksi) ?></li>
                <li><strong>Kecepatan Maks:</strong> <?= htmlspecialchars($mobilA->kecepatanMaksimum) ?> km/jam</li>
            </ul>
        </div>

        <div class="card" style="border-top-color: #ef4444;">
            <h3>Object: $mobilB</h3>
            <ul>
                <li><strong>Merk:</strong> <?= htmlspecialchars($mobilB->merk) ?></li>
                <li><strong>Tipe:</strong> <?= htmlspecialchars($mobilB->tipe) ?></li>
                <li><strong>Warna:</strong> <?= htmlspecialchars($mobilB->warna) ?></li>
                <li><strong>Tahun:</strong> <?= htmlspecialchars($mobilB->tahunProduksi) ?></li>
                <li><strong>Kecepatan Maks:</strong> <?= htmlspecialchars($mobilB->kecepatanMaksimum) ?> km/jam</li>
            </ul>
        </div>
    </div>

</body>
</html>
