# Modul Praktikum 3 — Encapsulation & Access Modifier

**Mata Kuliah:** Pemrograman Lanjut (3 SKS)  
**Pertemuan:** Minggu Ke-3  
**Durasi:** 1 × 120 Menit  
**Prasyarat:** Modul Praktikum 2 (Constructor & Method)  

---

## I. Sub-CPMK & Tujuan Pembelajaran

### Sub-CPMK (Minggu 3)
> **Mahasiswa mampu mengaplikasikan konsep encapsulation dan access modifier (C3, A4, P2).**

### Tujuan Praktikum
Setelah menyelesaikan sesi praktikum ini, mahasiswa diharapkan mampu:
1. Memahami prinsip **Encapsulation** (*information hiding*) untuk melindungi integritas data internal objek.
2. Memilih dan menerapkan access modifier (**`public`**, **`protected`**, **`private`**) secara tepat sesuai kebutuhan hak akses.
3. Membuat method **Getter** (membaca data) dan **Setter** (mengubah data).
4. Menyematkan **logika validasi** pada method setter guna mencegah state objek berada dalam kondisi tidak sah (*invalid invariant*).
5. Merancang properti *read-only* (hanya menyediakan getter) untuk data yang tidak boleh dimodifikasi setelah instansiasi.

---

## II. Dasar Teori

### 1. Apa itu Encapsulation?

**Encapsulation (Pembungkusan)** adalah mekanisme menyatukan data (properti) dan kode yang memanipulasinya (method) dalam satu unit class, sekaligus **membatasi akses langsung dari luar** terhadap komponen internal objek tersebut.

```
TANPA ENCAPSULATION (Semua Public):
[ Pengguna Luar ] ─── BISA MENGUBAH LANGSUNG ───> $rekening->saldo = -999999; (DATA RUSAK!)

DENGAN ENCAPSULATION (Private + Setter/Method):
[ Pengguna Luar ] ───> $rekening->tarik(500000) ───> [ Validasi Internal: Cek Saldo ] ───> Saldo Terjaga!
```

Tujuan utama encapsulation:
1. **Keamanan & Konsistensi Data**: Mencegah data diubah secara sembarangan dengan nilai yang tidak valid (misal: saldo minus, umur negatif, IPK > 4.0).
2. **Fleksibilitas & Pemeliharaan**: Jika struktur internal data berubah di masa depan, kode luar yang memanggil getter/setter tidak akan rusak.
3. **Penyembunyian Kompleksitas (*Information Hiding*)**: Pengguna class tidak perlu tahu bagaimana data diolah di dalam, cukup tahu cara memanggil method publiknya.

---

### 2. Tiga Jenis Access Modifier di PHP

| Access Modifier | Di dalam Class Sendiri | Di dalam Class Turunan (*Child Class*) | Dari Luar Class (*Public Context*) |
|---|:---:|:---:|:---:|
| **`public`** | ✅ Ya | ✅ Ya | ✅ Ya |
| **`protected`** | ✅ Ya | ✅ Ya | ❌ Tidak |
| **`private`** | ✅ Ya | ❌ Tidak | ❌ Tidak |

- **`private`**: Tingkat perlindungan tertinggi. Hanya dapat diakses oleh baris kode di dalam class tempat ia dideklarasikan.
- **`protected`**: Digunakan saat properti/method ingin diwariskan ke class anak (*inheritance*), namun tetap tertutup dari dunia luar.
- **`public`**: Terbuka untuk umum; biasanya digunakan untuk method antarmuka (API publik dari class).

---

### 3. Getter, Setter, dan Validasi State

- **Getter**: Method publik yang mengembalikan nilai properti non-publik.
- **Setter**: Method publik yang menerima nilai baru, **memvalidasi keabsahannya**, lalu menyimpannya ke properti private.

```php
class Mahasiswa
{
    private string $nim;
    private string $nama;
    private float $ipk = 0.0;

    public function __construct(string $nim, string $nama)
    {
        $this->nim = $nim;
        $this->nama = $nama;
    }

    // Getter untuk NIM (Read-Only: tidak ada setter karena NIM tidak boleh berubah)
    public function getNim(): string
    {
        return $this->nim;
    }

    public function getNama(): string
    {
        return $this->nama;
    }

    public function setNama(string $nama): void
    {
        $namaBersih = trim($nama);
        if (strlen($namaBersih) < 3) {
            throw new InvalidArgumentException("Nama mahasiswa minimal 3 karakter.");
        }
        $this->nama = $namaBersih;
    }

    public function getIpk(): float
    {
        return $this->ipk;
    }

    // Setter dengan validasi rentang nilai IPK (0.00 s.d. 4.00)
    public function setIpk(float $ipk): void
    {
        if ($ipk < 0.0 || $ipk > 4.0) {
            throw new InvalidArgumentException("Nilai IPK tidak valid! IPK harus di antara 0.00 hingga 4.00.");
        }
        $this->ipk = $ipk;
    }
}
```

