-- =====================================================
-- MODUL 8 — SQL SETUP: Tabel Users
-- File: database/setup_auth.sql
--
-- Cara menjalankan:
-- C:\xampp\mysql\bin\mysql.exe -u root -p
-- Kemudian: SOURCE C:\xampp\htdocs\praktikum_8\database\setup_auth.sql
--
-- ATAU copy-paste satu per satu ke MySQL CLI
-- =====================================================

USE praktikum_7;

-- Buat tabel users
CREATE TABLE IF NOT EXISTS users (
    id       INT          AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50)  NOT NULL UNIQUE,
    nama     VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL COMMENT 'Selalu simpan hash, BUKAN plaintext!',
    role     ENUM('admin', 'mahasiswa') NOT NULL DEFAULT 'mahasiswa',
    dibuat   TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =====================================================
-- PENTING: Password di bawah adalah CONTOH HASH
-- 
-- Password "admin123" di-hash dengan PHP:
-- password_hash("admin123", PASSWORD_DEFAULT)
-- 
-- Hash ini valid HANYA untuk verifikasi.
-- Setiap kali password_hash() dipanggil, hasilnya berbeda,
-- tapi password_verify() tetap bisa memverifikasinya.
-- 
-- Untuk membuat hash sendiri, buat file PHP sementara:
-- <?php echo password_hash("passwordmu", PASSWORD_DEFAULT); ?>
-- =====================================================

-- Hapus data lama jika ada
DELETE FROM users WHERE username IN ('admin', 'budi', 'siti');

-- Masukkan user demo
-- CATATAN: Ganti nilai password di bawah dengan hash dari PHP!
-- Buat hash di: http://localhost/buat_hash.php (lihat Week8.md Langkah 1)
INSERT INTO users (username, nama, password, role) VALUES
('admin', 'Administrator',
 '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
 'admin'),
('budi', 'Budi Santoso',
 '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
 'mahasiswa'),
('siti', 'Siti Rahayu',
 '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
 'mahasiswa');

-- PERINGATAN: Hash di atas adalah contoh (password: "password").
-- WAJIB diganti dengan hash buatan sendiri sebelum digunakan!

-- Verifikasi
SELECT id, username, nama, role, dibuat FROM users;
