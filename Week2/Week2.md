# Modul Praktikum 2 — Penerapan Class, Constructor, dan Method Lanjut

**Mata Kuliah:** Pemrograman Lanjut (3 SKS)  
**Pertemuan:** Minggu Ke-2  
**Durasi:** 1 × 120 Menit  
**Prasyarat:** Modul Praktikum 1 (Konsep Dasar PBO)  

---

## I. Sub-CPMK & Tujuan Pembelajaran

### Sub-CPMK (Minggu 2)
> **Mahasiswa mampu mengaplikasikan konsep class, constructor, dan method (C3, A1).**

### Tujuan Praktikum
Setelah menyelesaikan modul ini, mahasiswa diharapkan mampu:
1. Memahami siklus hidup instansiasi dan membuat **Constructor** (`__construct`) untuk inisialisasi state awal objek secara otomatis.
2. Menerapkan fitur modern PHP 8 (*Constructor Property Promotion*) untuk penulisan kode yang lebih ringkas dan bersih.
3. Mendeklarasikan serta membedakan penggunaan **Static Property** dan **Static Method** dibandingkan *instance member*.
4. Menggunakan **Class Constant** (`const`) untuk nilai konstan yang bersifat tetap pada level class.
5. Memahami perbedaan mendasar antara keyword **`$this`** (merujuk ke instance objek aktif) dan **`self`** (merujuk ke scope class statis).
6. Mengimplementasikan teknik **Method Chaining** (*fluent interface*) dengan mengembalikan objek `$this`.
7. Menggunakan objek sebagai **tipe data parameter** (*type hinting*) dan **nilai kembalian** (*return value*) dalam interaksi antar-objek.

---

## II. Dasar Teori

### 1. Constructor (`__construct`)
*Constructor* adalah method khusus (*magic method*) yang otomatis dieksekusi saat suatu objek diinstansiasi menggunakan operator `new`. Constructor memastikan objek berada dalam kondisi awal (*state*) yang valid dan siap digunakan.

```php
class Mahasiswa
{
    public string $nim;
    public string $nama;
    public float $ipk;

    // Constructor konvensional
    public function __construct(string $nim, string $nama, float $ipk = 0.0)
    {
        $this->nim = $nim;
        $this->nama = $nama;
        $this->ipk = $ipk;
    }
}

// Objek langsung memiliki nilai awal saat dibuat
$mhs = new Mahasiswa("10231001", "Ahmad Fauzi", 3.75);
```

#### Fitur PHP 8: Constructor Property Promotion
PHP 8+ memungkinkan deklarasi visibilitas dan properti langsung di dalam parameter constructor, sehingga tidak perlu menulis deklarasi properti dan penugasan `$this->... = ...` secara berulang:

```php
class MahasiswaModern
{
    // Constructor Property Promotion
    public function __construct(
        public string $nim,
        public string $nama,
        public float $ipk = 0.0
    ) {}
}
```

---

### 2. Static Member & Class Constant

#### Static Property & Static Method
Member statis (`static`) melekat pada **Class itu sendiri**, bukan pada instance objek. Nilai static property dibagi bersama (*shared*) oleh seluruh objek dari class tersebut, dan dapat diakses tanpa perlu melakukan instansiasi objek.

- Mengakses static member di dalam class: `self::$namaStatic` / `self::namaMethodStatic()`
- Mengakses static member dari luar class: `NamaClass::$namaStatic` / `NamaClass::namaMethodStatic()`

#### Class Constant (`const`)
Konstanta bernilai tetap yang tidak dapat diubah setelah didefinisikan. Diakses menggunakan operator resolusi scope (`::`).

```php
class Konfigurasi
{
    // Class Constant
    public const VERSI_APLIKASI = "2.1.0";
    public const MATA_UANG = "IDR";

    // Static Property
    public static int $totalPermintaan = 0;

    // Static Method
    public static function catatPermintaan(): void
    {
        self::$totalPermintaan++;
    }

    public static function formatRupiah(float $angka): string
    {
        return "Rp " . number_format($angka, 0, ',', '.');
    }
}

// Pemanggilan tanpa instansiasi
echo Konfigurasi::VERSI_APLIKASI;
Konfigurasi::catatPermintaan();
echo Konfigurasi::formatRupiah(150000);
```

---

### 3. Perbedaan Kritis: `$this` vs `self`

| Pembeda | `$this` | `self` |
|---|---|---|
| **Rujukan** | Merujuk ke objek instance yang sedang aktif | Merujuk ke definisi Class saat ini |
| **Konteks** | Non-static (membutuhkan `new Object()`) | Static context & Class Constant |
| **Sintaks Akses** | `$this->property` atau `$this->method()` | `self::$staticProperty` atau `self::const` |
| **Bisa dipakai di static method?** | ❌ **Tidak Bisa** (Fatal Error) | ✅ **Bisa** |

---

### 4. Method Chaining (Fluent Interface)

