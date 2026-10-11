<?php
/**
 * Class Turunan Mobil & Motor
 * Modul Evaluasi UTS - Minggu 8
 */

require_once __DIR__ . '/Kendaraan.php';

class Mobil extends Kendaraan
{
    public function __construct(
        ?int $id,
        string $platNomor,
        string $merk,
        float $hargaSewaPerHari,
        private string $kapasitasPenumpang,
        string $status = 'Tersedia'
    ) {
        parent::__construct($id, $platNomor, $merk, $hargaSewaPerHari, $status);
    }

    public function getTipeKendaraan(): string { return 'Mobil'; }
    public function getSpesifikasi(): string { return $this->kapasitasPenumpang; }

    // Overriding: Mobil dikenakan biaya asuransi perlindungan armada Rp 25.000/hari
    public function hitungBiayaSewa(int $lamaHari): float
    {
        $biayaDasar = parent::hitungBiayaSewa($lamaHari);
        $asuransi = 25000 * $lamaHari;
        return $biayaDasar + $asuransi;
    }
}

class Motor extends Kendaraan
{
    public function __construct(
        ?int $id,
        string $platNomor,
        string $merk,
        float $hargaSewaPerHari,
        private string $kapasitasCc,
        string $status = 'Tersedia'
    ) {
        parent::__construct($id, $platNomor, $merk, $hargaSewaPerHari, $status);
    }

    public function getTipeKendaraan(): string { return 'Motor'; }
    public function getSpesifikasi(): string { return $this->kapasitasCc; }
}
