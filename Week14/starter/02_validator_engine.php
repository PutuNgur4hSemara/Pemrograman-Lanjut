<?php
/**
 * Latihan 2: Pengujian Rule Engine & Error Bag Display
 * Modul Praktikum Minggu 14
 */

require_once __DIR__ . '/Input.php';
require_once __DIR__ . '/Validator.php';

$input = new Input($_POST);
$validator = new Validator();
$pesanSukses = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rules = [
        'nama'         => ['required', 'min:3', 'max:50'],
        'email'        => ['required', 'email'],
        'usia'         => ['required', 'integer', 'between:17,65'],
        'jumlah_tiket' => ['required', 'integer', 'between:1,4']
    ];

    $validator->validate($input, $rules);

    if ($validator->passes()) {
        $pesanSukses = true;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Latihan 2 - Validator Engine</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f8fafc; padding: 30px; }
        .card { max-width: 550px; margin: 0 auto; background: white; border-radius: 12px; padding: 24px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 14px; }
        label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 4px; color: #475569; }
        input { width: 100%; box-sizing: border-box; padding: 9px 12px; border: 1px solid #cbd5e1; border-radius: 6px; }
        .input-error { border-color: #ef4444; background: #fff5f5; }
        .error-text { color: #dc2626; font-size: 12px; margin-top: 4px; }
        button { background: #2563eb; color: white; border: none; padding: 10px 18px; border-radius: 6px; cursor: pointer; font-weight: 600; width: 100%; }
        .success-box { background: #dcfce7; color: #166534; padding: 12px; border-radius: 6px; margin-bottom: 16px; font-size: 14px; }
    </style>
</head>
<body>

<div class="card">
    <h2>Pendaftaran Lokakarya (Validator Rule Engine)</h2>

    <?php if ($pesanSukses): ?>
        <div class="success-box">
            ✓ Data pendaftaran valid dan siap diproses ke database!
        </div>
    <?php endif; ?>

    <form method="POST">
        <div class="form-group">
            <label>Nama Lengkap:</label>
            <input type="text" name="nama" value="<?= htmlspecialchars($input->get('nama', '')) ?>" class="<?= $validator->hasError('nama') ? 'input-error' : '' ?>">
            <?php if ($validator->hasError('nama')): ?>
                <div class="error-text"><?= $validator->getFirstError('nama') ?></div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label>Alamat Email:</label>
            <input type="text" name="email" value="<?= htmlspecialchars($input->get('email', '')) ?>" class="<?= $validator->hasError('email') ? 'input-error' : '' ?>">
            <?php if ($validator->hasError('email')): ?>
                <div class="error-text"><?= $validator->getFirstError('email') ?></div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label>Usia (17 - 65 tahun):</label>
            <input type="number" name="usia" value="<?= htmlspecialchars($input->get('usia', '')) ?>" class="<?= $validator->hasError('usia') ? 'input-error' : '' ?>">
            <?php if ($validator->hasError('usia')): ?>
                <div class="error-text"><?= $validator->getFirstError('usia') ?></div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label>Jumlah Tiket (Maksimal 4):</label>
            <input type="number" name="jumlah_tiket" value="<?= htmlspecialchars($input->get('jumlah_tiket', '1')) ?>" class="<?= $validator->hasError('jumlah_tiket') ? 'input-error' : '' ?>">
            <?php if ($validator->hasError('jumlah_tiket')): ?>
                <div class="error-text"><?= $validator->getFirstError('jumlah_tiket') ?></div>
            <?php endif; ?>
        </div>

        <button type="submit">Kirim Pendaftaran</button>
    </form>
</div>

</body>
</html>
