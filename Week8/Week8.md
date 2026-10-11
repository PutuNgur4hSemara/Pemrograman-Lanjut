# Modul Evaluasi — Ujian Tengah Semester (UTS) Praktikum Pemrograman Lanjut

**Mata Kuliah:** Pemrograman Lanjut (3 SKS)  
**Pertemuan:** Minggu Ke-8  
**Durasi:** 1 × 120 Menit  
**Cakupan Materi:** Minggu 1 s.d. Minggu 7  

---

## I. Deskripsi & Tujuan Evaluasi

Ujian Tengah Semester (UTS) praktikum bertujuan mengukur kompetensi mahasiswa dalam merancang dan mengimplementasikan aplikasi PHP berorientasi objek yang utuh, mulai dari pemodelan class, enkapsulasi, pewarisan/antarmuka, penanganan eksepsi, hingga interaksi database menggunakan MySQLi Object dengan prepared statement.

### Capaian Pembelajaran yang Diuji:
1. **PBO & Konstruktor (Minggu 1 & 2)**: Kemampuan merancang class, instansiasi, property promotion, static member, dan method chaining.
2. **Enkapsulasi & Validasi (Minggu 3)**: Melindungi data internal dengan `private`, membuat getter/setter, serta menjaga konsistensi state.
3. **Pewarisan & Polimorfisme (Minggu 4)**: Mengimplementasikan `extends`, abstract class, dan `implements` interface secara polymorphic.
4. **Exception Handling (Minggu 5)**: Menangani error secara anggun dengan `try-catch-finally` dan custom exception.
5. **Akses Database Aman & CRUD (Minggu 6 & 7)**: Menggunakan MySQLi Object, prepared statements, serta arsitektur repository.

---

## II. Studi Kasus Ujian: Sistem Manajemen Rental Kendaraan

Sebuah perusahaan rental kendaraan ingin mendigitalkan sistem penyewaan dan inventaris armada mereka. Anda diminta membangun modul aplikasi terintegrasi dengan spesifikasi teknis berikut:

```
                      +-----------------------------+
                      |     <<interface>>           |
                      |   LayananDiskonRental       |
                      +-----------------------------+
                      | + hitungDiskon(hari): float |
                      +-----------------------------+
                                     ^
                                     | implements
                      +-----------------------------+
                      |       <<abstract>>          |
                      |        Kendaraan            |
                      +-----------------------------+
                      | # id: int                   |
                      | # platNomor: string         |
                      | # merk: string              |
                      | # hargaSewaPerHari: float   |
                      +-----------------------------+
                      | + hitungBiaya(hari): float  |
                      | + getTipe(): string (abst)  |
                      +-----------------------------+
                                     ^
                                     | extends
                 +-------------------+-------------------+
                 |                                       |
+---------------------------------+     +---------------------------------+
|              Mobil              |     |              Motor              |
+---------------------------------+     +---------------------------------+
| - kapasitasPenumpang: int       |     | - kapasitasCc: int              |
| + hitungBiaya(hari): float      |     | + hitungBiaya(hari): float      |
+---------------------------------+     +---------------------------------+
```

---

## III. Instruksi Pengerjaan Teknis

### 1. Struktur Database
Jalankan skrip `Week8/database/setup_uts.sql` untuk menyiapkan tabel `kendaraan` dan `transaksi_sewa`.

### 2. Implementasi Komponen OOP
1. **Interface `LayananDiskonRental`**:
   - Method `hitungDiskon(int $lamaHari, float $totalSewa): float`
   - Aturan: Sewa $\ge 7$ hari mendapat diskon 15%; Sewa $\ge 3$ hari mendapat diskon 5%.
2. **Abstract Class `Kendaraan`**:
   - Menerapkan prinsip enkapsulasi (semua properti `protected`/`private`).
   - Menyediakan constructor, getter, dan validasi setter (harga sewa tidak boleh $\le 0$).
   - Abstract method: `getTipeKendaraan(): string`.
   - Method konkret `hitungTotalSewa(int $lamaHari): float`.
3. **Class `Mobil` & `Motor`**:
   - Mewarisi `Kendaraan` dan mengimplementasikan `LayananDiskonRental`.
   - Lakukan method overriding pada perhitungan biaya jika terdapat biaya tambahan (misal: asuransi mobil Rp 50.000/hari).
4. **Class `KendaraanRepository`**:
   - Menyediakan method `semua(): array`, `tambah(...)`, `cari(int $id)`, `ubah(...)`, dan `hapus(int $id)`.
   - **Wajib menggunakan Prepared Statement** pada seluruh operasi SQL dinamis.
   - Wajib menangani `mysqli_sql_exception` dengan blok `try-catch`.

---

## IV. Rubrik Penilaian UTS

| Kriteria | Bobot | Indikator Penilaian |
|---|:---:|---|
| **Perancangan OOP & Arsitektur** | **25%** | Ketepatan struktur class, instansiasi, modularitas file, dan pembagian tanggung jawab. |
| **Enkapsulasi & Validasi Data** | **20%** | Properti dilindungi (`private`/`protected`), getter/setter tervalidasi, pencegahan state tidak valid. |
| **Inheritance & Polymorphism** | **20%** | Penggunaan abstract class, interface, method overriding, dan `parent::` secara benar. |
| **Keamanan Query & Prepared Statement** | **25%** | Penggunaan `prepare()`, `bind_param()` konsisten tanpa celah SQL Injection, output escaping `htmlspecialchars()`. |
| **Exception Handling & User Feedback** | **10%** | Penerapan `try-catch`, tidak ada fatal error/blank screen, tampilan UI bersih & responsif. |
| **Total Nilai** | **100%** | |

---

## V. Petunjuk Pengumpulan

1. Pastikan seluruh kode tersimpan rapi di folder `Week8/starter/`.
2. Uji seluruh fitur CRUD dan kalkulasi sewa melalui browser sebelum mengumpulkan tugas.
