<?php
// =====================================================
// CONTROLLER: MahasiswaController.php
// Tanggung jawab: Menerima request, koordinasi Model & View
// =====================================================

require_once __DIR__ . '/../models/MahasiswaModel.php';

class MahasiswaController {

    private MahasiswaModel $model;

    public function __construct() {
        // Controller memiliki Model sebagai "asisten"
        $this->model = new MahasiswaModel();
    }

    /**
     * ACTION index(): Tampilkan daftar semua mahasiswa
     * URL: http://localhost/praktikum_7/ atau ?halaman=index
     */
    public function index(): void {
        // 1. Minta data ke Model
        $mahasiswa = $this->model->getAll();

        // 2. Kirim data ke View untuk ditampilkan
        require __DIR__ . '/../views/mahasiswa/index.php';
    }

    /**
     * ACTION tambah(): Tampilkan form DAN proses submit form
     * URL: ?halaman=tambah
     * GET  → tampilkan form kosong
     * POST → validasi & simpan, redirect ke index
     */
    public function tambah(): void {
        // Inisialisasi variabel
        $errors = [];
        $nim = $nama = $jurusan = $ipk = "";

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // --- Ambil dan bersihkan input ---
            $nim     = trim($_POST['nim']     ?? '');
            $nama    = trim($_POST['nama']    ?? '');
            $jurusan = trim($_POST['jurusan'] ?? '');
            $ipk     = trim($_POST['ipk']     ?? '');

            // --- Validasi ---
            if (empty($nim)) {
                $errors['nim'] = 'NIM wajib diisi.';
            } elseif (!ctype_digit($nim) || strlen($nim) !== 8) {
                $errors['nim'] = 'NIM harus 8 digit angka.';
            } elseif ($this->model->nimSudahAda($nim)) {
                $errors['nim'] = 'NIM sudah terdaftar.';
            }

            if (empty($nama)) {
                $errors['nama'] = 'Nama wajib diisi.';
            } elseif (strlen($nama) < 3 || strlen($nama) > 100) {
                $errors['nama'] = 'Nama harus 3-100 karakter.';
            }

            if (empty($jurusan)) {
                $errors['jurusan'] = 'Jurusan wajib diisi.';
            }

            if (empty($ipk)) {
                $errors['ipk'] = 'IPK wajib diisi.';
            } elseif (!is_numeric($ipk) || $ipk < 0 || $ipk > 4) {
                $errors['ipk'] = 'IPK harus angka antara 0.00 dan 4.00.';
            }

            // --- Simpan jika tidak ada error ---
            if (empty($errors)) {
                $this->model->create([
                    'nim'     => $nim,
                    'nama'    => $nama,
                    'jurusan' => $jurusan,
                    'ipk'     => (float) $ipk,
                ]);

                // Redirect ke halaman index setelah berhasil
                // (Post/Redirect/Get pattern — mencegah form ter-submit ulang saat refresh)
                header('Location: index.php?sukses=1');
                exit;
            }
        }

        // Kirim ke view form (baik GET maupun POST yang gagal validasi)
        require __DIR__ . '/../views/mahasiswa/form.php';
    }

    /**
     * ACTION hapus(): Hapus mahasiswa berdasarkan ID dari URL
     * URL: ?halaman=hapus&id=3
     */
    public function hapus(): void {
        $id = (int) ($_GET['id'] ?? 0);

        if ($id > 0) {
            $this->model->delete($id);
        }

        // Redirect kembali ke daftar setelah hapus
        header('Location: index.php?hapus=1');
        exit;
    }
}
