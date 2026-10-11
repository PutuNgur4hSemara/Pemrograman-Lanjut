# Modul Praktikum 9 — PDO Bagian 1: Pengenalan, Koneksi & Query Dasar

**Mata Kuliah:** Pemrograman Lanjut (3 SKS)  
**Pertemuan:** Minggu Ke-9  
**Durasi:** 1 × 120 Menit  
**Prasyarat:** Modul Praktikum 5 (Exception) & Modul 7 (CRUD MySQLi)  

---

## I. Sub-CPMK & Tujuan Pembelajaran

### Sub-CPMK (Rangkaian Minggu 9–10: Tahap 1)
> **Mahasiswa mampu mengaplikasikan PDO (PHP Data Objects), mengonfigurasi DSN, error handling PDOException, serta menjalankan query dasar dan fetching data (C3, A4, P2).**

### Tujuan Praktikum
Setelah menyelesaikan sesi praktikum ini, mahasiswa diharapkan mampu:
1. Memahami perbedaan mendasar dan keunggulan **PDO** dibandingkan MySQLi (abstraksi multi-database).
2. Membentuk string **DSN (Data Source Name)** yang benar dan menginisialisasi objek `new PDO(...)`.
3. Mengonfigurasi atribut kritis PDO: `ATTR_ERRMODE`, `ATTR_DEFAULT_FETCH_MODE`, dan `ATTR_EMULATE_PREPARES`.
4. Menangkap dan menangani **`PDOException`** secara aman.
5. Menggunakan method **`exec()`** untuk instruksi SQL tanpa result set (menghitung jumlah baris terpengaruh).
6. Menggunakan method **`query()`** untuk membaca data statis.
7. Membedakan mode pengambilan data: **`fetch()`**, **`fetchAll()`**, `FETCH_ASSOC`, dan `FETCH_OBJ`.

---

## II. Dasar Teori

### 1. Apa itu PDO (PHP Data Objects)?

**PDO** adalah lapisan abstraksi akses basis data (*database access abstraction layer*) yang seragam di PHP. Berbeda dengan MySQLi yang khusus untuk MySQL/MariaDB saja, PDO mendukung berbagai sistem database (MySQL, PostgreSQL, SQLite, Oracle, SQL Server) dengan sintaks API PHP yang persis sama.

```
+-----------------------------------------------------------+
|                    APLIKASI PHP ANDA                      |
+-----------------------------------------------------------+
                              │
                    PDO API Interface (Sama)
                              │
                              v
+-----------------------------------------------------------+
|                      PDO EXTENSION                        |
+-----------------------------------------------------------+
          │                   │                   │
  [ PDO_MYSQL Driver ]  [ PDO_PGSQL Driver ]  [ PDO_SQLITE Driver ]
          │                   │                   │
          v                   v                   v
     MySQL Server         PostgreSQL         SQLite File
```

---

### 2. DSN & Konfigurasi Koneksi PDO

Untuk menghubungkan PHP ke basis data via PDO, kita mendefinisikan string **DSN (Data Source Name)**:

```php
$dsn = "mysql:host=127.0.0.1;dbname=pemrograman_lanjut;charset=utf8mb4";
$username = "root";
$password = "";

$options = [
    // Wajib: Lempar PDOException setiap kali terjadi kesalahan SQL
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    // Mengembalikan hasil query sebagai array asosiatif secara default
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    // Matikan emulasi prepare agar menggunakan native prepared statement server database
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $username, $password, $options);
    echo "Koneksi PDO Berhasil!";
} catch (PDOException $e) {
    error_log("PDO Connection Failed: " . $e->getMessage());
    echo "Koneksi basis data gagal. Silakan hubungi administrator.";
}
```

---

### 3. Eksekusi Perintah: `exec()` vs `query()`

| Method | Karakteristik | Nilai Kembalian | Penggunaan Ideal |
|---|---|---|---|
| **`$pdo->exec($sql)`** | Mengeksekusi SQL tanpa menghasilkan result set. | Jumlah baris (*affected rows*) bertipe `int` | `CREATE TABLE`, `INSERT`, `UPDATE`, `DELETE` tanpa parameter dinamis |
| **`$pdo->query($sql)`** | Mengeksekusi SQL dan mengembalikan result set. | Objek `PDOStatement` | `SELECT` statis tanpa input form dari pengguna |

