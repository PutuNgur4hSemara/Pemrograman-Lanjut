<?php
/**
 * Latihan 1: Eksekusi CRUD Lengkap Menggunakan Query Builder
 * Modul Praktikum Minggu 13
 */

require_once __DIR__ . '/QueryBuilder.php';

$pdo = new PDO("mysql:host=127.0.0.1;dbname=pemrograman_lanjut;charset=utf8mb4", "root", "", [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
$builder = new QueryBuilder($pdo);

$notifikasi = "";

// 1. TAMBAH (INSERT)
if (isset($_POST['tambah'])) {
    $sukses = $builder->table('kegiatan')->insert([
        'kode' => trim($_POST['kode']),
        'nama' => trim($_POST['nama']),
        'lokasi' => trim($_POST['lokasi']),
        'tanggal' => $_POST['tanggal'],
        'kuota' => (int)$_POST['kuota'],
        'status' => 'terjadwal'
    ]);
    if ($sukses) {
        $notifikasi = "Kegiatan baru berhasil dimasukkan via QueryBuilder::insert()!";
    }
}

// 2. HAPUS (DELETE)
if (isset($_GET['hapus'])) {
    $kodeHapus = $_GET['hapus'];
    $deleted = $builder->table('kegiatan')->where('kode', '=', $kodeHapus)->delete();
    $notifikasi = "Kegiatan {$kodeHapus} berhasil dihapus ({$deleted} baris).";
}

// 3. BACA DATA (SELECT)
$semuaKegiatan = $builder->table('kegiatan')->orderBy('tanggal', 'DESC')->get();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Latihan 1 - Query Builder CRUD</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f8fafc; padding: 30px; }
        .container { max-width: 900px; margin: 0 auto; }
        .card { background: white; border-radius: 12px; padding: 24px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .alert { background: #dcfce7; color: #166534; padding: 12px; border-radius: 6px; margin-bottom: 16px; font-size: 14px; }
        .grid-form { display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; }
        input { padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; }
        button { background: #2563eb; color: white; border: none; padding: 10px; border-radius: 6px; cursor: pointer; font-weight: 600; grid-column: span 2; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; font-size: 14px; }
        th, td { padding: 10px 12px; border-bottom: 1px solid #e2e8f0; text-align: left; }
        th { background: #0f172a; color: white; }
    </style>
</head>
<body>

<div class="container">
    <div class="card">
        <h2>Tambah Agenda Baru (QueryBuilder::insert)</h2>
        <?php if ($notifikasi): ?>
            <div class="alert"><?= htmlspecialchars($notifikasi) ?></div>
        <?php endif; ?>

        <form method="POST" class="grid-form">
            <div>
                <label style="font-size: 12px; font-weight: 600; display: block; margin-bottom: 4px;">Kode Agenda:</label>
                <input type="text" name="kode" required placeholder="Contoh: ACT-11" style="width: 100%; box-sizing: border-box;">
            </div>
            <div>
                <label style="font-size: 12px; font-weight: 600; display: block; margin-bottom: 4px;">Nama Kegiatan:</label>
                <input type="text" name="nama" required placeholder="Nama kegiatan..." style="width: 100%; box-sizing: border-box;">
            </div>
            <div>
                <label style="font-size: 12px; font-weight: 600; display: block; margin-bottom: 4px;">Lokasi / Ruangan:</label>
                <input type="text" name="lokasi" required placeholder="Contoh: Lab Komputer 2" style="width: 100%; box-sizing: border-box;">
            </div>
            <div>
                <label style="font-size: 12px; font-weight: 600; display: block; margin-bottom: 4px;">Tanggal:</label>
                <input type="date" name="tanggal" required value="<?= date('Y-m-d') ?>" style="width: 100%; box-sizing: border-box;">
            </div>
            <div style="grid-column: span 2;">
                <label style="font-size: 12px; font-weight: 600; display: block; margin-bottom: 4px;">Kuota Peserta:</label>
                <input type="number" name="kuota" value="50" min="1" required style="width: 100%; box-sizing: border-box;">
            </div>
            <button type="submit" name="tambah">+ Simpan Agenda via Query Builder</button>
        </form>
    </div>

    <div class="card">
        <h3>Daftar Agenda (QueryBuilder::get)</h3>
        <table>
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Lokasi</th>
                    <th>Tanggal</th>
                    <th>Kuota</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($semuaKegiatan as $k): ?>
                    <tr>
                        <td><code><?= htmlspecialchars($k['kode']) ?></code></td>
                        <td><strong><?= htmlspecialchars($k['nama']) ?></strong></td>
                        <td><?= htmlspecialchars($k['lokasi']) ?></td>
                        <td><?= $k['tanggal'] ?></td>
                        <td><?= $k['kuota'] ?> orang</td>
                        <td>
                            <a href="?hapus=<?= urlencode($k['kode']) ?>" style="color: #dc2626; font-weight: bold; text-decoration: none;" onclick="return confirm('Yakin hapus?')">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
