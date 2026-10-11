<?php
/**
 * Latihan 3: PDO query() dan Perbandingan Mode Fetching (FETCH_ASSOC vs FETCH_OBJ)
 * Modul Praktikum Minggu 9
 */

$dsn = "mysql:host=127.0.0.1;dbname=pemrograman_lanjut;charset=utf8mb4";
$username = "root";
$password = "";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $username, $password, $options);

    // 1. Fetch Mode Default (FETCH_ASSOC): Mengakses via array subscript ($row['judul'])
    $stmtAssoc = $pdo->query("SELECT kode_buku, judul, penulis, stok FROM koleksi_buku ORDER BY stok DESC");
    $hasilAssoc = $stmtAssoc->fetchAll();

    // 2. Fetch Mode Objek (FETCH_OBJ): Mengakses via properti objek ($buku->judul)
    $stmtObj = $pdo->query("SELECT kode_buku, judul, penulis, tahun_terbit FROM koleksi_buku ORDER BY tahun_terbit DESC");
    $hasilObj = $stmtObj->fetchAll(PDO::FETCH_OBJ);

} catch (PDOException $e) {
    die("Database Error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Latihan 3 - PDO Fetch Modes</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f8fafc; padding: 30px; }
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; max-width: 1000px; margin: 0 auto; }
        .card { background: white; border-radius: 12px; padding: 20px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { padding: 8px 10px; border-bottom: 1px solid #e2e8f0; font-size: 13px; text-align: left; }
        th { background: #0f172a; color: white; }
    </style>
</head>
<body>

    <div class="grid">
        <!-- 1. FETCH_ASSOC -->
        <div class="card">
            <h3>1. Mode <code>PDO::FETCH_ASSOC</code></h3>
            <p style="font-size: 13px; color: #64748b;">Akses via kunci array asosiatif: <code>$row['judul']</code></p>
            <table>
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Judul Buku</th>
                        <th>Stok</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($hasilAssoc as $buku): ?>
                        <tr>
                            <td><code><?= htmlspecialchars($buku['kode_buku']) ?></code></td>
                            <td><?= htmlspecialchars($buku['judul']) ?></td>
                            <td><strong><?= $buku['stok'] ?></strong></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- 2. FETCH_OBJ -->
        <div class="card">
            <h3>2. Mode <code>PDO::FETCH_OBJ</code></h3>
            <p style="font-size: 13px; color: #64748b;">Akses via properti objek: <code>$buku->judul</code></p>
            <table>
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Judul Buku</th>
                        <th>Tahun</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($hasilObj as $buku): ?>
                        <tr>
                            <td><code><?= htmlspecialchars($buku->kode_buku) ?></code></td>
                            <td><?= htmlspecialchars($buku->judul) ?></td>
                            <td><strong><?= $buku->tahun_terbit ?></strong></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>
