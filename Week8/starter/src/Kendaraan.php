<?php
/**
 * Model Hirarki PBO: Abstract Class Kendaraan & Interface Diskon
 * Modul Evaluasi UTS - Minggu 8
 */

interface LayananDiskonRental
{
    public function hitungDiskon(int $lamaHari, float $totalSewa): float;
}

abstract class Kendaraan implements LayananDiskonRental
{
    public function __construct(
        protected ?int $id,
        protected string $platNomor,
        protected string $merk,
        protected float $hargaSewaPerHari,
        protected string $status = 'Tersedia'
    ) {
        if ($hargaSewaPerHari <= 0) {
            throw new InvalidArgumentException("Tarif sewa per hari harus lebih dari 0.");
        }
    }

    public function getId(): ?int { return $this->id; }
    public function getPlatNomor(): string { return $this->platNomor; }
    public function getMerk(): string { return $this->merk; }
    public function getHargaSewa(): float { return $this->hargaSewaPerHari; }
    public function getStatus(): string { return $this->status; }

    abstract public function getTipeKendaraan(): string;
    abstract public function getSpesifikasi(): string;

    public function hitungBiayaSewa(int $lamaHari): float
    {
        if ($lamaHari <= 0) {
            throw new InvalidArgumentException("Lama sewa minimal 1 hari.");
        }
        $subtotal = $this->hargaSewaPerHari * $lamaHari;
        $diskon = $this->hitungDiskon($lamaHari, $subtotal);
        return $subtotal - $diskon;
    }

    public function hitungDiskon(int $lamaHari, float $totalSewa): float
    {
        if ($lamaHari >= 7) {
            return $totalSewa * 0.15; // Diskon 15%
        } elseif ($lamaHari >= 3) {
            return $totalSewa * 0.05; // Diskon 5%
        }
        return 0.0;
    }
}
