<?php
/**
 * Class Input: Sanitasi Awal dan Pembaca Data Request
 * Modul Praktikum Minggu 14
 */

class Input
{
    private array $data;

    public function __construct(array $source)
    {
        $this->data = array_map(function ($item) {
            return is_string($item) ? trim($item) : $item;
        }, $source);
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->data[$key] ?? $default;
    }

    public function has(string $key): bool
    {
        return isset($this->data[$key]) && $this->data[$key] !== '';
    }

    public function all(): array
    {
        return $this->data;
    }
}
