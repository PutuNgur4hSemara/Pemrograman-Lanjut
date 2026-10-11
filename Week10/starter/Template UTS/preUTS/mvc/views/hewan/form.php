<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($hewan) && $hewan ? 'Edit Hewan' : 'Tambah Hewan' ?></title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <main class="container">
        <header class="hero">
            <p class="eyebrow">Pre-UTS · MVC</p>
            <h1><?= isset($hewan) && $hewan ? 'Edit Data Hewan' : 'Tambah Data Hewan' ?></h1>
            <p>Form untuk menambah atau memperbarui data hewan.</p>
        </header>

        <section class="card">
            <?php if (!empty($errors)): ?>
                <div class="alert error">
                    <ul style="margin: 0; padding-left: 20px;">
                        <?php foreach ($errors as $message): ?>
                            <li><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php
                $action = 'index.php?halaman=tambah';
                $kodeValue = $_POST['kode_hewan'] ?? ($hewan['kode_hewan'] ?? '');
                $namaValue = $_POST['nama'] ?? ($hewan['nama'] ?? '');
                $spesiesValue = $_POST['spesies'] ?? ($hewan['spesies'] ?? '');
                $jumlahValue = $_POST['jumlah'] ?? ($hewan['jumlah'] ?? '');
                $statusValue = $_POST['status'] ?? ($hewan['status'] ?? '');
                $umurValue = $_POST['umur'] ?? ($hewan['umur'] ?? '');

                if (isset($hewan) && $hewan) {
                    $action = 'index.php?halaman=edit&id=' . (int)$hewan['id'];
                }
            ?>

            <form method="POST" action="<?= htmlspecialchars($action, ENT_QUOTES, 'UTF-8') ?>">
                <label>
                    Kode Hewan
                    <input type="text" name="kode_hewan" value="<?= htmlspecialchars($kodeValue, ENT_QUOTES, 'UTF-8') ?>" required>
                </label>

                <label>
                    Nama
                    <input type="text" name="nama" value="<?= htmlspecialchars($namaValue, ENT_QUOTES, 'UTF-8') ?>" required>
                </label>

                <label>
                    Spesies
                    <input type="text" name="spesies" value="<?= htmlspecialchars($spesiesValue, ENT_QUOTES, 'UTF-8') ?>" required>
                </label>

                <label>
                    Jumlah
                    <input type="number" name="jumlah" min="0" value="<?= htmlspecialchars((string)$jumlahValue, ENT_QUOTES, 'UTF-8') ?>" required>
                </label>

                <label>
                    Status
                    <select name="status" required>
                        <option value="">-- Pilih Status --</option>
                        <option value="Dilindungi" <?= $statusValue === 'Dilindungi' ? 'selected' : '' ?>>Dilindungi</option>
                        <option value="Umum" <?= $statusValue === 'Umum' ? 'selected' : '' ?>>Umum</option>
                        <option value="Langka" <?= $statusValue === 'Langka' ? 'selected' : '' ?>>Langka</option>
                    </select>
                </label>

                <label>
                    Umur (tahun)
                    <input type="number" name="umur" min="0" value="<?= htmlspecialchars((string)$umurValue, ENT_QUOTES, 'UTF-8') ?>" required>
                </label>

                <div style="width: 100%; margin-top: 8px;">
                    <button type="submit"><?= isset($hewan) && $hewan ? 'Update' : 'Simpan' ?></button>
                    <a href="index.php" style="margin-left: 12px; color: #475569; text-decoration: none;">Kembali</a>
                </div>
            </form>
        </section>
    </main>
</body>
</html>
