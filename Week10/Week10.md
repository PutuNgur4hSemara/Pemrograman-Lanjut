# Modul Praktikum 10 — PDO Bagian 2: Prepared Statement, CRUD & Transaksi

**Mata Kuliah:** Pemrograman Lanjut (3 SKS)  
**Pertemuan:** Minggu Ke-10  
**Durasi:** 1 × 120 Menit  
**Prasyarat:** Modul Praktikum 9 (PDO Bagian 1)  

---

## I. Sub-CPMK & Tujuan Pembelajaran

### Sub-CPMK (Rangkaian Minggu 9–10: Tahap 2)
> **Mahasiswa mampu mengaplikasikan prepared statement, parameter binding (positional & named), operasi CRUD, serta manajemen transaksi pada PDO secara aman (C3, A4, P2).**

### Tujuan Praktikum
Setelah menyelesaikan sesi praktikum ini, mahasiswa diharapkan mampu:
1. Membuat **Prepared Statement** pada PDO untuk mencegah SQL Injection secara total.
2. Membedakan dan menerapkan **Positional Parameter** (`?`) dan **Named Parameter** (`:parameter_name`).
3. Menggunakan method **`bindParam()`**, **`bindValue()`**, atau langsung melewatkan array nilai ke dalam method **`execute()`**.
4. Membangun aplikasi **CRUD (Create, Read, Update, Delete)** lengkap menggunakan PDO.
5. Mengelola **Database Transaction** pada PDO (`beginTransaction`, `commit`, `rollBack`) untuk menjamin konsistensi data relasional.

---

## II. Dasar Teori

### 1. Positional Parameter vs Named Parameter

PDO mendukung dua jenis parameter placeholder:

#### A. Positional Parameter (`?`)
Parameter ditandai dengan tanda tanya berurutan:
```php
$stmt = $pdo->prepare("SELECT * FROM koleksi_buku WHERE kategori = ? AND stok >= ?");
$stmt->execute(['Programming', 3]);
```

#### B. Named Parameter (`:nama`) — *Sangat Direkomendasikan*
Parameter ditandai dengan awalan titik dua (`:`), membuat kode jauh lebih mudah dibaca dan tidak rentan salah urutan indeks:
```php
$stmt = $pdo->prepare("SELECT * FROM koleksi_buku WHERE kategori = :kat AND stok >= :minStok");
$stmt->execute([
    ':kat' => 'Programming',
    ':minStok' => 3
]);
```

---

### 2. Cara Mengikat Nilai: `bindValue()` vs `bindParam()`

- **`bindValue($param, $value)`**: Mengikat parameter ke sebuah **nilai konstan atau variabel saat ini**. Nilai langsung disalin.
- **`bindParam($param, &$variable)`**: Mengikat parameter ke **referensi variabel**. Jika variabel berubah sebelum `execute()`, nilai yang terkirim adalah nilai variabel terbaru.
- **Array pada `execute([...])`**: Pendekatan paling modern dan ringkas di PHP modern.

```php
$stmt = $pdo->prepare("INSERT INTO koleksi_buku (kode_buku, judul, stok) VALUES (:kode, :judul, :stok)");

// Cara Ringkas:
$stmt->execute([
    ':kode' => 'BK-101',
    ':judul' => 'Arsitektur Perangkat Lunak Modern',
    ':stok' => 10
]);
```

---

### 3. Transaksi pada PDO (Atomic Transactions)

Transaksi sangat penting ketika sebuah operasi bisnis melibatkan lebih dari satu mutasi data di database:

```
[ Mulai Transaksi: beginTransaction() ]
                 │
                 ├─── 1. Cek & Kurangi Stok Buku (UPDATE)
                 │
                 ├─── 2. Tambah Catatan Peminjaman (INSERT)
                 │
           (Apakah semua sukses?)
          /                      \
       [ YA ]                   [ TIDAK / ERROR ]
         │                              │
         v                              v
[ commit() ]                   [ rollBack() ]
(Simpan permanen)              (Batalkan seluruh perubahan)
```

