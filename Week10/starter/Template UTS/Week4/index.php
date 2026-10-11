<?php
// FILE REAL WEEK4: abstract class + interface + overriding + pemrosesan polymorphic.
interface DapatDihitung { public function hitungLuas(): float; }
abstract class BangunDatar
{
    public function __construct(protected string $nama) {}
    abstract public function hitungLuas(): float;
    public function getNama(): string { return $this->nama; }
}
class PersegiPanjang extends BangunDatar implements DapatDihitung
{
    public function __construct(string $nama, private float $panjang, private float $lebar)
    { parent::__construct($nama); }
    public function hitungLuas(): float { return $this->panjang * $this->lebar; }
}
class Lingkaran extends BangunDatar implements DapatDihitung
{
    public function __construct(string $nama, private float $jariJari) { parent::__construct($nama); }
    public function hitungLuas(): float { return pi() * $this->jariJari ** 2; }
}
$daftar = [new PersegiPanjang('Persegi panjang', 8, 4), new Lingkaran('Lingkaran', 5)];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Week4 — Inheritance & Polymorphism</title>
    <!-- Alamat CSS relatif dari folder Week4. Salin ke halaman Week lain tanpa diubah. -->
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body><main class="container">
    <header class="hero"><p class="eyebrow">Template UTS · Week4</p><h1>Inheritance, Abstraction & Polymorphism</h1><p>Objek berbeda dapat diproses seragam melalui kontrak interface.</p></header>
    <section class="card"><h2>Hasil kalkulasi bentuk</h2><table><thead><tr><th>Bangun datar</th><th>Luas</th><th>Kontrak</th></tr></thead><tbody>
        <?php foreach ($daftar as $bangun): // Loop menerima semua object dengan kontrak DapatDihitung. ?>
        <tr><td><?= htmlspecialchars($bangun->getNama(), ENT_QUOTES, 'UTF-8') ?></td><td><?= number_format($bangun->hitungLuas(), 2, ',', '.') ?></td><td><span class="badge">DapatDihitung</span></td></tr>
        <?php endforeach; ?>
    </tbody></table><p class="hint"><code>BangunDatar</code> menetapkan kerangka; implementasi <code>hitungLuas()</code> berbeda pada setiap class konkret.</p></section>
    <footer>Gunakan interface untuk kontrak perilaku dan abstract class untuk berbagi state/perilaku dasar.</footer>
</main></body></html>
