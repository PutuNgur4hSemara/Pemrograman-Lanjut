<?php
/**
 * Latihan 3: Transaksi Database MySQLi (begin_transaction, commit, rollback)
 * Modul Praktikum Minggu 6
 */

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$host = '127.0.0.1';
$user = 'root';
$pass = '';
$dbName = 'pemrograman_lanjut';

$pesanHasil = "";
$statusSukses = false;
$daftarRekening = [];

try {
    $db = new mysqli($host, $user, $pass, $dbName);
    $db->set_charset('utf8mb4');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $rekAsal = $_POST['rek_asal'];
        $rekTujuan = $_POST['rek_tujuan'];
        $nominal = (float)$_POST['nominal'];

        if ($nominal <= 0) {
            throw new Exception("Nominal transfer harus lebih dari nol.");
        }
        if ($rekAsal === $rekTujuan) {
            throw new Exception("Rekening asal dan tujuan tidak boleh sama.");
        }

        // MEMULAI TRANSAKSI DATABASE
        $db->begin_transaction();

        try {
            // 1. Cek Saldo Pengirim
            $stmtCek = $db->prepare("SELECT saldo FROM rekening WHERE no_rek = ? FOR UPDATE");
            $stmtCek->bind_param("s", $rekAsal);
            $stmtCek->execute();
            $res = $stmtCek->get_result()->fetch_assoc();
            $stmtCek->close();

            if (!$res || $res['saldo'] < $nominal) {
                throw new Exception("Saldo rekening asal ({$rekAsal}) tidak mencukupi untuk transfer Rp " . number_format($nominal, 0, ',', '.'));
            }

            // 2. Debet Rekening Pengirim
            $stmtDebet = $db->prepare("UPDATE rekening SET saldo = saldo - ? WHERE no_rek = ?");
            $stmtDebet->bind_param("ds", $nominal, $rekAsal);
            $stmtDebet->execute();
            $stmtDebet->close();

            // 3. Kredit Rekening Penerima
            $stmtKredit = $db->prepare("UPDATE rekening SET saldo = saldo + ? WHERE no_rek = ?");
            $stmtKredit->bind_param("ds", $nominal, $rekTujuan);
            $stmtKredit->execute();
            if ($stmtKredit->affected_rows === 0) {
                throw new Exception("Rekening tujuan ({$rekTujuan}) tidak terdaftar.");
            }
            $stmtKredit->close();

            // 4. COMMIT TRANSAKSI: Simpan Permanen ke Database
            $db->commit();
            $pesanHasil = "Transfer dana sebesar Rp " . number_format($nominal, 0, ',', '.') . " dari {$rekAsal} ke {$rekTujuan} BERHASIL!";
            $statusSukses = true;

        } catch (Exception $ex) {
            // ROLLBACK TRANSAKSI: Batalkan seluruh perubahan jika ada kesalahan
            $db->rollback();
            $pesanHasil = "Transaksi Dibatalkan (Rollback): " . $ex->getMessage();
            $statusSukses = false;
        }
    }

    // Ambil saldo terkini
    $resRek = $db->query("SELECT no_rek, nama_nasabah, saldo FROM rekening");
    $daftarRekening = $resRek->fetch_all(MYSQLI_ASSOC);
    $db->close();

} catch (mysqli_sql_exception $e) {
    $pesanHasil = "Kesalahan Sistem Database: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Latihan 3 - Transaksi Database MySQLi</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f8fafc; padding: 30px; }
        .container { max-width: 700px; margin: 0 auto; background: white; border-radius: 12px; padding: 24px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
        .rekening-list { display: flex; gap: 16px; margin: 16px 0; }
        .rek-card { flex: 1; background: #f1f5f9; padding: 16px; border-radius: 8px; border-top: 4px solid #0f172a; }
        .form-row { margin-bottom: 12px; }
        label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 4px; color: #475569; }
        select, input { width: 100%; box-sizing: border-box; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; }
        button { background: #16a34a; color: white; border: none; padding: 10px 16px; border-radius: 6px; cursor: pointer; font-weight: 600; width: 100%; }
        .alert-ok { background: #dcfce7; color: #166534; padding: 12px; border-radius: 6px; margin-bottom: 16px; font-size: 14px; }
        .alert-err { background: #fee2e2; color: #991b1b; padding: 12px; border-radius: 6px; margin-bottom: 16px; font-size: 14px; }
    </style>
</head>
<body>

    <div class="container">
        <h2>Simulasi Transaksi Database (ACID)</h2>
        <p style="color: #64748b; font-size: 14px;">Membuktikan sifat atomik: uang tidak akan hilang di jalan jika transfer gagal.</p>

        <?php if ($pesanHasil): ?>
            <div class="<?= $statusSukses ? 'alert-ok' : 'alert-err' ?>">
                <?= htmlspecialchars($pesanHasil) ?>
            </div>
        <?php endif; ?>

        <h3>Saldo Rekening Terkini:</h3>
        <div class="rekening-list">
            <?php foreach ($daftarRekening as $r): ?>
                <div class="rek-card">
                    <div style="font-size: 12px; color: #64748b;"><?= htmlspecialchars($r['no_rek']) ?></div>
                    <div style="font-weight: bold; margin: 4px 0;"><?= htmlspecialchars($r['nama_nasabah']) ?></div>
                    <div style="font-size: 16px; color: #2563eb; font-weight: bold;">Rp <?= number_format($r['saldo'], 0, ',', '.') ?></div>
                </div>
            <?php endforeach; ?>
        </div>

        <form method="POST" style="margin-top: 20px; background: #fafafa; padding: 16px; border-radius: 8px; border: 1px solid #e2e8f0;">
            <h4 style="margin-top: 0;">Formulir Transfer Saldo</h4>
            <div class="form-row">
                <label>Rekening Pengirim (Asal):</label>
                <select name="rek_asal">
                    <option value="REK-001">REK-001 (Fajar Pratama)</option>
                    <option value="REK-002">REK-002 (Gita Gutawa)</option>
                </select>
            </div>
            <div class="form-row">
                <label>Rekening Penerima (Tujuan):</label>
                <select name="rek_tujuan">
                    <option value="REK-002">REK-002 (Gita Gutawa)</option>
                    <option value="REK-001">REK-001 (Fajar Pratama)</option>
                </select>
            </div>
            <div class="form-row">
                <label>Nominal Transfer (Rp):</label>
                <input type="number" name="nominal" min="1000" step="1000" value="100000" required>
            </div>
            <button type="submit">Kirim Dana via Transaksi Database</button>
        </form>
    </div>

</body>
</html>
