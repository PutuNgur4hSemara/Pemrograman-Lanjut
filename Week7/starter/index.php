<?php
/**
 * READ View: Menampilkan Seluruh Mahasiswa
 * Modul Praktikum Minggu 7
 */

require_once __DIR__ . '/config/Database.php';
require_once __DIR__ . '/src/MahasiswaRepository.php';

$db = Database::getConnection();
$repo = new MahasiswaRepository($db);
$daftarMahasiswa = $repo->semua();

$pesan = $_GET['pesan'] ?? null;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplikasi CRUD Mahasiswa - MySQLi Object</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f8fafc; color: #1e293b; padding: 40px 20px; }
        .container { max-width: 900px; margin: 0 auto; background: white; border-radius: 12px; padding: 28px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
        .header-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
        .btn-add { background: #2563eb; color: white; padding: 10px 18px; border-radius: 6px; text-decoration: none; font-weight: 600; font-size: 14px; }
        .btn-add:hover { background: #1d4ed8; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { padding: 12px 14px; border-bottom: 1px solid #e2e8f0; text-align: left; font-size: 14px; }
        th { background: #0f172a; color: white; }
        .badge-prodi { background: #e0e7ff; color: #3730a3; padding: 3px 8px; border-radius: 4px; font-weight: 600; font-size: 12px; }
        .action-link { text-decoration: none; font-weight: 600; margin-right: 8px; font-size: 13px; }
        .edit-link { color: #d97706; }
        .delete-link { color: #dc2626; }
        .alert-flash { background: #dcfce7; color: #166534; padding: 12px; border-radius: 6px; margin-bottom: 20px; }
    </style>
</head>
<body>

<div class="container">
    <?php if ($pesan): ?>
        <div class="alert-flash">✓ <?= htmlspecialchars($pesan) ?></div>
    <?php endif; ?>

    <div class="header-bar">
        <div>
            <h2 style="margin: 0;">Data Mahasiswa Terdaftar</h2>
            <p style="margin: 4px 0 0; color: #64748b; font-size: 14px;">Implementasi CRUD Berbasis Class dengan MySQLi Object</p>
        </div>
        <a href="tambah.php" class="btn-add">+ Tambah Mahasiswa Baru</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>NIM</th>
                <th>Nama Lengkap</th>
                <th>Program Studi</th>
                <th>IPK</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($daftarMahasiswa)): ?>
                <?php foreach ($daftarMahasiswa as $i => $mhs): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><code><?= htmlspecialchars($mhs['nim']) ?></code></td>
                        <td><strong><?= htmlspecialchars($mhs['nama']) ?></strong></td>
                        <td><span class="badge-prodi"><?= htmlspecialchars($mhs['program_studi']) ?></span></td>
                        <td><strong style="color: #2563eb;"><?= number_format($mhs['ipk'], 2) ?></strong></td>
                        <td>
                            <a href="edit.php?id=<?= $mhs['id'] ?>" class="action-link edit-link">Edit</a>
                            <a href="hapus.php?id=<?= $mhs['id'] ?>" class="action-link delete-link" onclick="return confirm('Yakin ingin menghapus data mahasiswa ini?')">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" style="text-align: center; color: #94a3b8; padding: 30px;">Belum ada data mahasiswa.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
