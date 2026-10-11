<?php
// ================================================
// File: index.php
// Fungsi: menampilkan daftar hewan dan tombol CRUD.
// ================================================

// Memanggil file fungsi koneksi db.
require_once __DIR__ . '/db.php';

// Mengambil koneksi database.
$db = connectDatabase();

// Proses baca semua data hewan dari tabel.
$result = $db->query("SELECT * FROM hewan ORDER BY nama ASC");

// Jika result tidak ada, array kosong agar tampilan aman.
$hewan = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

// Pesan ini dipakai untuk menampilkan notifikasi hasil tambah/edit/hapus.
$pesan = $_GET['pesan'] ?? '';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pre-UTS Hewan</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <main class="container">
        <header class="hero">
            <p class="eyebrow">Pre-UTS · Sederhana</p>
            <h1>CRUD Data Hewan</h1>
            <p>Versi sederhana tanpa MVC. Semua logika dibuat dalam satu alur file.</p>
        </header>

        <section class="card">
            <h2>Daftar Hewan</h2>
            <?php if ($pesan !== ''): ?>
                <div class="alert success"><?= htmlspecialchars($pesan, ENT_QUOTES, 'UTF-8') ?></div>
            <?php endif; ?>

            <div style="margin-bottom: 16px;">
                <a href="tambah.php" class="badge" style="text-decoration: none;">+ Tambah Hewan</a>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Spesies</th>
                        <th>Jumlah</th>
                        <th>Status</th>
                        <th>Umur</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($hewan)): ?>
                        <?php foreach ($hewan as $i => $h): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><?= htmlspecialchars($h['kode_hewan'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($h['nama'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($h['spesies'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= (int)$h['jumlah'] ?></td>
                                <td><?= htmlspecialchars($h['status'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= (int)$h['umur'] ?> tahun</td>
                                <td>
                                    <a href="edit.php?id=<?= (int)$h['id'] ?>" class="badge" style="text-decoration: none;">Edit</a>
                                    <a href="hapus.php?id=<?= (int)$h['id'] ?>" class="badge" style="text-decoration: none; background: #fee2e2; color: #991b1b;" onclick="return confirm('Yakin ingin hapus data hewan ini?')">Hapus</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" style="text-align: center; color: #64748b;">Belum ada data hewan.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </main>
</body>
</html>
