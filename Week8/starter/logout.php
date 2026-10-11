<?php
// =====================================================
// MODUL 8 — LOGOUT
// File: logout.php
// =====================================================

// Wajib panggil session_start() sebelum apapun yang berkaitan dengan session
session_start();

// Hapus SEMUA data session
$_SESSION = [];

// Hapus cookie session (opsional, tapi lebih bersih)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(), '',
        time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Hancurkan session di server
session_destroy();

// Redirect ke halaman login dengan pesan
header('Location: login.php?logout=1');
exit;
