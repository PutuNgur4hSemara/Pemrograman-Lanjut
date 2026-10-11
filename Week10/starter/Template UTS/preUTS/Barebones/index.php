<?php
// Mengaktifkan exception MySQLi agar kesalahan database bisa ditangani.
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Menentukan nama database yang digunakan aplikasi.
$namaDatabase = 'uts_prek_2025';

try {
    // Membuka koneksi MySQL Laragon tanpa memilih database terlebih dahulu.
    $db = new mysqli('127.0.0.1', 'root', '');

    // Mengatur karakter agar teks UTF-8 tersimpan dengan benar.
    $db->set_charset('utf8mb4');

    // Membuat database apabila belum tersedia.
    $db->query("CREATE DATABASE IF NOT EXISTS `$namaDatabase` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

    // Memilih database untuk query selanjutnya.
    $db->select_db($namaDatabase);

    // Membuat tabel sesuai kolom tugas apabila belum tersedia.
    $db->query("CREATE TABLE IF NOT EXISTS hewan (
        id INT AUTO_INCREMENT PRIMARY KEY,
        kode_hewan VARCHAR(10) NOT NULL UNIQUE,
        nama VARCHAR(100) NOT NULL,
        spesies VARCHAR(50) NOT NULL,
        jumlah INT NOT NULL DEFAULT 0,
        status VARCHAR(50) NOT NULL,
        umur INT NOT NULL DEFAULT 0
    ) ENGINE=InnoDB");

    // Menambahkan contoh data sekali saja berdasarkan kode_hewan yang unik.
    $db->query("INSERT IGNORE INTO hewan (kode_hewan, nama, spesies, jumlah, status, umur) VALUES
        ('H001', 'Gajah', 'Mamalia', 1, 'Dilindungi', 12),
        ('H002', 'Harimau', 'Mamalia', 2, 'Dilindungi', 8),
        ('H003', 'Ikan', 'Ikan', 5, 'Umum', 2)");
} catch (mysqli_sql_exception $e) {
    // Menghentikan aplikasi dan menampilkan pesan jika koneksi/setup gagal.
    die('Koneksi database gagal: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
}

// Menyiapkan pesan untuk kesalahan validasi atau database.
$error = '';

// Menangani seluruh operasi yang dikirim melalui form POST.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Membaca nama operasi dari form.
    $aksi = $_POST['aksi'] ?? '';

    try {
        // Menghapus data dengan id yang dikirim melalui form.
        if ($aksi === 'hapus') {
            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
            if ($id && $id > 0) {
                $stmt = $db->prepare('DELETE FROM hewan WHERE id = ?');
                $stmt->bind_param('i', $id);
                $stmt->execute();
                $stmt->close();
            }
            header('Location: index.php?pesan=' . urlencode('Data berhasil dihapus.'));
            exit;
        }

        // Mengambil dan membersihkan seluruh nilai input dari form.
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT) ?: 0;
        $kode = trim($_POST['kode_hewan'] ?? '');
        $nama = trim($_POST['nama'] ?? '');
        $spesies = trim($_POST['spesies'] ?? '');
        $jumlahInput = trim($_POST['jumlah'] ?? '');
        $status = trim($_POST['status'] ?? '');
        $umurInput = trim($_POST['umur'] ?? '');
        $jumlah = filter_var($jumlahInput, FILTER_VALIDATE_INT);
        $umur = filter_var($umurInput, FILTER_VALIDATE_INT);

        // Memastikan teks wajib diisi dan angka tidak negatif.
        if ($kode === '' || $nama === '' || $spesies === '' || $status === '') {
            $error = 'Kode, nama, spesies, dan status wajib diisi.';
        } elseif ($jumlah === false || $jumlah < 0 || $umur === false || $umur < 0) {
            $error = 'Jumlah dan umur harus berupa angka nol atau lebih.';
        } elseif ($aksi === 'simpan') {
            // Memperbarui data ketika id tersedia.
            if ($id > 0) {
                $stmt = $db->prepare('UPDATE hewan SET kode_hewan = ?, nama = ?, spesies = ?, jumlah = ?, status = ?, umur = ? WHERE id = ?');
                $stmt->bind_param('sssisii', $kode, $nama, $spesies, $jumlah, $status, $umur, $id);
                $stmt->execute();
                $stmt->close();
                $pesan = 'Data hewan berhasil diperbarui.';
            } else {
                // Menambahkan data baru jika tidak ada id edit.
                $stmt = $db->prepare('INSERT INTO hewan (kode_hewan, nama, spesies, jumlah, status, umur) VALUES (?, ?, ?, ?, ?, ?)');
                $stmt->bind_param('sssisi', $kode, $nama, $spesies, $jumlah, $status, $umur);
                $stmt->execute();
                $stmt->close();
                $pesan = 'Data hewan berhasil ditambahkan.';
            }

            // Mengalihkan halaman agar form tidak terkirim ulang saat refresh.
            header('Location: index.php?pesan=' . urlencode($pesan));
            exit;
        }
    } catch (mysqli_sql_exception $e) {
        // Menangani misalnya kode hewan yang sudah digunakan.
        $error = 'Data gagal diproses. Pastikan kode hewan belum digunakan.';
    }
}

// Menyiapkan data default untuk form tambah.
$dataForm = [
    'id' => 0,
    'kode_hewan' => $_POST['kode_hewan'] ?? '',
    'nama' => $_POST['nama'] ?? '',
    'spesies' => $_POST['spesies'] ?? '',
    'jumlah' => $_POST['jumlah'] ?? '0',
    'status' => $_POST['status'] ?? '',
    'umur' => $_POST['umur'] ?? '0',
];

// Mengambil data yang dipilih apabila halaman sedang dalam mode edit.
$idEdit = filter_input(INPUT_GET, 'edit', FILTER_VALIDATE_INT);
if ($idEdit && $idEdit > 0) {
    $stmt = $db->prepare('SELECT * FROM hewan WHERE id = ?');
    $stmt->bind_param('i', $idEdit);
    $stmt->execute();
    $hasilEdit = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    if ($hasilEdit) {
        $dataForm = $hasilEdit;
    }
}

// Mengambil semua data untuk ditampilkan pada tabel.
$hasil = $db->query('SELECT * FROM hewan ORDER BY nama ASC');
$daftarHewan = $hasil->fetch_all(MYSQLI_ASSOC);

// Mengambil pesan sukses dari URL.
$pesan = $_GET['pesan'] ?? '';

// Mengubah teks sebelum ditampilkan agar aman dari HTML yang disisipkan.
function e(string $nilai): string
{
    return htmlspecialchars($nilai, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Data Hewan - Barebones</title>
</head>
<body>
    <h1>CRUD Data Hewan</h1>
    <p>Versi Barebones: PHP, HTML, dan tabel tanpa CSS.</p>

    <?php if ($pesan !== ''): ?>
        <p><?= e($pesan) ?></p>
    <?php endif; ?>

    <?php if ($error !== ''): ?>
        <p><?= e($error) ?></p>
    <?php endif; ?>

    <h2><?= (int)$dataForm['id'] > 0 ? 'Edit Hewan' : 'Tambah Hewan' ?></h2>
    <form method="post" action="index.php">
        <input type="hidden" name="aksi" value="simpan">
        <input type="hidden" name="id" value="<?= (int)$dataForm['id'] ?>">
        <table border="1">
            <tbody>
                <tr>
                    <td><label for="kode_hewan">Kode Hewan</label></td>
                    <td><input id="kode_hewan" type="text" name="kode_hewan" maxlength="10" value="<?= e((string)$dataForm['kode_hewan']) ?>" required></td>
                </tr>
                <tr>
                    <td><label for="nama">Nama</label></td>
                    <td><input id="nama" type="text" name="nama" maxlength="100" value="<?= e((string)$dataForm['nama']) ?>" required></td>
                </tr>
                <tr>
                    <td><label for="spesies">Spesies</label></td>
                    <td><input id="spesies" type="text" name="spesies" maxlength="50" value="<?= e((string)$dataForm['spesies']) ?>" required></td>
                </tr>
                <tr>
                    <td><label for="jumlah">Jumlah</label></td>
                    <td><input id="jumlah" type="number" name="jumlah" min="0" value="<?= e((string)$dataForm['jumlah']) ?>" required></td>
                </tr>
                <tr>
                    <td><label for="status">Status</label></td>
                    <td><input id="status" type="text" name="status" maxlength="50" value="<?= e((string)$dataForm['status']) ?>" required></td>
                </tr>
                <tr>
                    <td><label for="umur">Umur</label></td>
                    <td><input id="umur" type="number" name="umur" min="0" value="<?= e((string)$dataForm['umur']) ?>" required></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <button type="submit">Simpan</button>
                        <?php if ((int)$dataForm['id'] > 0): ?>
                            <a href="index.php">Batal</a>
                        <?php endif; ?>
                    </td>
                </tr>
            </tbody>
        </table>
    </form>

    <h2>Daftar Hewan</h2>
    <table border="1">
        <thead>
            <tr>
                <th>No.</th>
                <th>Kode Hewan</th>
                <th>Nama</th>
                <th>Spesies</th>
                <th>Jumlah</th>
                <th>Status</th>
                <th>Umur</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($daftarHewan): ?>
                <?php foreach ($daftarHewan as $nomor => $hewan): ?>
                    <tr>
                        <td><?= $nomor + 1 ?></td>
                        <td><?= e($hewan['kode_hewan']) ?></td>
                        <td><?= e($hewan['nama']) ?></td>
                        <td><?= e($hewan['spesies']) ?></td>
                        <td><?= (int)$hewan['jumlah'] ?></td>
                        <td><?= e($hewan['status']) ?></td>
                        <td><?= (int)$hewan['umur'] ?></td>
                        <td>
                            <a href="index.php?edit=<?= (int)$hewan['id'] ?>">Edit</a>
                            <form method="post" action="index.php">
                                <input type="hidden" name="aksi" value="hapus">
                                <input type="hidden" name="id" value="<?= (int)$hewan['id'] ?>">
                                <button type="submit" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="8">Belum ada data hewan.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
