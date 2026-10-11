<?php
/**
 * Latihan 1: Ekstraksi & Normalisasi Input
 * Modul Praktikum Minggu 14
 */

require_once __DIR__ . '/Input.php';

// Simulasi Request Data yang memiliki spasi liar
$mockRequest = [
    'nama' => '   Ahmad Dahlan Putra   ',
    'nim' => '  10231001  ',
    'catatan' => '   Mengikuti lokakarya backend programming.   '
];

$input = new Input($mockRequest);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Latihan 1 - Class Input</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f8fafc; padding: 30px; }
        .card { max-width: 600px; margin: 0 auto; background: white; border-radius: 12px; padding: 24px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
        .row { padding: 8px 0; border-bottom: 1px solid #f1f5f9; font-size: 14px; }
    </style>
</head>
<body>

    <div class="card">
        <h2>Demonstrasi Normalisasi Class Input</h2>
        <p style="color: #64748b; font-size: 14px;">Seluruh string otomatis di-trim tanpa mengubah tipe data asli.</p>

        <div class="row">
            <strong>Nama (Ternormalisasi):</strong> <code>"<?= htmlspecialchars($input->get('nama')) ?>"</code>
        </div>
        <div class="row">
            <strong>NIM (Ternormalisasi):</strong> <code>"<?= htmlspecialchars($input->get('nim')) ?>"</code>
        </div>
        <div class="row" style="border: none;">
            <strong>Catatan (Ternormalisasi):</strong> <code>"<?= htmlspecialchars($input->get('catatan')) ?>"</code>
        </div>
    </div>

</body>
</html>