*Method chaining* adalah teknik memanggil beberapa method secara beruntun dalam satu baris kode (misal: `$object->filter()->sort()->get()`). Syarat utamanya adalah setiap method dalam rantai harus mengembalikan objek instance itu sendiri menggunakan `return $this;`.

```php
class QuerySederhana
{
    private string $tabel = '';
    private array $kondisi = [];

    public function table(string $namaTabel): self
    {
        $this->tabel = $namaTabel;
        return $this; // Mengembalikan objek aktif
    }

    public function where(string $kolom, string $nilai): self
    {
        $this->kondisi[] = "{$kolom} = '{$nilai}'";
        return $this; // Mengembalikan objek aktif
    }

    public function toSql(): string
    {
        $sql = "SELECT * FROM {$this->tabel}";
        if (!empty($this->kondisi)) {
            $sql .= " WHERE " . implode(" AND ", $this->kondisi);
        }
        return $sql;
    }
}

// Pemanggilan berantai (Chaining)
$sql = (new QuerySederhana())
    ->table("mahasiswa")
    ->where("status", "aktif")
    ->where("jurusan", "SI")
    ->toSql();
```

---

### 5. Interaksi Antar Objek (Object Type Hinting)

Dalam PBO yang baik, objek saling berkomunikasi dengan mengirimkan objek lain sebagai argumen ataupun nilai kembalian.

```php
class Jurusan
{
    public function __construct(
        public string $kode,
        public string $namaJurusan
    ) {}
}

class Mahasiswa
{
    public function __construct(
        public string $nim,
        public string $nama,
        public Jurusan $jurusan // Object sebagai parameter type hinting
    ) {}

    public function getInfoLengkap(): string
    {
        return "{$this->nama} ({$this->nim}) - Jurusan: {$this->jurusan->namaJurusan}";
    }
}

$si = new Jurusan("SI", "Sistem Informasi");
$mhs = new Mahasiswa("10231088", "Budi Santoso", $si);
echo $mhs->getInfoLengkap();
```

---

## III. Langkah-Langkah Praktikum

Buka folder `Week2/starter/` pada teks editor Anda:

### Latihan 1: Constructor & Constructor Promotion
Buka `01_constructor.php`:
1. Amati cara kerja inisialisasi properti otomatis saat `new` dipanggil.
2. Bandingkan penulisan class klasik dengan *Constructor Property Promotion* PHP 8.
3. Jalankan dan verifikasi nilai objek.

### Latihan 2: Static Member & Class Constant
Buka `02_static_constant.php`:
1. Amati bagaimana static variable menghitung jumlah instansiasi objek yang terjadi di memori.
2. Periksa cara pemanggilan konstanta kelas dan helper fungsi statis tanpa instansiasi.

### Latihan 3: Implementasi Method Chaining
Buka `03_method_chaining.php`:
1. Buat class `KuitansiBuilder` yang dapat menambahkan item, menghitung PPN 11%, memberi diskon, dan mencetak total akhir dengan rantai pemanggilan method `->item(...)->diskon(...)->ppn()->render()`.

### Latihan 4: Kolaborasi Antar Objek (Object Parameter & Return Type)
Buka `04_object_interaction.php`:
1. Definisikan class `BahanBakar` dan class `Kendaraan`.
2. Buat method `isiBahanBakar(BahanBakar $bbm, float $liter)` pada class `Kendaraan`.
3. Buat method yang mereturn objek baru.

---

## IV. Tugas Praktik Mandiri

Buatlah file `latihan_mandiri.php` di dalam folder `Week2/starter/`:

1. **Skenario Sistem Kasir Toko Online**:
   - Buat class `ItemBelanja` dengan constructor property: `namaBarang`, `hargaSatuan`, `jumlahBeli`.
   - Buat class `KeranjangBelanja`:
     - Memiliki static property `$totalTransaksiGlobal` untuk mencatat jumlah keranjang yang diproses.
     - Memiliki class constant `TARIF_ONGKIR_DEFAULT = 15000`.
     - Method `tambahItem(ItemBelanja $item): self` (menggunakan chaining).
     - Method `setDiskon(float $persen): self`.
     - Method `hitungTotal(): float`.
     - Method `cetakStruk(): string` (HTML output rapi).
2. Uji dengan minimal 2 keranjang belanja terpisah yang masing-masing memuat 2-3 item belanja, lalu tampilkan struknya di browser.

---

## V. Checklist Pemahaman Praktikum

| No | Indikator Ketercapaian | Status |
|:--:|---|:---:|
| 1 | Saya mampu membuat constructor untuk menginisialisasi properti objek | [ ] |
| 2 | Saya memahami dan dapat memakai Constructor Promotion PHP 8 | [ ] |
| 3 | Saya memahami kapan harus menggunakan static member vs instance member | [ ] |
| 4 | Saya memahami perbedaan fungsi `$this` vs `self` | [ ] |
| 5 | Saya mampu membuat method yang mendukung method chaining (`return $this`) | [ ] |
| 6 | Saya mampu mengirimkan objek sebagai argumen ke method objek lain | [ ] |
