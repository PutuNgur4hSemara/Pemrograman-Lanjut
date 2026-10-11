# Modul Praktikum 7 — Integrasi CRUD Berbasis Class dengan MySQLi Object

**Mata Kuliah:** Pemrograman Lanjut (3 SKS)  
**Pertemuan:** Minggu Ke-7  
**Durasi:** 1 × 120 Menit  
**Prasyarat:** Modul Praktikum 5 (Exception) & Modul 6 (Prepared Statement)  

---

## I. Sub-CPMK & Tujuan Pembelajaran

### Sub-CPMK (Rangkaian Minggu 5–7: Tahap 3)
> **Mahasiswa mampu mengintegrasikan operasi CRUD berbasis class dan arsitektur repository dengan MySQLi Object (C3, A4, P2).**

### Tujuan Praktikum
Setelah menyelesaikan sesi praktikum ini, mahasiswa diharapkan mampu:
1. Memisahkan tanggung jawab kode (*Separation of Concerns*) antara konfigurasi database, logika manipulasi data (*Repository*), dan antarmuka tampilan (*View*).
2. Membangun operasi lengkap **CRUD (Create, Read, Update, Delete)** menggunakan class `MahasiswaRepository`.
3. Memastikan seluruh manipulasi parameter dinamis menggunakan **Prepared Statement** secara konsisten.
4. Menerapkan pola **PRG (Post-Redirect-Get)** dan pesan umpan balik (*Flash Messages*) untuk pengalaman pengguna yang baik.
5. Mengelola penanganan eksepsi database pada tingkat aplikasi tanpa menampilkan pesan error mentah ke pengguna.

---

## II. Dasar Teori

### 1. Pola Repository (*Repository Pattern*)

Dalam aplikasi berorientasi objek yang terstruktur, kita tidak menulis query SQL langsung di dalam file tampilan HTML. Kita memisahkannya ke dalam class khusus yang disebut **Repository**.

```
[ Browser Client ] ──(Request)──> [ Halaman Controller / Script (index.php, tambah.php) ]
                                                            │
                                        memanggil method domain (semua(), tambah(), ubah())
                                                            v
                                            [ MahasiswaRepository Class ]
                                                            │
                                           mengeksekusi SQL via Prepared Statement
                                                            v
                                            [ Objek MySQLi / Database ]
```

Keuntungan Pola Repository:
- **Maintainability**: Jika struktur tabel atau query berubah, kita cukup mengedit satu file repository saja.
- **Reusability**: Method seperti `semua()` atau `cariBerdasarkanId()` dapat dipanggil dari berbagai halaman tanpa menulis ulang query.
- **Testability**: Logika akses database menjadi mudah diuji secara terpisah.

---

### 2. Struktur Proyek Aplikasi CRUD

```text
Week7/starter/
├── config/
│   └── Database.php          # Class singleton / factory koneksi MySQLi
├── src/
│   └── MahasiswaRepository.php # Class penyedia operasi CRUD
├── database/
│   └── setup.sql             # Skrip pembuatan tabel database
├── index.php                 # READ: Menampilkan tabel daftar mahasiswa
├── tambah.php                # CREATE: Formulir & proses tambah data
├── edit.php                  # UPDATE: Formulir & proses ubah data
├── hapus.php                 # DELETE: Pemrosesan hapus data
```

---

### 3. Implementasi Class `MahasiswaRepository`

```php
class MahasiswaRepository
{
    // Dependency Injection: Menerima objek mysqli aktif
    public function __construct(private mysqli $db) {}

    public function semua(): array
    {
        $result = $this->db->query("SELECT id, nim, nama, program_studi, ipk FROM mahasiswa ORDER BY nim ASC");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function cariBerdasarkanId(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT id, nim, nama, program_studi, ipk FROM mahasiswa WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $res ?: null;
    }

    public function tambah(string $nim, string $nama, string $prodi, float $ipk): bool
    {
        $stmt = $this->db->prepare("INSERT INTO mahasiswa (nim, nama, program_studi, ipk) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssd", $nim, $nama, $prodi, $ipk);
        $sukses = $stmt->execute();
        $stmt->close();
        return $sukses;
    }

    public function ubah(int $id, string $nim, string $nama, string $prodi, float $ipk): bool
    {
        $stmt = $this->db->prepare("UPDATE mahasiswa SET nim = ?, nama = ?, program_studi = ?, ipk = ? WHERE id = ?");
        $stmt->bind_param("sssdi", $nim, $nama, $prodi, $ipk, $id);
        $sukses = $stmt->execute();
        $stmt->close();
        return $sukses;
    }

    public function hapus(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM mahasiswa WHERE id = ?");
        $stmt->bind_param("i", $id);
        $sukses = $stmt->execute();
        $stmt->close();
        return $sukses;
    }
}
```

---

## III. Langkah-Langkah Praktikum

Buka folder `Week7/starter/` pada teks editor Anda:

### 1. Konfigurasi Database
Pastikan file `config/Database.php` telah terkonfigurasi dengan nama host, database `pemrograman_lanjut`, user `root`, dan password lokal Anda.

### 2. Membuka Halaman Utama (READ)
Buka `index.php` pada browser Anda. Amati bagaimana `MahasiswaRepository::semua()` mengambil data dan menampilkannya dengan `htmlspecialchars()`.

### 3. Menguji Fitur Tambah Data (CREATE)
Klik tombol **"Tambah Mahasiswa"**, isi formulir, dan simpan. Amati validasi data dan pesan sukses yang muncul setelah proses redirect.

### 4. Menguji Fitur Ubah Data (UPDATE)
Klik tautan **"Edit"** pada salah satu baris mahasiswa, ubah nilainya, dan submit.

### 5. Menguji Fitur Hapus Data (DELETE)
Klik tautan **"Hapus"** pada salah satu baris untuk memverifikasi penghapusan data dengan prepared statement.

---

## IV. Tugas Praktik Mandiri

Buat modul CRUD baru di folder `Week7/starter/` untuk entitas **Mata Kuliah** (`DosenPengampuRepository` atau `MataKuliahRepository`):
1. Buat tabel `mata_kuliah` di database dengan kolom: `id`, `kode_mk`, `nama_mk`, `sks` (integer 1-6), `semester` (integer 1-8).
2. Buat class `MataKuliahRepository` dengan method CRUD lengkap (`semua`, `cariBerdasarkanId`, `tambah`, `ubah`, `hapus`).
3. Buat halaman antarmuka web untuk mengelola data mata kuliah dengan antarmuka yang rapi dan aman.

---

## V. Checklist Pemahaman Praktikum

| No | Indikator Ketercapaian | Status |
|:--:|---|:---:|
| 1 | Saya memahami konsep arsitektur Repository Pattern | [ ] |
| 2 | Saya mampu memisahkan class koneksi database dari logika query | [ ] |
| 3 | Saya mampu mengimplementasikan operasi INSERT, SELECT, UPDATE, DELETE via class | [ ] |
| 4 | Saya selalu menggunakan prepared statement pada seluruh input dinamis | [ ] |
| 5 | Saya menerapkan redirect dan penanganan error yang ramah pengguna | [ ] |
