# Modul Praktikum 14 — Validasi Class Bagian 1: Komponen Input, Validator & Rule Engine

**Mata Kuliah:** Pemrograman Lanjut (3 SKS)  
**Pertemuan:** Minggu Ke-14  
**Durasi:** 1 × 120 Menit  
**Prasyarat:** Modul Praktikum 3 (Encapsulation) & Modul 13 (Query Builder)  

---

## I. Sub-CPMK & Tujuan Pembelajaran

### Sub-CPMK (Rangkaian Minggu 14–15: Tahap 1)
> **Mahasiswa mampu merancang dan menerapkan validasi berbasis class (*Class-Based Validation*), komponen input, aturan validasi (*rule engine*), dan pengelolaan pesan kesalahan (*error collection*) (C3, A4).**

### Tujuan Praktikum
Setelah menyelesaikan sesi praktikum ini, mahasiswa diharapkan mampu:
1. Memahami arsitektur pemisahan tanggung jawab antara **Pembaca Input (`Input`)**, **Pemeriksa Aturan (`Validator`)**, dan **Penyimpan Data (`Repository`)**.
2. Membangun class `Input` untuk membaca data `$_POST`/`$_GET` serta menormalkan teks (*trimming*).
3. Membangun class `Validator` yang mendukung berbagai aturan: `required`, `min:n`, `max:n`, `email`, `integer`, dan `between:min,max`.
4. Mengelola kumpulan pesan kesalahan (*Error Bag*) per field input.
5. Membedakan secara tegas antara **Validasi Input** (memastikan tipe & logika data benar) dengan **Sanitasi Output** (`htmlspecialchars()`).

---

## II. Dasar Teori

### 1. Mengapa Validasi Berbasis Class?

Pada kode pemula, validasi form sering ditulis berulang-ulang dengan deretan `if (empty($_POST['nama'])) ... if (!filter_var($_POST['email'])) ...` di setiap halaman. Hal ini menyebabkan kode sulit dirawat dan rawan celah keamanan jika ada aturan yang terlewat.

Dalam arsitektur berorientasi objek:
1. **Class `Input`**: Bertanggung jawab mengambil data mentah dari superglobal dan membersihkan spasi tak perlu.
2. **Class `Validator`**: Mesin pemeriksa aturan (*Rule Engine*) yang independen dari tampilan HTML maupun database.
3. **Objek Domain / Model**: Hanya dibuat jika data telah lolos 100% validasi.

```
[ Form HTTP POST ] 
        │
        v
[ Class Input ] ──( Normalisasi teks, trim )
        │
        v
[ Class Validator ] ──( Uji Aturan: required, email, min:3 )
        │
        ├── [ GAGAL ] ──> Kumpulkan Error Bag ──> Tampilkan kembali ke Form
        │
        └── [ VALID ] ──> Buat Objek Domain ──> Kirim ke Repository / Database
```

---

### 2. Sintaks Perancangan Aturan Validasi

```php
$input = new Input($_POST);

$validator = new Validator();
$validator->validate($input, [
    'nama'         => ['required', 'min:3', 'max:50'],
    'email'        => ['required', 'email'],
    'jumlah_kursi' => ['required', 'integer', 'between:1,5'],
]);

if ($validator->passes()) {
    echo "Seluruh data valid!";
} else {
    $errors = $validator->errors(); // Mengembalikan array pesan error
}
```

---

### 3. Implementasi Class `Input` dan `Validator`

```php
class Input
{
    private array $data;

    public function __construct(array $source)
    {
        $this->data = array_map(function ($item) {
            return is_string($item) ? trim($item) : $item;
        }, $source);
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->data[$key] ?? $default;
    }

    public function all(): array
    {
        return $this->data;
    }
}

class Validator
{
    private array $errors = [];

    public function validate(Input $input, array $rules): bool
    {
        $this->errors = [];

        foreach ($rules as $field => $fieldRules) {
            $value = $input->get($field);

            foreach ($fieldRules as $rule) {
                // Rule: required
                if ($rule === 'required' && ($value === null || $value === '')) {
                    $this->addError($field, "Field {$field} wajib diisi.");
                    break;
                }

                // Jika field kosong dan bukan required, lewati aturan lainnya
                if ($value === null || $value === '') {
                    continue;
                }

                // Rule: email
                if ($rule === 'email' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->addError($field, "Format email pada {$field} tidak valid.");
                }

                // Rule: min:n
                if (str_starts_with($rule, 'min:')) {
                    $min = (int)substr($rule, 4);
                    if (strlen((string)$value) < $min) {
                        $this->addError($field, "{$field} minimal harus berisi {$min} karakter.");
                    }
                }

                // Rule: integer
                if ($rule === 'integer' && !filter_var($value, FILTER_VALIDATE_INT)) {
                    $this->addError($field, "{$field} harus berupa angka bulat.");
                }
            }
        }

        return empty($this->errors);
    }

    private function addError(string $field, string $message): void
    {
        $this->errors[$field][] = $message;
    }

    public function passes(): bool { return empty($this->errors); }
    public function fails(): bool { return !empty($this->errors); }
    public function errors(): array { return $this->errors; }
    public function getFirstError(string $field): ?string { return $this->errors[$field][0] ?? null; }
}
```

---

## III. Langkah-Langkah Praktikum

Buka folder `Week14/starter/` pada teks editor Anda:

### Latihan 1: Komponen Input & Normalisasi Teks
Buka `01_class_input.php`:
1. Menguji pembacaan data POST dan pembersihan spasi liar (*trimming*).

### Latihan 2: Pengujian Mesin Validator & Error Bag
Buka `02_validator_engine.php`:
1. Menerapkan aturan `required`, `email`, `min:n`, dan `integer`.
2. Menampilkan pesan error spesifik per elemen form input.

---

## IV. Tugas Praktik Mandiri

Buat file baru `latihan_mandiri.php` di dalam folder `Week14/starter/`:

1. Kembangkan class `Validator` dengan menambahkan 2 aturan baru:
   - Aturan `numeric`: Memeriksa apakah nilai merupakan angka valid (termasuk desimal).
   - Aturan `between:min,max`: Memeriksa apakah nilai numerik berada dalam rentang minimum dan maksimum tertentu.
2. Buat formulir pendaftaran lokakarya kampus dan terapkan aturan:
   - Nama: `['required', 'min:3']`
   - Email: `['required', 'email']`
   - Usia: `['required', 'integer', 'between:17,60']`
   - IPK: `['required', 'numeric', 'between:0.0,4.0']`

---

## V. Checklist Pemahaman Praktikum

| No | Indikator Ketercapaian | Status |
|:--:|---|:---:|
| 1 | Saya memahami konsep pemisahan class Input dan Validator | [ ] |
| 2 | Saya mampu membangun class Input untuk sanitasi awal | [ ] |
| 3 | Saya mampu membedah string aturan (misal `min:5`) | [ ] |
| 4 | Saya mampu mengelola Error Bag dan menampilkan error per field | [ ] |
| 5 | Saya memahami perbedaan validasi input vs sanitasi output | [ ] |
