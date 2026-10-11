<?php
// FILE REAL WEEK1: contoh halaman utuh Class/Object + HTML.
// Data contoh bisa diganti untuk entitas soal UTS Anda.
class Mahasiswa
{
    public string $nim;
    public string $nama;
    public float $nilaiTugas;
    public float $nilaiUts;
    public float $nilaiUas;

    public function hitungNilaiAkhir(): float
    {
        return ($this->nilaiTugas * 0.30) + ($this->nilaiUts * 0.30) + ($this->nilaiUas * 0.40);
    }

    public function predikat(float $nilai): string
    {
        if ($nilai >= 85) return 'A';
        if ($nilai >= 75) return 'B';
        if ($nilai >= 65) return 'C';
        if ($nilai >= 50) return 'D';
        return 'E';
    }
}

$mhs = new Mahasiswa(); // Instansiasi object dari class Mahasiswa.
$mhs->nim = '10251053';
$mhs->nama = 'Nama Mahasiswa';
$mhs->nilaiTugas = 88;
$mhs->nilaiUts = 82;
$mhs->nilaiUas = 90;
$nilaiAkhir = $mhs->hitungNilaiAkhir();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Week1 — Dasar PBO</title>
    <!-- SALIN alamat stylesheet ini ke halaman lain dalam folder WeekN. -->
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<main class="container">
    <header class="hero"><p class="eyebrow">Template UTS · Week1</p><h1>HTML, CSS & Dasar PBO</h1><p>Class, object, property, method, parameter, return value, dan $this.</p></header>
    <section class="card">
        <h2>Hasil penilaian</h2>
        <table><thead><tr><th>NIM</th><th>Nama</th><th>Nilai akhir</th><th>Predikat</th></tr></thead>
        <tbody><tr>
            <!-- Escape teks dinamis sebelum dicetak ke HTML untuk mengurangi risiko XSS. -->
            <td><?= htmlspecialchars($mhs->nim, ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= htmlspecialchars($mhs->nama, ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= number_format($nilaiAkhir, 2, ',', '.') ?></td>
            <td><span class="badge"><?= htmlspecialchars($mhs->predikat($nilaiAkhir), ENT_QUOTES, 'UTF-8') ?></span></td>
        </tr></tbody></table>
        <p class="hint">Class adalah cetak biru; <code>new Mahasiswa()</code> membuat object. Method memakai <code>$this</code> untuk membaca property object aktif.</p>
    </section>
    <footer>Styling berasal dari satu CSS bersama: <strong>assets/css/style.css</strong>.</footer>
</main>
</body>
</html>
