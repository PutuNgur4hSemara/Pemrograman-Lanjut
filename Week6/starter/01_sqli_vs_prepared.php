<?php
/**
 * Latihan 1: Perbandingan Query Biasa (Rentan SQLi) vs Prepared Statement
 * Modul Praktikum Minggu 6
 */

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$host = '127.0.0.1';
$user = 'root';
$pass = '';
$dbName = 'pemrograman_lanjut';

$dbConnected = false;
$hasilAman = [];
$pesanStatus = "";

try {
    $db = new mysqli($host, $user, $pass, $dbName);
    $db->set_charset('utf8mb4');
    $dbConnected = true;

    // Simulasi input dari user yang berpotensi jahat
    $inputNIM = isset($_GET['nim']) ? $_GET['nim'] : '10231001';

    // PENDEKATAN AMAN: Menggunakan Prepared Statement
    $stmt = $db->prepare("SELECT nim, nama, program_studi, ipk FROM mahasiswa WHERE nim = ?");
    $stmt->bind_param("s", $inputNIM);
    $stmt->execute();
    
    $result = $stmt->get_result();
    $hasilAman = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    $db->close();
} catch (mysqli_sql_exception $e) {
    $pesanStatus = "Database Error: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Latihan 1 - Prepared Statement vs SQLi</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f8fafc; padding: 30px; }
        .card { max-width: 700px; margin: 0 auto; background: white; border-radius: 12px; padding: 24px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
        .input-group { display: flex; gap: 10px; margin: 16px 0; }
        input[type="text"] { flex: 1; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px; }
        button { background: #2563eb; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; font-weight: 600; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th, td { padding: 10px 12px; border-bottom: 1px solid #e2e8f0; text-align: left; }
        th { background: #0f172a; color: white; }
    </style>
</head>
<body>

    <div class="card">
        <h2>Pencarian Mahasiswa Berbasis Prepared Statement</h2>
        <p style="color: #64748b; font-size: 14px;">Uji coba masukkan input normal seperti <code>10231001</code> atau karakter manipulasi seperti <code>' OR '1'='1</code>.</p>

        <?php if ($pesanStatus): ?>
            <div style="background: #fee2e2; color: #991b1b; padding: 10px; border-radius: 6px;"><?= htmlspecialchars($pesanStatus) ?></div>
        <?php endif; ?>

        <form method="GET" class="input-group">
            <input type="text" name="nim" value="<?= htmlspecialchars($inputNIM) ?>" placeholder="Masukkan NIM...">
            <button type="submit">Cari Data</button>
        </form>

        <h3>Hasil Query Aman:</h3>
        <?php if (!empty($hasilAman)): ?>
            <table>
                <thead>
                    <tr>
                        <th>NIM</th>
                        <th>Nama</th>
                        <th>Program Studi</th>
                        <th>IPK</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($hasilAman as $mhs): ?>
                        <tr>
                            <td><?= htmlspecialchars($mhs['nim']) ?></td>
                            <td><?= htmlspecialchars($mhs['nama']) ?></td>
                            <td><?= htmlspecialchars($mhs['program_studi']) ?></td>
                            <td><?= number_format($mhs['ipk'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p style="color: #94a3b8; font-style: italic;">Data mahasiswa dengan NIM tersebut tidak ditemukan.</p>
        <?php endif; ?>
    </div>

</body>
</html>
