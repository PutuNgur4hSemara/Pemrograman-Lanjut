<?php
// =====================================================
// MODEL: MahasiswaModel.php
// Tanggung jawab: SEMUA operasi database untuk tabel mahasiswa
// =====================================================

class MahasiswaModel {

    // Properti untuk menyimpan koneksi PDO
    private PDO $pdo;

    /**
     * Constructor: membuat koneksi PDO saat object dibuat
     */
    public function __construct() {
        // Konfigurasi database — sesuaikan jika perlu
        $host   = "localhost";
        $db     = "praktikum_7";
        $user   = "root";
        $pass   = "";         // Kosong = default XAMPP

        try {
            $this->pdo = new PDO(
                "mysql:host={$host};dbname={$db};charset=utf8",
                $user,
                $pass,
                [
                    // Error langsung menjadi exception (mudah di-debug)
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    // Hasil fetch menjadi array associative
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]
            );
        } catch (PDOException $e) {
            // Hentikan aplikasi jika koneksi gagal
            die("<h2>❌ Koneksi Database Gagal</h2><p>" . $e->getMessage() . "</p>
                 <p>Pastikan: XAMPP MySQL berjalan, database 'praktikum_7' sudah dibuat.</p>");
        }
    }

    /**
     * READ: Ambil semua data mahasiswa, diurutkan by nama
     * 
     * @return array Array of arrays (setiap elemen = satu baris)
     */
    public function getAll(): array {
        // Prepared statement tanpa parameter (query statis)
        $stmt = $this->pdo->prepare("SELECT * FROM mahasiswa ORDER BY nama ASC");
        $stmt->execute();
        return $stmt->fetchAll(); // Kembalikan semua baris
    }

    /**
     * READ: Ambil satu mahasiswa berdasarkan ID
     * 
     * @param int $id ID mahasiswa
     * @return array|false Satu baris atau false jika tidak ditemukan
     */
    public function findById(int $id): array|false {
        $stmt = $this->pdo->prepare("SELECT * FROM mahasiswa WHERE id = ?");
        $stmt->execute([$id]); // ? diganti nilai $id secara aman
        return $stmt->fetch();
    }

    /**
     * CREATE: Tambah mahasiswa baru ke database
     * 
     * @param array $data Berisi: nim, nama, jurusan, ipk
     * @return bool True jika berhasil
     */
    public function create(array $data): bool {
        // Named placeholder (:nim, :nama, dll.) untuk kejelasan
        $sql = "INSERT INTO mahasiswa (nim, nama, jurusan, ipk) 
                VALUES (:nim, :nama, :jurusan, :ipk)";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':nim'     => $data['nim'],
            ':nama'    => $data['nama'],
            ':jurusan' => $data['jurusan'],
            ':ipk'     => $data['ipk'],
        ]);
    }

    /**
     * DELETE: Hapus mahasiswa berdasarkan ID
     * 
     * @param int $id ID mahasiswa yang akan dihapus
     * @return bool True jika berhasil
     */
    public function delete(int $id): bool {
        $stmt = $this->pdo->prepare("DELETE FROM mahasiswa WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Cek apakah NIM sudah terdaftar (untuk validasi duplikat)
     * 
     * @param string $nim NIM yang dicek
     * @return bool True jika NIM sudah ada
     */
    public function nimSudahAda(string $nim): bool {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM mahasiswa WHERE nim = ?");
        $stmt->execute([$nim]);
        return $stmt->fetchColumn() > 0; // fetchColumn() ambil nilai kolom pertama
    }
}
