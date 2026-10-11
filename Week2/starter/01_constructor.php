<?php
/**
 * Latihan 1: Constructor Konvensional & Constructor Property Promotion (PHP 8+)
 * Modul Praktikum Minggu 2
 */

// Class dengan Constructor Klasik
class MahasiswaKlasik
{
    public string $nim;
    public string $nama;
    public float $ipk;

    public function __construct(string $nim, string $nama, float $ipk = 0.0)
    {
        $this->nim = $nim;
        $this->nama = $nama;
        $this->ipk = $ipk;
    }
}

// Class dengan Constructor Property Promotion (PHP 8)
class MahasiswaModern
{
    public function __construct(
        public string $nim,
        public string $nama,
        public string $programStudi,
        public float $ipk = 0.0,
        public string $status = "Aktif"
    ) {}

    public function getPredikat(): string
    {
        if ($this->ipk >= 3.51) return "Dengan Pujian (Cumlaude)";
        if ($this->ipk >= 3.00) return "Sangat Memuaskan";
        if ($this->ipk >= 2.75) return "Memuaskan";
        return "Cukup";
    }
}

// Instansiasi langsung memberikan nilai melalui constructor
$mhs1 = new MahasiswaModern("10231011", "Rian Hidayat", "Sistem Informasi", 3.82);
$mhs2 = new MahasiswaModern("10231025", "Nadia Safitri", "Informatika", 3.45);
$mhs3 = new MahasiswaModern("10231033", "Dimas Anggara", "Sains Data", 2.90);

$daftarMahasiswa = [$mhs1, $mhs2, $mhs3];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Latihan 1 - Constructor</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f8fafc; padding: 30px; }
        .card-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px; max-width: 900px; margin-top: 20px; }
        .card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.06); border-top: 4px solid #2563eb; }
        .predikat { font-size: 13px; font-weight: bold; color: #0284c7; margin-top: 8px; }
    </style>
</head>
<body>

    <h2>Daftar Mahasiswa (Inisialisasi via Constructor Promotion)</h2>

    <div class="card-grid">
        <?php foreach ($daftarMahasiswa as $mhs): ?>
            <div class="card">
                <h3><?= htmlspecialchars($mhs->nama) ?></h3>
                <p style="color: #64748b; font-size: 14px; margin-bottom: 8px;">NIM: <?= htmlspecialchars($mhs->nim) ?></p>
                <p><strong>Prodi:</strong> <?= htmlspecialchars($mhs->programStudi) ?></p>
                <p><strong>IPK:</strong> <?= number_format($mhs->ipk, 2) ?></p>
                <div class="predikat">Predikat: <?= $mhs->getPredikat() ?></div>
            </div>
        <?php endforeach; ?>
    </div>

</body>
</html>
