<?php
// ================================================
// File: edit.php
// Fungsi: menampilkan form edit dan menyimpan perubahan data hewan.
// ================================================

require_once __DIR__ . '/db.php';

// Ambil id dari parameter URL. Jika tidak ada, maka arahkan kembali ke index.
$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    header('Location: index.php');
    exit;
}

// Koneksi ke database.
$db = connectDatabase();

// Ambil data hewan berdasarkan id.
$stmt = $db->prepare("SELECT * FROM hewan WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Jika data tidak ditemukan, kembali ke halaman daftar.
if (!$data) {
    header('Location: index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil input baru dari form.
    $kode = trim($_POST['kode_hewan'] ?? '');
    $nama = trim($_POST['nama'] ?? '');
    $spesies = trim($_POST['spesies'] ?? '');
    $jumlah = (int)($_POST['jumlah'] ?? 0);
    $status = trim($_POST['status'] ?? '');
    $umur = (int)($_POST['umur'] ?? 0);

    // Validasi input.
    if ($kode === '' || $nama === '' || $spesies === '' || $status === '') {
        $error = 'Semua field wajib diisi.';
    } elseif ($jumlah < 0) {
        $error = 'Jumlah tidak boleh negatif.';
    } elseif ($umur < 0) {
        $error = 'Umur tidak boleh negatif.';
    } else {
        // Update data sesuai id.
        $update = $db->prepare("UPDATE hewan SET kode_hewan = ?, nama = ?, spesies = ?, jumlah = ?, status = ?, umur = ? WHERE id = ?");
        $update->bind_param('sssiisi', $kode, $nama, $spesies, $jumlah, $status, $umur, $id);
        $update->execute();
        $update->close();

        // Redirect kembali dengan pesan sukses.
        header('Location: index.php?pesan=' . urlencode("Data hewan '{$nama}' berhasil diperbarui."));
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Hewan</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <main class="container">
        <header class="hero">
            <p class="eyebrow">Pre-UTS · Sederhana</p>
            <h1>Edit Data Hewan</h1>
            <p>Perbarui data hewan yang sudah ada di database.</p>
        </header>

        <section class="card">
            <?php if ($error !== ''): ?>
                <div class="alert error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
            <?php endif; ?>

            <form method="POST">
                <label>
                    Kode Hewan
                    <input type="text" name="kode_hewan" value="<?= htmlspecialchars($_POST['kode_hewan'] ?? $data['kode_hewan'], ENT_QUOTES, 'UTF-8') ?>" required>
                </label>

                <label>
                    Nama
                    <input type="text" name="nama" value="<?= htmlspecialchars($_POST['nama'] ?? $data['nama'], ENT_QUOTES, 'UTF-8') ?>" required>
                </label>

                <label>
                    Spesies
                    <input type="text" name="spesies" value="<?= htmlspecialchars($_POST['spesies'] ?? $data['spesies'], ENT_QUOTES, 'UTF-8') ?>" required>
                </label>

                <label>
                    Jumlah
                    <input type="number" name="jumlah" min="0" value="<?= htmlspecialchars($_POST['jumlah'] ?? (string)$data['jumlah'], ENT_QUOTES, 'UTF-8') ?>" required>
                </label>

                <label>
                    Status
                    <select name="status" required>
                        <?php $selectedStatus = $_POST['status'] ?? $data['status']; ?>
                        <option value="Dilindungi" <?= $selectedStatus === 'Dilindungi' ? 'selected' : '' ?>>Dilindungi</option>
                        <option value="Umum" <?= $selectedStatus === 'Umum' ? 'selected' : '' ?>>Umum</option>
                        <option value="Langka" <?= $selectedStatus === 'Langka' ? 'selected' : '' ?>>Langka</option>
                    </select>
                </label>

                <label>
                    Umur (tahun)
                    <input type="number" name="umur" min="0" value="<?= htmlspecialchars($_POST['umur'] ?? (string)$data['umur'], ENT_QUOTES, 'UTF-8') ?>" required>
                </label>

                <div style="width: 100%; margin-top: 8px;">
                    <button type="submit">Update</button>
                    <a href="index.php" style="margin-left: 12px; color: #475569; text-decoration: none;">Batal</a>
                </div>
            </form>
        </section>
    </main>
</body>
</html>
