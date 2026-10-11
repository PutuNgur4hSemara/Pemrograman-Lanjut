# Modul Praktikum 5 — Exception Handling & Pengantar MySQLi Object

**Mata Kuliah:** Pemrograman Lanjut (3 SKS)  
**Pertemuan:** Minggu Ke-5  
**Durasi:** 1 × 120 Menit  
**Prasyarat:** Modul Praktikum 3 (Encapsulation) & Modul 4 (Inheritance/Interface)  

---

## I. Sub-CPMK & Tujuan Pembelajaran

### Sub-CPMK (Minggu 5)
> **Mahasiswa mampu mengaplikasikan exception handling dan MySQLi Object (C3, A4, P2).**

### Tujuan Praktikum
Setelah menyelesaikan sesi praktikum ini, mahasiswa diharapkan mampu:
1. Memahami perbedaan antara kesalahan fatal (*fatal error*) dan eksepsi berbasis objek (*Object-Oriented Exception*).
2. Menggunakan struktur kontrol **`try`**, **`catch`**, dan **`finally`** untuk mengendalikan alur program saat terjadi kesalahan.
3. Melemparkan eksepsi secara eksplisit menggunakan kata kunci **`throw`**.
4. Membuat dan menggunakan **Custom Exception** turunan dari class `Exception` / `RuntimeException`.
5. Menginisialisasi koneksi database MySQL menggunakan pendekatan berorientasi objek (**`new mysqli(...)`**).
6. Mengaktifkan mode pelaporan ketat dengan `mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT)`.
7. Menangkap **`mysqli_sql_exception`** untuk menjaga keamanan data rahasia dari publik.

---

## II. Dasar Teori

### 1. Mengapa Exception Handling Sangat Penting?

Pada kode konvensional, penanganan error sering dilakukan dengan `die()` atau `exit()`. Cara ini sangat buruk karena:
- Menghentikan seluruh aplikasi secara mendadak.
- Tampilan web menjadi rusak (*blank white screen* atau pesan error acak).
- Sering membocorkan informasi sensitif (nama host database, password, atau struktur tabel) kepada peretas.

Dengan **Exception Handling**, kesalahan diperlakukan sebagai **Objek**. Jika terjadi masalah di suatu baris kode, objek exception akan "dilempar" (*thrown*) dan dapat "ditangkap" (*caught*) secara anggun tanpa mematikan aplikasi.

```
[ Blok TRY ] ──────────( Ada Kesalahan! )──────────> [ THROW new Exception ]
      │                                                         │
(Berjalan Mulus)                                                v
      │                                                [ Blok CATCH ]
      │                                         (Tangani error, catat ke log,
      │                                         tampilkan pesan ramah ke user)
      │                                                         │
      └───────────────────────┬─────────────────────────────────┘
                              v
                      [ Blok FINALLY ]
             (Selalu dijalankan, misal: tutup koneksi)
```

---

### 2. Sintaks Dasar: `try`, `catch`, `finally`

```php
function bagi(float $pembilang, float $penyebut): float
{
    if ($penyebut == 0.0) {
        throw new InvalidArgumentException("Pembagi tidak boleh bernilai nol.");
    }
    return $pembilang / $penyebut;
}

try {
    echo bagi(100, 0);
} catch (InvalidArgumentException $e) {
    echo "Peringatan: " . $e->getMessage();
} finally {
    // Blok ini PASTI dieksekusi, baik terjadi error maupun tidak
    echo "<br>Kalkulasi selesai diproses.";
}
```

---

### 3. Membuat Custom Exception

Kita dapat membuat tipe exception khusus dengan mewarisi class `Exception` untuk mengelompokkan jenis error domain tertentu:

```php
class SaldoTidakCukupException extends Exception
{
    public function __construct(float $saldoSaatIni, float $nominalTarik)
    {
        $pesan = "Penarikan Rp " . number_format($nominalTarik, 0, ',', '.') . 
                 " gagal! Saldo Anda hanya Rp " . number_format($saldoSaatIni, 0, ',', '.') . ".";
        parent::__construct($pesan);
    }
}
```

