<?php
/**
 * Latihan 3: Interface & Polymorphism (Polimorfisme Pembayaran)
 * Modul Praktikum Minggu 4
 */

// Kontrak Interface: Menjamin semua metode pembayaran punya method bayar()
interface MetodePembayaran
{
    public function bayar(float $nominal): string;
    public function getNama(): string;
}

// Implementasi 1: Transfer Virtual Account
class TransferVA implements MetodePembayaran
{
    public function __construct(private string $bank) {}

    public function bayar(float $nominal): string
    {
        $vaNumber = "8808" . rand(10000000, 99999999);
        return "Silakan transfer ke Virtual Account {$this->bank} No: <strong>{$vaNumber}</strong> sebesar Rp " . number_format($nominal, 0, ',', '.');
    }

    public function getNama(): string { return "Virtual Account {$this->bank}"; }
}

// Implementasi 2: Dompet Digital QRIS
class DompetDigitalQris implements MetodePembayaran
{
    public function bayar(float $nominal): string
    {
        return "Scan kode QRIS berikut untuk menyelesaikan transaksi Rp " . number_format($nominal, 0, ',', '.') . ". [QRIS-IMAGE-CODE]";
    }

    public function getNama(): string { return "QRIS Multi-Payment"; }
}

// Implementasi 3: Kartu Kredit
class KartuKredit implements MetodePembayaran
{
    public function __construct(private string $nomorKartu) {}

    public function bayar(float $nominal): string
    {
        $sensor = "**** **** **** " . substr($this->nomorKartu, -4);
        return "Pembayaran Rp " . number_format($nominal, 0, ',', '.') . " menggunakan Kartu Kredit {$sensor} telah di-otorisasi secara instan.";
    }

    public function getNama(): string { return "Kartu Kredit"; }
}

// FUNGSI POLYMORPHIC: Memproses metode apa saja tanpa peduli class aslinya
function prosesCheckout(MetodePembayaran $metode, float $totalTagihan): array
{
    return [
        'metode' => $metode->getNama(),
        'instruksi' => $metode->bayar($totalTagihan)
    ];
}

$tagihan = 450000;
$hasilSimulasi = [
    prosesCheckout(new TransferVA("Mandiri"), $tagihan),
    prosesCheckout(new DompetDigitalQris(), $tagihan),
    prosesCheckout(new KartuKredit("4111222233334567"), $tagihan)
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Latihan 3 - Interface & Polymorphism</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f8fafc; padding: 30px; }
        .container { max-width: 700px; margin: 0 auto; background: white; border-radius: 12px; padding: 24px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
        .payment-box { background: #f1f5f9; border-left: 4px solid #3b82f6; padding: 16px; border-radius: 6px; margin-bottom: 14px; }
    </style>
</head>
<body>

    <div class="container">
        <h2>Simulasi Polymorphism: Gateway Pembayaran</h2>
        <p style="color: #64748b;">Tagihan Pembelian: <strong>Rp <?= number_format($tagihan, 0, ',', '.') ?></strong></p>

        <div style="margin-top: 20px;">
            <?php foreach ($hasilSimulasi as $item): ?>
                <div class="payment-box">
                    <div style="font-size: 13px; font-weight: bold; color: #1e40af; text-transform: uppercase;"><?= $item['metode'] ?></div>
                    <div style="margin-top: 6px; font-size: 14px; color: #334155;"><?= $item['instruksi'] ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

</body>
</html>
