<?php
/**
 * Latihan 1: Class Database Wrapper (Pondasi Query Builder)
 * Modul Praktikum Minggu 11
 */

class Database
{
    public function __construct(private PDO $pdo) {}

    public function run(string $sql, array $params = []): PDOStatement
    {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            error_log("DB Query Error: " . $e->getMessage());
            throw new RuntimeException("Kesalahan eksekusi database: " . $e->getMessage());
        }
    }

    public function getAll(string $sql, array $params = []): array
    {
        return $this->run($sql, $params)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRow(string $sql, array $params = []): ?array
    {
        $res = $this->run($sql, $params)->fetch(PDO::FETCH_ASSOC);
        return $res ?: null;
    }
}

// Inisialisasi PDO Native
$pdo = new PDO(
    "mysql:host=127.0.0.1;dbname=pemrograman_lanjut;charset=utf8mb4",
    "root",
    "",
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);

// Inject PDO ke Class Database Wrapper
$db = new Database($pdo);

// Membaca data dengan method wrapper yang bersih
$daftarKegiatan = $db->getAll("SELECT kode, nama, lokasi, tanggal, status FROM kegiatan ORDER BY tanggal ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Latihan 1 - Class Database Wrapper</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f8fafc; padding: 30px; }
        .card { max-width: 850px; margin: 0 auto; background: white; border-radius: 12px; padding: 24px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; font-size: 14px; }
        th, td { padding: 10px 14px; border-bottom: 1px solid #e2e8f0; text-align: left; }
        th { background: #0f172a; color: white; }
        .badge { display: inline-block; padding: 3px 8px; border-radius: 4px; font-size: 12px; font-weight: 600; text-transform: uppercase; }
        .badge-terjadwal { background: #dbeafe; color: #1e40af; }
        .badge-selesai { background: #f1f5f9; color: #64748b; }
    </style>
</head>
<body>

    <div class="card">
        <h2>Daftar Agenda Kegiatan Kampus (via Database Wrapper)</h2>
        <table>
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama Kegiatan</th>
                    <th>Lokasi</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($daftarKegiatan as $k): ?>
                    <tr>
                        <td><code><?= htmlspecialchars($k['kode']) ?></code></td>
                        <td><strong><?= htmlspecialchars($k['nama']) ?></strong></td>
                        <td><?= htmlspecialchars($k['lokasi']) ?></td>
                        <td><?= $k['tanggal'] ?></td>
                        <td>
                            <span class="badge badge-<?= $k['status'] ?>">
                                <?= $k['status'] ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</body>
</html>
