# Modul Praktikum 1 — Pengantar Web & Konsep Dasar Pemrograman Berorientasi Objek (PBO)

**Mata Kuliah:** Pemrograman Lanjut (3 SKS)  
**Pertemuan:** Minggu Ke-1  
**Durasi:** 1 × 120 Menit  
---

## I. Sub-CPMK & Tujuan Pembelajaran

### Sub-CPMK (Minggu 1)
> **Mahasiswa mampu menjelaskan dan mengaplikasikan konsep dasar pemrograman berorientasi objek (PBO) seperti objek, kelas, atribut/properti, dan metode pada PHP (C2).**

### Tujuan Praktikum
Setelah menyelesaikan sesi praktikum ini, mahasiswa diharapkan mampu:
1. Mengingat kembali struktur dasar HTML & CSS untuk menyajikan antarmuka output web secara rapi.
2. Membedakan paradigma pemrograman prosedural dengan paradigma berorientasi objek (*Object-Oriented Programming*).
3. Mendefinisikan **Class** dan melakukan instansiasi menjadi **Object** menggunakan operator `new`.
4. Mendeklarasikan **Property** (atribut) dan **Method** (perilaku) pada Class.
5. Menggunakan *pseudo-variable* `$this` untuk mengakses property dan method dari dalam class itu sendiri.
6. Mengirimkan argumen/parameter ke dalam method serta menangani nilai kembalian (*return value*).

> [!NOTE]
> Materi mendalam mengenai *Constructor*, *Static Member*, *Class Constant*, *Method Chaining*, dan *Object Type Hinting* akan dipelajari secara khusus pada **Minggu Ke-2**. Pada Minggu 1 ini, fokus utama adalah memahami fondasi cara berpikir objek, instansiasi, serta interaksi property dan method dasar.

---

## II. Dasar Teori

### 1. Refreshment Cepat: HTML & CSS sebagai Antarmuka Output
Sebelum masuk ke logika PHP OOP, mari mengingat kembali bagaimana browser menampilkan informasi:
- **HTML (HyperText Markup Language)** menyediakan kerangka semantik dokumen (judul `<h1>`, paragraf `<p>`, tabel `<table>`, container `<div>`, dan formulir `<form>`).
- **CSS (Cascading Style Sheets)** mengatur tata letak, warna, tipografi, dan *spacing* (margin, padding, border) agar informasi mudah dibaca.

Dalam pengembangan web dengan PHP, kode PHP akan dieksekusi di sisi server (*server-side*), lalu hasilnya disisipkan ke dalam tag-tag HTML untuk dikirimkan ke browser pengguna (*client-side*).

```html
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Output PBO Sederhana</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, sans-serif; background: #f8fafc; padding: 20px; }
        .card { background: white; border-radius: 8px; padding: 16px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); max-width: 400px; margin-bottom: 12px; }
        .badge { background: #e0f2fe; color: #0369a1; padding: 4px 8px; border-radius: 4px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="card">
        <h3><?php echo "Judul dari PHP"; ?></h3>
        <p>Konten yang dihasilkan oleh PHP.</p>
    </div>
</body>
</html>
```

---

### 2. Paradigma Prosedural vs Pemrograman Berorientasi Objek (PBO)

Pada pemrograman **prosedural**, kita memandang program sebagai urutan instruksi dan fungsi-fungsi terpisah yang mengolah data global. Hal ini berisiko menimbulkan masalah redundansi, sulitnya pemeliharaan (*maintenance*), dan tabrakan nama (*naming collisions*) ketika skala aplikasi membesar.

Pada **Pemrograman Berorientasi Objek (PBO)**, program dimodelkan berdasarkan entitas dunia nyata (*real-world entities*). Setiap entitas dibungkus menjadi sebuah objek yang memiliki:
1. **State / Karakteristik (Data)** $\rightarrow$ Disebut **Property** (variabel di dalam class).
2. **Behavior / Perilaku (Aksi)** $\rightarrow$ Disebut **Method** (fungsi di dalam class).

