<?php
/**
 * Latihan 3: Pengantar Koneksi MySQLi Object & Exception Mode
 * Modul Praktikum Minggu 5
 */

// 1. Mengaktifkan strict mode exception untuk mysqli
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$host = '127.0.0.1';
$user = 'root';
$pass = '';
$dbName = 'pemrograman_lanjut'; // Database latihan

$statusKoneksi = false;
$pesanUser = "";
$detailServerLog = "";

try {
    // 2. Instansiasi Objek MySQLi
    $db = new mysqli($host, $user, $pass, $dbName);
    
    // Set charset
    $db->set_charset('utf8mb4');
    
    $statusKoneksi = true;
    $pesanUser = "Koneksi ke MySQL Server via MySQLi Object BERHASIL! (Host Info: " . $db->host_info . ")";
    
    // Tutup koneksi setelah selesai
    $db->close();
} catch (mysqli_sql_exception $e) {
    $statusKoneksi = false;
    
    // 1. Log error teknis ke internal server (Aman, tidak bocor ke user)
    error_log("Database Connection Failed: " . $e->getMessage());
    $detailServerLog = $e->getMessage();

    // 2. Pesan ramah dan aman untuk user
    $pesanUser = "Tidak dapat terhubung ke server database. Pastikan service MySQL di XAMPP telah aktif dan database 'pemrograman_lanjut' telah dibuat.";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Latihan 3 - Koneksi MySQLi Object</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f8fafc; padding: 30px; }
        .card { max-width: 650px; margin: 0 auto; background: white; border-radius: 12px; padding: 24px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
        .status-badge { display: inline-block; padding: 4px 10px; border-radius: 6px; font-weight: bold; font-size: 13px; margin-bottom: 12px; }
        .status-ok { background: #dcfce7; color: #15803d; }
        .status-fail { background: #fee2e2; color: #991b1b; }
        .technical-box { background: #0f172a; color: #f87171; font-family: monospace; font-size: 12px; padding: 12px; border-radius: 6px; margin-top: 16px; word-break: break-all; }
    </style>
</head>
<body>

    <div class="card">
        <span class="status-badge <?= $statusKoneksi ? 'status-ok' : 'status-fail' ?>">
            <?= $statusKoneksi ? 'DATABASE ONLINE' : 'DATABASE OFFLINE / PERLU PERHATIAN' ?>
        </span>

        <h2>Uji Koneksi MySQLi Object</h2>
        <p style="margin-top: 8px; color: #334155;"><?= htmlspecialchars($pesanUser) ?></p>

        <?php if (!$statusKoneksi && $detailServerLog): ?>
            <div style="margin-top: 20px;">
                <p style="font-size: 13px; color: #64748b; font-weight: 600;">Pesan Teknis (mysqli_sql_exception dicatat ke error_log):</p>
                <div class="technical-box">
                    <?= htmlspecialchars($detailServerLog) ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

</body>
</html>
