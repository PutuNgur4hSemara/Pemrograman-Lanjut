<?php
// FILE REAL WEEK2: demonstrasi constructor, static/const, chaining, dan hubungan objek.
class Jurusan
{
    public function __construct(public string $kode, public string $nama) {}
}
class Mahasiswa
{
    public const STATUS_AKTIF = 'Aktif';
    public static int $jumlah = 0;

    public function __construct(public string $nim, public string $nama, public Jurusan $jurusan)
    {
        self::$jumlah++;
    }
}
class Ringkasan
{
    private string $teks = '';
    public function tambah(string $teks): self { $this->teks .= $teks . ' '; return $this; }
    public function hasil(): string { return trim($this->teks); }
}
$jurusan = new Jurusan('SI', 'Sistem Informasi');
$mhs = new Mahasiswa('10251053', 'Nama Mahasiswa', $jurusan);
$ringkasan = (new Ringkasan())->tambah($mhs->nama)->tambah($mhs->jurusan->nama)->hasil();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Week2 — Constructor & Method Lanjut</title>
    <!-- Jalur ini digunakan bersama oleh semua halaman Week1–Week6. -->
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body><main class="container">
    <header class="hero"><p class="eyebrow">Template UTS · Week2</p><h1>Constructor & Method Lanjut</h1><p>Inisialisasi otomatis, member class, method chaining, dan interaksi antar-object.</p></header>
    <section class="grid">
        <article class="card"><h2>Constructor promotion</h2><p>Nama object: <strong><?= htmlspecialchars($mhs->nama, ENT_QUOTES, 'UTF-8') ?></strong></p><p>Jurusan berasal dari object <code>Jurusan</code> yang diberikan sebagai argumen constructor.</p></article>
        <article class="card"><h2>Static & constant</h2><p>Jumlah object dibuat: <strong><?= Mahasiswa::$jumlah ?></strong></p><p>Status class: <span class="badge"><?= Mahasiswa::STATUS_AKTIF ?></span></p></article>
        <article class="card"><h2>Method chaining</h2><p><?= htmlspecialchars($ringkasan, ENT_QUOTES, 'UTF-8') ?></p><p>Setiap method penghubung mengembalikan <code>$this</code>.</p></article>
    </section>
    <footer>Salin pola yang dibutuhkan dari <strong>template.php</strong>; property promotion memerlukan PHP 8+.</footer>
</main></body></html>
