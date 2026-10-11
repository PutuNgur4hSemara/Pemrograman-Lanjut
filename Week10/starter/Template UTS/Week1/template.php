<?php
/**
 * TEMPLATE UTUH WEEK1 — salin seluruh isi file ini ke file REAL tugas.
 * Ganti class, property, data, dan teks dengan entitas dari studi kasus UTS.
 * Jika file tujuan sudah berisi PHP, gabungkan bagian class dan variabel tanpa tag PHP bertumpuk.
 */
class EntitasKasus
{
    // GANTI property berikut dengan data yang dimiliki entitas studi kasus.
    public string $kode;
    public string $nama;
    public float $nilai1;
    public float $nilai2;

    // Method mengakses property object melalui $this dan mengembalikan hasil.
    public function hitungTotal(): float
    {
        return $this->nilai1 + $this->nilai2;
    }

    public function ringkasan(): string
    {
        return $this->nama . ' (' . $this->kode . ')';
    }
}

// GANTI nilai-nilai ini dengan data yang sesuai studi kasus UTS.
$entitas = new EntitasKasus();
$entitas->kode = 'DATA-001';
$entitas->nama = 'Contoh Entitas';
$entitas->nilai1 = 120;
$entitas->nilai2 = 80;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REAL UTS — Week1</title>
    <!-- Jalur CSS untuk file REAL di folder Week1–Week6; ubah jika memindahkan file. -->
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<main class="container">
    <header class="hero"><p class="eyebrow">TEMPLATE WEEK1</p><h1>Judul Studi Kasus</h1><p>Ganti judul dan keterangan ini sesuai instruksi UTS.</p></header>
    <section class="card">
        <h2>Hasil Studi Kasus</h2>
        <p>Ringkasan: <strong><?= htmlspecialchars($entitas->ringkasan(), ENT_QUOTES, 'UTF-8') ?></strong></p>
        <p>Total: <strong><?= number_format($entitas->hitungTotal(), 0, ',', '.') ?></strong></p>
        <!-- Salin/ubah baris HTML untuk menampilkan property lain yang diminta soal. -->
    </section>
    <footer>HTML adalah struktur halaman; seluruh tampilan memakai CSS bersama.</footer>
</main>
</body>
</html>
