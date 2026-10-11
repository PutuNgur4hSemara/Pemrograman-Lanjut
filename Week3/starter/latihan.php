<?php
// =====================================================
// MODUL 3 — PHP DASAR
// File: latihan.php
//
// Cara menjalankan:
// 1. Salin file ini ke C:\xampp\htdocs\praktikum_3\
// 2. Buka XAMPP, Start Apache
// 3. Buka browser: http://localhost/praktikum_3/latihan.php
// =====================================================

// Aktifkan tampilan error (untuk development/latihan saja)
ini_set('display_errors', 1);
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Latihan PHP Dasar — Modul 3</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 900px; margin: 30px auto; padding: 0 20px; background: #f5f5f5; }
        h1 { color: #2c3e50; border-bottom: 3px solid #3498db; padding-bottom: 10px; }
        h2 { color: #2980b9; margin-top: 30px; }
        .kotak { background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #2c3e50; color: white; }
        tr:nth-child(even) { background-color: #f2f2f2; }
        code { background: #f0f0f0; padding: 2px 6px; border-radius: 3px; font-family: monospace; }
    </style>
</head>
<body>

<h1>Latihan PHP Dasar — Modul 3</h1>

<!-- =====================================================
     BAGIAN 1: VARIABEL DAN TIPE DATA
===================================================== -->
<div class="kotak">
    <h2>1. Variabel dan Tipe Data</h2>

    <?php
        // --- String: teks ---
        $nama = "Agus Ikhsyan";       // Ganti dengan namamu
        $jurusan = "Sistem Informasi";

        // --- Integer: bilangan bulat ---
        $nim = 10231001;              // Ganti dengan NIM-mu
        $tahun_lahir = 2003;          // Ganti dengan tahun lahirmu
        $tahun_sekarang = 2025;

        // --- Float: bilangan desimal ---
        $ipk = 3.75;

        // --- Boolean: benar/salah ---
        $sudah_lulus = false;

        // Menghitung usia dari tahun
        $usia = $tahun_sekarang - $tahun_lahir;

        // Menampilkan variabel
        echo "<p><strong>Nama:</strong> $nama</p>";
        echo "<p><strong>NIM:</strong> $nim</p>";
        echo "<p><strong>Jurusan:</strong> $jurusan</p>";
        echo "<p><strong>IPK:</strong> $ipk</p>";
        echo "<p><strong>Usia:</strong> $usia tahun</p>";
        
        // Menampilkan boolean — true = 1, false = "" (kosong)
        // Kita konversi ke teks agar lebih mudah dibaca
        $status_lulus = $sudah_lulus ? "Sudah lulus" : "Belum lulus";
        echo "<p><strong>Status:</strong> $status_lulus</p>";
    ?>
</div>

<!-- =====================================================
     BAGIAN 2: OPERASI ARITMATIKA
===================================================== -->
<div class="kotak">
    <h2>2. Operasi Aritmatika</h2>

    <?php
        $nilai_uts = 80;
        $nilai_uas = 90;
        $nilai_tugas = 85;

        // Menghitung nilai akhir dengan bobot
        // Rumus: UTS 30% + UAS 40% + Tugas 30%
        $nilai_akhir = ($nilai_uts * 0.30) + ($nilai_uas * 0.40) + ($nilai_tugas * 0.30);

        echo "<p>Nilai UTS: $nilai_uts</p>";
        echo "<p>Nilai UAS: $nilai_uas</p>";
        echo "<p>Nilai Tugas: $nilai_tugas</p>";
        echo "<p><strong>Nilai Akhir (UTS×30% + UAS×40% + Tugas×30%): $nilai_akhir</strong></p>";

        // Operasi modulus (sisa bagi) — contoh: cek apakah angkatan genap/ganjil
        $angkatan = 2023;
        $sisa = $angkatan % 2;
        echo "<p>Angkatan $angkatan adalah " . ($sisa == 0 ? "genap" : "ganjil") . ".</p>";
    ?>
</div>

<!-- =====================================================
     BAGIAN 3: PENGGABUNGAN STRING
===================================================== -->
<div class="kotak">
    <h2>3. Penggabungan String</h2>

    <?php
        $gelar_depan = "";
        $nama_mhs = "Budi Santoso";
        $gelar_belakang = ", S.Kom";

        // Cara 1: Titik (.) untuk menggabungkan
        $nama_lengkap = $gelar_depan . $nama_mhs . $gelar_belakang;
        echo "<p>Nama lengkap (titik): " . $nama_lengkap . "</p>";

        // Cara 2: Variabel langsung dalam string petik ganda
        echo "<p>Halo, $nama_mhs! Selamat datang.</p>";

        // Cara 3: Kurung kurawal (berguna jika variabel tepat di sebelah teks)
        $kota = "Surabaya";
        echo "<p>Beliau berasal dari kota {$kota}.</p>";
        
        // Fungsi string yang berguna
        echo "<p>Panjang nama: " . strlen($nama_mhs) . " karakter</p>";
        echo "<p>Huruf besar: " . strtoupper($nama_mhs) . "</p>";
        echo "<p>Huruf kecil: " . strtolower($nama_mhs) . "</p>";
    ?>
</div>

<!-- =====================================================
     BAGIAN 4: ARRAY INDEXED
===================================================== -->
<div class="kotak">
    <h2>4. Array Indexed (Daftar Berurutan)</h2>

    <?php
        // Array mata kuliah semester 3 SI
        // Index dimulai dari 0: [0], [1], [2], dst.
        $mata_kuliah = [
            "Pemrograman Lanjut",   // index 0
            "Basis Data",           // index 1
            "Analisis Sistem",      // index 2
            "Jaringan Komputer",    // index 3
            "Statistik",            // index 4
        ];

        echo "<p>Jumlah mata kuliah: " . count($mata_kuliah) . "</p>";
        echo "<p>Mata kuliah pertama (index 0): " . $mata_kuliah[0] . "</p>";

        echo "<ul>";
        // foreach: untuk setiap elemen di $mata_kuliah, simpan di $mk
        foreach ($mata_kuliah as $mk) {
            echo "<li>$mk</li>";
        }
        echo "</ul>";
    ?>
</div>

<!-- =====================================================
     BAGIAN 5: ARRAY ASSOCIATIVE
===================================================== -->
<div class="kotak">
    <h2>5. Array Associative (Key => Value)</h2>

    <?php
        // Array associative: setiap elemen punya nama (key)
        $mahasiswa = [
            "nama"    => "Agus Ikhsyan",
            "nim"     => "10231001",
            "jurusan" => "Sistem Informasi",
            "angkatan"=> 2023,
            "ipk"     => 3.75,
        ];

        echo "<p>Mengakses via key:</p>";
        echo "<p>Nama: " . $mahasiswa["nama"] . "</p>";
        echo "<p>NIM: " . $mahasiswa["nim"] . "</p>";

        echo "<p>Menampilkan semua key dan value:</p>";
        echo "<ul>";
        foreach ($mahasiswa as $kunci => $nilai) {
            // ucfirst = huruf pertama kapital
            echo "<li><strong>" . ucfirst($kunci) . ":</strong> $nilai</li>";
        }
        echo "</ul>";
    ?>
</div>

<!-- =====================================================
     BAGIAN 6: ARRAY OF ARRAYS — TABEL DATA
===================================================== -->
<div class="kotak">
    <h2>6. Array of Arrays — Data Mahasiswa dalam Tabel</h2>

    <?php
        // Array berisi banyak mahasiswa
        // Setiap elemen adalah array associative satu mahasiswa
        $daftar_mahasiswa = [
            ["nim" => "10231001", "nama" => "Agus Ikhsyan",    "angkatan" => 2023, "ipk" => 3.75],
            ["nim" => "10231002", "nama" => "Budi Santoso",    "angkatan" => 2023, "ipk" => 3.50],
            ["nim" => "10231003", "nama" => "Citra Lestari",   "angkatan" => 2023, "ipk" => 3.90],
            ["nim" => "10231004", "nama" => "Dian Pratama",    "angkatan" => 2022, "ipk" => 3.25],
            // Tambahkan mahasiswa lain di sini
        ];
    ?>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>NIM</th>
                <th>Nama</th>
                <th>Angkatan</th>
                <th>IPK</th>
            </tr>
        </thead>
        <tbody>
            <?php
                // $no = penomoran baris, mulai dari 1
                $no = 1;
                foreach ($daftar_mahasiswa as $mhs) {
                    echo "<tr>";
                    echo "<td>" . $no . "</td>";
                    echo "<td>" . $mhs["nim"] . "</td>";
                    echo "<td>" . $mhs["nama"] . "</td>";
                    echo "<td>" . $mhs["angkatan"] . "</td>";
                    echo "<td>" . $mhs["ipk"] . "</td>";
                    echo "</tr>";
                    $no++; // Tambah 1 setiap baris
                }
            ?>
        </tbody>
    </table>

    <?php
        // Hitung total dan rata-rata IPK
        $total_mahasiswa = count($daftar_mahasiswa);
        $total_ipk = 0;
        foreach ($daftar_mahasiswa as $mhs) {
            $total_ipk += $mhs["ipk"]; // += artinya tambahkan ke total
        }
        $rata_ipk = $total_ipk / $total_mahasiswa;

        echo "<p><strong>Total mahasiswa:</strong> $total_mahasiswa</p>";
        // number_format: format angka dengan 2 desimal
        echo "<p><strong>Rata-rata IPK:</strong> " . number_format($rata_ipk, 2) . "</p>";
    ?>
</div>

</body>
</html>
