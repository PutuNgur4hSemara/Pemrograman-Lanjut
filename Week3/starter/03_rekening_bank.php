<?php
/**
 * Latihan 3: Studi Kasus Domain Model Rekening Bank
 * Modul Praktikum Minggu 3
 */

class RekeningBank
{
    private string $nomorRekening;
    private string $namaPemilik;
    private float $saldo;
    private array $riwayatTransaksi = [];

    public function __construct(string $nomorRekening, string $namaPemilik, float $saldoAwal = 0.0)
    {
        if ($saldoAwal < 0) {
            throw new InvalidArgumentException("Saldo awal tidak boleh minus.");
        }
        $this->nomorRekening = $nomorRekening;
        $this->namaPemilik = $namaPemilik;
        $this->saldo = $saldoAwal;
        $this->catatLog("Pembukaan Rekening", $saldoAwal, $this->saldo);
    }

    public function getNomorRekening(): string { return $this->nomorRekening; }
    public function getNamaPemilik(): string { return $this->namaPemilik; }
    public function getSaldo(): float { return $this->saldo; }
    public function getRiwayat(): array { return $this->riwayatTransaksi; }

    public function setor(float $nominal): void
    {
        if ($nominal <= 0) {
            throw new InvalidArgumentException("Nominal setoran harus lebih dari 0.");
        }
        $this->saldo += $nominal;
        $this->catatLog("Setor Tunai", $nominal, $this->saldo);
    }

    public function tarik(float $nominal): void
    {
        if ($nominal <= 0) {
            throw new InvalidArgumentException("Nominal penarikan harus lebih dari 0.");
        }
        if ($nominal > $this->saldo) {
            throw new RuntimeException("Saldo tidak mencukupi untuk melakukan penarikan Rp " . number_format($nominal, 0, ',', '.'));
        }
        $this->saldo -= $nominal;
        $this->catatLog("Tarik Tunai", -$nominal, $this->saldo);
    }

    // Helper method private untuk internal class saja
    private function catatLog(string $jenis, float $perubahan, float $saldoAkhir): void
    {
        $this->riwayatTransaksi[] = [
            'waktu' => date('Y-m-d H:i:s'),
            'jenis' => $jenis,
            'nominal' => $perubahan,
            'saldo' => $saldoAkhir
        ];
    }
}

// Simulasi Operasi Perbankan
$rekening = new RekeningBank("9001-2234-88", "Dewi Sartika", 1000000);
$rekening->setor(500000);
$rekening->tarik(250000);
$rekening->setor(1200000);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Latihan 3 - Studi Kasus Rekening Bank</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f8fafc; padding: 30px; }
        .container { max-width: 700px; margin: 0 auto; background: white; border-radius: 12px; padding: 24px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
        .saldo-box { background: linear-gradient(135deg, #1e293b, #0f172a); color: white; padding: 20px; border-radius: 8px; margin: 16px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th, td { padding: 10px 12px; border-bottom: 1px solid #e2e8f0; font-size: 14px; text-align: left; }
        th { background: #f1f5f9; color: #475569; }
        .text-right { text-align: right; }
        .plus { color: #16a34a; font-weight: 600; }
        .minus { color: #dc2626; font-weight: 600; }
    </style>
</head>
<body>

    <div class="container">
        <h2>Buku Tabungan Nasabah</h2>
        <p style="color: #64748b;">No. Rekening: <strong><?= $rekening->getNomorRekening() ?></strong> | Pemilik: <strong><?= $rekening->getNamaPemilik() ?></strong></p>

        <div class="saldo-box">
            <div style="font-size: 13px; color: #94a3b8; text-transform: uppercase;">Saldo Rekening Saat Ini</div>
            <div style="font-size: 28px; font-weight: bold; margin-top: 4px;">Rp <?= number_format($rekening->getSaldo(), 0, ',', '.') ?></div>
        </div>

        <h3>Mutasi Riwayat Transaksi</h3>
        <table>
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>Jenis Transaksi</th>
                    <th class="text-right">Nominal</th>
                    <th class="text-right">Saldo Akhir</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rekening->getRiwayat() as $log): ?>
                    <tr>
                        <td><?= $log['waktu'] ?></td>
                        <td><?= $log['jenis'] ?></td>
                        <td class="text-right <?= $log['nominal'] >= 0 ? 'plus' : 'minus' ?>">
                            <?= $log['nominal'] >= 0 ? '+' : '' ?>Rp <?= number_format($log['nominal'], 0, ',', '.') ?>
                        </td>
                        <td class="text-right">Rp <?= number_format($log['saldo'], 0, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</body>
</html>
