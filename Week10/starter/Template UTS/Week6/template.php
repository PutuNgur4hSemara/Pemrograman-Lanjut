<?php
/**
 * TEMPLATE UTUH WEEK6 — salin sebagai satu halaman REAL untuk kasus database.
 * Contoh berjalan memakai database/setup.sql di folder Week6/database.
 * GANTI konfigurasi, nama tabel/kolom, input, serta binding untuk skema UTS yang berbeda.
 * Membutuhkan MySQLi dan database yang sudah dibuat. Query memakai prepared statement.
 */
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$daftarData = [];
$pesanError = null;
$kataKunci = trim($_GET['q'] ?? '');
$kategori = trim($_GET['kategori'] ?? '');
$hargaMaksimum = max(0, (float) ($_GET['maks'] ?? 1000000));

try {
    // GANTI database/user/password sesuai Laragon dan basis data kasus UTS.
    $db = new mysqli('127.0.0.1', 'root', '', 'pemrograman_lanjut');
    $db->set_charset('utf8mb4');

        // Query ini sesuai tabel katalog_buku pada database/setup.sql.
        // Untuk studi kasus lain, ganti identifier SQL; placeholder hanya untuk nilai.
        $sql = "SELECT id, judul, penulis, kategori, harga FROM katalog_buku
                        WHERE (judul LIKE ? OR penulis LIKE ?)
                            AND (? = '' OR kategori = ?)
                            AND harga <= ? ORDER BY judul";
    $stmt = $db->prepare($sql);
    $kataKunciSql = '%' . $kataKunci . '%';
        // s=string, i=integer, d=decimal, b=binary/blob; urutan harus cocok dengan ? di SQL.
        $stmt->bind_param('ssssd', $kataKunciSql, $kataKunciSql, $kategori, $kategori, $hargaMaksimum);
    $stmt->execute();
    $daftarData = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
} catch (mysqli_sql_exception $e) {
    // Error teknis tetap di server log; halaman menampilkan pemberitahuan umum.
    error_log('Operasi database gagal: ' . $e->getMessage());
    $pesanError = 'Data belum dapat dimuat. Periksa konfigurasi database.';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REAL UTS — Week6</title>
    <!-- Jalur CSS untuk file REAL di folder Week1–Week6; ubah jika memindahkan file. -->
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body><main class="container">
    <header class="hero"><p class="eyebrow">TEMPLATE WEEK6</p><h1>Judul Studi Kasus</h1><p>Prepared statement, binding parameter, dan output yang aman.</p></header>
    <section class="card"><h2>Pencarian Katalog Buku</h2>
        <form method="get">
            <label>Judul atau penulis<input name="q" value="<?= htmlspecialchars($kataKunci, ENT_QUOTES, 'UTF-8') ?>" placeholder="Cari buku"></label>
            <label>Kategori<select name="kategori">
                <option value="">Semua kategori</option>
                <?php foreach (['Novel', 'Teknologi', 'Pengembangan Diri', 'Pendidikan'] as $opsi): ?>
                    <option value="<?= htmlspecialchars($opsi, ENT_QUOTES, 'UTF-8') ?>" <?= $kategori === $opsi ? 'selected' : '' ?>><?= htmlspecialchars($opsi, ENT_QUOTES, 'UTF-8') ?></option>
                <?php endforeach; ?>
            </select></label>
            <label>Harga maksimum<input type="number" name="maks" min="0" value="<?= htmlspecialchars((string)$hargaMaksimum, ENT_QUOTES, 'UTF-8') ?>"></label>
            <button type="submit">Cari</button>
        </form>
        <?php if ($pesanError !== null): ?>
            <div class="alert error"><?= htmlspecialchars($pesanError, ENT_QUOTES, 'UTF-8') ?></div>
        <?php else: ?>
            <table><thead><tr><th>Judul</th><th>Penulis</th><th>Kategori</th><th>Harga</th></tr></thead><tbody>
                <?php if ($daftarData): foreach ($daftarData as $baris): ?>
                    <tr>
                        <!-- Escape setiap kolom yang berasal dari database untuk mencegah XSS. -->
                        <td><?= htmlspecialchars((string)$baris['judul'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars((string)$baris['penulis'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars((string)$baris['kategori'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td>Rp <?= number_format((float)$baris['harga'], 0, ',', '.') ?></td>
                    </tr>
                <?php endforeach; else: ?>
                    <tr><td colspan="4">Tidak ada buku yang cocok.</td></tr>
                <?php endif; ?>
            </tbody></table>
        <?php endif; ?>
    </section>
    <section class="card"><h2>Transaksi (bila dibutuhkan kasus)</h2>
        <p class="hint">Untuk beberapa query yang harus berhasil bersama: panggil <code>begin_transaction()</code>, jalankan prepared statements, lalu <code>commit()</code>. Pada <code>catch</code>, panggil <code>rollback()</code> sebelum menampilkan pesan aman.</p>
    </section>
    <footer>Import database/setup.sql untuk mencoba contoh. Sesuaikan SELECT, binding, dan tabel hasil dengan studi kasus UTS.</footer>
</main></body></html>