---

### 4. Pengantar Koneksi Database: MySQLi Berbasis Objek

PHP menyediakan ekstensi MySQLi (*MySQL Improved*) dalam dua paradigma: prosedural (`mysqli_connect`) dan berorientasi objek (`new mysqli()`). Pada mata kuliah ini, kita wajib menggunakan pendekatan **berorientasi objek murni**.

#### Mengaktifkan Strict Exception Reporting
Secara default pada PHP versi lama, kegagalan koneksi mysqli hanya memicu PHP Warning. Agar error database otomatis melempar `mysqli_sql_exception`, kita aktifkan strict mode:

```php
// Wajib diletakkan sebelum inisialisasi koneksi
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $dbHost = '127.0.0.1';
    $dbUser = 'root';
    $dbPass = '';
    $dbName = 'pemrograman_lanjut';

    // Inisialisasi Objek MySQLi
    $db = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
    
    // Set charset UTF-8
    $db->set_charset('utf8mb4');

    echo "Koneksi ke database berhasil!";
} catch (mysqli_sql_exception $e) {
    // 1. Catat detail teknis asli ke server log (AMAN)
    error_log("DB Error: " . $e->getMessage());

    // 2. Berikan pesan santun dan aman kepada pengunjung web
    echo "Maaf, saat ini sistem kami sedang dalam pemeliharaan basis data.";
}
```

---

## III. Langkah-Langkah Praktikum

Buka folder `Week5/starter/` pada teks editor Anda:

### Latihan 1: Exception Dasar & Blok Finally
Buka `01_try_catch_finally.php`:
1. Amati cara kerja melempar exception saat pembagian dengan nol atau validasi format gagal.
2. Amati bagaimana blok `finally` selalu berjalan untuk proses *clean-up*.

### Latihan 2: Custom Domain Exception
Buka `02_custom_exception.php`:
1. Buat custom exception `StokHabisException` dan `KapasitasPenuhException`.
2. Uji alur bisnis saat stok gudang tidak mencukupi permintaan transaksi.

### Latihan 3: Pengantar Koneksi MySQLi Object
Buka `03_koneksi_mysqli.php`:
1. Konfigurasi kredensial koneksi MySQL lokal (XAMPP: host `127.0.0.1`, user `root`, pass kosong).
2. Simulasikan error koneksi (sengaja salah nama database atau password) untuk melihat bagaimana `mysqli_sql_exception` ditangkap dengan aman.

---

## IV. Tugas Praktik Mandiri

Buat file baru `latihan_mandiri.php` di dalam folder `Week5/starter/`:

1. **Skenario Sistem Reservasi Tiket Konser**:
   - Buat custom exception:
     - `TiketHabisException`
     - `BatasBeliMelebihiMaksimalException` (maksimal beli 4 tiket per orang)
   - Buat class `PemesananTiket`:
     - Properti: `$kategori`, `$stokTersedia`, `$hargaSatuan`.
     - Method `pesan(string $namaPemesan, int $jumlahTiket): float` yang memvalidasi jumlah tiket dan melempar custom exception jika melanggar batas atau stok kurang.
2. Tangkap exception pada antarmuka web, tampilkan box error jika gagal, atau box sukses jika reservasi berhasil.

---

## V. Checklist Pemahaman Praktikum

| No | Indikator Ketercapaian | Status |
|:--:|---|:---:|
| 1 | Saya memahami perbedaan error fatal vs exception berbasis objek | [ ] |
| 2 | Saya mampu menuliskan blok `try-catch-finally` | [ ] |
| 3 | Saya mampu melempar exception menggunakan kata kunci `throw` | [ ] |
| 4 | Saya mampu membuat class Custom Exception sendiri | [ ] |
| 5 | Saya memahami cara membuat koneksi database dengan `new mysqli()` | [ ] |
| 6 | Saya memahami pentingnya `mysqli_report` untuk strict error handling | [ ] |
| 7 | Saya memahami pemisahan antara `error_log()` dan pesan publik pengguna | [ ] |
