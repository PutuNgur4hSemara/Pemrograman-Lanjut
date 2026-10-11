<?php
/**
 * TEMPLATE UTUH WEEK7 — salin seluruh file ini untuk file REAL tugas.
 * Fokus utama: CRUD repository pattern, prepared statement, dan PRG (Post-Redirect-Get).
 * Ganti nama tabel, field, dan input sesuai studi kasus UTS.
 */
class RepositoryKasus
{
    public function __construct(private mysqli $db) {}

    public function semua(): array
    {
        $result = $this->db->query('SELECT id, kode, nama, kategori, nilai FROM data_kasus ORDER BY id ASC');
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function cariBerdasarkanId(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT id, kode, nama, kategori, nilai FROM data_kasus WHERE id = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $data = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $data ?: null;
    }

    public function tambah(string $kode, string $nama, string $kategori, float $nilai): bool
    {
        $stmt = $this->db->prepare('INSERT INTO data_kasus (kode, nama, kategori, nilai) VALUES (?, ?, ?, ?)');
        $stmt->bind_param('sssd', $kode, $nama, $kategori, $nilai);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function ubah(int $id, string $kode, string $nama, string $kategori, float $nilai): bool
    {
        $stmt = $this->db->prepare('UPDATE data_kasus SET kode = ?, nama = ?, kategori = ?, nilai = ? WHERE id = ?');
        $stmt->bind_param('sssdi', $kode, $nama, $kategori, $nilai, $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function hapus(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM data_kasus WHERE id = ?');
        $stmt->bind_param('i', $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }
}

$pesan = $_GET['pesan'] ?? '';
$daftarData = [];

try {
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $db = new mysqli('127.0.0.1', 'root', '', 'pemrograman_lanjut');
    $db->set_charset('utf8mb4');
    $repo = new RepositoryKasus($db);
    $daftarData = $repo->semua();
} catch (mysqli_sql_exception $e) {
    error_log('Database setup belum dibuat: ' . $e->getMessage());
    $pesan = 'Database belum siap. Import file SQL di folder database/ terlebih dahulu.';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REAL UTS — Week7</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<main class="container">
    <header class="hero">
        <p class="eyebrow">TEMPLATE WEEK7</p>
        <h1>Judul Studi Kasus</h1>
        <p>Repository pattern, CRUD, prepared statement, dan PRG.</p>
    </header>

    <?php if ($pesan !== ''): ?>
        <div class="alert success"><?= htmlspecialchars($pesan, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <section class="card">
        <h2>Daftar Data</h2>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Kategori</th>
                    <th>Nilai</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($daftarData)): ?>
                    <?php foreach ($daftarData as $index => $row): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($row['kode'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($row['nama'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($row['kategori'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= number_format((float)$row['nilai'], 2, ',', '.') ?></td>
                            <td>
                                <a href="edit.php?id=<?= (int)$row['id'] ?>" class="badge">Edit</a>
                                <a href="hapus.php?id=<?= (int)$row['id'] ?>" class="badge" onclick="return confirm('Yakin ingin hapus?')">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">Belum ada data.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </section>

    <section class="card">
        <h2>Form Tambah</h2>
        <form method="post" action="tambah.php">
            <label>Kode<input type="text" name="kode" required></label>
            <label>Nama<input type="text" name="nama" required></label>
            <label>Kategori<input type="text" name="kategori" required></label>
            <label>Nilai<input type="number" step="0.01" name="nilai" required></label>
            <button type="submit">Simpan</button>
        </form>
    </section>

    <footer>Ganti nama tabel, kolom, dan form sesuai studi kasus yang diberikan. Hindari menulis query langsung di file tampilan.</footer>
</main>
</body>
</html>
