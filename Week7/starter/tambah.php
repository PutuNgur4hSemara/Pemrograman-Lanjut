<?php
/**
 * CREATE View: Tambah Mahasiswa
 * Modul Praktikum Minggu 7
 */

require_once __DIR__ . '/config/Database.php';
require_once __DIR__ . '/src/MahasiswaRepository.php';

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nim = trim($_POST['nim'] ?? '');
    $nama = trim($_POST['nama'] ?? '');
    $prodi = trim($_POST['program_studi'] ?? '');
    $ipk = (float)($_POST['ipk'] ?? 0);

    if (empty($nim) || empty($nama) || empty($prodi)) {
        $error = "Seluruh field formulir wajib diisi.";
    } elseif ($ipk < 0 || $ipk > 4) {
        $error = "Nilai IPK harus berada pada rentang 0.00 s.d. 4.00.";
    } else {
        try {
            $db = Database::getConnection();
            $repo = new MahasiswaRepository($db);
            $repo->tambah($nim, $nama, $prodi, $ipk);

            header("Location: index.php?pesan=" . urlencode("Mahasiswa '{$nama}' berhasil ditambahkan."));
            exit;
        } catch (mysqli_sql_exception $e) {
            $error = "Gagal menyimpan data: " . (str_contains($e->getMessage(), 'Duplicate') ? "NIM sudah terdaftar." : $e->getMessage());
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Mahasiswa Baru</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f8fafc; padding: 40px 20px; }
        .form-card { max-width: 500px; margin: 0 auto; background: white; border-radius: 12px; padding: 24px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 16px; }
        label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px; color: #475569; }
        input, select { width: 100%; box-sizing: border-box; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px; }
        .btn-submit { background: #2563eb; color: white; border: none; padding: 10px 18px; border-radius: 6px; cursor: pointer; font-weight: 600; }
        .btn-cancel { color: #64748b; text-decoration: none; margin-left: 12px; font-size: 14px; }
        .alert-error { background: #fee2e2; color: #991b1b; padding: 10px; border-radius: 6px; margin-bottom: 16px; font-size: 14px; }
    </style>
</head>
<body>

<div class="form-card">
    <h2 style="margin-top: 0; color: #1e293b;">Tambah Mahasiswa</h2>

    <?php if ($error): ?>
        <div class="alert-error">❌ <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="form-group">
            <label>NIM (Nomor Induk Mahasiswa):</label>
            <input type="text" name="nim" required placeholder="Contoh: 10231099" value="<?= htmlspecialchars($_POST['nim'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label>Nama Lengkap:</label>
            <input type="text" name="nama" required placeholder="Nama lengkap mahasiswa" value="<?= htmlspecialchars($_POST['nama'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label>Program Studi:</label>
            <select name="program_studi" required>
                <option value="">-- Pilih Program Studi --</option>
                <option value="Sistem Informasi">Sistem Informasi</option>
                <option value="Informatika">Informatika</option>
                <option value="Sains Data">Sains Data</option>
            </select>
        </div>

        <div class="form-group">
            <label>IPK Terkini (0.00 - 4.00):</label>
            <input type="number" step="0.01" min="0" max="4" name="ipk" required value="<?= htmlspecialchars($_POST['ipk'] ?? '3.50') ?>">
        </div>

        <button type="submit" class="btn-submit">Simpan Mahasiswa</button>
        <a href="index.php" class="btn-cancel">Batal</a>
    </form>
</div>

</body>
</html>
