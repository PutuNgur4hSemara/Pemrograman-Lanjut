# Modul Praktikum 6 — MySQLi Object & Prepared Statement

**Mata Kuliah:** Pemrograman Lanjut (3 SKS)  
**Pertemuan:** Minggu Ke-6  
**Durasi:** 1 × 120 Menit  
**Prasyarat:** Modul Praktikum 5 (Exception & Koneksi MySQLi)  

---

## I. Sub-CPMK & Tujuan Pembelajaran

### Sub-CPMK (Rangkaian Minggu 5–7: Tahap 2)
> **Mahasiswa mampu mengaplikasikan koneksi MySQLi Object, prepared statement, parameter binding, result set, dan exception handling untuk akses database berorientasi objek yang aman (C3, A4, P2).**

### Tujuan Praktikum
Setelah menyelesaikan sesi praktikum ini, mahasiswa diharapkan mampu:
1. Memahami bahaya kerentanan **SQL Injection** yang ditimbulkan oleh penggabungan string query langsung (*string concatenation*).
2. Membuat **Prepared Statement** menggunakan method `$db->prepare()`.
3. Mengikat nilai dinamis secara aman menggunakan method **`bind_param()`** dengan penentu tipe data yang tepat (`s`, `i`, `d`, `b`).
4. Mengeksekusi statement dan membaca data dari **Result Set** menggunakan `get_result()`, `fetch_assoc()`, atau `fetch_all()`.
5. Mencegah kerentanan **XSS (Cross-Site Scripting)** dengan selalu menerapkan **output escaping** (`htmlspecialchars()`).
6. Memahami dan mengoperasikan mekanisme **Database Transaction** (`begin_transaction`, `commit`, `rollback`) pada MySQLi Object.

---

## II. Dasar Teori

### 1. Bahaya Fatal: SQL Injection

SQL Injection terjadi ketika input pengguna yang belum disterilkan digabungkan langsung ke dalam perintah SQL.

```php
// CONTOH KODE SANGAT BERBAHAYA (JANGAN DITIRU!):
$inputNim = $_POST['nim']; // Misal penyerang mengisi: ' OR '1'='1
$sql = "SELECT * FROM mahasiswa WHERE nim = '$inputNim'";
// Query menjadi: SELECT * FROM mahasiswa WHERE nim = '' OR '1'='1'
// Akibatnya: Seluruh data mahasiswa bocor ke publik!
```

---

### 2. Solusi Standar Industri: Prepared Statement

**Prepared Statement** memisahkan antara **struktur logika SQL** dan **nilai data input**.
1. **Prepare**: Server database meng-compile struktur query SQL terlebih dahulu dengan tanda tanya (`?`) sebagai *placeholder* parameter.
2. **Bind**: Nilai input dimasukkan murni sebagai *literal data*, bukan sebagai kode SQL yang bisa dieksekusi.
3. **Execute**: Query dijalankan dengan aman.

```
Logika Query SQL (Dikirim dulu ke Database):
"SELECT nim, nama, ipk FROM mahasiswa WHERE ipk >= ? AND jurusan = ?"
                                                  ^                 ^
Parameter Binding (Dikirim terpisah sebagai data murni):
                                                  │                 │
                                          [ 3.50 (double) ]  [ 'SI' (string) ]
```

---

### 3. Tipe Data Parameter pada `bind_param()`

Format penentu tipe karakter pertama pada `bind_param()`:
- **`s`**: String (teks, varchar, tanggal)
- **`i`**: Integer (angka bulat)
- **`d`**: Double / Float (angka desimal / pecahan)
- **`b`**: Blob (data biner, gambar/dokumen)

```php
$minimumIpk = 3.50;
$prodi = "Sistem Informasi";

$stmt = $db->prepare("SELECT nim, nama, ipk FROM mahasiswa WHERE ipk >= ? AND program_studi = ?");
// 'd' untuk double ($minimumIpk), 's' untuk string ($prodi)
$stmt->bind_param("ds", $minimumIpk, $prodi);
$stmt->execute();

$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    echo htmlspecialchars($row['nama']) . " - IPK: " . $row['ipk'] . "<br>";
}
$stmt->close();
```

