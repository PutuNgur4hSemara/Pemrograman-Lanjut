<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Mahasiswa — Praktikum 7 MVC</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f4f6f9; }
        nav { background: #2c3e50; padding: 15px 30px; }
        nav a { color: white; text-decoration: none; margin-right: 20px; font-size: 14px; }
        nav a:hover { color: #3498db; }
        .container { max-width: 1000px; margin: 30px auto; padding: 0 20px; }
        h1 { color: #2c3e50; margin-bottom: 20px; }
        
        /* Alert sukses/hapus */
        .alert { padding: 12px 18px; border-radius: 6px; margin-bottom: 20px; font-size: 14px; }
        .alert-sukses { background: #d5f5e3; border: 1px solid #27ae60; color: #1e8449; }
        .alert-hapus  { background: #fdecea; border: 1px solid #e74c3c; color: #c0392b; }
        
        /* Tombol tambah */
        .tombol-tambah { display: inline-block; background: #27ae60; color: white; padding: 10px 20px; border-radius: 6px; text-decoration: none; margin-bottom: 20px; font-size: 14px; }
        .tombol-tambah:hover { background: #1e8449; }
        
        /* Tabel */
        table { width: 100%; border-collapse: collapse; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,.1); }
        th { background: #2c3e50; color: white; padding: 13px 15px; text-align: left; font-size: 13px; }
        td { padding: 12px 15px; border-bottom: 1px solid #f0f0f0; font-size: 14px; }
        tr:last-child td { border-bottom: none; }
        
        /* Tombol hapus */
        .tombol-hapus { background: #e74c3c; color: white; border: none; padding: 6px 14px; border-radius: 4px; cursor: pointer; font-size: 13px; text-decoration: none; }
        .tombol-hapus:hover { background: #c0392b; }
        
        /* Empty state */
        .kosong { text-align: center; color: #999; padding: 40px; }
    </style>
</head>
<body>

<nav>
    <a href="index.php">🏠 Beranda</a>
    <a href="?halaman=tambah">➕ Tambah Mahasiswa</a>
</nav>

<div class="container">
    <h1>📋 Daftar Mahasiswa</h1>

    <!-- Pesan sukses setelah tambah -->
    <?php if (isset($_GET['sukses'])): ?>
        <div class="alert alert-sukses">✅ Mahasiswa berhasil ditambahkan ke database!</div>
    <?php endif; ?>

    <!-- Pesan setelah hapus -->
    <?php if (isset($_GET['hapus'])): ?>
        <div class="alert alert-hapus">🗑️ Mahasiswa berhasil dihapus dari database.</div>
    <?php endif; ?>

    <a href="?halaman=tambah" class="tombol-tambah">➕ Tambah Mahasiswa Baru</a>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>NIM</th>
                <th>Nama</th>
                <th>Jurusan</th>
                <th>IPK</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($mahasiswa)): ?>
                <tr>
                    <td colspan="6" class="kosong">
                        Belum ada data mahasiswa. 
                        <a href="?halaman=tambah">Tambahkan sekarang →</a>
                    </td>
                </tr>
            <?php else: ?>
                <?php $no = 1; foreach ($mahasiswa as $mhs): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <!-- htmlspecialchars() untuk mencegah XSS -->
                        <td><?= htmlspecialchars($mhs['nim']) ?></td>
                        <td><?= htmlspecialchars($mhs['nama']) ?></td>
                        <td><?= htmlspecialchars($mhs['jurusan']) ?></td>
                        <td><?= $mhs['ipk'] ?></td>
                        <td>
                            <!-- Tombol hapus dengan konfirmasi JavaScript -->
                            <a href="?halaman=hapus&id=<?= $mhs['id'] ?>"
                               class="tombol-hapus"
                               onclick="return confirm('Yakin hapus mahasiswa <?= htmlspecialchars($mhs['nama'], ENT_QUOTES) ?>?')">
                                🗑️ Hapus
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <p style="margin-top:15px; font-size:13px; color:#888;">
        Total: <?= count($mahasiswa) ?> mahasiswa | Data dari database MySQL
    </p>
</div>

</body>
</html>
