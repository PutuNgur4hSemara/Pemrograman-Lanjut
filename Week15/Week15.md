# Modul Praktikum 15 — Validasi Class Bagian 2: Validasi Lanjutan, Sticky Form & Integrasi Database

**Mata Kuliah:** Pemrograman Lanjut (3 SKS)  
**Pertemuan:** Minggu Ke-15  
**Durasi:** 1 × 120 Menit  
**Prasyarat:** Modul Praktikum 14 (Validasi Class Bagian 1)  

---

## I. Sub-CPMK & Tujuan Pembelajaran

### Sub-CPMK (Rangkaian Minggu 14–15: Tahap 2)
> **Mahasiswa mampu mengintegrasikan validasi class, validasi lintas field, repopulasi form (sticky form), pembuatan domain model, serta penyimpanan ke database secara utuh (C3, A4).**

### Tujuan Praktikum
Setelah menyelesaikan sesi praktikum ini, mahasiswa diharapkan mampu:
1. Menerapkan **Validasi Lintas-Field** (*Cross-Field Validation*), seperti aturan `same:field_target` dan aturan pilihan `in:a,b,c`.
2. Menerapkan teknik **Sticky Form (Repopulasi Input)** pada berbagai elemen formulir: text input, radio button, select dropdown, dan checkbox array.
3. Membangun **Domain Model Entity** (`PesertaLokakarya`) dengan konstruktor tervalidasi.
4. Mengintegrasikan seluruh siklus aplikasi: **Form Request $\rightarrow$ `Input` $\rightarrow$ `Validator` $\rightarrow$ `Domain Object` $\rightarrow$ `Repository` $\rightarrow$ `Database`**.

---

## II. Dasar Teori

### 1. Validasi Lintas-Field (*Cross-Field Rules*)

Terkadang sebuah nilai field bergantung pada nilai field lain. Contoh:
- `password_konfirmasi` harus sama persis dengan `password` (`same:password`).
- Pilihan paket workshop harus merupakan salah satu dari nilai yang diizinkan (`in:dasar,menengah,lanjut`).

```php
// Aturan same:target_field
if (str_starts_with($rule, 'same:')) {
    $targetField = substr($rule, 5);
    $targetValue = $input->get($targetField);
    if ($value !== $targetValue) {
        $this->addError($field, "{$field} harus bernilai sama dengan {$targetField}.");
    }
}

// Aturan in:opsi1,opsi2,opsi3
if (str_starts_with($rule, 'in:')) {
    $allowed = explode(',', substr($rule, 3));
    if (!in_array($value, $allowed)) {
        $this->addError($field, "Pilihan {$field} tidak valid.");
    }
}
```

---

### 2. Repopulasi Form (*Sticky Form*)

Pengalaman pengguna (*User Experience*) yang buruk terjadi jika pengguna salah mengisi satu field, lalu seluruh formulir terhapus bersih dan harus diketik ulang dari awal.
Dengan **Sticky Form**, kita mengembalikan nilai lama dari `$input->get(...)` ke atribut `value`, `checked`, atau `selected`:

```html
<!-- Text Input Sticky -->
<input type="text" name="nama" value="<?= htmlspecialchars($input->get('nama', '')) ?>">

<!-- Select Option Sticky -->
<select name="paket">
    <option value="dasar" <?= $input->get('paket') === 'dasar' ? 'selected' : '' ?>>Paket Dasar</option>
    <option value="lanjut" <?= $input->get('paket') === 'lanjut' ? 'selected' : '' ?>>Paket Lanjut</option>
</select>

<!-- Radio Button Sticky -->
<input type="radio" name="gender" value="L" <?= $input->get('gender') === 'L' ? 'checked' : '' ?>> Laki-laki
<input type="radio" name="gender" value="P" <?= $input->get('gender') === 'P' ? 'checked' : '' ?>> Perempuan
```

---

### 3. Alur Lengkap: Dari Input Menuju Database

```
  [ Pengguna Submit Form ]
             │
             v
   [ 1. Class Input ] ──> Sanitasi spasi & normalisasi
             │
             v
 [ 2. Class Validator ] ──> Cek required, email, same, in
             │
      (Apakah valid?)
      /             \
   [TIDAK]          [YA]
      │               │
      │               v
      │     [ 3. Domain Model Object ]
      │     $peserta = new Peserta($nama, $email, $paket)
      │               │
      │               v
      │     [ 4. Database Repository / QueryBuilder ]
      │     $repo->simpan($peserta)
      │               │
      v               v
 [ Render Sticky Form ]   [ Redirect Sukses ]
 (Tampilkan error merah)
```

---

## III. Langkah-Langkah Praktikum

Buka folder `Week15/starter/` pada teks editor Anda:

### 1. Inisialisasi Database
Jalankan file `Week15/database/setup_lokakarya.sql` untuk menyiapkan tabel `peserta_lokakarya`.

### 2. Latihan 1: Validasi Lintas-Field & Pilihan (same & in)
Buka `01_cross_validation.php`:
1. Menguji validasi konfirmasi email dan pilihan kategori paket.

### 3. Latihan 2: Komponen Form Lengkap & Sticky Repopulasi
Buka `02_sticky_form_components.php`:
1. Mempelajari repopulasi radio button, select dropdown, dan checkbox topik minat.

### 4. Latihan 3: Integrasi End-to-End dengan Database
Buka `03_integrasi_database.php`:
1. Mengalirkan data valid ke class domain `PesertaLokakarya` dan menyimpannya menggunakan PDO Prepared Statement.

---

## IV. Tugas Praktik Mandiri

Buat file baru `latihan_mandiri.php` di dalam folder `Week15/starter/`:

1. **Skenario Registrasi Akun Asisten Laboratorium**:
   - Aturan validasi:
     - `nim`: `['required', 'min:8', 'max:10']`
     - `nama`: `['required', 'min:3']`
     - `email`: `['required', 'email']`
     - `password`: `['required', 'min:6']`
     - `konfirmasi_password`: `['required', 'same:password']`
     - `bidang_minat`: Minimal memilih 1 checkbox dari daftar keahlian (*Web*, *Mobile*, *AI*, *Data*).
   - Terapkan repopulasi sticky form secara menyeluruh.
   - Simpan data yang telah tervalidasi ke tabel database menggunakan hash password aman `password_hash($password, PASSWORD_DEFAULT)`.

---

## V. Checklist Pemahaman Praktikum

| No | Indikator Ketercapaian | Status |
|:--:|---|:---:|
| 1 | Saya memahami cara membuat aturan validasi lintas-field (`same:target`) | [ ] |
| 2 | Saya mampu membuat sticky form pada input, select, radio, dan checkbox | [ ] |
| 3 | Saya memahami mengapa Domain Model hanya dibuat setelah data lolos validasi | [ ] |
| 4 | Saya mampu mengintegrasikan seluruh komponen (Input + Validator + Model + DB) | [ ] |
