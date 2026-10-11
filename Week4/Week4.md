# Modul Praktikum 4 — Inheritance, Polymorphism, Abstraksi, dan Interface

**Mata Kuliah:** Pemrograman Lanjut (3 SKS)  
**Pertemuan:** Minggu Ke-4  
**Durasi:** 1 × 120 Menit  
**Prasyarat:** Modul Praktikum 3 (Encapsulation & Access Modifier)  

---

## I. Sub-CPMK & Tujuan Pembelajaran

### Sub-CPMK (Minggu 4)
> **Mahasiswa mampu mengaplikasikan inheritance, polymorphism, abstraksi, dan interface (C3, A4, P2).**

### Tujuan Praktikum
Setelah menyelesaikan sesi praktikum ini, mahasiswa diharapkan mampu:
1. Menerapkan konsep **Inheritance** (pewarisan sifat) menggunakan kata kunci `extends` untuk *code reuse*.
2. Memanfaatkan access modifier **`protected`** agar properti/method dapat diwariskan ke class anak (*subclass*) namun tetap tersembunyi dari publik.
3. Melakukan **Method Overriding** dan memanggil implementasi class induk menggunakan keyword **`parent::`**.
4. Mendefinisikan dan mengimplementasikan **Abstract Class** serta **Abstract Method** sebagai kerangka dasar class turunan.
5. Mendefinisikan **Interface** dan mengimplementasikannya menggunakan kata kunci **`implements`**.
6. Memahami dan mempraktikkan konsep **Polymorphism** (banyak bentuk), di mana kumpulan objek yang berbeda dieksekusi secara seragam melalui antarmuka/tipe abstrak yang sama.

---

## II. Dasar Teori

