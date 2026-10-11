<?php
/**
 * Latihan 1: Positional vs Named Parameter Binding pada PDO
 * Modul Praktikum Minggu 10
 */

$dsn = "mysql:host=127.0.0.1;dbname=pemrograman_lanjut;charset=utf8mb4";
$username = "root";
$password = "";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

$kataKunci = $_GET['q'] ?? '';
$minTahun = isset($_GET['tahun']) && is_numeric($_GET['tahun']) ? (int)$_GET['tahun'] : 2000;
$hasilBuku = [];

try {
    $pdo = new PDO($dsn, $username, $password, $options);

    // MENGGUNAKAN NAMED PARAMETER BINDING
    $sql = "SELECT kode_buku, judul, penulis, kategori, stok, tahun_terbit 
            FROM koleksi_buku 
            WHERE (judul LIKE :keyword OR penulis LIKE :keyword)
              AND tahun_terbit >= :tahunMin
            ORDER BY tahun_terbit DESC";

    $stmt = $pdo->prepare($sql);
    
    // Binding menggunakan array asosiatif di execute()
    $stmt->execute([
        ':keyword' => '%' . $kataKunci . '%',
        ':tahunMin' => $minTahun
    ]);

    $hasilBuku = $stmt->fetchAll();

} catch (PDOException $e) {
    die("Database Error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Latihan 1 - Named Parameter PDO</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f8fafc; padding: 30px; }
        .container { max-width: 800px; margin: 0 auto; background: white; border-radius: 12px; padding: 24px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
        .search-box { display: flex; gap: 12px; margin-bottom: 20px; align-items: flex-end; background: #f1f5f9; padding: 16px; border-radius: 8px; }
        input { padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; }
        button { background: #2563eb; color: white; border: none; padding: 9px 16px; border-radius: 6px; cursor: pointer; font-weight: 600; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px 12px; border-bottom: 1px solid #e2e8f0; text-align: left; font-size: 14px; }
        th { background: #0f172a; color: white; }
    </style>
</head>
<body>

    <div class="container">
        <h2>Pencarian Koleksi Buku (Named Parameter Binding)</h2>

        <form method="GET" class="search-box">
            <div style="flex: 2;">
                <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Kata Kunci (Judul/Penulis):</label>
                <input type="text" name="q" value="<?= htmlspecialchars($kataKunci) ?>" placeholder="Contoh: Architecture atau Martin..." style="width: 100%; box-sizing: border-box;">
            </div>
            <div style="flex: 1;">
                <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Tahun Terbit Min:</label>
                <input type="number" name="tahun" value="<?= $minTahun ?>" style="width: 100%; box-sizing: border-box;">
            </div>
            <button type="submit">Cari Buku</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Judul Buku</th>
                    <th>Penulis</th>
                    <th>Kategori</th>
                    <th>Tahun</th>
                    <th>Stok</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($hasilBuku)): ?>
                    <?php foreach ($hasilBuku as $b): ?>
                        <tr>
                            <td><code><?= htmlspecialchars($b['kode_buku']) ?></code></td>
                            <td><strong><?= htmlspecialchars($b['judul']) ?></strong></td>
                            <td><?= htmlspecialchars($b['penulis']) ?></td>
                            <td><?= htmlspecialchars($b['kategori']) ?></td>
                            <td><?= $b['tahun_terbit'] ?></td>
                            <td><?= $b['stok'] ?> unit</td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align: center; color: #94a3b8; padding: 20px;">Tidak ada buku yang cocok.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</body>
</html>
