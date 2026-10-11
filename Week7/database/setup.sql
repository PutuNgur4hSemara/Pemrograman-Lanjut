-- =====================================================
-- MODUL 7 — SQL SETUP SCRIPT
-- File: database/setup.sql
--
-- Cara menjalankan via CLI MySQL:
-- 1. Buka Command Prompt
-- 2. Jalankan: C:\xampp\mysql\bin\mysql.exe -u root -p
-- 3. Salin dan tempelkan perintah SQL ini satu per satu
--    ATAU jalankan: SOURCE C:\path\ke\file\setup.sql
-- =====================================================

-- Buat database baru (abaikan error jika sudah ada)
CREATE DATABASE IF NOT EXISTS praktikum_7
    CHARACTER SET utf8
    COLLATE utf8_unicode_ci;

-- Gunakan database ini
USE praktikum_7;

-- Hapus tabel lama jika ada (untuk reset)
DROP TABLE IF EXISTS mahasiswa;

-- Buat tabel mahasiswa
CREATE TABLE mahasiswa (
    id       INT          AUTO_INCREMENT PRIMARY KEY,
    nim      VARCHAR(20)  NOT NULL UNIQUE COMMENT 'Nomor Induk Mahasiswa, unik',
    nama     VARCHAR(100) NOT NULL,
    jurusan  VARCHAR(100) NOT NULL,
    ipk      DECIMAL(3,2) NOT NULL DEFAULT 0.00,
    dibuat   TIMESTAMP    DEFAULT CURRENT_TIMESTAMP COMMENT 'Waktu data dibuat'
);

-- Isi dengan data awal
INSERT INTO mahasiswa (nim, nama, jurusan, ipk) VALUES
('10231001', 'Agus Ikhsyan',   'Sistem Informasi', 3.75),
('10231002', 'Budi Santoso',   'Sistem Informasi', 3.50),
('10231003', 'Citra Lestari',  'Sistem Informasi', 3.90),
('10231004', 'Dian Pratama',   'Sistem Informasi', 2.85),
('10231005', 'Eka Wulandari',  'Teknik Informatika', 3.10);

-- Verifikasi: tampilkan semua data
SELECT * FROM mahasiswa;

-- Output yang diharapkan:
-- +----+----------+---------------+---------------------+------+---------------------+
-- | id | nim      | nama          | jurusan             | ipk  | dibuat              |
-- +----+----------+---------------+---------------------+------+---------------------+
-- |  1 | 10231001 | Agus Ikhsyan  | Sistem Informasi    | 3.75 | 2025-01-01 00:00:00 |
-- ...
