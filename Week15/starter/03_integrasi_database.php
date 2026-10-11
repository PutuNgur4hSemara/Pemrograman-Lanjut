<?php
/**
 * Latihan 3: Integrasi End-to-End (Input -> Validator -> Model -> Database)
 * Modul Praktikum Minggu 15
 */

require_once __DIR__ . '/Validator.php';
require_once __DIR__ . '/Peserta.php';

$pdo = new PDO("mysql:host=127.0.0.1;dbname=pemrograman_lanjut;charset=utf8mb4", "root", "", [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

$input = new Input($_POST);
$validator = new Validator();
$pesanSukses = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rules = [
        'nama'  => ['required', 'min:3'],
        'email' => ['required', 'email'],
        'level' => ['required', 'in:dasar,menengah,lanjut'],
        'sesi'  => ['required', 'in:Pagi,Siang'],
        'topik' => ['required']
    ];

    $validator->validate($input, $rules);

    if ($validator->passes()) {
        try {
            // 1. Instansiasi Domain Model (Hanya dibuat setelah data terbukti valid)
            $peserta = new PesertaLokakarya(
                $input->get('nama'),
                $input->get('email'),
                $input->get('level'),
                $input->get('sesi'),
                (array)$input->get('topik', [])
            );

            // 2. Simpan ke Database menggunakan Prepared Statement
            $stmt = $pdo->prepare("INSERT INTO peserta_lokakarya (nama, email, level, sesi, topik_minat) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([
                $peserta->getNama(),
                $peserta->getEmail(),
                $peserta->getLevel(),
                $peserta->getSesi(),
                $peserta->getTopikMinatString()
            ]);

            $pesanSukses = "Pendaftaran atas nama {$peserta->getNama()} BERHASIL disimpan ke database!";
        } catch (PDOException $e) {
            $validator->validate($input, []); // kosongkan
            $errorMessage = str_contains($e->getMessage(), 'Duplicate') ? "Alamat email ini sudah terdaftar sebelumnya." : $e->getMessage();
        }
    }
}

// Ambil daftar peserta terdaftar
$daftarPeserta = $pdo->query("SELECT nama, email, level, sesi, topik_minat, created_at FROM peserta_lokakarya ORDER BY id DESC LIMIT 5")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Latihan 3 - Integrasi End-to-End Database</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f8fafc; padding: 30px; }
        .container { max-width: 900px; margin: 0 auto; }
        .card { background: white; border-radius: 12px; padding: 24px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); margin-bottom: 24px; }
        .alert-ok { background: #dcfce7; color: #166534; padding: 12px; border-radius: 6px; margin-bottom: 16px; }
        .alert-err { background: #fee2e2; color: #991b1b; padding: 12px; border-radius: 6px; margin-bottom: 16px; }
        .form-group { margin-bottom: 14px; }
        label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 4px; color: #475569; }
        input[type="text"], select { width: 100%; box-sizing: border-box; padding: 9px 12px; border: 1px solid #cbd5e1; border-radius: 6px; }
        button { background: #16a34a; color: white; border: none; padding: 10px 18px; border-radius: 6px; cursor: pointer; font-weight: 600; width: 100%; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; font-size: 14px; }
        th, td { padding: 10px 12px; border-bottom: 1px solid #e2e8f0; text-align: left; }
        th { background: #0f172a; color: white; }
    </style>
</head>
<body>

<div class="container">
    <div class="card">
        <h2>Pendaftaran Peserta Lokakarya (Arsitektur Lengkap)</h2>

        <?php if ($pesanSukses): ?>
            <div class="alert-ok">✓ <?= htmlspecialchars($pesanSukses) ?></div>
        <?php endif; ?>

        <?php if (isset($errorMessage)): ?>
            <div class="alert-err">❌ <?= htmlspecialchars($errorMessage) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Nama Lengkap:</label>
                <input type="text" name="nama" value="<?= htmlspecialchars($input->get('nama', '')) ?>" required>
            </div>
            <div class="form-group">
                <label>Alamat Email:</label>
                <input type="text" name="email" value="<?= htmlspecialchars($input->get('email', '')) ?>" required>
            </div>
            <div class="form-group">
                <label>Tingkat Kemampuan:</label>
                <select name="level" required>
                    <option value="dasar" <?= $input->get('level') === 'dasar' ? 'selected' : '' ?>>Dasar</option>
                    <option value="menengah" <?= $input->get('level') === 'menengah' ? 'selected' : '' ?>>Menengah</option>
                    <option value="lanjut" <?= $input->get('level') === 'lanjut' ? 'selected' : '' ?>>Lanjut</option>
                </select>
            </div>
            <div class="form-group">
                <label>Pilihan Sesi:</label>
                <select name="sesi" required>
                    <option value="Pagi" <?= $input->get('sesi') === 'Pagi' ? 'selected' : '' ?>>Sesi Pagi</option>
                    <option value="Siang" <?= $input->get('sesi') === 'Siang' ? 'selected' : '' ?>>Sesi Siang</option>
                </select>
            </div>
            <div class="form-group">
                <label>Pilihan Topik:</label>
                <label><input type="checkbox" name="topik[]" value="PBO Modern" checked> PBO Modern & Clean Code</label>
                <label><input type="checkbox" name="topik[]" value="Database Security" checked> Database Security</label>
            </div>
            <button type="submit">Daftar Sekarang</button>
        </form>
    </div>

    <div class="card">
        <h3>5 Peserta Lokakarya Terbaru (Database Record)</h3>
        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Level</th>
                    <th>Sesi</th>
                    <th>Topik Minat</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($daftarPeserta as $p): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($p['nama']) ?></strong></td>
                        <td><?= htmlspecialchars($p['email']) ?></td>
                        <td><?= htmlspecialchars($p['level']) ?></td>
                        <td><?= htmlspecialchars($p['sesi']) ?></td>
                        <td><?= htmlspecialchars($p['topik_minat']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
