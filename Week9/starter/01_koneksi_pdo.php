<?php
/**
 * Latihan 1: Inisialisasi Koneksi PDO & Konfigurasi Opsi Atribut
 * Modul Praktikum Minggu 9
 */

$dsn = "mysql:host=127.0.0.1;dbname=pemrograman_lanjut;charset=utf8mb4";
$username = "root";
$password = "";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

$koneksiSukses = false;
$pesanStatus = "";
$serverVersion = "";

try {
    $pdo = new PDO($dsn, $username, $password, $options);
    $koneksiSukses = true;
    $pesanStatus = "Koneksi PDO ke MySQL Berhasil!";
    $serverVersion = $pdo->getAttribute(PDO::ATTR_SERVER_VERSION);
    $driverName = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
} catch (PDOException $e) {
    error_log("PDO Connection Error: " . $e->getMessage());
    $pesanStatus = "Koneksi Gagal: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Latihan 1 - Koneksi PDO</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f8fafc; padding: 30px; }
        .card { max-width: 600px; margin: 0 auto; background: white; border-radius: 12px; padding: 24px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
        .badge-ok { background: #dcfce7; color: #15803d; padding: 4px 10px; border-radius: 6px; font-weight: bold; }
        .badge-err { background: #fee2e2; color: #991b1b; padding: 4px 10px; border-radius: 6px; font-weight: bold; }
        .info-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f1f5f9; font-size: 14px; }
    </style>
</head>
<body>

    <div class="card">
        <span class="<?= $koneksiSukses ? 'badge-ok' : 'badge-err' ?>">
            <?= $koneksiSukses ? 'PDO CONNECTED' : 'PDO ERROR' ?>
        </span>

        <h2 style="margin-top: 12px;"><?= htmlspecialchars($pesanStatus) ?></h2>

        <?php if ($koneksiSukses): ?>
            <div style="margin-top: 20px;">
                <div class="info-row">
                    <span>Driver Database:</span>
                    <strong><?= htmlspecialchars($driverName) ?></strong>
                </div>
                <div class="info-row">
                    <span>Versi Server MySQL:</span>
                    <strong><?= htmlspecialchars($serverVersion) ?></strong>
                </div>
                <div class="info-row">
                    <span>Error Mode:</span>
                    <code>PDO::ERRMODE_EXCEPTION</code>
                </div>
                <div class="info-row" style="border: none;">
                    <span>Default Fetch Mode:</span>
                    <code>PDO::FETCH_ASSOC</code>
                </div>
            </div>
        <?php endif; ?>
    </div>

</body>
</html>
