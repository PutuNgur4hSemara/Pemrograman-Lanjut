<?php
/**
 * Latihan 2: Abstract Class dan Abstract Method
 * Modul Praktikum Minggu 4
 */

// Abstract Class: Menjadi cetak biru fondasi
abstract class LayananNotifikasi
{
    public function __construct(
        protected string $namaAplikasi = "Sistem Akademik ITK"
    ) {}

    // Abstract Method: Wajib diisi oleh class turunan
    abstract public function kirim(string $penerima, string $pesan): bool;

    // Method konkret yang bisa langsung digunakan oleh semua class anak
    public function formatHeaderPesan(): string
    {
        return "[{$this->namaAplikasi} - " . date('d M Y H:i') . "]\n";
    }
}

// Class Konkret 1: Notifikasi Email
class NotifikasiEmail extends LayananNotifikasi
{
    public function kirim(string $penerima, string $pesan): bool
    {
        // Simulasi pengiriman email
        $formatLengkap = $this->formatHeaderPesan() . "Kepada: {$penerima}\n" . $pesan;
        return filter_var($penerima, FILTER_VALIDATE_EMAIL) !== false;
    }

    public function getLabel(): string { return "Email Gateway (SMTP)"; }
}

// Class Konkret 2: Notifikasi WhatsApp
class NotifikasiWhatsApp extends LayananNotifikasi
{
    public function kirim(string $penerima, string $pesan): bool
    {
        // Simulasi validasi nomor WhatsApp
        return str_starts_with($penerima, "08") || str_starts_with($penerima, "+62");
    }

    public function getLabel(): string { return "WhatsApp Business API"; }
}

$emailSender = new NotifikasiEmail();
$waSender = new NotifikasiWhatsApp();

$targetEmail = "mahasiswa@itk.ac.id";
$targetWa = "081234567890";
$isiPesan = "Pemberitahuan: Jadwal praktikum Pemrograman Lanjut telah diperbarui.";

$statusEmail = $emailSender->kirim($targetEmail, $isiPesan);
$statusWa = $waSender->kirim($targetWa, $isiPesan);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Latihan 2 - Abstract Class</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f8fafc; padding: 30px; }
        .card { max-width: 600px; margin: 0 auto 16px; background: white; border-radius: 10px; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.06); }
        .badge { background: #dcfce7; color: #15803d; font-size: 12px; font-weight: bold; padding: 4px 8px; border-radius: 4px; }
    </style>
</head>
<body>

    <div class="card">
        <h3>Layanan Notifikasi Email</h3>
        <p>Driver: <strong><?= $emailSender->getLabel() ?></strong></p>
        <p>Penerima: <?= htmlspecialchars($targetEmail) ?></p>
        <p>Status: <span class="badge"><?= $statusEmail ? 'BERHASIL TERKIRIM' : 'GAGAL' ?></span></p>
    </div>

    <div class="card">
        <h3>Layanan Notifikasi WhatsApp</h3>
        <p>Driver: <strong><?= $waSender->getLabel() ?></strong></p>
        <p>Penerima: <?= htmlspecialchars($targetWa) ?></p>
        <p>Status: <span class="badge"><?= $statusWa ? 'BERHASIL TERKIRIM' : 'GAGAL' ?></span></p>
    </div>

</body>
</html>