---

### 4. Transaksi Database (ACID)

Ketika beberapa query harus dieksekusi bersamaan (contoh: transfer saldo: kurangi saldo pengirim DAN tambah saldo penerima), kita wajib menggunakan transaksi. Jika salah satu query gagal, seluruh perubahan dibatalkan (*rollback*).

```php
try {
    $db->begin_transaction();

    // Query 1: Kurangi saldo A
    $stmt1 = $db->prepare("UPDATE rekening SET saldo = saldo - ? WHERE no_rek = ?");
    $stmt1->bind_param("ds", $nominal, $rekPengirim);
    $stmt1->execute();

    // Query 2: Tambah saldo B
    $stmt2 = $db->prepare("UPDATE rekening SET saldo = saldo + ? WHERE no_rek = ?");
    $stmt2->bind_param("ds", $nominal, $rekPenerima);
    $stmt2->execute();

    // Jika semua berhasil, simpan permanen
    $db->commit();
} catch (mysqli_sql_exception $e) {
    // Batalkan seluruh perubahan jika terjadi error
    $db->rollback();
    error_log("Transaksi gagal: " . $e->getMessage());
}
```

---

## III. Langkah-Langkah Praktikum

Buka folder `Week6/starter/` pada teks editor Anda:

### Persiapan Database
Jalankan file script SQL di folder `Week6/database/setup.sql` melalui phpMyAdmin / MySQL CLI untuk membuat tabel `mahasiswa` dan `rekening`.

### Latihan 1: Demonstrasi Kerentanan Query Biasa vs Prepared Statement
Buka `01_sqli_vs_prepared.php`:
1. Amati bagaimana query string rentan dieksploitasi bypass authentication.
2. Periksa perbaikan menggunakan prepared statement.

### Latihan 2: Filter Data & Parameter Binding
Buka `02_filter_mahasiswa.php`:
1. Menerima input filter prodi dan batas minimal IPK dari form.
2. Menggunakan `bind_param("ds", ...)` untuk mengambil daftar mahasiswa.
3. Menampilkan hasil ke dalam tabel HTML dengan proteksi `htmlspecialchars()`.

### Latihan 3: Transaksi Perbankan Aman (Commit & Rollback)
Buka `03_transaksi_mysqli.php`:
1. Mensimulasikan transfer saldo antar dua rekening.
2. Menguji skenario gagal (misal: saldo tidak cukup atau nomor rekening tujuan tidak ditemukan) untuk membuktikan fitur `rollback()`.

---

## IV. Tugas Praktik Mandiri

Buat file baru `latihan_mandiri.php` di dalam folder `Week6/starter/`:

1. **Skenario Pencarian & Filter Katalog Buku**:
   - Buat tabel `katalog_buku` dengan kolom: `id`, `isbn`, `judul`, `penulis`, `kategori`, `harga`, `stok`.
   - Buat formulir pencarian dengan 3 parameter filter dinamis:
     - Kata kunci judul/penulis (`LIKE ?`)
     - Pilihan kategori (*dropdown*)
     - Rentang harga maksimum
   - Gunakan prepared statement MySQLi Object untuk mengeksekusi pencarian tersebut secara aman.
   - Sajikan hasil pencarian dalam bentuk grid kartu atau tabel responsif.

---

## V. Checklist Pemahaman Praktikum

| No | Indikator Ketercapaian | Status |
|:--:|---|:---:|
| 1 | Saya memahami bagaimana SQL Injection dapat merusak aplikasi | [ ] |
| 2 | Saya mampu membuat query dengan `$db->prepare()` | [ ] |
| 3 | Saya memahami penentu tipe pada `bind_param()` (`s`, `i`, `d`, `b`) | [ ] |
| 4 | Saya mampu mengambil data hasil query dengan `get_result()` dan `fetch_assoc()` | [ ] |
| 5 | Saya selalu menggunakan `htmlspecialchars()` saat menampilkan data database ke HTML | [ ] |
| 6 | Saya memahami fungsi `begin_transaction()`, `commit()`, dan `rollback()` | [ ] |
