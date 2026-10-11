<?php
/**
 * TEMPLATE UTUH WEEK2 — salin seluruh file sebagai satu halaman REAL tugas.
 * Ganti nama class/property dan isi data sesuai studi kasus. Property promotion perlu PHP 8+.
 */
class KategoriKasus
{
    public function __construct(public string $nama) {}
}

class EntitasKasus
{
    public const STATUS_AKTIF = 'Aktif';
    public static int $jumlahDibuat = 0;

    // Constructor promotion langsung membuat dan mengisi property object.
    public function __construct(
        public string $kode,
        public string $nama,
        public KategoriKasus $kategori,
        public float $nilai
    ) {
        self::$jumlahDibuat++;
    }

    public static function jumlah(): int
    {
        return self::$jumlahDibuat;
    }
}

class RingkasanKasus
{
    private array $bagian = [];

    // Return $this agar pemanggilan method dapat dirangkai (method chaining).
    public function tambah(string $bagian): self
    {
        $this->bagian[] = $bagian;
        return $this;
    }

    public function hasil(): string { return implode(' — ', $this->bagian); }
}

// GANTI contoh object dan field berikut dengan informasi dari soal.
$kategori = new KategoriKasus('Kategori Contoh');
$entitas = new EntitasKasus('KAS-001', 'Nama Contoh', $kategori, 125000);
$ringkasan = (new RingkasanKasus())->tambah($entitas->nama)->tambah($entitas->kategori->nama)->hasil();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REAL UTS — Week2</title>
    <!-- Jalur CSS untuk file REAL di folder Week1–Week6; ubah jika memindahkan file. -->
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body><main class="container">
    <header class="hero"><p class="eyebrow">TEMPLATE WEEK2</p><h1>Judul Studi Kasus</h1><p>Ganti dengan kebutuhan kasus UTS.</p></header>
    <section class="grid">
        <article class="card"><h2>Object</h2><p><?= htmlspecialchars($ringkasan, ENT_QUOTES, 'UTF-8') ?></p><p>Nilai: Rp <?= number_format($entitas->nilai, 0, ',', '.') ?></p></article>
        <article class="card"><h2>Static & constant</h2><p>Object dibuat: <?= EntitasKasus::jumlah() ?></p><p>Status: <span class="badge"><?= EntitasKasus::STATUS_AKTIF ?></span></p></article>
    </section>
    <footer>Salin file ini menjadi file REAL, lalu ubah class dan bagian tampilan sesuai soal.</footer>
</main></body></html>
