<?php
/**
 * Latihan 2: Komponen Form Lengkap & Repopulasi Sticky Form
 * Modul Praktikum Minggu 15
 */

require_once __DIR__ . '/Validator.php';

$input = new Input($_POST);
$validator = new Validator();
$topikPilihan = (array)$input->get('topik', []);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $validator->validate($input, [
        'nama'  => ['required', 'min:3'],
        'sesi'  => ['required', 'in:Pagi,Siang'],
        'topik' => ['required']
    ]);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Latihan 2 - Sticky Form Components</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f8fafc; padding: 30px; }
        .card { max-width: 550px; margin: 0 auto; background: white; border-radius: 12px; padding: 24px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 16px; }
        label.title { display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px; color: #475569; }
        input[type="text"] { width: 100%; box-sizing: border-box; padding: 9px 12px; border: 1px solid #cbd5e1; border-radius: 6px; }
        .radio-group, .checkbox-group { display: flex; gap: 16px; margin-top: 4px; font-size: 14px; }
        .error-text { color: #dc2626; font-size: 12px; margin-top: 4px; }
        button { background: #2563eb; color: white; border: none; padding: 10px 18px; border-radius: 6px; cursor: pointer; font-weight: 600; width: 100%; margin-top: 10px; }
    </style>
</head>
<body>

<div class="card">
    <h2>Repopulasi Komponen Form (Sticky State)</h2>
    <p style="color: #64748b; font-size: 13px; margin-bottom: 16px;">Nilai pilihan radio dan checkbox tidak akan hilang saat terjadi error validasi.</p>

    <form method="POST">
        <div class="form-group">
            <label class="title">Nama Lengkap:</label>
            <input type="text" name="nama" value="<?= htmlspecialchars($input->get('nama', '')) ?>">
            <?php if ($validator->hasError('nama')): ?>
                <div class="error-text"><?= $validator->getFirstError('nama') ?></div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label class="title">Pilihan Sesi (Radio Button):</label>
            <div class="radio-group">
                <label><input type="radio" name="sesi" value="Pagi" <?= $input->get('sesi') === 'Pagi' ? 'checked' : '' ?>> Sesi Pagi (09.00 - 12.00)</label>
                <label><input type="radio" name="sesi" value="Siang" <?= $input->get('sesi') === 'Siang' ? 'checked' : '' ?>> Sesi Siang (13.00 - 16.00)</label>
            </div>
            <?php if ($validator->hasError('sesi')): ?>
                <div class="error-text"><?= $validator->getFirstError('sesi') ?></div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label class="title">Peminatan Topik (Checkbox Array):</label>
            <div class="checkbox-group">
                <label><input type="checkbox" name="topik[]" value="Clean Code" <?= in_array('Clean Code', $topikPilihan) ? 'checked' : '' ?>> Clean Code</label>
                <label><input type="checkbox" name="topik[]" value="Query Builder" <?= in_array('Query Builder', $topikPilihan) ? 'checked' : '' ?>> Query Builder</label>
                <label><input type="checkbox" name="topik[]" value="Security" <?= in_array('Security', $topikPilihan) ? 'checked' : '' ?>> Security</label>
            </div>
            <?php if ($validator->hasError('topik')): ?>
                <div class="error-text"><?= $validator->getFirstError('topik') ?></div>
            <?php endif; ?>
        </div>

        <button type="submit">Submit Formulir</button>
    </form>
</div>

</body>
</html>
