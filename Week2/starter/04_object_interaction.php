<?php
/**
 * Latihan 4: Interaksi Antar Objek (Object Type Hinting & Return Value)
 * Modul Praktikum Minggu 2
 */

class DosenWali
{
    public function __construct(
        public string $nip,
        public string $namaDosen,
        public string $gelar
    ) {}

    public function getNamaLengkap(): string
    {
        return "{$this->namaDosen}, {$this->gelar}";
    }
}

class ProgramStudi
{
    public function __construct(
        public string $kodeProdi,
        public string $namaProdi,
        public string $fakultas
    ) {}
}

class MahasiswaBimbingan
{
    public function __construct(
        public string $nim,
        public string $nama,
        public ProgramStudi $prodi,      // Type hinting objek ProgramStudi
        public DosenWali $dosenWali,      // Type hinting objek DosenWali
        public float $ipk = 0.0
    ) {}

    public function gantiDosenWali(DosenWali $dosenBaru): void
    {
        $this->dosenWali = $dosenBaru;
    }

    public function buatKartuRencanaStudi(): string
    {
        return "KRS Mahasiswa {$this->nama} ({$this->nim}) disetujui oleh {$this->dosenWali->getNamaLengkap()}.";
    }
}

// Inisialisasi Objek Dependensi
$prodiSI = new ProgramStudi("SI", "Sistem Informasi", "Fakultas Sains & Teknologi Terpadu");
$dosenA = new DosenWali("198801012015041001", "Rosa Elviani", "S.Kom., M.Kom.");

// Inisialisasi Mahasiswa dengan melewatkan objek dependensi
$mhs = new MahasiswaBimbingan("10231045", "Clarissa Amanda", $prodiSI, $dosenA, 3.89);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Latihan 4 - Interaksi Antar Objek</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f8fafc; padding: 30px; }
        .card { max-width: 600px; margin: 0 auto; background: white; border-radius: 12px; padding: 24px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
        .badge { background: #e0e7ff; color: #3730a3; padding: 4px 10px; border-radius: 6px; font-weight: 600; font-size: 13px; }
        .section-title { font-size: 14px; color: #64748b; font-weight: 600; text-transform: uppercase; margin-top: 16px; margin-bottom: 6px; }
    </style>
</head>
<body>

    <div class="card">
        <span class="badge"><?= htmlspecialchars($mhs->prodi->namaProdi) ?></span>
        <h2 style="margin-top: 10px; color: #1e293b;"><?= htmlspecialchars($mhs->nama) ?></h2>
        <p style="color: #64748b; font-size: 14px;">NIM: <?= htmlspecialchars($mhs->nim) ?> | IPK: <strong><?= number_format($mhs->ipk, 2) ?></strong></p>

        <hr style="margin: 16px 0; border: 0; border-top: 1px solid #e2e8f0;">

        <div class="section-title">Informasi Program Studi</div>
        <p><strong><?= htmlspecialchars($mhs->prodi->namaProdi) ?></strong> (Kode: <?= htmlspecialchars($mhs->prodi->kodeProdi) ?>)</p>
        <p style="font-size: 14px; color: #64748b;"><?= htmlspecialchars($mhs->prodi->fakultas) ?></p>

        <div class="section-title">Dosen Wali Akademik</div>
        <p><strong><?= htmlspecialchars($mhs->dosenWali->getNamaLengkap()) ?></strong></p>
        <p style="font-size: 13px; color: #64748b;">NIP: <?= htmlspecialchars($mhs->dosenWali->nip) ?></p>

        <div style="background: #f0fdf4; border-left: 4px solid #22c55e; padding: 12px; border-radius: 4px; margin-top: 20px;">
            <p style="color: #166534; font-size: 14px; margin: 0;">
                ✓ <?= $mhs->buatKartuRencanaStudi() ?>
            </p>
        </div>
    </div>

</body>
</html>
