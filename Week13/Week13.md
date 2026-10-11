# Modul Praktikum 13 — Database Query Builder Bagian 3: INSERT, UPDATE, DELETE & Reset State

**Mata Kuliah:** Pemrograman Lanjut (3 SKS)  
**Pertemuan:** Minggu Ke-13  
**Durasi:** 1 × 120 Menit  
**Prasyarat:** Modul Praktikum 12 (Query Builder Bagian 2)  

---

## I. Sub-CPMK & Tujuan Pembelajaran

### Sub-CPMK (Rangkaian Minggu 11–13: Tahap 3)
> **Mahasiswa mampu mengimplementasikan operasi perubahan data (INSERT, UPDATE, DELETE), fitur limit & single fetch (`first()`), manajemen reset state, serta pengujian Query Builder lengkap (C3, A4, P2).**

### Tujuan Praktikum
Setelah menyelesaikan sesi praktikum ini, mahasiswa diharapkan mampu:
1. Mengimplementasikan method **`insert(array $data)`** yang merakit SQL `INSERT INTO tabel (kolom...) VALUES (?, ...)` dari associative array.
2. Mengimplementasikan method **`update(array $data)`** dengan menggabungkan parameter update dan parameter klausul `WHERE`.
3. Mengimplementasikan method **`delete()`** yang aman dan mewajibkan klausul kondisi WHERE.
4. Menambahkan helper method **`limit(int $n)`** dan **`first(): ?array`**.
5. Menerapkan mekanisme **Reset State** agar properti internal query builder tidak bocor (*state leakage*) ke query berikutnya.
6. Melakukan pengujian fungsional Query Builder secara mandiri.

---

## II. Dasar Teori

### 1. Operasi Perubahan Data (DML)

#### A. Method `insert(array $data)`
Menerima array asosiatif key-value data yang akan disimpan:
```php
$builder->table('kegiatan')->insert([
    'kode' => 'ACT-09',
    'nama' => 'Seminar Cloud Computing',
    'lokasi' => 'Daring Zoom',
    'tanggal' => '2026-11-01',
    'kuota' => 100,
    'status' => 'terjadwal'
]);
```

#### B. Method `update(array $data)`
Memperbarui kolom sesuai kondisi WHERE:
```php
$builder->table('kegiatan')
        ->where('kode', '=', 'ACT-09')
        ->update(['status' => 'berlangsung', 'kuota' => 120]);
```

#### C. Method `delete()`
Menghapus record secara spesifik:
```php
$builder->table('kegiatan')
        ->where('kode', '=', 'ACT-09')
        ->delete();
```

---

### 2. Konsep Kritis: Reset State (Pembersihan Memori Internal)

Pada Query Builder berorientasi objek, jika sebuah instance `$builder` digunakan berulang kali:
- Jika kita memanggil `->where('status', '=', 'terjadwal')->get()`,
- Lalu memanggil query kedua `->where('kuota', '>', 50)->get()`,
- Tanpa **Reset State**, kondisi WHERE dari query pertama akan tetap menempel di query kedua!

Oleh karena itu, setiap kali method eksekusi (`get()`, `first()`, `insert()`, `update()`, `delete()`) selesai dijalankan, kita wajib mereset seluruh variabel internal kembali ke keadaan semula (*default state*):

```php
private function reset(): void
{
    $this->table = '';
    $this->columns = ['*'];
    $this->wheres = [];
    $this->bindings = [];
    $this->orders = [];
    $this->limit = null;
}
```

---

### 3. Implementasi QueryBuilder Lengkap

