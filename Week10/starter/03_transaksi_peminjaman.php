<?php
/**
 * Latihan 3: Transaksi Database pada PDO (beginTransaction, commit, rollBack)
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
$pesan = "";
$statusSukses = false;

// PROSES PEMINJAMAN DENGAN TRANSAKSI
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idBuku = (int)$_POST['id_buku'];
    $namaMhs = trim($_POST['nama']);
    $nimMhs = trim($_POST['nim']);

    try {
        // 1. MULAI TRANSAKSI PDO
        $pdo->beginTransaction();

        // 2. Periksa ketersediaan stok
        $stmtCek = $pdo->prepare("SELECT stok, judul FROM koleksi_buku WHERE id = :id FOR UPDATE");
        $stmtCek->execute([':id' => $idBuku]);
        $buku = $stmtCek->fetch();

        if (!$buku || $buku['stok'] <= 0) {
            throw new Exception("Buku '{$buku['judul']}' sedang tidak tersedia (Stok: 0).");
        }

        // 3. Kurangi Stok Buku (-1)
        $stmtKurang = $pdo->prepare("UPDATE koleksi_buku SET stok = stok - 1 WHERE id = :id");
        $stmtKurang->execute([':id' => $idBuku]);

        // 4. Catat Riwayat Peminjaman
        $stmtPinjam = $pdo->prepare("INSERT INTO peminjaman_buku (id_buku, nama_peminjam, nim, tanggal_pinjam, status) 
                                     VALUES (:id_buku, :nama, :nim, :tgl, 'Dipinjam')");
        $stmtPinjam->execute([
            ':id_buku' => $idBuku,
            ':nama' => $namaMhs,
            ':nim' => $nimMhs,
            ':tgl' => date('Y-m-d')
        ]);

        // 5. COMMIT: Simpan Permanen ke Database
        $pdo->commit();
        $pesan = "Peminjaman buku '{$buku['judul']}' oleh {$namaMhs} ({$nimMhs}) berhasil dicatat!";
        $statusSukses = true;

    } catch (Exception $e) {
        // ROLLBACK: Batalkan Seluruh Operasi jika ada kegagalan
        $pdo->rollBack();
        $pesan = "Transaksi Gagal (Rollback): " . $e->getMessage();
        $statusSukses = false;
    }
}

// AMBIL DAFTAR BUKU & RIWAYAT PEMINJAMAN
$daftarBuku = $pdo->query("SELECT id, judul, stok FROM koleksi_buku WHERE stok > 0")->fetchAll();
$daftarRiwayat = $pdo->query("SELECT p.id, p.nama_peminjam, p.nim, p.tanggal_pinjam, p.status, b.judul 
                             FROM peminjaman_buku p 
                             JOIN koleksi_buku b ON p.id_buku = b.id 
                             ORDER BY p.id DESC LIMIT 5")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Latihan 3 - Transaksi PDO</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f8fafc; padding: 30px; }
        .container { max-width: 800px; margin: 0 auto; }
        .card { background: white; border-radius: 12px; padding: 24px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); margin-bottom: 24px; }
        .alert-ok { background: #dcfce7; color: #166534; padding: 12px; border-radius: 6px; margin-bottom: 16px; }
        .alert-err { background: #fee2e2; color: #991b1b; padding: 12px; border-radius: 6px; margin-bottom: 16px; }
        .form-row { margin-bottom: 12px; }
        label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 4px; color: #475569; }
        input, select { width: 100%; box-sizing: border-box; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px; }
        button { background: #2563eb; color: white; border: none; padding: 10px 16px; border-radius: 6px; font-weight: 600; cursor: pointer; width: 100%; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; font-size: 14px; }
        th, td { padding: 10px 12px; border-bottom: 1px solid #e2e8f0; text-align: left; }
        th { background: #0f172a; color: white; }
    </style>
</head>
<body>

<div class="container">
    <div class="card">
        <h2>Transaksi Peminjaman Buku (Atomic PDO Transaction)</h2>

        <?php if ($pesan): ?>
            <div class="<?= $statusSukses ? 'alert-ok' : 'alert-err' ?>">
                <?= htmlspecialchars($pesan) ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-row">
                <label>Pilih Judul Buku (Hanya yang Stok Tersedia):</label>
                <select name="id_buku" required>
                    <?php foreach ($daftarBuku as $b): ?>
                        <option value="<?= $b['id'] ?>"><?= htmlspecialchars($b['judul']) ?> (Sisa: <?= $b['stok'] ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-row">
                <label>Nama Peminjam:</label>
                <input type="text" name="nama" required placeholder="Nama mahasiswa...">
            </div>
            <div class="form-row">
                <label>NIM Mahasiswa:</label>
                <input type="text" name="nim" required placeholder="Contoh: 10231011">
            </div>
            <button type="submit">Proses Peminjaman (Atomic)</button>
        </form>
    </div>

    <div class="card">
        <h3>5 Riwayat Peminjaman Terakhir</h3>
        <table>
            <thead>
                <tr>
                    <th>Peminjam</th>
                    <th>NIM</th>
                    <th>Judul Buku</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($daftarRiwayat as $r): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($r['nama_peminjam']) ?></strong></td>
                        <td><code><?= htmlspecialchars($r['nim']) ?></code></td>
                        <td><?= htmlspecialchars($r['judul']) ?></td>
                        <td><?= $r['tanggal_pinjam'] ?></td>
                        <td><span style="color: #2563eb; font-weight: bold;"><?= $r['status'] ?></span></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
