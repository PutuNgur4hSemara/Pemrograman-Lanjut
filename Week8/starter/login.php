<?php
// =====================================================
// MODUL 8 — HALAMAN LOGIN
// File: login.php
// =====================================================

// Jangan mulai session lagi jika sudah dimulai
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Jika sudah login, arahkan ke dashboard
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

// Konfigurasi database
$db_config = [
    'host' => 'localhost',
    'db'   => 'praktikum_7', // Gunakan database yang sama dengan Week 7
    'user' => 'root',
    'pass' => '',
];

$errors   = [];
$username = "";
$pesan_logout = "";

// Cek apakah baru saja logout
if (isset($_GET['logout'])) {
    $pesan_logout = "Kamu berhasil logout. Sampai jumpa!";
}

// Proses form login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Validasi wajib isi
    if (empty($username)) $errors['username'] = 'Username wajib diisi.';
    if (empty($password)) $errors['password'] = 'Password wajib diisi.';

    if (empty($errors)) {
        try {
            // Buat koneksi PDO
            $pdo = new PDO(
                "mysql:host={$db_config['host']};dbname={$db_config['db']};charset=utf8",
                $db_config['user'],
                $db_config['pass'],
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
            );

            // Cari user berdasarkan username
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch();

            // Verifikasi: user ada DAN password cocok
            // Catatan: JANGAN bedakan "username salah" vs "password salah"
            //          (mengurangi risiko user enumeration attack)
            if ($user && password_verify($password, $user['password'])) {
                // ✅ LOGIN BERHASIL!

                // Simpan info minimal ke session (jangan simpan password!)
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['nama']    = $user['nama'];
                $_SESSION['role']    = $user['role'];

                // Redirect ke dashboard (atau halaman yang tadi mau diakses)
                $redirect = $_SESSION['redirect_setelah_login'] ?? 'dashboard.php';
                unset($_SESSION['redirect_setelah_login']); // Hapus setelah dipakai
                header("Location: $redirect");
                exit;

            } else {
                // ❌ USERNAME ATAU PASSWORD SALAH
                $errors['login'] = 'Username atau password salah.';
            }

        } catch (PDOException $e) {
            $errors['login'] = 'Gagal terhubung ke database. Pastikan XAMPP MySQL berjalan.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Sistem Informasi Akademik</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 400px;
        }
        .login-card h1 { color: #2c3e50; font-size: 22px; margin-bottom: 5px; }
        .login-card p.sub { color: #777; font-size: 13px; margin-bottom: 25px; }
        
        .alert { padding: 12px; border-radius: 6px; margin-bottom: 20px; font-size: 14px; }
        .alert-sukses { background: #d5f5e3; color: #1e8449; border: 1px solid #27ae60; }
        .alert-error  { background: #fdecea; color: #c0392b; border: 1px solid #e74c3c; }
        
        .form-group { margin-bottom: 18px; }
        .form-group label { display: block; font-size: 13px; font-weight: bold; color: #555; margin-bottom: 5px; }
        .form-group input {
            width: 100%; padding: 11px 14px;
            border: 1px solid #ddd; border-radius: 6px;
            font-size: 15px; transition: border-color 0.2s;
        }
        .form-group input:focus { outline: none; border-color: #3498db; box-shadow: 0 0 0 3px rgba(52,152,219,.15); }
        .form-group input.error { border-color: #e74c3c; }
        .pesan-error { color: #e74c3c; font-size: 12px; margin-top: 4px; display: block; }
        
        .tombol-login {
            width: 100%; background: #3498db; color: white;
            border: none; padding: 13px; border-radius: 6px;
            font-size: 16px; font-weight: bold; cursor: pointer;
            transition: background 0.2s;
        }
        .tombol-login:hover { background: #2980b9; }
        
        .info-akun { margin-top: 20px; background: #f8f9fa; padding: 12px; border-radius: 6px; font-size: 12px; color: #555; }
        .info-akun strong { display: block; margin-bottom: 5px; }
    </style>
</head>
<body>

<div class="login-card">
    <h1>🔐 Login</h1>
    <p class="sub">Sistem Informasi Akademik — Mahasiswa SI</p>

    <!-- Pesan setelah logout -->
    <?php if ($pesan_logout): ?>
        <div class="alert alert-sukses"><?= htmlspecialchars($pesan_logout) ?></div>
    <?php endif; ?>

    <!-- Pesan error login -->
    <?php if (isset($errors['login'])): ?>
        <div class="alert alert-error">⚠ <?= htmlspecialchars($errors['login']) ?></div>
    <?php endif; ?>

    <form method="POST" action="login.php">

        <div class="form-group">
            <label for="username">Username</label>
            <input type="text"
                   id="username"
                   name="username"
                   value="<?= htmlspecialchars($username) ?>"
                   class="<?= isset($errors['username']) ? 'error' : '' ?>"
                   placeholder="Masukkan username"
                   autocomplete="username">
            <?php if (isset($errors['username'])): ?>
                <span class="pesan-error">⚠ <?= htmlspecialchars($errors['username']) ?></span>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password"
                   id="password"
                   name="password"
                   class="<?= isset($errors['password']) ? 'error' : '' ?>"
                   placeholder="Masukkan password"
                   autocomplete="current-password">
            <?php if (isset($errors['password'])): ?>
                <span class="pesan-error">⚠ <?= htmlspecialchars($errors['password']) ?></span>
            <?php endif; ?>
        </div>

        <button type="submit" class="tombol-login">Masuk →</button>

    </form>

    <!-- Info akun demo — hapus ini di production! -->
    <div class="info-akun">
        <strong>📋 Akun untuk latihan:</strong>
        Admin: <code>admin</code> / <code>admin123</code><br>
        Mahasiswa: <code>budi</code> / <code>mhs123</code>
    </div>
</div>

</body>
</html>
