<?php
/**
 * Latihan 2: Eksekusi Parameter & Modifikasi Status
 * Modul Praktikum Minggu 11
 */

class Database
{
    public function __construct(private PDO $pdo) {}

    public function run(string $sql, array $params = []): PDOStatement
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public function getAll(string $sql, array $params = []): array
    {
        return $this->run($sql, $params)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAffectedRows(string $sql, array $params = []): int
    {
        return $this->run($sql, $params)->rowCount();
    }
}

$pdo = new PDO("mysql:host=127.0.0.1;dbname=pemrograman_lanjut;charset=utf8mb4", "root", "", [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
$db = new Database($pdo);

$notifikasi = "";

if (isset($_POST['update_status'])) {
    $kode = $_POST['kode'];
    $statusBaru = $_POST['status_baru'];

    $affected = $db->getAffectedRows(
        "UPDATE kegiatan SET status = :status WHERE kode = :kode",
        [':status' => $statusBaru, ':kode' => $kode]
    );

    $notifikasi = "Status kegiatan {$kode} berhasil diubah menjadi '{$statusBaru}' (Baris terdampak: {$affected}).";
}

$filterStatus = $_GET['filter'] ?? 'terjadwal';
$kegiatanTerfilter = $db->getAll(
    "SELECT kode, nama, lokasi, kuota, status FROM kegiatan WHERE status = :status ORDER BY kode ASC",
    [':status' => $filterStatus]
);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Latihan 2 - Eksekusi Parameter</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f8fafc; padding: 30px; }
        .container { max-width: 800px; margin: 0 auto; }
        .card { background: white; border-radius: 12px; padding: 24px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .alert { background: #dcfce7; color: #166534; padding: 12px; border-radius: 6px; margin-bottom: 16px; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { padding: 10px 12px; border-bottom: 1px solid #e2e8f0; text-align: left; font-size: 14px; }
        th { background: #0f172a; color: white; }
    </style>
</head>
<body>

<div class="container">
    <div class="card">
        <h2>Eksekusi Query Berparameter Dinamis</h2>
        
        <?php if ($notifikasi): ?>
            <div class="alert"><?= htmlspecialchars($notifikasi) ?></div>
        <?php endif; ?>

        <form method="GET" style="margin-bottom: 16px;">
            <label style="font-weight: 600; font-size: 13px;">Filter Status:</label>
            <select name="filter" onchange="this.form.submit()" style="padding: 6px 12px; border-radius: 6px; border: 1px solid #cbd5e1;">
                <option value="terjadwal" <?= $filterStatus === 'terjadwal' ? 'selected' : '' ?>>Terjadwal</option>
                <option value="selesai" <?= $filterStatus === 'selesai' ? 'selected' : '' ?>>Selesai</option>
            </select>
        </form>

        <table>
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama Kegiatan</th>
                    <th>Lokasi</th>
                    <th>Kuota</th>
                    <th>Aksi Cepat</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($kegiatanTerfilter as $k): ?>
                    <tr>
                        <td><code><?= htmlspecialchars($k['kode']) ?></code></td>
                        <td><?= htmlspecialchars($k['nama']) ?></td>
                        <td><?= htmlspecialchars($k['lokasi']) ?></td>
                        <td><?= $k['kuota'] ?> orang</td>
                        <td>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="kode" value="<?= $k['kode'] ?>">
                                <input type="hidden" name="status_baru" value="<?= $k['status'] === 'terjadwal' ? 'selesai' : 'terjadwal' ?>">
                                <button type="submit" name="update_status" style="padding: 4px 8px; font-size: 12px; cursor: pointer;">
                                    Tandai <?= $k['status'] === 'terjadwal' ? 'Selesai' : 'Terjadwal' ?>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
