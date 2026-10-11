<?php
/**
 * Domain Model: PesertaLokakarya
 * Modul Praktikum Minggu 15
 */

class PesertaLokakarya
{
    public function __construct(
        private string $nama,
        private string $email,
        private string $level,
        private string $sesi,
        private array $topikMinat
    ) {}

    public function getNama(): string { return $this->nama; }
    public function getEmail(): string { return $this->email; }
    public function getLevel(): string { return $this->level; }
    public function getSesi(): string { return $this->sesi; }
    public function getTopikMinatString(): string { return implode(', ', $this->topikMinat); }
}