```php
class CompleteQueryBuilder
{
    private string $table = '';
    private array $columns = ['*'];
    private array $wheres = [];
    private array $bindings = [];
    private array $orders = [];
    private ?int $limit = null;

    public function __construct(private PDO $pdo) {}

    public function table(string $table): self { $this->table = $table; return $this; }
    public function select(array $columns): self { $this->columns = $columns; return $this; }

    public function where(string $column, string $operator, mixed $value): self
    {
        $this->wheres[] = "{$column} {$operator} ?";
        $this->bindings[] = $value;
        return $this;
    }

    public function limit(int $count): self
    {
        if ($count <= 0) throw new InvalidArgumentException("Limit harus bernilai positif.");
        $this->limit = $count;
        return $this;
    }

    public function first(): ?array
    {
        $this->limit(1);
        $results = $this->get();
        return $results[0] ?? null;
    }

    public function insert(array $data): bool
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";

        $stmt = $this->pdo->prepare($sql);
        $sukses = $stmt->execute(array_values($data));
        $this->reset();
        return $sukses;
    }

    public function update(array $data): int
    {
        $setClauses = [];
        $updateValues = [];
        foreach ($data as $col => $val) {
            $setClauses[] = "{$col} = ?";
            $updateValues[] = $val;
        }

        $sql = "UPDATE {$this->table} SET " . implode(', ', $setClauses);
        if (!empty($this->wheres)) {
            $sql .= " WHERE " . implode(' AND ', $this->wheres);
        }

        $allBindings = array_merge($updateValues, $this->bindings);
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($allBindings);
        $affected = $stmt->rowCount();
        $this->reset();
        return $affected;
    }

    public function delete(): int
    {
        if (empty($this->wheres)) {
            throw new RuntimeException("Operasi DELETE tanpa klausul WHERE ditolak demi keamanan data.");
        }

        $sql = "DELETE FROM {$this->table} WHERE " . implode(' AND ', $this->wheres);
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($this->bindings);
        $affected = $stmt->rowCount();
        $this->reset();
        return $affected;
    }

    private function reset(): void
    {
        $this->table = '';
        $this->columns = ['*'];
        $this->wheres = [];
        $this->bindings = [];
        $this->orders = [];
        $this->limit = null;
    }
}
```

---

## III. Langkah-Langkah Praktikum

Buka folder `Week13/starter/` pada teks editor Anda:

### Latihan 1: Operasi INSERT, UPDATE, dan DELETE
Buka `01_crud_builder.php`:
1. Menguji penambahan data baru menggunakan `$builder->table(...)->insert([...])`.
2. Menguji perubahan kuota kegiatan dengan `$builder->table(...)->where(...)->update([...])`.
3. Menguji penghapusan kegiatan dengan `$builder->table(...)->where(...)->delete()`.

### Latihan 2: Pengujian `first()`, `limit()` & Uji Kebocoran State (Reset State)
Buka `02_test_state_reset.php`:
1. Menjalankan query berurutan untuk membuktikan bahwa state query pertama tidak mencemari query kedua.
2. Menguji method `first()` untuk mengambil satu record tunggal secara aman.

---

## IV. Tugas Praktik Mandiri

Buat file baru `latihan_mandiri.php` di dalam folder `Week13/starter/`:

1. Buat class pengujian sederhana (*Test Runner*) yang menguji seluruh fitur Query Builder:
   - Pengujian INSERT data valid.
   - Pengujian WHERE ganda dan verifikasi isi data via `first()`.
   - Pengujian keamanan: Memastikan method `delete()` melempar Exception jika dipanggil tanpa `where()`.
   - Pengujian reset state.
2. Tampilkan laporan kelulusan tes dalam bentuk tabel hijau/merah (*Test Summary Dashboard*) di browser.

---

## V. Checklist Pemahaman Praktikum

| No | Indikator Ketercapaian | Status |
|:--:|---|:---:|
| 1 | Saya mampu membuat method `insert()` berbasis array key-value | [ ] |
| 2 | Saya mampu membuat method `update()` dengan penggabungan parameter set dan where | [ ] |
| 3 | Saya memahami pentingnya memblokir `delete()` tanpa where clause | [ ] |
| 4 | Saya memahami konsep dan implementasi Reset State pada Query Builder | [ ] |
| 5 | Saya memahami cara kerja method `limit()` dan `first()` | [ ] |
