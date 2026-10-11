<?php
/**
 * KendaraanRepository: Pengelola Operasi Database Rental Kendaraan
 * Modul Evaluasi UTS - Minggu 8
 */

require_once __DIR__ . '/Kendaraan.php';
require_once __DIR__ . '/Mobil.php';
require_once __DIR__ . '/Motor.php';

class KendaraanRepository
{
    public function __construct(private mysqli $db) {}

    /**
     * Mengembalikan daftar objek polimorfis Kendaraan (Mobil atau Motor)
     * @return Kendaraan[]
     */
    public function semua(): array
    {
        $sql = "SELECT id, plat_nomor, merk, tipe, harga_sewa_per_hari, spesifikasi, status FROM kendaraan ORDER BY id DESC";
        $res = $this->db->query($sql);
        $daftarObjek = [];

        while ($row = $res->fetch_assoc()) {
            if ($row['tipe'] === 'Mobil') {
                $daftarObjek[] = new Mobil(
                    (int)$row['id'],
                    $row['plat_nomor'],
                    $row['merk'],
                    (float)$row['harga_sewa_per_hari'],
                    $row['spesifikasi'],
                    $row['status']
                );
            } else {
                $daftarObjek[] = new Motor(
                    (int)$row['id'],
                    $row['plat_nomor'],
                    $row['merk'],
                    (float)$row['harga_sewa_per_hari'],
                    $row['spesifikasi'],
                    $row['status']
                );
            }
        }

        return $daftarObjek;
    }

    public function cariBerdasarkanId(int $id): ?Kendaraan
    {
        $stmt = $this->db->prepare("SELECT id, plat_nomor, merk, tipe, harga_sewa_per_hari, spesifikasi, status FROM kendaraan WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$row) return null;

        return ($row['tipe'] === 'Mobil')
            ? new Mobil((int)$row['id'], $row['plat_nomor'], $row['merk'], (float)$row['harga_sewa_per_hari'], $row['spesifikasi'], $row['status'])
            : new Motor((int)$row['id'], $row['plat_nomor'], $row['merk'], (float)$row['harga_sewa_per_hari'], $row['spesifikasi'], $row['status']);
    }

    public function tambah(string $plat, string $merk, string $tipe, float $tarif, string $spesifikasi): bool
    {
        $stmt = $this->db->prepare("INSERT INTO kendaraan (plat_nomor, merk, tipe, harga_sewa_per_hari, spesifikasi) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssds", $plat, $merk, $tipe, $tarif, $spesifikasi);
        $sukses = $stmt->execute();
        $stmt->close();
        return $sukses;
    }
}
