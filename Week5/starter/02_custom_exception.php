<?php
/**
 * Latihan 2: Custom Exception Domain
 * Modul Praktikum Minggu 5
 */

// Membuat Custom Exception Class
class StokGudangKurangException extends Exception
{
    public function __construct(string $namaBarang, int $stokTersedia, int $jumlahMinta)
    {
        $pesan = "Permintaan barang '{$namaBarang}' sebanyak {$jumlahMinta} unit ditolak! Stok gudang yang tersedia hanya {$stokTersedia} unit.";
        parent::__construct($pesan);
    }
}

class ProdukGudang
{
    public function __construct(
        public string $kode,
        public string $nama,
        private int $stok
    ) {}

    public function getStok(): int { return $this->stok; }

    public function ambilStok(int $jumlah): void
    {
        if ($jumlah <= 0) {
            throw new InvalidArgumentException("Jumlah pengambilan harus lebih dari 0.");
        }
        if ($jumlah > $this->stok) {
            // Melempar Custom Exception
            throw new StokGudangKurangException($this->nama, $this->stok, $jumlah);
        }
        $this->stok -= $jumlah;
    }
}

$barang = new ProdukGudang("LAP-001", "Laptop ASUS Zenbook", 5);
$notifikasi = [];

// Simulasi 1: Pengambilan Normal
try {
    $barang->ambilStok(2);
    $notifikasi[] = ['tipe' => 'sukses', 'pesan' => "Pengambilan 2 unit berhasil. Sisa stok: {$barang->getStok()} unit."];
} catch (Exception $e) {
    $notifikasi[] = ['tipe' => 'error', 'pesan' => $e->getMessage()];
}

// Simulasi 2: Pengambilan Melebihi Stok (Akan memicu StokGudangKurangException)
try {
    $barang->ambilStok(10);
    $notifikasi[] = ['tipe' => 'sukses', 'pesan' => "Pengambilan 10 unit berhasil."];
} catch (StokGudangKurangException $e) {
    $notifikasi[] = ['tipe' => 'error', 'pesan' => "[Custom Exception] " . $e->getMessage()];
} catch (Exception $e) {
    $notifikasi[] = ['tipe' => 'error', 'pesan' => $e->getMessage()];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Latihan 2 - Custom Exception</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f8fafc; padding: 30px; }
        .card { max-width: 600px; margin: 0 auto; background: white; border-radius: 12px; padding: 24px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
        .alert-sukses { background: #dcfce7; color: #166534; padding: 12px; border-radius: 6px; margin-bottom: 10px; font-size: 14px; }
        .alert-error { background: #fee2e2; color: #991b1b; padding: 12px; border-radius: 6px; margin-bottom: 10px; font-size: 14px; }
    </style>
</head>
<body>

    <div class="card">
        <h2>Simulasi Gudang & Custom Exception</h2>
        <p style="color: #64748b; margin-bottom: 16px;">Barang: <strong><?= htmlspecialchars($barang->nama) ?></strong> (Sisa Stok: <?= $barang->getStok() ?> unit)</p>

        <?php foreach ($notifikasi as $notif): ?>
            <div class="<?= $notif['tipe'] === 'sukses' ? 'alert-sukses' : 'alert-error' ?>">
                <?= htmlspecialchars($notif['pesan']) ?>
            </div>
        <?php endforeach; ?>
    </div>

</body>
</html>
