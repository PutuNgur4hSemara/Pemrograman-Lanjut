<?php
/**
 * Index & Simulator Evaluasi UTS Praktikum
 * Modul Evaluasi UTS - Minggu 8
 */

require_once __DIR__ . '/config/Database.php';
require_once __DIR__ . '/src/KendaraanRepository.php';

$db = Database::getConnection();
$repo = new KendaraanRepository($db);
$daftarArmada = $repo->semua();

$simulasiBiaya = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['simulasi'])) {
    $idKendaraan = (int)$_POST['id_kendaraan'];
    $durasiHari = (int)$_POST['durasi_hari'];

    try {
        $kendaraanPilihan = $repo->cariBerdasarkanId($idKendaraan);
        if ($kendaraanPilihan) {
            $totalBiaya = $kendaraanPilihan->hitungBiayaSewa($durasiHari);
            $simulasiBiaya = [
                'kendaraan' => $kendaraanPilihan,
                'durasi' => $durasiHari,
                'total' => $totalBiaya
            ];
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UTS Praktikum - Sistem Rental Kendaraan PBO</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f8fafc; color: #1e293b; padding: 40px 20px; }
        .container { max-width: 950px; margin: 0 auto; }
        .card { background: white; border-radius: 12px; padding: 24px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); margin-bottom: 24px; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { padding: 12px 14px; border-bottom: 1px solid #e2e8f0; text-align: left; font-size: 14px; }
        th { background: #0f172a; color: white; }
        .badge-mobil { background: #dbeafe; color: #1e40af; padding: 3px 8px; border-radius: 4px; font-weight: 600; font-size: 12px; }
        .badge-motor { background: #fef3c7; color: #92400e; padding: 3px 8px; border-radius: 4px; font-weight: 600; font-size: 12px; }
        .text-right { text-align: right; }
        .simulasi-box { background: #f0fdf4; border-left: 4px solid #16a34a; padding: 16px; border-radius: 6px; margin-top: 16px; }
    </style>
</head>
<body>

<div class="container">
    <div class="card">
        <h1 style="margin: 0; color: #0f172a; font-size: 24px;">Sistem Manajemen Rental Kendaraan (UTS PBO)</h1>
        <p style="color: #64748b; margin: 4px 0 20px;">Implementasi Abstract Class, Interface, Overriding, dan MySQLi Repository</p>

        <h3>Daftar Armada Kendaraan</h3>
        <table>
            <thead>
                <tr>
                    <th>Plat Nomor</th>
                    <th>Merk / Model</th>
                    <th>Tipe</th>
                    <th>Spesifikasi</th>
                    <th class="text-right">Tarif Dasar / Hari</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($daftarArmada as $k): ?>
                    <tr>
                        <td><code><?= htmlspecialchars($k->getPlatNomor()) ?></code></td>
                        <td><strong><?= htmlspecialchars($k->getMerk()) ?></strong></td>
                        <td>
                            <span class="<?= $k->getTipeKendaraan() === 'Mobil' ? 'badge-mobil' : 'badge-motor' ?>">
                                <?= $k->getTipeKendaraan() ?>
                            </span>
                        </td>
                        <td><?= htmlspecialchars($k->getSpesifikasi()) ?></td>
                        <td class="text-right">Rp <?= number_format($k->getHargaSewa(), 0, ',', '.') ?></td>
                        <td>
                            <span style="color: <?= $k->getStatus() === 'Tersedia' ? '#16a34a' : '#dc2626' ?>; font-weight: 600;">
                                <?= $k->getStatus() ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="card">
        <h3>Kalkulator Simulasi Biaya Sewa (Polymorphic Calculation)</h3>
        
        <?php if ($error): ?>
            <div style="background: #fee2e2; color: #991b1b; padding: 10px; border-radius: 6px; margin-bottom: 12px;">Error: <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" style="display: flex; gap: 12px; align-items: flex-end;">
            <div style="flex: 2;">
                <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 4px;">Pilih Kendaraan:</label>
                <select name="id_kendaraan" style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px;" required>
                    <?php foreach ($daftarArmada as $k): ?>
                        <option value="<?= $k->getId() ?>">
                            <?= htmlspecialchars($k->getMerk()) ?> (<?= $k->getTipeKendaraan() ?>) - Rp <?= number_format($k->getHargaSewa(), 0, ',', '.') ?>/hari
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div style="flex: 1;">
                <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 4px;">Durasi (Hari):</label>
                <input type="number" name="durasi_hari" min="1" max="30" value="3" style="width: 100%; box-sizing: border-box; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px;" required>
            </div>

            <button type="submit" name="simulasi" style="background: #2563eb; color: white; border: none; padding: 11px 20px; border-radius: 6px; font-weight: 600; cursor: pointer;">
                Hitung Biaya
            </button>
        </form>

        <?php if ($simulasiBiaya): ?>
            <div class="simulasi-box">
                <h4 style="margin-top: 0; color: #15803d;">Hasil Kalkulasi Sewa:</h4>
                <p>Kendaraan: <strong><?= htmlspecialchars($simulasiBiaya['kendaraan']->getMerk()) ?></strong> (<?= $simulasiBiaya['kendaraan']->getTipeKendaraan() ?>)</p>
                <p>Lama Sewa: <strong><?= $simulasiBiaya['durasi'] ?> Hari</strong></p>
                <p style="font-size: 18px; font-weight: bold; color: #0f172a; margin-top: 8px;">
                    Total Biaya Bersih: <span style="color: #16a34a;">Rp <?= number_format($simulasiBiaya['total'], 0, ',', '.') ?></span>
                </p>
                <p style="font-size: 12px; color: #64748b; margin-bottom: 0;">*Termasuk kalkulasi diskon bertingkat dan asuransi proteksi kendaraan (jika mobil).</p>
            </div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
