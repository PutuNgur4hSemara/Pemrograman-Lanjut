<?php
// FILE REAL WEEK5: contoh exception domain dan tampilan pesan aman.
class TiketHabisException extends RuntimeException {}
class Pemesanan
{
    public function pesan(int $stok, int $jumlah): string
    {
        if ($jumlah < 1) throw new InvalidArgumentException('Jumlah tiket minimal satu.');
        if ($jumlah > 4) throw new InvalidArgumentException('Maksimal pembelian adalah empat tiket.');
        if ($jumlah > $stok) throw new TiketHabisException('Stok tiket tidak mencukupi.');
        return "Pemesanan {$jumlah} tiket berhasil.";
    }
}
$pesan = '';
$jenisPesan = 'success';
try { $pesan = (new Pemesanan())->pesan(3, 2); }
catch (TiketHabisException | InvalidArgumentException $e) { $pesan = $e->getMessage(); $jenisPesan = 'error'; }
catch (Throwable $e) { error_log($e->getMessage()); $pesan = 'Terjadi kesalahan. Silakan coba kembali.'; $jenisPesan = 'error'; }
finally { /* Letakkan cleanup resource di sini bila ada resource yang perlu ditutup. */ }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Week5 — Exception Handling</title>
    <!-- Stylesheet yang sama dipakai oleh halaman Week1–Week6. -->
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body><main class="container">
    <header class="hero"><p class="eyebrow">Template UTS · Week5</p><h1>Exception Handling & MySQLi</h1><p>Try/catch/finally, custom exception, dan pola koneksi database object.</p></header>
    <section class="card"><h2>Simulasi pemesanan</h2>
        <div class="alert <?= $jenisPesan ?>"><?= htmlspecialchars($pesan, ENT_QUOTES, 'UTF-8') ?></div>
        <p>Pesan khusus domain dapat ditangani terpisah dari error teknis. Detail exception teknis dicatat dengan <code>error_log()</code>, bukan ditampilkan kepada pengguna.</p>
        <p class="hint">Contoh koneksi MySQLi Object dan konfigurasi strict mode tersedia di <code>template.php</code>. Ganti kredensial sebelum digunakan.</p>
    </section>
    <footer>Gunakan koneksi di server lokal; jangan menyimpan password database ke repositori publik.</footer>
</main></body></html>