```php
try {
    $pdo->beginTransaction();

    // 1. Kurangi stok buku
    $stmt1 = $pdo->prepare("UPDATE koleksi_buku SET stok = stok - 1 WHERE id = :id AND stok > 0");
    $stmt1->execute([':id' => $idBuku]);
    
    if ($stmt1->rowCount() === 0) {
        throw new RuntimeException("Stok buku habis atau buku tidak ditemukan.");
    }

    // 2. Catat peminjaman
    $stmt2 = $pdo->prepare("INSERT INTO peminjaman_buku (id_buku, nama_peminjam, nim, tanggal_pinjam) VALUES (:id, :nama, :nim, :tgl)");
    $stmt2->execute([
        ':id' => $idBuku,
        ':nama' => $namaMhs,
        ':nim' => $nimMhs,
        ':tgl' => date('Y-m-d')
    ]);

    // Komit transaksi
    $pdo->commit();
    echo "Peminjaman berhasil dicatat!";
} catch (Exception $e) {
    // Batalkan seluruh aksi
    $pdo->rollBack();
    error_log("Peminjaman gagal: " . $e->getMessage());
    echo "Gagal: " . $e->getMessage();
}
```

---

## III. Langkah-Langkah Praktikum

Buka folder `Week10/starter/` pada teks editor Anda:

### Latihan 1: Parameter Binding Positional vs Named
Buka `01_binding_parameters.php`:
1. Amati perbedaan sintaks positional parameter dan named parameter.
2. Uji query pencarian buku berdasarkan judul dan rentang tahun terbit.

### Latihan 2: CRUD Lengkap Berbasis PDO
Buka `02_crud_buku_pdo.php`:
1. Mempelajari operasi Create, Read, Update, Delete buku perpustakaan menggunakan PDO.
2. Amati bagaimana `rowCount()` digunakan untuk mengecek keberhasilan modifikasi baris.

### Latihan 3: Transaksi Peminjaman Buku Perpustakaan
Buka `03_transaksi_peminjaman.php`:
1. Melakukan transaksi peminjaman buku yang memotong stok dan mencatat riwayat peminjam.
2. Menguji skenario saat stok buku 0 untuk membuktikan mekanisme `rollBack()`.

---

## IV. Tugas Praktik Mandiri

Buat file `latihan_mandiri.php` di dalam folder `Week10/starter/`:

1. **Skenario Sistem Pengembalian Buku Perpustakaan**:
   - Buat proses pengembalian buku dalam satu transaksi PDO:
     1. Ubah status peminjaman dari `Dipinjam` menjadi `Kembali` pada tabel `peminjaman_buku`.
     2. Tambah stok buku (+1) pada tabel `koleksi_buku`.
     3. Jika tanggal kembali melebihi batas waktu 7 hari, catat denda keterlambatan (Rp 2.000/hari).
   - Seluruh alur harus berjalan di dalam blok `beginTransaction()` $\rightarrow$ `commit()` $\rightarrow$ `rollBack()`.
2. Sajikan formulir pengembalian dan riwayat peminjaman dalam tabel HTML yang rapi.

---

## V. Checklist Pemahaman Praktikum

| No | Indikator Ketercapaian | Status |
|:--:|---|:---:|
| 1 | Saya memahami cara menggunakan `$pdo->prepare()` | [ ] |
| 2 | Saya mampu membedakan positional (`?`) dan named parameter (`:name`) | [ ] |
| 3 | Saya memahami perbedaan `bindValue()` dan `bindParam()` | [ ] |
| 4 | Saya mampu membangun alur CRUD lengkap dengan PDO | [ ] |
| 5 | Saya mampu mengoperasikan `beginTransaction()`, `commit()`, dan `rollBack()` | [ ] |
