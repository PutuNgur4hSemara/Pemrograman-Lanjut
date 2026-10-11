<?php
/**
 * Latihan 3: Property, Method, dan Penggunaan Pseudo-Variable $this
 * Modul Praktikum Minggu 1
 */

class Karyawan
{
    public $nip;
    public $nama;
    public $jabatan;
    public $gajiPokok;
    public $tunjangan;

    // Method untuk menghitung total gaji (membaca property internal dengan $this)
    public function hitungTotalGaji()
    {
        return $this->gajiPokok + $this->tunjangan;
    }

    // Method untuk menampilkan ringkasan informasi
    public function getRingkasan()
    {
        return "{$this->nama} [{$this->nip}] menjabat sebagai {$this->jabatan}.";
    }
}

// Membuat Objek Karyawan 1
$karyawan1 = new Karyawan();
$karyawan1->nip = "KRY-001";
$karyawan1->nama = "Siti Rahmawati";
$karyawan1->jabatan = "Software Engineer";
$karyawan1->gajiPokok = 8500000;
$karyawan1->tunjangan = 1500000;

// Membuat Objek Karyawan 2
$karyawan2 = new Karyawan();
$karyawan2->nip = "KRY-002";
$karyawan2->nama = "Bambang Pamungkas";
$karyawan2->jabatan = "UI/UX Designer";
$karyawan2->gajiPokok = 7000000;
$karyawan2->tunjangan = 1000000;

$daftarKaryawan = [$karyawan1, $karyawan2];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Latihan 3 - Method & $this</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f8fafc; padding: 30px; }
        table { width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        th, td { padding: 12px 16px; text-align: left; border-bottom: 1px solid #e2e8f0; }
        th { background: #0f172a; color: white; }
        tr:hover { background: #f1f5f9; }
        .text-right { text-align: right; }
    </style>
</head>
<body>

    <h2>Daftar Payroll Karyawan (Menggunakan Method dan $this)</h2>
    <br>

    <table>
        <thead>
            <tr>
                <th>NIP</th>
                <th>Nama Lengkap</th>
                <th>Jabatan</th>
                <th class="text-right">Gaji Pokok</th>
                <th class="text-right">Tunjangan</th>
                <th class="text-right">Total Gaji</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($daftarKaryawan as $karyawan): ?>
                <tr>
                    <td><code><?= htmlspecialchars($karyawan->nip) ?></code></td>
                    <td><strong><?= htmlspecialchars($karyawan->nama) ?></strong></td>
                    <td><?= htmlspecialchars($karyawan->jabatan) ?></td>
                    <td class="text-right">Rp <?= number_format($karyawan->gajiPokok, 0, ',', '.') ?></td>
                    <td class="text-right">Rp <?= number_format($karyawan->tunjangan, 0, ',', '.') ?></td>
                    <td class="text-right" style="color: #059669; font-weight: bold;">
                        Rp <?= number_format($karyawan->hitungTotalGaji(), 0, ',', '.') ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</body>
</html>
