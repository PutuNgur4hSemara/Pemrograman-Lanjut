<?php
/**
 * Latihan 2: Eksekusi SQL Tanpa Result Set Menggunakan exec()
 * Modul Praktikum Minggu 9
 */

$dsn = "mysql:host=127.0.0.1;dbname=pemrograman_lanjut;charset=utf8mb4";
$username = "root";
$password = "";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

$pesan = "";
$affectedRows = 0;

try {
    $pdo = new PDO($dsn, $username, $password, $options);

    if (isset($_POST['tambah_stok'])) {
        // exec() mengeksekusi DML dan mengembalikan integer affected rows
        $sql = "UPDATE koleksi_buku SET stok = stok + 1 WHERE kategori = 'Programming'";
        $affectedRows = $pdo->exec($sql);
        $pesan = "Berhasil menambah 1 stok untuk seluruh buku berkategori 'Programming'. Baris terdampak: {$affectedRows} baris.";
    }

    // Ambil daftar buku untuk verifikasi
    $stmt = $pdo->query("SELECT kode_buku, judul, kategori, stok FROM koleksi_buku WHERE kategori = 'Programming'");
    $bukuList = $stmt->fetchAll();

} catch (PDOException $e) {
    $pesan = "Error: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Latihan 2 - PDO exec()</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f8fafc; padding: 30px; }
        .card { max-width: 650px; margin: 0 auto; background: white; border-radius: 12px; padding: 24px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
        .alert { background: #dcfce7; color: #166534; padding: 12px; border-radius: 6px; margin: 16px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th, td { padding: 10px 12px; border-bottom: 1px solid #e2e8f0; text-align: left; font-size: 14px; }
        th { background: #0f172a; color: white; }
        button { background: #2563eb; color: white; border: none; padding: 10px 16px; border-radius: 6px; cursor: pointer; font-weight: 600; }
    </style>
</head>
<body>

    <div class="card">
        <h2>Demonstrasi PDO Method <code>exec()</code></h2>
        <p style="color: #64748b; font-size: 14px;">Method <code>exec()</code> digunakan untuk instruksi yang tidak menghasilkan result set dan mengembalikan jumlah baris yang terpengaruh (*affected rows*).</p>

        <?php if ($pesan): ?>
            <div class="alert"><?= htmlspecialchars($pesan) ?></div>
        <?php endif; ?>

        <form method="POST">
            <button type="submit" name="tambah_stok">+ Tambah Stok Kategori Programming (+1)</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Judul Buku</th>
                    <th>Stok</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bukuList as $b): ?>
                    <tr>
                        <td><code><?= htmlspecialchars($b['kode_buku']) ?></code></td>
                        <td><?= htmlspecialchars($b['judul']) ?></td>
                        <td><strong style="color: #2563eb;"><?= $b['stok'] ?></strong> unit</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</body>
</html>
