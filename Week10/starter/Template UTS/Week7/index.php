<?php
// FILE REAL WEEK7: contoh operasi CRUD Mahasiswa berdasarkan repository pattern.
// Skema database bisa di-import dari database/setup.sql.
class MahasiswaRepository
{
    public function __construct(private mysqli $db) {}

    public function semua(): array
    {
        $result = $this->db->query('SELECT id, nim, nama, program_studi, ipk FROM mahasiswa ORDER BY nim ASC');
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function cariBerdasarkanId(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT id, nim, nama, program_studi, ipk FROM mahasiswa WHERE id = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $data = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $data ?: null;
    }

    public function tambah(string $nim, string $nama, string $prodi, float $ipk): bool
    {
        $stmt = $this->db->prepare('INSERT INTO mahasiswa (nim, nama, program_studi, ipk) VALUES (?, ?, ?, ?)');
        $stmt->bind_param('sssd', $nim, $nama, $prodi, $ipk);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function ubah(int $id, string $nim, string $nama, string $prodi, float $ipk): bool
    {
        $stmt = $this->db->prepare('UPDATE mahasiswa SET nim = ?, nama = ?, program_studi = ?, ipk = ? WHERE id = ?');
        $stmt->bind_param('sssdi', $nim, $nama, $prodi, $ipk, $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function hapus(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM mahasiswa WHERE id = ?');
        $stmt->bind_param('i', $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }
}

$pesan = $_GET['pesan'] ?? '';
$daftarMahasiswa = [];

try {
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $db = new mysqli('127.0.0.1', 'root', '', 'pemrograman_lanjut');
    $db->set_charset('utf8mb4');
    $repo = new MahasiswaRepository($db);
    $daftarMahasiswa = $repo->semua();
} catch (mysqli_sql_exception $e) {
    error_log('Database error: ' . $e->getMessage());
    $pesan = 'Database belum siap. Import file Week7/database/setup.sql terlebih dahulu.';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Week7 — CRUD Mahasiswa</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<main class="container">
    <header class="hero">
        <p class="eyebrow">Template UTS · Week7</p>
        <h1>Repository CRUD Mahasiswa</h1>
        <p>Arsitektur repository, prepared statement, redirect, dan pesan pengguna.</p>
    </header>

    <?php if ($pesan !== ''): ?>
        <div class="alert success"><?= htmlspecialchars($pesan, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <section class="card">
        <h2>Daftar Mahasiswa</h2>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>NIM</th>
                    <th>Nama</th>
                    <th>Program Studi</th>
                    <th>IPK</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($daftarMahasiswa)): ?>
                    <?php foreach ($daftarMahasiswa as $index => $m): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($m['nim'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($m['nama'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($m['program_studi'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= number_format((float)$m['ipk'], 2, ',', '.') ?></td>
                            <td>
                                <a href="edit.php?id=<?= (int)$m['id'] ?>" class="badge">Edit</a>
                                <a href="hapus.php?id=<?= (int)$m['id'] ?>" class="badge" onclick="return confirm('Yakin ingin hapus?')">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">Belum ada data mahasiswa.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </section>

    <section class="card">
        <h2>Form tambah data</h2>
        <form method="post" action="tambah.php">
            <label>NIM<input type="text" name="nim" required></label>
            <label>Nama<input type="text" name="nama" required></label>
            <label>Program Studi<input type="text" name="program_studi" required></label>
            <label>IPK<input type="number" step="0.01" name="ipk" min="0" max="4" required></label>
            <button type="submit">Tambah</button>
        </form>
    </section>

    <footer>Template dapat disalin menjadi file REAL dan disesuaikan dengan studi kasus UTS Anda.</footer>
</main>
</body>
</html>
