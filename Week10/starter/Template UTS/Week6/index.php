<?php
// FILE REAL WEEK6: demo filter HTML dengan dataset contoh agar bisa dijalankan tanpa MySQL.
// Implementasi query sesungguhnya dengan prepared statement ada di template.php.
$daftarBuku = [
    ['judul' => 'PHP untuk Pemula', 'penulis' => 'A. Wijaya', 'kategori' => 'Pemrograman', 'harga' => 95000],
    ['judul' => 'Basis Data Modern', 'penulis' => 'S. Lestari', 'kategori' => 'Database', 'harga' => 120000],
    ['judul' => 'Pemrograman Berorientasi Objek', 'penulis' => 'D. Pratama', 'kategori' => 'Pemrograman', 'harga' => 145000],
];
$kata = trim($_GET['q'] ?? '');
$kategori = trim($_GET['kategori'] ?? '');
$hargaMaks = (float) ($_GET['maks'] ?? 200000);
$hasil = array_filter($daftarBuku, static function (array $buku) use ($kata, $kategori, $hargaMaks): bool {
    $cocokKata = $kata === '' || stripos($buku['judul'] . ' ' . $buku['penulis'], $kata) !== false;
    $cocokKategori = $kategori === '' || $buku['kategori'] === $kategori;
    return $cocokKata && $cocokKategori && $buku['harga'] <= $hargaMaks;
});
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Week6 — Prepared Statement</title>
    <!-- Gunakan alamat stylesheet ini agar CSS bersama bisa dipakai berulang. -->
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body><main class="container">
    <header class="hero"><p class="eyebrow">Template UTS · Week6</p><h1>Prepared Statement & Keamanan Data</h1><p>Demo filter dataset; template MySQLi sebenarnya tersedia pada file template.php.</p></header>
    <section class="card"><h2>Filter katalog (data demo)</h2>
        <form method="get">
            <label>Kata kunci<input name="q" value="<?= htmlspecialchars($kata, ENT_QUOTES, 'UTF-8') ?>" placeholder="Judul atau penulis"></label>
            <label>Kategori<select name="kategori"><option value="">Semua kategori</option><?php foreach (['Pemrograman', 'Database'] as $opsi): ?><option value="<?= htmlspecialchars($opsi, ENT_QUOTES, 'UTF-8') ?>" <?= $kategori === $opsi ? 'selected' : '' ?>><?= htmlspecialchars($opsi, ENT_QUOTES, 'UTF-8') ?></option><?php endforeach; ?></select></label>
            <label>Harga maksimum<input type="number" min="0" name="maks" value="<?= htmlspecialchars((string)$hargaMaks, ENT_QUOTES, 'UTF-8') ?>"></label>
            <button type="submit">Terapkan filter</button>
        </form>
        <p class="muted">Ditemukan <?= count($hasil) ?> buku.</p>
        <table><thead><tr><th>Judul</th><th>Penulis</th><th>Kategori</th><th>Harga</th></tr></thead><tbody>
        <?php if ($hasil): foreach ($hasil as $buku): ?>
            <tr><td><?= htmlspecialchars($buku['judul'], ENT_QUOTES, 'UTF-8') ?></td><td><?= htmlspecialchars($buku['penulis'], ENT_QUOTES, 'UTF-8') ?></td><td><?= htmlspecialchars($buku['kategori'], ENT_QUOTES, 'UTF-8') ?></td><td>Rp <?= number_format($buku['harga'], 0, ',', '.') ?></td></tr>
        <?php endforeach; else: ?><tr><td colspan="4">Tidak ada buku yang cocok.</td></tr><?php endif; ?>
        </tbody></table>
    </section>
    <section class="card"><h2>Yang perlu disalin ke proyek database</h2><p>Gunakan <code>prepare()</code>, <code>bind_param()</code>, dan <code>execute()</code>. Ambil data dengan <code>get_result()</code>; escape seluruh teks database memakai <code>htmlspecialchars()</code>. Template juga berisi pola transaksi <code>commit()</code>/<code>rollback()</code>.</p></section>
    <footer>Data pada halaman REAL ini hanya contoh lokal; tidak ada koneksi/database yang diperlukan.</footer>
</main></body></html>