```
+-------------------------------------------------------------+
|                           CLASS                             |
|                 (Cetakan / Blueprint / Desain)              |
|                                                             |
|   Properties (Data):                                        |
|   - $nim                                                    |
|   - $nama                                                   |
|   - $ipk                                                    |
|                                                             |
|   Methods (Perilaku):                                       |
|   - belajar()                                               |
|   - hitungPredikat()                                        |
+-------------------------------------------------------------+
                              |
                     instansiasi (`new`)
                              |
       +----------------------+----------------------+
       |                                             |
       v                                             v
+-----------------------------+               +-----------------------------+
|     OBJECT: $mahasiswa1     |               |     OBJECT: $mahasiswa2     |
|                             |               |                             |
| nim: "10231001"             |               | nim: "10231002"             |
| nama: "Ahmad Dahlan"        |               | nama: "Siti Aminah"         |
| ipk: 3.85                   |               | ipk: 3.60                   |
+-----------------------------+               +-----------------------------+
```

---

### 3. Konsep Class dan Object
- **Class**: Cetak biru (*blueprint*) atau template yang mendefinisikan bentuk serta kemampuan objek.
- **Object**: Wujud nyata (*instance*) dari suatu class yang menempati memori dan memiliki data spesifik.

```php
// Mendefinisikan Class
class Mahasiswa
{
    // Property
    public $nim;
    public $nama;
    public $jurusan;
}

// Instansiasi Object
$mhs1 = new Mahasiswa();
$mhs1->nim = "10231001";
$mhs1->nama = "Budi Santoso";
$mhs1->jurusan = "Sistem Informasi";

$mhs2 = new Mahasiswa();
$mhs2->nim = "10231002";
$mhs2->nama = "Citra Lestari";
$mhs2->jurusan = "Informatika";
```

---

### 4. Property, Method, dan Pseudo-Variable `$this`

- **Property**: Variabel yang melekat pada class. Pada tahap awal ini, kita mendeklarasikannya dengan keyword `public`.
- **Method**: Fungsi yang dideklarasikan di dalam class untuk merepresentasikan tindakan atau kalkulasi yang dapat dilakukan objek.
- **`$this`**: Variabel khusus (*pseudo-variable*) yang merujuk pada objek yang sedang aktif menjalankan method tersebut. Melalui `$this->namaProperty`, sebuah method dapat membaca atau memodifikasi data objek miliknya sendiri.

```php
class Produk
{
    public $sku;
    public $nama;
    public $harga;
    public $stok;

    public function tampilkanInfo()
    {
        return "Produk: {$this->nama} (SKU: {$this->sku}) - Rp " . number_format($this->harga, 0, ',', '.');
    }

    public function cekKetersediaan()
    {
        if ($this->stok > 0) {
            return "Stok tersedia ({$this->stok} unit)";
        }
        return "Stok habis";
    }
}
```

---

### 5. Method dengan Parameter (Argumen)

Sama seperti fungsi standar di PHP, method dapat menerima parameter untuk memproses data dari luar:

```php
class AkunBank
{
    public $nomorRekening;
    public $pemilik;
    public $saldo = 0; // Default value

    public function setor($jumlah)
    {
        if ($jumlah > 0) {
            $this->saldo += $jumlah;
            return "Setoran sebesar Rp " . number_format($jumlah, 0, ',', '.') . " berhasil.";
        }
        return "Jumlah setoran tidak valid.";
    }

    public function tarik($jumlah)
    {
        if ($jumlah > 0 && $jumlah <= $this->saldo) {
            $this->saldo -= $jumlah;
            return "Penarikan sebesar Rp " . number_format($jumlah, 0, ',', '.') . " berhasil.";
        }
        return "Penarikan gagal: Saldo tidak mencukupi atau nominal tidak valid.";
    }
}
```

---

## III. Langkah-Langkah Praktikum

Buka folder `Week1/starter/` pada teks editor (VS Code) Anda dan jalankan melalui web server lokal (Apache XAMPP / PHP Built-in Server: `php -S localhost:8000`).

### Latihan 1: Quickstart HTML, CSS & PHP Output
Buka file `01_html_css_quickstart.php`:
1. Amati bagaimana tag HTML membentuk kerangka kartu profil.
2. Amati bagaimana properti CSS (background, padding, flexbox, border-radius) mempercantik tampilan.
3. Jalankan di browser dan pastikan data dinamis PHP tercetak di dalam elemen HTML yang sesuai.

