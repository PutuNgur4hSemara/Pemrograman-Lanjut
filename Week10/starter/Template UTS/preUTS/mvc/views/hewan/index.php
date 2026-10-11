<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Hewan MVC</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <main class="container">
        <header class="hero">
            <p class="eyebrow">Pre-UTS · MVC</p>
            <h1>CRUD Hewan dengan MVC</h1>
            <p>Versi MVC dengan pemisahan model, controller, dan view.</p>
        </header>

        <section class="card">
            <h2>Daftar Hewan</h2>
            <?php if (!empty($pesan)): ?>
                <div class="alert success"><?= htmlspecialchars($pesan, ENT_QUOTES, 'UTF-8') ?></div>
            <?php endif; ?>

            <div style="margin-bottom: 16px;">
                <a href="index.php?halaman=tambah" class="badge" style="text-decoration: none;">+ Tambah Hewan</a>
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
                                    <a href="index.php?halaman=edit&id=<?= (int)$h['id'] ?>" class="badge" style="text-decoration: none;">Edit</a>
                                    <a href="index.php?halaman=hapus&id=<?= (int)$h['id'] ?>" class="badge" style="text-decoration: none; background: #fee2e2; color: #991b1b;" onclick="return confirm('Yakin ingin hapus data hewan ini?')">Hapus</a>
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
