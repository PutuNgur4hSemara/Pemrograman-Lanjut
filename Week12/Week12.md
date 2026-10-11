# Modul Praktikum 12 — Database Query Builder Bagian 2: SELECT Builder & Method Chaining

**Mata Kuliah:** Pemrograman Lanjut (3 SKS)  
**Pertemuan:** Minggu Ke-12  
**Durasi:** 1 × 120 Menit  
**Prasyarat:** Modul Praktikum 11 (Query Builder Bagian 1)  

---

## I. Sub-CPMK & Tujuan Pembelajaran

### Sub-CPMK (Rangkaian Minggu 11–13: Tahap 2)
> **Mahasiswa mampu mengimplementasikan SELECT query builder dengan fitur method chaining, multi-kondisi WHERE, ORDER BY, serta allowlist identifier untuk keamanan SQL (C3, A4, P2).**

### Tujuan Praktikum
Setelah menyelesaikan sesi praktikum ini, mahasiswa diharapkan mampu:
1. Menerapkan pola **Method Chaining** (`return $this`) untuk membangun query SQL secara dinamis dan ekspresif.
2. Mengelola array *internal state* (tabel, kolom select, klausul where, order by, dan binding parameters).
3. Mengamankan nama kolom dan operator SQL menggunakan teknik **Allowlist Validation**.
4. Mengkompilasi array internal menjadi string SQL valid beserta array parameter pengikatnya.
5. Mengeksekusi query hasil builder dan mengembalikan hasil dataset yang bersih.

---

## II. Dasar Teori

### 1. Anatomi dan Alur Kerja SELECT Query Builder

Sebuah query builder bekerja seperti perakit kalimat SQL. Setiap pemanggilan method menambahkan potongan instruksi ke dalam memori internal objek sebelum method eksekutor (`get()` atau `first()`) dipanggil.

```
$builder->table('kegiatan')                   // Menyimpan nama tabel: "kegiatan"
        ->select(['kode', 'nama', 'tanggal']) // Menyimpan kolom: ["kode", "nama", "tanggal"]
        ->where('status', '=', 'terjadwal')   // Menambah WHERE "status = ?" & binding: ["terjadwal"]
        ->where('kuota', '>=', 50)            // Menambah WHERE "kuota >= ?" & binding: [50]
        ->orderBy('tanggal', 'ASC')           // Menambah ORDER BY: "tanggal ASC"
        ->get();                              // Merakit SQL, bind parameter, jalankan via PDO
```

---

### 2. Allowlist Identifier untuk Mencegah SQL Injection Kolom

Prepared statement hanya dapat mengikat **nilai data** (`WHERE kolom = ?`), **BUKAN nama kolom atau operator** (`WHERE ? = 'nilai'`). Oleh karena itu, nama tabel, nama kolom, operator, dan arah urut wajib divalidasi menggunakan daftar putih (*allowlist*):

```php
private array $allowedOperators = ['=', '!=', '<', '<=', '>', '>=', 'LIKE', 'NOT LIKE'];
private array $allowedDirections = ['ASC', 'DESC'];

public function where(string $column, string $operator, mixed $value): self
{
    $operator = strtoupper($operator);
    if (!in_array($operator, $this->allowedOperators)) {
        throw new InvalidArgumentException("Operator SQL tidak diizinkan: {$operator}");
    }

    // Bersihkan nama kolom (hanya alfanumerik dan underscore)
    if (!preg_match('/^[a-zA-Z0-9_]+$/', $column)) {
        throw new InvalidArgumentException("Nama kolom tidak valid: {$column}");
    }

    $this->wheres[] = "{$column} {$operator} ?";
    $this->bindings[] = $value;

    return $this;
}
```

---

### 3. Struktur Class `QueryBuilder` (SELECT)

```php
class QueryBuilder
{
    private string $table = '';
    private array $columns = ['*'];
    private array $wheres = [];
    private array $bindings = [];
    private array $orders = [];

    public function __construct(private PDO $pdo) {}

    public function table(string $table): self
    {
        $this->table = $table;
        return $this;
    }

    public function select(array $columns): self
    {
        $this->columns = $columns;
        return $this;
    }

    public function where(string $column, string $operator, mixed $value): self
    {
        $this->wheres[] = "{$column} {$operator} ?";
        $this->bindings[] = $value;
        return $this;
    }

    public function orderBy(string $column, string $direction = 'ASC'): self
    {
        $dir = strtoupper($direction) === 'DESC' ? 'DESC' : 'ASC';
        $this->orders[] = "{$column} {$dir}";
        return $this;
    }

    public function toSql(): string
    {
        $sql = "SELECT " . implode(', ', $this->columns) . " FROM {$this->table}";
        if (!empty($this->wheres)) {
            $sql .= " WHERE " . implode(' AND ', $this->wheres);
        }
        if (!empty($this->orders)) {
            $sql .= " ORDER BY " . implode(', ', $this->orders);
        }
        return $sql;
    }

    public function get(): array
    {
        $sql = $this->toSql();
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($this->bindings);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
```

---

## III. Langkah-Langkah Praktikum

Buka folder `Week12/starter/` pada teks editor Anda:

### Latihan 1: Membangun Method Chaining SELECT
Buka `01_select_builder.php`:
1. Amati cara kerja perakitan SQL dengan method `table()`, `select()`, dan `toSql()`.
2. Verifikasi hasil query SQL yang dihasilkan.

### Latihan 2: Multi-Klausul WHERE dan Parameter Binding
Buka `02_where_chaining.php`:
1. Menggabungkan beberapa kondisi WHERE dengan operator `=`, `>=`, dan `LIKE`.
2. Memeriksa array `$bindings` untuk memastikan nilai parameter terisolasi dari struktur SQL.

### Latihan 3: Integrasi ORDER BY & Eksekusi Data
Buka `03_order_and_execution.php`:
1. Menerapkan sorting dinamis.
2. Memanggil method `get()` untuk menampilkan data kegiatan ke antarmuka tabel.

---

## IV. Tugas Praktik Mandiri

Buat file baru `latihan_mandiri.php` di dalam folder `Week12/starter/`:

1. Kembangkan class `QueryBuilder` dengan menambahkan method `whereIn(string $column, array $values): self`.
   - Contoh pemanggilan: `->whereIn('status', ['terjadwal', 'berlangsung'])`
   - Harus menghasilkan SQL: `status IN (?, ?)` dan menambahkan nilai-nilai tersebut ke array `$bindings`.
2. Uji method tersebut untuk menampilkan agenda kegiatan berdasarkan beberapa status sekaligus.

---

## V. Checklist Pemahaman Praktikum

| No | Indikator Ketercapaian | Status |
|:--:|---|:---:|
| 1 | Saya memahami bagaimana method chaining menyusun struktur query | [ ] |
| 2 | Saya memahami cara mengumpulkan array binding parameter | [ ] |
| 3 | Saya memahami pentingnya validasi allowlist untuk identifier SQL | [ ] |
| 4 | Saya mampu merakit string SQL SELECT dinamis | [ ] |
| 5 | Saya mampu mengeksekusi hasil builder dengan method `get()` | [ ] |
