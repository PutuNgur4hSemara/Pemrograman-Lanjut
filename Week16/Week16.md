# Modul Evaluasi — Ujian Akhir Semester (UAS) Proyek Case Method

**Mata Kuliah:** Pemrograman Lanjut (3 SKS)  
**Pertemuan:** Minggu Ke-16  
**Durasi:** Proyek Akhir Terbimbing / Case Method  
**Cakupan Materi:** Minggu 1 s.d. Minggu 15 (PBO, PDO, Query Builder, Validasi Class)  

---

## I. Deskripsi Proyek Akhir (*Case Method*)

Ujian Akhir Semester (UAS) mata kuliah Pemrograman Lanjut berbentuk **Proyek Pengembangan Aplikasi Berbasis *Case Method***. Mahasiswa ditugaskan untuk merancang dan membangun sistem informasi berbasis web yang mengintegrasikan seluruh pilar materi yang telah dipelajari selama satu semester ke dalam satu arsitektur modular yang solid, aman, dan berstandar industri.

### Pilar Kompetensi Utama yang Diintegrasikan:
1. **Pemrograman Berorientasi Objek Murni (PBO)**: Enkapsulasi, Constructor Promotion, Pewarisan (*Inheritance*), Abstraksi (*Abstract Class*), Antarmuka (*Interface*), dan Polimorfisme (*Polymorphism*).
2. **Koneksi Multi-Database (PDO)**: DSN, penanganan eksepsi `PDOException`, mode transaksi atomik (`beginTransaction`, `commit`, `rollBack`).
3. **Custom Database Query Builder**: Penggunaan Query Builder buatan sendiri (dengan method chaining: `table`, `select`, `where`, `orderBy`, `limit`, `get`, `first`, `insert`, `update`, `delete`, dan mekanisme *reset state*).
4. **Validasi Berbasis Class (*Class-Based Validation*)**: Pemisahan class `Input`, class `Validator` dengan rule engine, serta penyajian *sticky form* (repopulasi input).
5. **Keamanan Aplikasi Web**: Bebas dari celah SQL Injection (100% Prepared Statement) dan bebas dari celah XSS (Output Escaping `htmlspecialchars`).

---

## II. Pilihan Studi Kasus Proyek

Mahasiswa dapat memilih salah satu dari studi kasus berikut:

### Opsi A: Sistem Manajemen Event & Tiket Konser Kampus (*Event & Ticketing Platform*)
- Pengelolaan kategori event, jadwal, kapasitas kuota, dan harga tiket.
- Pemesanan tiket dengan transaksi atomik (kuota otomatis berkurang, gagal jika kuota habis).
- Validasi registrasi peserta (email valid, nomor HP, pilihan sesi & paket).
- Pencarian dan penyaringan event dinamis menggunakan Query Builder.

### Opsi B: Sistem Inventaris & Peminjaman Aset Laboratorium (*Lab Asset Management*)
- Hirarki aset: `AsetElektronik` (memiliki masa garansi & konsumsi daya) vs `AsetMebel` (memiliki bahan material).
- Peminjaman aset mahasiswa dengan validasi tanggal pinjam dan masa batas waktu.
- Laporan inventaris dengan filter multi-kolom dan pengurutan dinamis melalui Query Builder.

### Opsi C: Sistem Informasi Penjualan & Layanan Bengkel (*Auto Repair & Sparepart*)
- Transaksi servis kendaraan (jasa mekanik + pembelian suku cadang).
- Perhitungan tagihan polimorfis (diskon loyalitas member vs pelanggan non-member).
- Manajemen stok suku cadang dengan validasi ketersediaan barang.

---

## III. Standar Struktur Arsitektur Direktori Proyek

Proyek akhir wajib mematuhi pemisahan tanggung jawab (*Separation of Concerns*):

```text
uas-proyek-akhir/
├── config/
│   └── Database.php          # Inisialisasi PDO Singleton / Factory
├── core/
│   ├── Input.php             # Class pembaca & normalisasi input
│   ├── Validator.php         # Class rule engine validasi
│   └── QueryBuilder.php      # Class perakit SQL & eksekutor PDO
├── models/
│   ├── KontrakInterface.php  # Definisi interface domain
│   ├── EntitasUtama.php      # Abstract / Parent Class model
│   └── EntitasTurunan.php    # Class turunan konkret
├── repositories/
│   └── DomainRepository.php  # Class pengelola data via QueryBuilder
├── database/
│   └── schema.sql            # Skema tabel database & dummy data awal
├── views/
│   ├── layout/header.php     # Header & styling CSS
│   └── layout/footer.php     # Footer HTML
├── public/                   # Halaman antarmuka pengguna
│   ├── index.php             # Dashboard & daftar data
│   ├── tambah.php            # Formulir entri tervalidasi
│   ├── detail.php            # Rincian transaksi & cetak struk
│   └── aksi.php              # Pengendali request
└── README.md                 # Dokumentasi sistem, arsitektur class & panduan instalasi
```

---

## IV. Rubrik Penilaian UAS

| Kriteria Penilaian | Bobot | Indikator Ketercapaian |
|---|:---:|---|
| **Arsitektur PBO & Desain Class** | **25%** | Penerapan inheritance, abstract class, interface, encapsulation yang ketat, serta polymorphism yang tepat guna. |
| **Kualitas Custom Query Builder** | **25%** | Query Builder berfungsi penuh (CRUD, chaining, parameter binding aman, allowlist identifier, dan bebas kebocoran reset state). |
| **Validasi Class & Keamanan Sistem** | **25%** | Validasi form menggunakan class validator mandiri, sticky form tanpa data hilang, pencegahan SQLi dan XSS menyeluruh. |
| **Integrasi Fungsional & UI/UX** | **15%** | Seluruh alur aplikasi berjalan lancar dari input form sampai database, transaksi ACID bekerja dengan benar, tampilan rapi dan responsif. |
| **Clean Code & Dokumentasi** | **10%** | Penamaan variabel/method deskriptif (PSR-12), komentar kode jelas, ketiadaan kode redundan (*DRY*), dan kelengkapan file README. |
| **Total Nilai** | **100%** | |

---

## V. Checklist Pengumpulan Proyek

Pastikan sebelum mengumpulkan proyek:
- [ ] Database dapat di-import dengan lancar via `database/schema.sql`.
- [ ] Tidak ada query SQL mentah (*raw string SQL concatenation*) yang rentan SQL Injection.
- [ ] Seluruh properti domain dilindungi dengan access modifier yang tepat.
- [ ] Formulir memiliki pesan error per field dan mempertahankan nilai lama saat terjadi kesalahan.
- [ ] File `README.md` memuat diagram relasi class dan panduan menjalankan aplikasi secara lokal.
