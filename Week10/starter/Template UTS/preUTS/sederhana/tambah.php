<?php
// ================================================
// File: tambah.php
// Fungsi: menangani form tambah data hewan dan simpan ke database.
// ================================================

// Mengambil helper koneksi database.
require_once __DIR__ . '/db.php';

// Variabel error dipakai untuk menampilkan pesan validasi.
$error = '';

// Jika form dikirim lewat method POST, kita proses input.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil dan bersihkan input dari form.
    $kode = trim($_POST['kode_hewan'] ?? '');
    $nama = trim($_POST['nama'] ?? '');
    $spesies = trim($_POST['spesies'] ?? '');
    $jumlah = (int)($_POST['jumlah'] ?? 0);
    $status = trim($_POST['status'] ?? '');
    $umur = (int)($_POST['umur'] ?? 0);

    // Validasi sederhana agar input tidak kosong.
    if ($kode === '' || $nama === '' || $spesies === '' || $status === '') {
        $error = 'Semua field wajib diisi.';
    } elseif ($jumlah < 0) {
        $error = 'Jumlah tidak boleh negatif.';
    } elseif ($umur < 0) {
        $error = 'Umur tidak boleh negatif.';
    } else {
        // Buat koneksi ke database.
        $db = connectDatabase();

        // Gunakan prepared statement agar aman dari SQL injection.
        $stmt = $db->prepare("INSERT INTO hewan (kode_hewan, nama, spesies, jumlah, status, umur) VALUES (?, ?, ?, ?, ?, ?)");

        // bind_param: s=string, i=integer.
        $stmt->bind_param('sssiis', $kode, $nama, $spesies, $jumlah, $status, $umur);

        // Proses eksekusi query.
        $stmt->execute();

        // redirect agar form tidak submit ulang saat refresh.
        header('Location: index.php?pesan=' . urlencode("Data hewan '{$nama}' berhasil ditambahkan."));
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Hewan</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <main class="container">
        <header class="hero">
            <p class="eyebrow">Pre-UTS · Sederhana</p>
            <h1>Tambah Hewan Baru</h1>
            <p>Form ini digunakan untuk menambahkan data hewan baru ke database.</p>
        </header>

        <section class="card">
            <?php if ($error !== ''): ?>
                <div class="alert error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
            <?php endif; ?>

            <form method="POST">
                <label>
                    Kode Hewan
                    <input type="text" name="kode_hewan" placeholder="Contoh: H004" value="<?= htmlspecialchars($_POST['kode_hewan'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
                </label>

                <label>
                    Nama
                    <input type="text" name="nama" placeholder="Contoh: Jerapah" value="<?= htmlspecialchars($_POST['nama'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
                </label>

                <label>
                    Spesies
                    <input type="text" name="spesies" placeholder="Contoh: Mamalia" value="<?= htmlspecialchars($_POST['spesies'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
                </label>

                <label>
                    Jumlah
                    <input type="number" name="jumlah" min="0" value="<?= htmlspecialchars($_POST['jumlah'] ?? '0', ENT_QUOTES, 'UTF-8') ?>" required>
                </label>

                <label>
                    Status
                    <select name="status" required>
                        <option value="">-- Pilih status --</option>
                        <?php $selectedStatus = $_POST['status'] ?? ''; ?>
                        <option value="Dilindungi" <?= $selectedStatus === 'Dilindungi' ? 'selected' : '' ?>>Dilindungi</option>
                        <option value="Umum" <?= $selectedStatus === 'Umum' ? 'selected' : '' ?>>Umum</option>
                        <option value="Langka" <?= $selectedStatus === 'Langka' ? 'selected' : '' ?>>Langka</option>
                    </select>
                </label>

                <label>
                    Umur (tahun)
                    <input type="number" name="umur" min="0" value="<?= htmlspecialchars($_POST['umur'] ?? '0', ENT_QUOTES, 'UTF-8') ?>" required>
                </label>

                <div style="width: 100%; margin-top: 8px;">
                    <button type="submit">Simpan</button>
                    <a href="index.php" style="margin-left: 12px; color: #475569; text-decoration: none;">Batal</a>
                </div>
            </form>
        </section>
    </main>
</body>
</html>
