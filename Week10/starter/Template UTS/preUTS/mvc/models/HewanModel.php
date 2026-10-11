<?php
// ================================================
// File: models/HewanModel.php
// Fungsi: menyimpan logika query database untuk tabel hewan.
// ================================================

class HewanModel
{
    // Properti $db menampung koneksi database.
    private mysqli $db;

    // Constructor menerima koneksi database dari controller.
    public function __construct(mysqli $db)
    {
        $this->db = $db;
    }

    // Method getAll(): mengambil semua data hewan.
    public function getAll(): array
    {
        $result = $this->db->query("SELECT * FROM hewan ORDER BY nama ASC");
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    // Method findById(): mencari satu data berdasarkan id.
    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM hewan WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $data = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $data ?: null;
    }

    // Method create(): menambah data baru.
    public function create(string $kode, string $nama, string $spesies, int $jumlah, string $status, int $umur): bool
    {
        $stmt = $this->db->prepare("INSERT INTO hewan (kode_hewan, nama, spesies, jumlah, status, umur) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('sssiis', $kode, $nama, $spesies, $jumlah, $status, $umur);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

    // Method update(): memperbarui data berdasarkan id.
    public function update(int $id, string $kode, string $nama, string $spesies, int $jumlah, string $status, int $umur): bool
    {
        $stmt = $this->db->prepare("UPDATE hewan SET kode_hewan = ?, nama = ?, spesies = ?, jumlah = ?, status = ?, umur = ? WHERE id = ?");
        $stmt->bind_param('sssiisi', $kode, $nama, $spesies, $jumlah, $status, $umur, $id);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

    // Method delete(): menghapus data berdasarkan id.
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM hewan WHERE id = ?");
        $stmt->bind_param('i', $id);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }
}
