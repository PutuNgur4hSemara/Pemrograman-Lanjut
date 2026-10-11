<?php
/**
 * Latihan 2: Penerapan Private Property, Getter, dan Setter dengan Validasi
 * Modul Praktikum Minggu 3
 */

class MahasiswaTerproteksi
{
    private string $nim;
    private string $nama;
    private float $ipk = 0.0;

    public function __construct(string $nim, string $nama, float $ipk = 0.0)
    {
        $this->nim = $nim;
        $this->setNama($nama);
        $this->setIpk($ipk);
    }

    // Read-only Property: Hanya Getter tanpa Setter
    public function getNim(): string
    {
        return $this->nim;
    }

    public function getNama(): string
    {
        return $this->nama;
    }

    public function setNama(string $nama): void
    {
        $namaBersih = trim($nama);
        if (strlen($namaBersih) < 3) {
            throw new InvalidArgumentException("Nama mahasiswa tidak boleh kurang dari 3 karakter.");
        }
        $this->nama = $namaBersih;
    }

    public function getIpk(): float
    {
        return $this->ipk;
    }

    public function setIpk(float $ipk): void
    {
        // Validasi Range IPK 0.00 s.d. 4.00
        if ($ipk < 0.0 || $ipk > 4.0) {
            throw new InvalidArgumentException("IPK [{$ipk}] tidak valid! Rentang IPK harus antara 0.00 sampai 4.00.");
        }
        $this->ipk = $ipk;
    }
}

$pesanError = null;
$mhs = null;

try {
    $mhs = new MahasiswaTerproteksi("10231077", "Muhammad Kevin", 3.88);
    // Mencoba update IPK valid
    $mhs->setIpk(3.92);

    // Jika baris di bawah diaktifkan, exception akan ditangkap:
    // $mhs->setIpk(5.5);
} catch (InvalidArgumentException $e) {
    $pesanError = $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Latihan 2 - Getter & Setter</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f8fafc; padding: 30px; }
        .card { max-width: 500px; margin: 0 auto; background: white; border-radius: 12px; padding: 24px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); border-left: 5px solid #16a34a; }
        .row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #f1f5f9; }
    </style>
</head>
<body>

    <div class="card">
        <h3 style="margin-top: 0; color: #15803d;">Data Mahasiswa Aman (Terenkapsulasi)</h3>

        <?php if ($pesanError): ?>
            <div style="background: #fee2e2; color: #991b1b; padding: 10px; border-radius: 6px; margin-bottom: 12px;">
                Error: <?= htmlspecialchars($pesanError) ?>
            </div>
        <?php endif; ?>

        <?php if ($mhs): ?>
            <div class="row">
                <span>NIM (Read-only):</span>
                <strong><?= htmlspecialchars($mhs->getNim()) ?></strong>
            </div>
            <div class="row">
                <span>Nama Lengkap:</span>
                <strong><?= htmlspecialchars($mhs->getNama()) ?></strong>
            </div>
            <div class="row" style="border: none;">
                <span>IPK (Tervalidasi):</span>
                <strong style="color: #2563eb;"><?= number_format($mhs->getIpk(), 2) ?></strong>
            </div>
        <?php endif; ?>
    </div>

</body>
</html>