```php
// Contoh exec():
$jumlahBaris = $pdo->exec("UPDATE koleksi_buku SET status = 'Tersedia'");
echo "Jumlah buku yang diupdate: {$jumlahBaris}";

// Contoh query():
$statement = $pdo->query("SELECT kode_buku, judul, penulis, stok FROM koleksi_buku");
```

---

### 4. Teknik Pengambilan Data (Fetching)

- **`fetch()`**: Mengambil **satu baris** data pada cursor saat ini. Berguna untuk perulangan `while` pada data besar agar hemat memori.
- **`fetchAll()`**: Mengambil **seluruh baris** data sekaligus dan mengembalikannya dalam satu array multidimensi.

```php
// 1. Fetch Satu Per Satu (Hemat Memori):
$stmt = $pdo->query("SELECT judul FROM koleksi_buku");
while ($row = $stmt->fetch()) {
    echo $row['judul'] . "<br>";
}

// 2. FetchAll Sekaligus (Praktis untuk Templating):
$stmt = $pdo->query("SELECT * FROM koleksi_buku");
$semuaBuku = $stmt->fetchAll(); // Array asosiatif berisi semua baris
```

---

## III. Langkah-Langkah Praktikum

Buka folder `Week9/starter/` pada teks editor Anda:

### 1. Inisialisasi Database Perpustakaan
Jalankan file `Week9/database/setup_perpustakaan.sql` untuk membuat tabel `koleksi_buku` dan `anggota_perpustakaan`.

### 2. Latihan 1: Inisialisasi Koneksi & Opsi PDO
Buka `01_koneksi_pdo.php`:
1. Amati pembentukan string DSN dan array opsi atribut.
2. Coba ubah nama database menjadi nama acak untuk melihat bagaimana `PDOException` ditangkap.

### 3. Latihan 2: Eksekusi `exec()` dan Menghitung Affected Rows
Buka `02_exec_dml.php`:
1. Menjalankan skrip DDL/DML sederhana dengan `$pdo->exec()`.
2. Membaca jumlah baris data yang berhasil dimutasi.

### 4. Latihan 3: Query & Fetching (Assoc vs Object)
Buka `03_query_fetch.php`:
1. Mengambil data buku menggunakan `$pdo->query()`.
2. Mencoba mode `PDO::FETCH_ASSOC` dan `PDO::FETCH_OBJ` (`$buku->judul`).

---

## IV. Tugas Praktik Mandiri

Buat file baru `latihan_mandiri.php` di dalam folder `Week9/starter/`:

1. **Skenario Katalog Peminjaman Buku**:
   - Buat file koneksi PDO dengan options lengkap.
   - Buat query untuk menampilkan daftar seluruh anggota perpustakaan dan jumlah buku yang sedang dipinjam.
   - Gunakan `fetchAll(PDO::FETCH_OBJ)` untuk menampilkan data anggota ke dalam antarmuka kartu profil HTML.
   - Tambahkan tombol / aksi yang menjalankan `$pdo->exec()` untuk mereset status penalti anggota jika terlambat mengembalikan buku.

---

## V. Checklist Pemahaman Praktikum

| No | Indikator Ketercapaian | Status |
|:--:|---|:---:|
| 1 | Saya memahami apa itu PDO dan perbedaannya dengan MySQLi | [ ] |
| 2 | Saya mampu merangkai DSN PDO dengan charset UTF-8 | [ ] |
| 3 | Saya memahami pentingnya `ATTR_ERRMODE => ERRMODE_EXCEPTION` | [ ] |
| 4 | Saya mampu membedakan penggunaan `$pdo->exec()` vs `$pdo->query()` | [ ] |
| 5 | Saya memahami cara kerja `fetch()` dan `fetchAll()` | [ ] |
| 6 | Saya memahami perbedaan hasil `FETCH_ASSOC` dan `FETCH_OBJ` | [ ] |
