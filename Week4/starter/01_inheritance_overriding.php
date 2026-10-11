<?php
/**
 * Latihan 1: Inheritance, Protected Visibility, dan Method Overriding dengan parent::
 * Modul Praktikum Minggu 4
 */

// Superclass (Parent)
class Karyawan
{
    public function __construct(
        protected string $idKaryawan,
        protected string $nama,
        protected float $gajiPokok
    ) {}

    public function hitungGajiTotal(): float
    {
        return $this->gajiPokok;
    }

    public function getInformasi(): string
    {
        return "[{$this->idKaryawan}] {$this->nama} - Gaji Pokok: Rp " . number_format($this->gajiPokok, 0, ',', '.');
    }
}

// Subclass 1: Karyawan Tetap (Mendapat Tunjangan Tetap + Bonus)
class KaryawanTetap extends Karyawan
{
    public function __construct(
        string $idKaryawan,
        string $nama,
        float $gajiPokok,
        private float $tunjanganKeluarga,
        private float $bonusKinerja
    ) {
        parent::__construct($idKaryawan, $nama, $gajiPokok);
    }

    // Overriding: Menambahkan tunjangan dan bonus ke gaji pokok parent
    public function hitungGajiTotal(): float
    {
        return parent::hitungGajiTotal() + $this->tunjanganKeluarga + $this->bonusKinerja;
    }
}

// Subclass 2: Karyawan Lepas / Freelance (Gaji Pokok + Jam Lembur)
class KaryawanFreelance extends Karyawan
{
    public function __construct(
        string $idKaryawan,
        string $nama,
        float $gajiPokok,
        private int $totalJamKerja,
        private float $upahPerJam
    ) {
        parent::__construct($idKaryawan, $nama, $gajiPokok);
    }

    // Overriding
    public function hitungGajiTotal(): float
    {
        return parent::hitungGajiTotal() + ($this->totalJamKerja * $this->upahPerJam);
    }
}

$daftarKaryawan = [
    new KaryawanTetap("KT-101", "Hendro Siswanto", 7000000, 1500000, 1000000),
    new KaryawanFreelance("KF-205", "Alifa Putri", 2000000, 45, 120000),
    new KaryawanTetap("KT-102", "Maya Kartika", 8500000, 2000000, 2500000)
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Latihan 1 - Inheritance & Overriding</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f8fafc; padding: 30px; }
        .container { max-width: 750px; margin: 0 auto; background: white; border-radius: 12px; padding: 24px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th, td { padding: 12px; border-bottom: 1px solid #e2e8f0; text-align: left; }
        th { background: #0f172a; color: white; }
        .badge-tetap { background: #dbeafe; color: #1e40af; padding: 3px 8px; border-radius: 4px; font-size: 12px; font-weight: 600; }
        .badge-freelance { background: #fef3c7; color: #92400e; padding: 3px 8px; border-radius: 4px; font-size: 12px; font-weight: 600; }
        .text-right { text-align: right; }
    </style>
</head>
<body>

    <div class="container">
        <h2>Daftar Penggajian Karyawan (Inheritance & Overriding)</h2>
        <table>
            <thead>
                <tr>
                    <th>Karyawan</th>
                    <th>Tipe Kontrak</th>
                    <th class="text-right">Total Pendapatan</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($daftarKaryawan as $kry): ?>
                    <tr>
                        <td><?= htmlspecialchars($kry->getInformasi()) ?></td>
                        <td>
                            <?php if ($kry instanceof KaryawanTetap): ?>
                                <span class="badge-tetap">Karyawan Tetap</span>
                            <?php else: ?>
                                <span class="badge-freelance">Freelance</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-right" style="font-weight: bold; color: #16a34a;">
                            Rp <?= number_format($kry->hitungGajiTotal(), 0, ',', '.') ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</body>
</html>
