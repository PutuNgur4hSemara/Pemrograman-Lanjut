<?php
/**
 * Latihan 1: Exception Handling Dasar (try, catch, finally, throw)
 * Modul Praktikum Minggu 5
 */

function hitungDiskon(float $totalBelanja, float $persenDiskon): float
{
    if ($totalBelanja < 0) {
        throw new InvalidArgumentException("Total belanja tidak boleh bernilai negatif.");
    }
    if ($persenDiskon < 0 || $persenDiskon > 100) {
        throw new OutOfRangeException("Persentase diskon harus berada di antara rentang 0% hingga 100%.");
    }

    $potongan = $totalBelanja * ($persenDiskon / 100);
    return $totalBelanja - $potongan;
}

$hasil = null;
$pesanError = null;
$logProses = [];

try {
    $logProses[] = "Memulai perhitungan transaksi...";
    
    // Uji coba kasus: Ubah diskon ke 150 untuk melihat OutOfRangeException
    $total = 500000;
    $diskon = 20; 

    $hasil = hitungDiskon($total, $diskon);
    $logProses[] = "Perhitungan selesai dengan sukses.";
} catch (InvalidArgumentException $e) {
    $pesanError = "Input Nilai Tidak Valid: " . $e->getMessage();
    $logProses[] = "Terjadi InvalidArgumentException.";
} catch (OutOfRangeException $e) {
    $pesanError = "Rentang Nilai Salah: " . $e->getMessage();
    $logProses[] = "Terjadi OutOfRangeException.";
} catch (Exception $e) {
    $pesanError = "Error Umum: " . $e->getMessage();
    $logProses[] = "Terjadi Exception tak terduga.";
} finally {
    $logProses[] = "Blok Finally dijalankan: Seluruh resource dan memory dibersihkan.";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Latihan 1 - Exception Handling</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f8fafc; padding: 30px; }
        .card { max-width: 600px; margin: 0 auto; background: white; border-radius: 12px; padding: 24px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
        .success { background: #dcfce7; color: #166534; padding: 12px; border-radius: 6px; margin: 12px 0; }
        .error { background: #fee2e2; color: #991b1b; padding: 12px; border-radius: 6px; margin: 12px 0; }
        .log-box { background: #0f172a; color: #38bdf8; font-family: monospace; font-size: 13px; padding: 12px; border-radius: 6px; margin-top: 16px; }
    </style>
</head>
<body>

    <div class="card">
        <h2>Demonstrasi Exception Handling</h2>

        <?php if ($pesanError): ?>
            <div class="error">❌ <?= htmlspecialchars($pesanError) ?></div>
        <?php else: ?>
            <div class="success">
                ✓ Total Setelah Diskon: <strong>Rp <?= number_format($hasil, 0, ',', '.') ?></strong>
            </div>
        <?php endif; ?>

        <h4>Log Eksekusi Program (Termasuk Finally):</h4>
        <div class="log-box">
            <?php foreach ($logProses as $log): ?>
                <div>&gt; <?= htmlspecialchars($log) ?></div>
            <?php endforeach; ?>
        </div>
    </div>

</body>
</html>
