<?php
/**
 * Latihan 4: Argument Method dan Nilai Kembalian (Return Value)
 * Modul Praktikum Minggu 1
 */

class KalkulatorNilai
{
    public $namaMahasiswa;
    public $mataKuliah;

    // Method menerima argumen bobot nilai
    public function hitungNilaiAkhir($nilaiTugas, $nilaiUTS, $nilaiUAS)
    {
        // Formula: 30% Tugas + 30% UTS + 40% UAS
        return ($nilaiTugas * 0.30) + ($nilaiUTS * 0.30) + ($nilaiUAS * 0.40);
    }

    // Method untuk menentukan grade huruf
    public function konversiGrade($nilaiAkhir)
    {
        if ($nilaiAkhir >= 85) return 'A';
        if ($nilaiAkhir >= 75) return 'B';
        if ($nilaiAkhir >= 65) return 'C';
        if ($nilaiAkhir >= 50) return 'D';
        return 'E';
    }

    // Method untuk menentukan status kelulusan
    public function cekKelulusan($grade)
    {
        return in_array($grade, ['A', 'B', 'C']) ? 'LULUS' : 'MENGULANG';
    }
}

// Simulasi Penilaian
$penilai = new KalkulatorNilai();
$penilai->namaMahasiswa = "Rizky Firmansyah";
$penilai->mataKuliah = "Pemrograman Lanjut";

$tugas = 85;
$uts = 78;
$uas = 90;

$nilaiAkhir = $penilai->hitungNilaiAkhir($tugas, $uts, $uas);
$grade = $penilai->konversiGrade($nilaiAkhir);
$status = $penilai->cekKelulusan($grade);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Latihan 4 - Argument Method</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f8fafc; padding: 30px; }
        .card { max-width: 500px; margin: 0 auto; background: white; border-radius: 12px; padding: 24px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
        .row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f1f5f9; }
        .badge-success { background: #dcfce7; color: #166534; padding: 4px 10px; border-radius: 6px; font-weight: bold; }
        .badge-danger { background: #fee2e2; color: #991b1b; padding: 4px 10px; border-radius: 6px; font-weight: bold; }
        .score-highlight { font-size: 28px; font-weight: bold; color: #2563eb; text-align: center; margin: 16px 0; }
    </style>
</head>
<body>

    <div class="card">
        <h3 style="margin-top: 0; text-align: center;">Hasil Evaluasi Mahasiswa</h3>
        <p style="text-align: center; color: #64748b; font-size: 14px;"><?= htmlspecialchars($penilai->mataKuliah) ?></p>

        <div class="score-highlight">
            <?= number_format($nilaiAkhir, 2) ?> (Grade: <?= $grade ?>)
        </div>

        <div class="row">
            <span>Nama Mahasiswa:</span>
            <strong><?= htmlspecialchars($penilai->namaMahasiswa) ?></strong>
        </div>
        <div class="row">
            <span>Nilai Tugas (30%):</span>
            <span><?= $tugas ?></span>
        </div>
        <div class="row">
            <span>Nilai UTS (30%):</span>
            <span><?= $uts ?></span>
        </div>
        <div class="row">
            <span>Nilai UAS (40%):</span>
            <span><?= $uas ?></span>
        </div>
        <div class="row" style="border: none; margin-top: 10px;">
            <span>Status Kelulusan:</span>
            <span class="<?= $status === 'LULUS' ? 'badge-success' : 'badge-danger' ?>">
                <?= $status ?>
            </span>
        </div>
    </div>

</body>
</html>
