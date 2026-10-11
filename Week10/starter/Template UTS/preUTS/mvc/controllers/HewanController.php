<?php
// ================================================
// File: controllers/HewanController.php
// Fungsi: menerima request user, memanggil model, lalu menampilkan view.
// ================================================

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/HewanModel.php';

class HewanController
{
    // Properti repository/model object.
    private HewanModel $model;

    // Constructor membuat model dan koneksi.
    public function __construct()
    {
        $db = Database::getConnection();
        $this->model = new HewanModel($db);
    }

    // Method index(): menampilkan daftar data hewan.
    public function index(): void
    {
        $hewan = $this->model->getAll();
        $pesan = $_GET['pesan'] ?? '';
        require __DIR__ . '/../views/hewan/index.php';
    }

    // Method tambah(): menampilkan form dan jika POST, simpan data.
    public function tambah(): void
    {
        $errors = [];
        $kode = $nama = $spesies = $status = '';
        $jumlah = $umur = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Ambil input form.
            $kode = trim($_POST['kode_hewan'] ?? '');
            $nama = trim($_POST['nama'] ?? '');
            $spesies = trim($_POST['spesies'] ?? '');
            $jumlah = trim($_POST['jumlah'] ?? '');
            $status = trim($_POST['status'] ?? '');
            $umur = trim($_POST['umur'] ?? '');

            // Validasi field.
            if ($kode === '') {
                $errors['kode_hewan'] = 'Kode hewan wajib diisi.';
            }
            if ($nama === '') {
                $errors['nama'] = 'Nama hewan wajib diisi.';
            }
            if ($spesies === '') {
                $errors['spesies'] = 'Spesies wajib diisi.';
            }
            if ($jumlah === '' || !ctype_digit((string)$jumlah) || (int)$jumlah < 0) {
                $errors['jumlah'] = 'Jumlah harus angka non-negatif.';
            }
            if ($status === '') {
                $errors['status'] = 'Status wajib dipilih.';
            }
            if ($umur === '' || !ctype_digit((string)$umur) || (int)$umur < 0) {
                $errors['umur'] = 'Umur harus angka non-negatif.';
            }

            // Jika tidak ada error, simpan data.
            if (empty($errors)) {
                $this->model->create($kode, $nama, $spesies, (int)$jumlah, $status, (int)$umur);
                header('Location: index.php?pesan=' . urlencode("Data hewan '{$nama}' berhasil ditambahkan."));
                exit;
            }
        }

        require __DIR__ . '/../views/hewan/form.php';
    }

    // Method edit(): menampilkan form edit dan proses update.
    public function edit(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $hewan = $this->model->findById($id);

        if (!$hewan) {
            header('Location: index.php');
            exit;
        }

        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $kode = trim($_POST['kode_hewan'] ?? '');
            $nama = trim($_POST['nama'] ?? '');
            $spesies = trim($_POST['spesies'] ?? '');
            $jumlah = trim($_POST['jumlah'] ?? '');
            $status = trim($_POST['status'] ?? '');
            $umur = trim($_POST['umur'] ?? '');

            if ($kode === '') {
                $errors['kode_hewan'] = 'Kode hewan wajib diisi.';
            }
            if ($nama === '') {
                $errors['nama'] = 'Nama hewan wajib diisi.';
            }
            if ($spesies === '') {
                $errors['spesies'] = 'Spesies wajib diisi.';
            }
            if ($jumlah === '' || !ctype_digit((string)$jumlah) || (int)$jumlah < 0) {
                $errors['jumlah'] = 'Jumlah harus angka non-negatif.';
            }
            if ($status === '') {
                $errors['status'] = 'Status wajib dipilih.';
            }
            if ($umur === '' || !ctype_digit((string)$umur) || (int)$umur < 0) {
                $errors['umur'] = 'Umur harus angka non-negatif.';
            }

            if (empty($errors)) {
                $this->model->update($id, $kode, $nama, $spesies, (int)$jumlah, $status, (int)$umur);
                header('Location: index.php?pesan=' . urlencode("Data hewan '{$nama}' berhasil diperbarui."));
                exit;
            }
        }

        require __DIR__ . '/../views/hewan/form.php';
    }

    // Method hapus(): menghapus data dari database.
    public function hapus(): void
    {
        $id = (int)($_GET['id'] ?? 0);

        if ($id > 0) {
            $this->model->delete($id);
        }

        header('Location: index.php?pesan=' . urlencode('Data hewan berhasil dihapus.'));
        exit;
    }
}
