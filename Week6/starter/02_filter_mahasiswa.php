<?php
/**
 * Latihan 2: Multi-Parameter Binding & Output Escaping
 * Modul Praktikum Minggu 6
 */

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$host = '127.0.0.1';
$user = 'root';
$pass = '';
$dbName = 'pemrograman_lanjut';

$daftarMahasiswa = [];
$filterProdi = $_GET['prodi'] ?? 'Semua';
$minIpk = isset($_GET['min_ipk']) && is_numeric($_GET['min_ipk']) ? (float)$_GET['min_ipk'] : 3.00;

try {
    $db = new mysqli($host, $user, $pass, $dbName);
    $db->set_charset('utf8mb4');

    if ($filterProdi === 'Semua') {
        $sql = "SELECT nim, nama, program_studi, ipk FROM mahasiswa WHERE ipk >= ? ORDER BY ipk DESC";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("d", $minIpk);
    } else {
        $sql = "SELECT nim, nama, program_studi, ipk FROM mahasiswa WHERE ipk >= ? AND program_studi = ? ORDER BY ipk DESC";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("ds", $minIpk, $filterProdi);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    $daftarMahasiswa = $result->fetch_all(MYSQLI_ASSOC);

    $stmt->close();
    $db->close();
} catch (mysqli_sql_exception $e) {
    error_log($e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Latihan 2 - Parameter Binding</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f8fafc; padding: 30px; }
        .container { max-width: 800px; margin: 0 auto; background: white; border-radius: 12px; padding: 24px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
        .filter-form { display: flex; gap: 12px; margin-bottom: 20px; align-items: flex-end; background: #f1f5f9; padding: 16px; border-radius: 8px; }
        .form-group { display: flex; flex-direction: column; gap: 4px; }
        .form-group label { font-size: 12px; font-weight: 600; color: #475569; }
        select, input { padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; }
        button { background: #2563eb; color: white; border: none; padding: 9px 16px; border-radius: 6px; cursor: pointer; font-weight: 600; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px 14px; border-bottom: 1px solid #e2e8f0; text-align: left; }
        th { background: #0f172a; color: white; }
    </style>
</head>
<body>

    <div class="container">
        <h2>Filter Data Mahasiswa (Multi-Parameter Binding)</h2>

        <form method="GET" class="filter-form">
            <div class="form-group">
                <label>Program Studi:</label>
                <select name="prodi">
                    <option value="Semua" <?= $filterProdi === 'Semua' ? 'selected' : '' ?>>Semua Program Studi</option>
                    <option value="Sistem Informasi" <?= $filterProdi === 'Sistem Informasi' ? 'selected' : '' ?>>Sistem Informasi</option>
                    <option value="Informatika" <?= $filterProdi === 'Informatika' ? 'selected' : '' ?>>Informatika</option>
                    <option value="Sains Data" <?= $filterProdi === 'Sains Data' ? 'selected' : '' ?>>Sains Data</option>
                </select>
            </div>

            <div class="form-group">
                <label>Batas Minimal IPK:</label>
                <input type="number" step="0.05" min="0" max="4" name="min_ipk" value="<?= htmlspecialchars((string)$minIpk) ?>">
            </div>

            <button type="submit">Terapkan Filter</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>NIM</th>
                    <th>Nama Lengkap</th>
                    <th>Program Studi</th>
                    <th>IPK</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($daftarMahasiswa)): ?>
                    <?php foreach ($daftarMahasiswa as $mhs): ?>
                        <tr>
                            <td><code><?= htmlspecialchars($mhs['nim']) ?></code></td>
                            <td><strong><?= htmlspecialchars($mhs['nama']) ?></strong></td>
                            <td><?= htmlspecialchars($mhs['program_studi']) ?></td>
                            <td><span style="color: #2563eb; font-weight: bold;"><?= number_format($mhs['ipk'], 2) ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" style="text-align: center; color: #94a3b8;">Tidak ada data yang memenuhi kriteria filter.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</body>
</html>