### Latihan 2: Deklarasi Class dan Pembuatan Object
Buka file `02_class_dan_object.php`:
1. Buat class `Mobil` dengan property: `$merk`, `$tipe`, `$warna`, dan `$tahunProduksi`.
2. Lakukan instansiasi minimal dua object berbeda (`$mobil1` dan `$mobil2`).
3. Berikan nilai pada masing-masing property, lalu cetak menggunakan `echo` atau `var_dump()`.

### Latihan 3: Menambahkan Method dan Penggunaan `$this`
Buka file `03_property_dan_method.php`:
1. Definisikan class `Karyawan` dengan property: `$nip`, `$nama`, `$gajiPokok`, dan `$tunjangan`.
2. Tambahkan method `hitungTotalGaji()` yang menjumlahkan `$this->gajiPokok` dan `$this->tunjangan`.
3. Tambahkan method `cetakSlipGaji()` yang mengembalikan format teks/HTML rapi.
4. Uji dengan membuat dua object karyawan dan panggil method masing-masing.

### Latihan 4: Method Berparameter dan Interaksi Logika
Buka file `04_argument_method.php`:
1. Definisikan class `KalkulatorNilai` dengan property `$namaMahasiswa`.
2. Buat method `hitungNilaiAkhir($nilaiTugas, $nilaiUTS, $nilaiUAS)` dengan bobot: Tugas 30%, UTS 30%, UAS 40%.
3. Buat method `konversiGrade($nilaiAkhir)` yang mengembalikan nilai huruf (A, B, C, D, E).
4. Tampilkan hasil penilaian mahasiswa dalam bentuk tabel HTML.

---

## IV. Tugas Praktik Mandiri

Buatlah file baru bernama `latihan_mandiri.php` di dalam folder `starter/` dengan ketentuan sebagai berikut:

1. **Skenario Sistem Pengelolaan Perpustakaan Sederhana**:
   - Buat class `Buku` dengan properti:
     - `kodeBuku`
     - `judul`
     - `penulis`
     - `statusDipinjam` (nilai boolean: `false` = tersedia, `true` = sedang dipinjam)
   - Sediakan method:
     - `pinjamBuku()`: Mengubah status buku menjadi sedang dipinjam jika buku tersedia, atau menampilkan pesan peringatan jika buku sudah dipinjam.
     - `kembalikanBuku()`: Mengubah status kembali menjadi tersedia.
     - `getStatus()`: Mengembalikan teks "Tersedia" (warna hijau) atau "Sedang Dipinjam" (warna merah).
2. **Instansiasi & Tampilan Antarmuka**:
   - Buat minimal 3 objek buku dengan judul dan penulis yang berbeda.
   - Simulasikan peminjaman pada salah satu buku.
   - Sajikan daftar seluruh buku dalam tabel HTML yang diberi styling CSS rapi (menggunakan border, header berwarna, dan status berlabel badge).

---

## V. Checklist Pemahaman Praktikum

| No | Indikator Kompetensi | Status |
|:--:|---|:---:|
| 1 | Saya memahami perbedaan mendasar paradigma prosedural vs PBO | [ ] |
| 2 | Saya mampu menuliskan sintaks deklarasi class di PHP dengan benar | [ ] |
| 3 | Saya mampu membuat object dari class menggunakan kata kunci `new` | [ ] |
| 4 | Saya memahami perbedaan antara properti (data) dan method (perilaku) | [ ] |
| 5 | Saya memahami fungsi kata kunci `$this` saat mengakses member di dalam class | [ ] |
| 6 | Saya mampu mengirimkan parameter ke method dan memanfaatkan nilai kembaliannya | [ ] |

---

## VI. Troubleshooting & Masalah Umum

1. **Error: `Undefined property: ClassName::$namaProperty`**
   - **Penyebab:** Typo atau lupa mendeklarasikan nama property di dalam class sebelum mengaksesnya.
   - **Solusi:** Periksa ejaan deklarasi properti di bagian atas class.

2. **Error: `Using $this when not in object context`**
   - **Penyebab:** Menggunakan `$this` di luar method class atau di dalam fungsi biasa / static context.
   - **Solusi:** Pastikan `$this` hanya dipanggil di dalam method reguler milik class.

3. **Lupa Tanda `$` pada Property saat Menggunakan `$this`**:
   - ❌ Salah: `$this->$nama`
   - ✅ Benar: `$this->nama` (tanda `$` hanya diletakkan di depan `this`).
