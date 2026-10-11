<?php
/**
 * TEMPLATE UTUH WEEK3 — salin seluruh file sebagai satu halaman REAL tugas.
 * Ubah nama class dan aturan validasi agar cocok dengan entitas pada studi kasus.
 */
class EntitasTervalidasi
{
    // Property private tidak boleh diubah langsung dari luar class.
    public function __construct(private string $kode, private string $nama, private float $nilai) {}

    // Getter: akses baca yang terkontrol.
    public function getKode(): string { return $this->kode; }
    public function getNama(): string { return $this->nama; }
    public function getNilai(): float { return $this->nilai; }

    // Setter: validasi dilakukan sebelum state object diubah.
    public function setNama(string $nama): void
    {
        $nama = trim($nama);
        if (strlen($nama) < 3) {
            throw new InvalidArgumentException('Nama minimal harus 3 karakter.');
        }
        $this->nama = $nama;
    }

    public function setNilai(float $nilai): void
    {
        // GANTI rentang validasi sesuai aturan pada soal.
        if ($nilai < 0 || $nilai > 100) {
            throw new InvalidArgumentException('Nilai harus berada di antara 0 dan 100.');
        }
        $this->nilai = $nilai;
    }
}

// GANTI data awal dengan data studi kasus; ID/kode dapat dibuat read-only (tanpa setter).
$entitas = new EntitasTervalidasi('KAS-001', 'Nama Contoh', 85);
$pesan = 'Data berhasil dimuat.';
try {
    // Contoh perubahan tervalidasi; ganti dengan aksi/logic soal.
    $entitas->setNama('Nama Studi Kasus');
    $pesan = 'Data berhasil diproses.';
} catch (InvalidArgumentException $e) {
    $pesan = $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REAL UTS — Week3</title>
    <!-- Jalur CSS untuk file REAL di folder Week1–Week6; ubah jika memindahkan file. -->
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body><main class="container">
    <header class="hero"><p class="eyebrow">TEMPLATE WEEK3</p><h1>Judul Studi Kasus</h1><p>Enkapsulasi dan validasi data.</p></header>
    <section class="card">
        <h2>Data Entitas</h2><div class="alert success"><?= htmlspecialchars($pesan, ENT_QUOTES, 'UTF-8') ?></div>
        <table><tbody>
            <tr><th>Kode</th><td><?= htmlspecialchars($entitas->getKode(), ENT_QUOTES, 'UTF-8') ?></td></tr>
            <tr><th>Nama</th><td><?= htmlspecialchars($entitas->getNama(), ENT_QUOTES, 'UTF-8') ?></td></tr>
            <tr><th>Nilai</th><td><?= number_format($entitas->getNilai(), 2, ',', '.') ?></td></tr>
        </tbody></table>
        <!-- Tambahkan field dan pesan validasi yang diminta soal di bagian ini. -->
    </section>
    <footer>Property private dibaca melalui getter; perubahan melewati setter tervalidasi.</footer>
</main></body></html>
