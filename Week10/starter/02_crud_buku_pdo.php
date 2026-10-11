<?php
/**
 * Latihan 2: Operasi CRUD Berbasis PDO
 * Modul Praktikum Minggu 10
 */

$dsn = "mysql:host=127.0.0.1;dbname=pemrograman_lanjut;charset=utf8mb4";
$username = "root";
$password = "";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

$pdo = new PDO($dsn, $username, $password, $options);
$notifikasi = "";

// PROSES TAMBAH (CREATE)
if (isset($_POST['tambah'])) {
    $kode = trim($_POST['kode']);
    $judul = trim($_POST['judul']);
    $penulis = trim($_POST['penulis']);
    $kategori = trim($_POST['kategori']);
    $stok = (int)$_POST['stok'];
    $tahun = (int)$_POST['tahun'];

    try {
        $stmt = $pdo->prepare("INSERT INTO koleksi_buku (kode_buku, judul, penulis, kategori, stok, tahun_terbit) 
                               VALUES (:kode, :judul, :penulis, :kategori, :stok, :tahun)");
        $stmt->execute([
            ':kode' => $kode,
            ':judul' => $judul,
            ':penulis' => $penulis,
            ':kategori' => $kategori,
            ':stok' => $stok,
            ':tahun' => $tahun
        ]);
        $notifikasi = "Buku baru '{$judul}' berhasil ditambahkan ke database!";
    } catch (PDOException $e) {
        $notifikasi = "Gagal menambah buku: " . $e->getMessage();
    }
}

// PROSES HAPUS (DELETE)
if (isset($_GET['hapus_id'])) {
    $idHapus = (int)$_GET['hapus_id'];
    $stmt = $pdo->prepare("DELETE FROM koleksi_buku WHERE id = :id");
    $stmt->execute([':id' => $idHapus]);
    header("Location: 02_crud_buku_pdo.php");
    exit;
}

// AMBIL DAFTAR BUKU (READ)
$daftarBuku = $pdo->query("SELECT * FROM koleksi_buku ORDER BY id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Latihan 2 - CRUD PDO</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f8fafc; padding: 30px; }
        .container { max-width: 900px; margin: 0 auto; }
        .card { background: white; border-radius: 12px; padding: 24px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); margin-bottom: 24px; }
        .grid-form { display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; }
        .form-group { display: flex; flex-direction: column; gap: 4px; }
        label { font-size: 12px; font-weight: 600; color: #475569; }
        input { padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; }
        button { background: #16a34a; color: white; border: none; padding: 10px; border-radius: 6px; cursor: pointer; font-weight: 600; grid-column: span 2; margin-top: 8px; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; font-size: 14px; }
        th, td { padding: 10px 12px; border-bottom: 1px solid #e2e8f0; text-align: left; }
        th { background: #0f172a; color: white; }
        .del-link { color: #dc2626; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>

<div class="container">
    <div class="card">
        <h2>Entri Buku Baru (PDO INSERT)</h2>
        <?php if ($notifikasi): ?>
            <div style="background: #dcfce7; color: #166534; padding: 10px; border-radius: 6px; margin: 12px 0;">
                <?= htmlspecialchars($notifikasi) ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="grid-form">
            <div class="form-group">
                <label>Kode Buku:</label>
                <input type="text" name="kode" required placeholder="BK-999">
            </div>
            <div class="form-group">
                <label>Judul Buku:</label>
                <input type="text" name="judul" required placeholder="Judul buku...">
            </div>
            <div class="form-group">
                <label>Penulis:</label>
                <input type="text" name="penulis" required placeholder="Nama penulis...">
            </div>
            <div class="form-group">
                <label>Kategori:</label>
                <input type="text" name="kategori" required placeholder="Programming / Architecture...">
            </div>
            <div class="form-group">
                <label>Stok Awal:</label>
                <input type="number" name="stok" min="0" value="5" required>
            </div>
            <div class="form-group">
                <label>Tahun Terbit:</label>
                <input type="number" name="tahun" min="1990" max="2030" value="2024" required>
            </div>
            <button type="submit" name="tambah">+ Simpan Buku via PDO</button>
        </form>
    </div>

    <div class="card">
        <h3>Daftar Inventaris Buku (PDO SELECT & DELETE)</h3>
        <table>
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Judul</th>
                    <th>Penulis</th>
                    <th>Stok</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($daftarBuku as $b): ?>
                    <tr>
                        <td><code><?= htmlspecialchars($b['kode_buku']) ?></code></td>
                        <td><strong><?= htmlspecialchars($b['judul']) ?></strong></td>
                        <td><?= htmlspecialchars($b['penulis']) ?></td>
                        <td><?= $b['stok'] ?> unit</td>
                        <td>
                            <a href="?hapus_id=<?= $b['id'] ?>" class="del-link" onclick="return confirm('Yakin hapus buku ini?')">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
