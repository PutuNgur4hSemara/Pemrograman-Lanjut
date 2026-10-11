<?php
/**
 * TEMPLATE UTUH WEEK4 — salin sebagai satu halaman REAL, lalu sesuaikan domain dan operasi.
 * Pola ini menggabungkan inheritance, abstract class, interface, overriding, polymorphism.
 */
interface DapatDiproses
{
    public function hitungHasil(): float;
}

abstract class ItemKasus
{
    public function __construct(protected string $nama) {}
    abstract public function hitungHasil(): float;
    public function getNama(): string { return $this->nama; }
}

class ItemTipeA extends ItemKasus implements DapatDiproses
{
    public function __construct(string $nama, protected float $nilai, private float $faktor)
    {
        parent::__construct($nama);
    }

    // Implementasi/overriding berbeda untuk setiap class turunan.
    public function hitungHasil(): float { return $this->nilai * $this->faktor; }
}

class ItemTipeB extends ItemKasus implements DapatDiproses
{
    public function __construct(string $nama, private float $nilai, private float $tambahan)
    {
        parent::__construct($nama);
    }

    public function hitungHasil(): float { return $this->nilai + $this->tambahan; }
}

// Satu function memproses objek berbeda lewat interface yang sama (polymorphism).
function prosesSemua(array $items): array
{
    $hasil = [];
    foreach ($items as $item) {
        if (!$item instanceof DapatDiproses) {
            throw new InvalidArgumentException('Item harus mengimplementasikan DapatDiproses.');
        }
        $hasil[] = ['nama' => $item->getNama(), 'hasil' => $item->hitungHasil()];
    }
    return $hasil;
}

// GANTI data dan class konkrit mengikuti variasi objek di studi kasus UTS.
$hasilKasus = prosesSemua([
    new ItemTipeA('Tipe A', 100, 1.2),
    new ItemTipeB('Tipe B', 100, 25),
]);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REAL UTS — Week4</title>
    <!-- Jalur CSS untuk file REAL di folder Week1–Week6; ubah jika memindahkan file. -->
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body><main class="container">
    <header class="hero"><p class="eyebrow">TEMPLATE WEEK4</p><h1>Judul Studi Kasus</h1><p>Inheritance, abstract class, interface, dan polymorphism.</p></header>
    <section class="card"><h2>Hasil Pemrosesan</h2><table><thead><tr><th>Jenis</th><th>Hasil</th></tr></thead><tbody>
        <?php foreach ($hasilKasus as $baris): ?>
        <tr><td><?= htmlspecialchars($baris['nama'], ENT_QUOTES, 'UTF-8') ?></td><td><?= number_format($baris['hasil'], 2, ',', '.') ?></td></tr>
        <?php endforeach; ?>
    </tbody></table></section>
    <footer>Tambahkan tipe child class baru tanpa mengubah pola pemrosesan yang memakai interface.</footer>
</main></body></html>
