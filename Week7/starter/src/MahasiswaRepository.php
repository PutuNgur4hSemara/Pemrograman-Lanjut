<?php
/**
 * MahasiswaRepository: Pengelola Operasi Database Mahasiswa Berbasis PBO
 * Modul Praktikum Minggu 7
 */

class MahasiswaRepository
{
    public function __construct(private mysqli $db) {}

    public function semua(): array
    {
        $result = $this->db->query("SELECT id, nim, nama, program_studi, ipk FROM mahasiswa ORDER BY nim ASC");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function cariBerdasarkanId(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT id, nim, nama, program_studi, ipk FROM mahasiswa WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $res ?: null;
    }

    public function tambah(string $nim, string $nama, string $prodi, float $ipk): bool
    {
        $stmt = $this->db->prepare("INSERT INTO mahasiswa (nim, nama, program_studi, ipk) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssd", $nim, $nama, $prodi, $ipk);
        $sukses = $stmt->execute();
        $stmt->close();
        return $sukses;
    }

    public function ubah(int $id, string $nim, string $nama, string $prodi, float $ipk): bool
    {
        $stmt = $this->db->prepare("UPDATE mahasiswa SET nim = ?, nama = ?, program_studi = ?, ipk = ? WHERE id = ?");
        $stmt->bind_param("sssdi", $nim, $nama, $prodi, $ipk, $id);
        $sukses = $stmt->execute();
        $stmt->close();
        return $sukses;
    }

    public function hapus(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM mahasiswa WHERE id = ?");
        $stmt->bind_param("i", $id);
        $sukses = $stmt->execute();
        $stmt->close();
        return $sukses;
    }
}
