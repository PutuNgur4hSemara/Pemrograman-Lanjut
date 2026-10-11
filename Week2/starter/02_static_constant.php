<?php
/**
 * Latihan 2: Static Member dan Class Constant
 * Modul Praktikum Minggu 2
 */

class KonfigurasiAkademik
{
    // Class Constant
    public const NAMA_INSTITUT = "Institut Teknologi Kalimantan";
    public const BATAS_MAX_SKS = 24;
    public const BATAS_MIN_IPK_CUMLAUDE = 3.51;

    // Static Property: Dibagi bersama oleh seluruh instance
    public static int $counterObjekDibuat = 0;

    public string $namaPengguna;

    public function __construct(string $namaPengguna)
    {
        $this->namaPengguna = $namaPengguna;
        // Setiap objek dibuat, inkremen counter statis menggunakan self
        self::$counterObjekDibuat++;
    }

    // Static Helper Method
    public static function cekKelayakanSks(float $ipk): int
    {
        if ($ipk >= 3.00) {
            return self::BATAS_MAX_SKS; // 24 SKS
        } elseif ($ipk >= 2.50) {
            return 21;
        } elseif ($ipk >= 2.00) {
            return 18;
        } else {
            return 15;
        }
    }
}

// Mengakses konstanta tanpa perlu instansiasi objek
$namaKampus = KonfigurasiAkademik::NAMA_INSTITUT;
$maxSksMhs = KonfigurasiAkademik::cekKelayakanSks(3.45);

// Instansiasi beberapa objek untuk membuktikan kenaikan static property
$user1 = new KonfigurasiAkademik("Admin 1");
$user2 = new KonfigurasiAkademik("Dosen 1");
$user3 = new KonfigurasiAkademik("Mahasiswa 1");

$totalInstance = KonfigurasiAkademik::$counterObjekDibuat;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Latihan 2 - Static Member & Class Constant</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f8fafc; padding: 30px; }
        .box { background: white; border-radius: 8px; padding: 20px; max-width: 600px; box-shadow: 0 2px 4px rgba(0,0,0,0.06); margin-bottom: 20px; }
        .code-badge { background: #e2e8f0; padding: 2px 6px; border-radius: 4px; font-family: monospace; }
        .highlight { font-size: 20px; font-weight: bold; color: #16a34a; }
    </style>
</head>
<body>

    <div class="box">
        <h3>1. Akses Class Constant</h3>
        <p>Nama Kampus: <strong><?= $namaKampus ?></strong></p>
        <p>Batas Max SKS: <span class="code-badge"><?= KonfigurasiAkademik::BATAS_MAX_SKS ?> SKS</span></p>
    </div>

    <div class="box">
        <h3>2. Pemanggilan Static Helper Method</h3>
        <p>Simulasi: Mahasiswa dengan IPK <strong>3.45</strong> berhak mengambil maksimal: <strong class="highlight"><?= $maxSksMhs ?> SKS</strong>.</p>
    </div>

    <div class="box">
        <h3>3. Pelacakan Static Property</h3>
        <p>Jumlah objek yang telah diinstansiasi di memori: <span class="code-badge"><?= $totalInstance ?> objek</span>.</p>
    </div>

</body>
</html>
