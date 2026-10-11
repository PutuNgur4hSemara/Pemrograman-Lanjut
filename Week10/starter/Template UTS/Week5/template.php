<?php
/**
 * TEMPLATE UTUH WEEK5 — salin sebagai satu halaman REAL, lalu sesuaikan aturan kasus.
 * Berisi custom exception dan penanganan try/catch/finally. Koneksi database opsional.
 */
class KasusException extends RuntimeException {}

class ProsesKasus
{
    public function proses(int $jumlahTersedia, int $jumlahDiminta): string
    {
        if ($jumlahDiminta <= 0) {
            throw new InvalidArgumentException('Jumlah harus lebih besar dari nol.');
        }
        if ($jumlahDiminta > $jumlahTersedia) {
            throw new KasusException('Jumlah yang diminta tidak tersedia.');
        }
        return "Proses berhasil untuk {$jumlahDiminta} item.";
    }
}

$pesan = '';
$jenisPesan = 'success';
try {
    // GANTI angka/data dengan input dan aturan bisnis studi kasus UTS.
    $pesan = (new ProsesKasus())->proses(10, 2);
} catch (KasusException $e) {
    $pesan = $e->getMessage();
    $jenisPesan = 'error';
} catch (InvalidArgumentException $e) {
    $pesan = $e->getMessage();
    $jenisPesan = 'error';
} catch (Throwable $e) {
    // Detail error teknis hanya dicatat di server; jangan ditampilkan ke pengguna.
    error_log($e->getMessage());
    $pesan = 'Terjadi kesalahan. Silakan coba kembali.';
    $jenisPesan = 'error';
} finally {
    // Tambahkan cleanup hanya jika proses membuka file/koneksi/resource.
}

/*
 * OPSIONAL — pola koneksi MySQLi Object dari materi Week5.
 * Hapus komentar setelah mengganti kredensial dan mengintegrasikan dengan try/catch di atas.
 * mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
 * try {
 *     $db = new mysqli('127.0.0.1', 'root', '', 'NAMA_DATABASE'); // GANTI kredensial
 *     $db->set_charset('utf8mb4');
 * } catch (mysqli_sql_exception $e) {
 *     error_log('Koneksi gagal: ' . $e->getMessage());
 *     // Tampilkan pesan aman, bukan detail teknis database.
 * }
 */
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REAL UTS — Week5</title>
    <!-- Jalur CSS untuk file REAL di folder Week1–Week6; ubah jika memindahkan file. -->
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body><main class="container">
    <header class="hero"><p class="eyebrow">TEMPLATE WEEK5</p><h1>Judul Studi Kasus</h1><p>Exception handling dan koneksi MySQLi Object.</p></header>
    <section class="card"><h2>Status Proses</h2>
        <div class="alert <?= $jenisPesan ?>"><?= htmlspecialchars($pesan, ENT_QUOTES, 'UTF-8') ?></div>
        <!-- Letakkan hasil/detail kasus tambahan di sini; escape semua teks dinamis. -->
    </section>
    <footer>Gunakan custom exception untuk kesalahan domain dan log server untuk error teknis.</footer>
</main></body></html>
