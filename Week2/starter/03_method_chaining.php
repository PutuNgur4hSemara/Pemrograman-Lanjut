<?php
/**
 * Latihan 3: Method Chaining (Fluent Interface)
 * Modul Praktikum Minggu 2
 */

class KuitansiBuilder
{
    private string $nomorTransaksi;
    private string $namaPelanggan;
    private array $daftarItem = [];
    private float $diskonPersen = 0.0;
    private bool $kenaPajak = false;

    public function __construct(string $nomorTransaksi)
    {
        $this->nomorTransaksi = $nomorTransaksi;
    }

    // Method chaining: Selalu mengembalikan instance objek aktif (return $this)
    public function untuk(string $namaPelanggan): self
    {
        $this->namaPelanggan = $namaPelanggan;
        return $this;
    }

    public function tambahItem(string $nama, int $qty, float $hargaSatuan): self
    {
        $this->daftarItem[] = [
            'nama' => $nama,
            'qty' => $qty,
            'harga' => $hargaSatuan,
            'subtotal' => $qty * $hargaSatuan
        ];
        return $this;
    }

    public function diskon(float $persen): self
    {
        $this->diskonPersen = $persen;
        return $this;
    }

    public function denganPPN(): self
    {
        $this->kenaPajak = true;
        return $this;
    }

    public function hitungSubtotal(): float
    {
        $total = 0;
        foreach ($this->daftarItem as $item) {
            $total += $item['subtotal'];
        }
        return $total;
    }

    public function hitungTotalAkhir(): float
    {
        $subtotal = $this->hitungSubtotal();
        $potongan = $subtotal * ($this->diskonPersen / 100);
        $setelahDiskon = $subtotal - $potongan;
        $ppn = $this->kenaPajak ? ($setelahDiskon * 0.11) : 0;
        return $setelahDiskon + $ppn;
    }

    public function getDaftarItem(): array { return $this->daftarItem; }
    public function getPelanggan(): string { return $this->namaPelanggan; }
    public function getNomor(): string { return $this->nomorTransaksi; }
    public function getDiskon(): float { return $this->diskonPersen; }
    public function isKenaPajak(): bool { return $this->kenaPajak; }
}

// Penggunaan Method Chaining dalam 1 ekspresi yang elegan
$kuitansi = (new KuitansiBuilder("TRX-2026-0091"))
    ->untuk("Fajar Nugroho")
    ->tambahItem("Mechanical Keyboard RGB", 1, 650000)
    ->tambahItem("Wireless Gaming Mouse", 1, 350000)
    ->tambahItem("Mousepad Desk Mat XXL", 2, 85000)
    ->diskon(10) // Diskon 10%
    ->denganPPN(); // PPN 11%
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Latihan 3 - Method Chaining</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f8fafc; padding: 30px; }
        .receipt { max-width: 550px; margin: 0 auto; background: white; border-radius: 12px; padding: 24px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); border: 1px solid #e2e8f0; }
        table { width: 100%; border-collapse: collapse; margin: 16px 0; }
        th, td { padding: 8px 12px; text-align: left; font-size: 14px; }
        th { border-bottom: 2px solid #cbd5e1; color: #475569; }
        td { border-bottom: 1px solid #f1f5f9; }
        .text-right { text-align: right; }
        .total-row { font-size: 18px; font-weight: bold; color: #0f172a; border-top: 2px solid #0f172a; }
    </style>
</head>
<body>

    <div class="receipt">
        <h3 style="margin: 0; color: #1e293b;">Struk Pembelian</h3>
        <p style="color: #64748b; font-size: 13px;">No: <?= htmlspecialchars($kuitansi->getNomor()) ?> | Pelanggan: <strong><?= htmlspecialchars($kuitansi->getPelanggan()) ?></strong></p>

        <table>
            <thead>
                <tr>
                    <th>Item</th>
                    <th class="text-right">Qty</th>
                    <th class="text-right">Harga</th>
                    <th class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($kuitansi->getDaftarItem() as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['nama']) ?></td>
                        <td class="text-right"><?= $item['qty'] ?></td>
                        <td class="text-right">Rp <?= number_format($item['harga'], 0, ',', '.') ?></td>
                        <td class="text-right">Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="3" class="text-right"><strong>Subtotal:</strong></td>
                    <td class="text-right">Rp <?= number_format($kuitansi->hitungSubtotal(), 0, ',', '.') ?></td>
                </tr>
                <tr>
                    <td colspan="3" class="text-right" style="color: #dc2626;">Diskon (<?= $kuitansi->getDiskon() ?>%):</td>
                    <td class="text-right" style="color: #dc2626;">- Rp <?= number_format($kuitansi->hitungSubtotal() * ($kuitansi->getDiskon() / 100), 0, ',', '.') ?></td>
                </tr>
                <?php if ($kuitansi->isKenaPajak()): ?>
                    <tr>
                        <td colspan="3" class="text-right">PPN (11%):</td>
                        <td class="text-right">Rp <?= number_format(($kuitansi->hitungSubtotal() * 0.90) * 0.11, 0, ',', '.') ?></td>
                    </tr>
                <?php endif; ?>
                <tr class="total-row">
                    <td colspan="3" class="text-right">TOTAL AKHIR:</td>
                    <td class="text-right">Rp <?= number_format($kuitansi->hitungTotalAkhir(), 0, ',', '.') ?></td>
                </tr>
            </tbody>
        </table>
    </div>

</body>
</html>