### 1. Inheritance (Pewarisan)
Inheritance memungkinkan suatu class (*child class/subclass*) mewarisi properti dan method dari class lain (*parent class/superclass*). Konsep ini mencegah duplikasi kode (*DRY - Don't Repeat Yourself*).

```php
class Pengguna
{
    public function __construct(
        protected string $username,
        protected string $email
    ) {}

    public function getInfo(): string
    {
        return "Pengguna: {$this->username} ({$this->email})";
    }
}

// Dosen mewarisi Pengguna
class Dosen extends Pengguna
{
    public function __construct(
        string $username,
        string $email,
        private string $nidn
    ) {
        // Memanggil constructor milik class induk
        parent::__construct($username, $email);
    }

    // Method Overriding: Mengubah perilaku method getInfo()
    public function getInfo(): string
    {
        return parent::getInfo() . " - NIDN: {$this->nidn}";
    }
}
```

---

### 2. Abstract Class & Abstract Method

- **Abstract Class**: Class setengah matang yang **tidak dapat diinstansiasi langsung**. Berfungsi sebagai fondasi atau cetak biru bagi class-class turunannya.
- **Abstract Method**: Method tanpa isi (*body*) di dalam abstract class yang **wajib diimplementasikan** oleh class konkret turunannya.

```php
abstract class BangunDatar
{
    abstract public function hitungLuas(): float;
    abstract public function hitungKeliling(): float;

    // Abstract class boleh memiliki method konkret biasa
    public function cetakHasil(): string
    {
        return "Luas: " . number_format($this->hitungLuas(), 2) . " | Keliling: " . number_format($this->hitungKeliling(), 2);
    }
}

class PersegiPanjang extends BangunDatar
{
    public function __construct(
        private float $panjang,
        private float $lebar
    ) {}

    public function hitungLuas(): float
    {
        return $this->panjang * $this->lebar;
    }

    public function hitungKeliling(): float
    {
        return 2 * ($this->panjang + $this->lebar);
    }
}
```

---

### 3. Interface

**Interface** adalah kontrak perilaku murni. Interface hanya mendefinisikan method apa saja yang harus dimiliki sebuah class, tanpa menyediakan implementasi kode sama sekali.
- Sebuah class dapat mengimplementasikan **lebih dari satu interface** (`implements InterfaceA, InterfaceB`).
- Seluruh method dalam interface secara otomatis bersifat `public`.

```php
interface DapatDibayar
{
    public function bayar(float $nominal): string;
}

interface DapatDiverifikasi
{
    public function verifikasiToken(string $token): bool;
}

class GatewayQris implements DapatDibayar, DapatDiverifikasi
{
    public function bayar(float $nominal): string
    {
        return "Pembayaran QRIS sebesar Rp " . number_format($nominal, 0, ',', '.') . " berhasil di-generate.";
    }

    public function verifikasiToken(string $token): bool
    {
        return strlen($token) === 32;
    }
}
```

---

### 4. Perbandingan: Abstract Class vs Interface

| Fitur | Abstract Class | Interface |
|---|---|---|
| **Sifat Relasi** | Hubungan *"Is-a"* (contoh: Kucing *adalah* Hewan) | Hubungan *"Can-do"* (contoh: Mobil *dapat* DiisiBensin) |
| **Implementasi Method** | Boleh memiliki method konkret (sudah ada kodenya) | Hanya deklarasi method kosong |
| **Properti** | Boleh memiliki properti biasa (non-static) | Hanya boleh mendefinisikan konstanta (`const`) |
| **Multiple Inheritance** | ❌ Tidak (PHP hanya single inheritance `extends`) | ✅ Ya (bisa `implements` banyak interface) |

---

### 5. Polymorphism (Polimorfisme)

Polymorphism berarti "banyak bentuk". Dalam pemrograman berorientasi objek, polymorphism memungkinkan kita memperlakukan berbagai objek dari class yang berbeda secara seragam asalkan mereka menginduk ke interface atau abstract class yang sama.

```php
// Fungsi Polymorphic: Menerima tipe interface DapatDibayar apapun
function prosesTransaksi(DapatDibayar $metode, float $total)
{
    echo $metode->bayar($total);
}

$metode1 = new TransferBank();
$metode2 = new GatewayQris();
$metode3 = new EWalletDana();

// Ketiganya bisa diproses lewat fungsi yang sama!
prosesTransaksi($metode1, 50000);
prosesTransaksi($metode2, 75000);
prosesTransaksi($metode3, 100000);
```

---

## III. Langkah-Langkah Praktikum

Buka folder `Week4/starter/` pada teks editor Anda:

### Latihan 1: Inheritance & Overriding
Buka `01_inheritance_overriding.php`:
1. Buat class induk `Pegawai` dan turunannya: `PegawaiTetap` dan `PegawaiKontrak`.
2. Gunakan `protected` pada properti `gajiPokok`.
3. Lakukan method overriding pada `hitungGajiTotal()` dan manfaatkan `parent::hitungGajiTotal()`.

### Latihan 2: Abstract Class & Template Method
Buka `02_abstract_class.php`:
1. Buat abstract class `PengolahNotifikasi` dengan abstract method `kirimPesan(string $tujuan, string $pesan)`.
2. Buat class konkret `NotifikasiEmail` dan `NotifikasiWhatsApp`.

### Latihan 3: Interface & Polymorphism
Buka `03_interface_polymorphism.php`:
1. Definisikan interface `MetodePembayaran`.
2. Implementasikan pada `TransferVA`, `KartuKredit`, dan `GoPay`.
3. Buat keranjang checkout yang memproses pembayaran polymorphic.

---

## IV. Tugas Praktik Mandiri

Buat file baru `latihan_mandiri.php` di dalam folder `Week4/starter/`:

1. **Skenario Sistem Pengiriman Logistik (Ekspedisi)**:
   - Buat interface `LayananPengiriman` dengan method:
     - `hitungOngkir(float $beratKg, int $jarakKm): float`
     - `getEstimasiSampai(): string`
     - `getNamaLayanan(): string`
   - Buat minimal 3 class pengiriman yang mengimplementasikan interface tersebut:
     1. `PengirimanReguler`: Tarif dasar Rp 8.000/kg + Rp 1.500/km (Estimasi: 3-4 hari).
     2. `PengirimanExpress`: Tarif dasar Rp 15.000/kg + Rp 3.000/km (Estimasi: 1-2 hari).
     3. `PengirimanKargo`: Minimal berat 10 kg, tarif Rp 4.000/kg + Rp 800/km (Estimasi: 5-7 hari).
   - Buat class `KalkulatorEkspedisi` dengan method polymorphic `bandingkanTarif(array $daftarLayanan, float $beratKg, int $jarakKm)` yang menghasilkan tabel perbandingan ongkos kirim seluruh layanan.
2. Tampilkan antarmuka hasil perbandingan tersebut menggunakan kartu / tabel HTML & CSS.

---

## V. Checklist Pemahaman Praktikum

| No | Indikator Ketercapaian | Status |
|:--:|---|:---:|
| 1 | Saya memahami cara mewariskan class menggunakan `extends` | [ ] |
| 2 | Saya memahami fungsi visibilitas `protected` | [ ] |
| 3 | Saya mampu melakukan overriding method dan memanggil `parent::` | [ ] |
| 4 | Saya memahami kapan harus menggunakan abstract class | [ ] |
| 5 | Saya mampu mendefinisikan dan mengimplementasikan interface | [ ] |
| 6 | Saya memahami cara kerja polymorphism melalui type hinting interface | [ ] |
