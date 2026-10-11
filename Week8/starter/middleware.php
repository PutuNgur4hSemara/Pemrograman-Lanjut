<?php
// =====================================================
// MODUL 8 — MIDDLEWARE
// File: middleware.php
// Disertakan di setiap halaman yang perlu perlindungan
// =====================================================

// Pastikan session sudah dimulai
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Pastikan pengguna sudah login.
 * Jika belum, redirect ke halaman login.
 */
function harus_login(): void {
    if (!isset($_SESSION['user_id'])) {
        // Simpan URL yang ingin diakses — berguna untuk redirect setelah login
        $_SESSION['redirect_setelah_login'] = $_SERVER['REQUEST_URI'];
        header('Location: index.php');
        exit;
    }
}

/**
 * Pastikan pengguna memiliki role tertentu.
 * Jika tidak, tampilkan halaman akses ditolak.
 * 
 * @param string $role Role yang dibutuhkan: 'admin' atau 'mahasiswa'
 */
function harus_role(string $role): void {
    harus_login(); // Cek login dulu

    if ($_SESSION['role'] !== $role) {
        // Tampilkan pesan akses ditolak
        http_response_code(403);
        ?>
        <!DOCTYPE html>
        <html lang="id">
        <head>
            <meta charset="UTF-8">
            <title>403 — Akses Ditolak</title>
            <style>
                body { font-family: Arial, sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; background: #f4f6f9; margin: 0; }
                .box { text-align: center; background: white; padding: 40px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,.1); }
                h1 { color: #e74c3c; font-size: 64px; margin: 0; }
                h2 { color: #2c3e50; margin: 10px 0; }
                p  { color: #777; margin-bottom: 20px; }
                a  { background: #3498db; color: white; padding: 10px 20px; border-radius: 6px; text-decoration: none; }
            </style>
        </head>
        <body>
            <div class="box">
                <h1>🚫</h1>
                <h2>Akses Ditolak</h2>
                <p>Kamu tidak memiliki izin untuk mengakses halaman ini.<br>
                   Halaman ini hanya untuk role: <strong><?= htmlspecialchars($role) ?></strong></p>
                <a href="dashboard.php">← Kembali ke Dashboard</a>
            </div>
        </body>
        </html>
        <?php
        exit;
    }
}

/**
 * Ambil data user yang sedang login dari session
 * 
 * @return array Data user: id, nama, role
 */
function user_login(): array {
    return [
        'id'   => $_SESSION['user_id'] ?? null,
        'nama' => $_SESSION['nama']    ?? '',
        'role' => $_SESSION['role']    ?? '',
    ];
}

/**
 * Cek apakah user yang login adalah admin
 * 
 * @return bool
 */
function adalah_admin(): bool {
    return ($_SESSION['role'] ?? '') === 'admin';
}
