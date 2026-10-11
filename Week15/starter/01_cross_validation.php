<?php
/**
 * Latihan 1: Validasi Lintas-Field (same) & Pilihan Terbatas (in)
 * Modul Praktikum Minggu 15
 */

require_once __DIR__ . '/Validator.php';

$input = new Input($_POST);
$validator = new Validator();
$sukses = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rules = [
        'email' => ['required', 'email'],
        'konfirmasi_email' => ['required', 'same:email'],
        'level' => ['required', 'in:dasar,menengah,lanjut']
    ];

    $validator->validate($input, $rules);
    if ($validator->passes()) {
        $sukses = true;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Latihan 1 - Validasi Lintas-Field</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f8fafc; padding: 30px; }
        .card { max-width: 500px; margin: 0 auto; background: white; border-radius: 12px; padding: 24px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 14px; }
        label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 4px; color: #475569; }
        input, select { width: 100%; box-sizing: border-box; padding: 9px 12px; border: 1px solid #cbd5e1; border-radius: 6px; }
        .input-error { border-color: #ef4444; background: #fff5f5; }
        .error-text { color: #dc2626; font-size: 12px; margin-top: 4px; }
        button { background: #2563eb; color: white; border: none; padding: 10px 18px; border-radius: 6px; cursor: pointer; font-weight: 600; width: 100%; }
        .alert-ok { background: #dcfce7; color: #166534; padding: 12px; border-radius: 6px; margin-bottom: 16px; }
    </style>
</head>
<body>

<div class="card">
    <h2>Validasi Lintas-Field (same:email)</h2>

    <?php if ($sukses): ?>
        <div class="alert-ok">✓ Email dan konfirmasi cocok! Level pilihan valid.</div>
    <?php endif; ?>

    <form method="POST">
        <div class="form-group">
            <label>Alamat Email:</label>
            <input type="text" name="email" value="<?= htmlspecialchars($input->get('email', '')) ?>" class="<?= $validator->hasError('email') ? 'input-error' : '' ?>">
            <?php if ($validator->hasError('email')): ?>
                <div class="error-text"><?= $validator->getFirstError('email') ?></div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label>Ulangi Alamat Email (Konfirmasi):</label>
            <input type="text" name="konfirmasi_email" value="<?= htmlspecialchars($input->get('konfirmasi_email', '')) ?>" class="<?= $validator->hasError('konfirmasi_email') ? 'input-error' : '' ?>">
            <?php if ($validator->hasError('konfirmasi_email')): ?>
                <div class="error-text"><?= $validator->getFirstError('konfirmasi_email') ?></div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label>Tingkat Kemampuan (in:dasar,menengah,lanjut):</label>
            <select name="level" class="<?= $validator->hasError('level') ? 'input-error' : '' ?>">
                <option value="">-- Pilih Level --</option>
                <option value="dasar" <?= $input->get('level') === 'dasar' ? 'selected' : '' ?>>Tingkat Dasar (Beginner)</option>
                <option value="menengah" <?= $input->get('level') === 'menengah' ? 'selected' : '' ?>>Tingkat Menengah (Intermediate)</option>
                <option value="lanjut" <?= $input->get('level') === 'lanjut' ? 'selected' : '' ?>>Tingkat Lanjut (Advanced)</option>
            </select>
            <?php if ($validator->hasError('level')): ?>
                <div class="error-text"><?= $validator->getFirstError('level') ?></div>
            <?php endif; ?>
        </div>

        <button type="submit">Verifikasi Input</button>
    </form>
</div>

</body>
</html>
