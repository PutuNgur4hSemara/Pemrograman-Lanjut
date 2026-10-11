<?php
// FILE REAL WEEK3: property private melindungi saldo; mutasi dikontrol oleh method.
class RekeningDemo
{
    public function __construct(private string $pemilik, private float $saldo) {}
    public function getPemilik(): string { return $this->pemilik; }
    public function getSaldo(): float { return $this->saldo; }
    public function setor(float $jumlah): void
    {
        if ($jumlah <= 0) throw new InvalidArgumentException('Setoran harus lebih dari nol.');
        $this->saldo += $jumlah;
    }
    public function tarik(float $jumlah): void
    {
        if ($jumlah <= 0 || $jumlah > $this->saldo) throw new InvalidArgumentException('Penarikan tidak valid.');
        $this->saldo -= $jumlah;
    }
}
$rekening = new RekeningDemo('Nama Pemilik', 2500000);
$pesan = null;
try { $rekening->setor(500000); $pesan = 'Setoran berhasil diproses.'; }
catch (InvalidArgumentException $e) { $pesan = $e->getMessage(); }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Week3 — Encapsulation</title>
    <!-- Panggil CSS bersama, bukan menyalin ulang blok CSS pada tiap halaman. -->
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body><main class="container">
    <header class="hero"><p class="eyebrow">Template UTS · Week3</p><h1>Encapsulation & Access Modifier</h1><p>Getter untuk membaca data; method tervalidasi menjaga state object tetap sah.</p></header>
    <section class="card">
        <h2>Ringkasan rekening</h2>
        <div class="alert success"><?= htmlspecialchars($pesan, ENT_QUOTES, 'UTF-8') ?></div>
        <div class="grid"><div class="item"><strong>Pemilik</strong><?= htmlspecialchars($rekening->getPemilik(), ENT_QUOTES, 'UTF-8') ?></div>
        <div class="item"><strong>Saldo</strong>Rp <?= number_format($rekening->getSaldo(), 0, ',', '.') ?></div></div>
        <p class="hint">Coba ubah saldo menjadi property <code>private</code> dan lakukan perubahan hanya melalui <code>setor()</code> atau <code>tarik()</code>. Identitas permanen dapat dibuat read-only dengan tidak menyediakan setter.</p>
    </section>
    <footer>File template berisi contoh getter, setter, validasi, serta exception.</footer>
</main></body></html>
