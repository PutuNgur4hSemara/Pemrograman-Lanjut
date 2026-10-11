# Modul Praktikum 11 — Database Query Builder Bagian 1: Perancangan Class & Wrapper PDO

**Mata Kuliah:** Pemrograman Lanjut (3 SKS)  
**Pertemuan:** Minggu Ke-11  
**Durasi:** 1 × 120 Menit  
**Prasyarat:** Modul Praktikum 9 & 10 (PDO)  

---

## I. Sub-CPMK & Tujuan Pembelajaran

### Sub-CPMK (Rangkaian Minggu 11–13: Tahap 1)
> **Mahasiswa mampu menganalisis kebutuhan, merancang class, dan mengimplementasikan wrapper database berbasis PDO sebagai fondasi pembuatan Query Builder (C3, A4, P2).**

### Tujuan Praktikum
Setelah menyelesaikan sesi praktikum ini, mahasiswa diharapkan mampu:
1. Memahami latar belakang dan keuntungan menggunakan **Query Builder** dibandingkan menulis query SQL mentah (*raw SQL string*).
2. Merancang arsitektur class database dengan prinsip **Dependency Injection** (menerima instance PDO melalui constructor).
3. Membangun method pembantu eksekusi query dinamis yang otomatis menangani prepared statement dan parameter binding.
4. Memisahkan *low-level database execution* dari logika bisnis aplikasi.

---

## II. Dasar Teori

### 1. Mengapa Kita Membangun Query Builder?

Meskipun PDO sudah aman dan mendukung multi-database, menulis string SQL manual (`SELECT * FROM ... WHERE ... ORDER BY ...`) di banyak tempat memiliki kelemahan:
1. **Rentan Salah Ketik (Syntax Error)**: Kesalahan satu koma atau spasi pada string baru terdeteksi saat runtime.
2. **Kondisi WHERE yang Dinamis Sangat Rumit**: Menyatukan string `WHERE` dengan banyak filter `if ($status) ... if ($tgl) ...` menggunakan penggabungan string manual menghasilkan kode berantakan (*spaghetti code*).
3. **Query Builder** menyediakan API berorientasi objek yang elegan (*Fluent Interface*) untuk menyusun dan mengeksekusi SQL secara otomatis.

```
Pendekatan Raw SQL:
$sql = "SELECT * FROM kegiatan WHERE status = 'terjadwal' ORDER BY tanggal ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute();

Pendekatan Query Builder (Target Akhir Minggu 11–13):
$kegiatan = $builder
    ->table('kegiatan')
    ->select(['kode', 'nama', 'tanggal'])
    ->where('status', '=', 'terjadwal')
    ->orderBy('tanggal', 'ASC')
    ->get();
```

---

### 2. Prinsip Desain Class Database Core (Tahap 1)

Sebelum membuat logika perakit string SQL (Minggu 12 & 13), kita harus memiliki **Class Database Core** yang bertugas membungkus PDO (*PDO Wrapper*).

Prinsip desain utama:
1. **Dependency Injection**: Instance PDO dikirimkan melalui constructor.
2. **Method Eksekusi Parameter**: Sebuah method sentral `run($sql, $params)` yang otomatis melakukan `prepare` dan `execute`.
3. **Penyederhanaan Fetching**: Method langsung mengembalikan array hasil (`fetchAll()`) atau boolean keberhasilan.

```php
class Database
{
    public function __construct(private PDO $pdo) {}

    /**
     * Mengeksekusi query SQL dengan prepared statement
     */
    public function run(string $sql, array $params = []): PDOStatement
    {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            error_log("Database Error: " . $e->getMessage());
            throw new RuntimeException("Terjadi kesalahan saat memproses data basis data.");
        }
    }

    public function getAll(string $sql, array $params = []): array
    {
        return $this->run($sql, $params)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRow(string $sql, array $params = []): ?array
    {
        $result = $this->run($sql, $params)->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function getAffectedRows(string $sql, array $params = []): int
    {
        return $this->run($sql, $params)->rowCount();
    }
}
```

---

## III. Langkah-Langkah Praktikum

Buka folder `Week11/starter/` pada teks editor Anda:

### 1. Inisialisasi Database Agenda Kegiatan
Jalankan file `Week11/database/setup_kegiatan.sql` untuk membuat tabel `kegiatan`.

### 2. Latihan 1: Perancangan Class Database Wrapper
Buka `01_class_database.php`:
1. Pelajari bagaimana class `Database` menerima objek `PDO` di constructor.
2. Amati bagaimana method `getAll()` dan `getRow()` menyederhanakan kode pemanggilan data.

### 3. Latihan 2: Eksekusi Query Dinamis Berparameter
Buka `02_eksekusi_parameter.php`:
1. Menguji eksekusi query filter agenda kegiatan dengan status tertentu.
2. Menguji method `getAffectedRows()` untuk pembaruan status kegiatan.

---

## IV. Tugas Praktik Mandiri

Buat file baru `latihan_mandiri.php` di dalam folder `Week11/starter/`:

1. Kembangkan class `Database` dengan menambahkan method:
   - `public function getColumn(string $sql, array $params = []): mixed` $\rightarrow$ Mengembalikan nilai skalar tunggal (misal `COUNT(*)`).
   - `public function lastInsertId(): string` $\rightarrow$ Mengembalikan ID baris terakhir yang baru saja di-insert.
2. Buat aplikasi mini pemantauan agenda kegiatan yang menghitung total kegiatan aktif menggunakan method `getColumn()`.

---

## V. Checklist Pemahaman Praktikum

| No | Indikator Ketercapaian | Status |
|:--:|---|:---:|
| 1 | Saya memahami alasan pengembangan Query Builder | [ ] |
| 2 | Saya memahami penerapan Dependency Injection pada class Database | [ ] |
| 3 | Saya mampu membuat method pembungkus eksekusi query dinamis | [ ] |
| 4 | Saya mampu menangani eksepsi di lapisan wrapper secara bersih | [ ] |