---

### 4. Best Practice Perancangan Encapsulation

1. **Jadikan Properti `private` secara Default**: Selalu mulai dengan visibilitas `private` untuk semua properti data. Baru ubah menjadi `protected` jika memang akan diwariskan.
2. **Hindari Membuat Setter Tanpa Alasan**: Jangan otomatis membuatkan setter untuk semua properti. Jika sebuah properti tidak boleh berubah sepanjang daur hidup objek (misal: ID unik, nomor invoice, tanggal dibuat), **hanya sediakan Getter**.
3. **Validasi Sejak Awal**: Lakukan validasi data di dalam Setter dan Constructor agar objek tidak pernah berada dalam keadaan rusak (*invalid state*).

---

## III. Langkah-Langkah Praktikum

Buka folder `Week3/starter/` pada teks editor Anda:

### Latihan 1: Memahami Pelanggaran Akses Data Langsung
Buka `01_masalah_akses_public.php`:
1. Amati kode yang menggunakan properti `public` tanpa enkapsulasi.
2. Jalankan simulasi di mana nilai negatif dan data tidak logis dimasukkan langsung ke properti objek.
3. Pelajari dampak buruknya terhadap integritas data aplikasi.

### Latihan 2: Implementasi Private, Getter & Setter
Buka `02_getter_setter.php`:
1. Mengubah seluruh properti menjadi `private`.
2. Menyediakan method getter untuk membaca data.
3. Menyediakan setter dengan pengecekan kondisi data.

### Latihan 3: Studi Kasus Rekening Bank
Buka `03_rekening_bank.php`:
1. Menerapkan enkapsulasi pada model akun finansial: `$saldo` bersifat `private`.
2. Method `setor(float $nominal)` dan `tarik(float $nominal)` mengontrol mutasi saldo secara ketat.
3. Mencatat riwayat mutasi transaksi di dalam array internal private.

---

## IV. Tugas Praktik Mandiri

Buat file baru `latihan_mandiri.php` di dalam folder `Week3/starter/`:

1. **Skenario Sistem Penggajian Karyawan**:
   - Buat class `KaryawanKontrak`:
     - Properti `private string $idKaryawan` (Read-only, diisi via constructor).
     - Properti `private string $nama`.
     - Properti `private float $gajiPokok` (Minimal Rp 3.000.000, tolak jika di bawah nominal ini).
     - Properti `private int $jumlahHariHadir` (Rentang 0 s.d. 25 hari kerja, tolak jika di luar rentang).
     - Properti `private float $uangMakanPerHari = 50000` (konstan/default).
   - Method:
     - Getter untuk seluruh properti.
     - Setter untuk `nama`, `gajiPokok`, dan `jumlahHariHadir` lengkap dengan validasi.
     - Method `hitungTunjanganMakan(): float` $\rightarrow$ `$jumlahHariHadir * $uangMakanPerHari`.
     - Method `hitungTotalGaji(): float` $\rightarrow$ `$gajiPokok + $this->hitungTunjanganMakan()`.
2. Buat simulasi pembuatan 2 karyawan, coba berikan input tidak valid (misal: kehadiran 30 hari atau gaji minus) dengan penanganan `try-catch`, dan sajikan data karyawan yang valid ke dalam tabel HTML.

---

## V. Checklist Pemahaman Praktikum

| No | Indikator Ketercapaian | Status |
|:--:|---|:---:|
| 1 | Saya memahami alasan mengapa properti tidak boleh dibiarkan `public` sembarangan | [ ] |
| 2 | Saya memahami perbedaan jangkauan `private`, `protected`, dan `public` | [ ] |
| 3 | Saya mampu membuat Getter dan Setter yang sesuai standar penamaan | [ ] |
| 4 | Saya mampu menyematkan validasi di dalam setter | [ ] |
| 5 | Saya mampu merancang properti yang bersifat *read-only* | [ ] |
