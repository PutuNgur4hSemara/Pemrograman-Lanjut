<?php
/**
 * Latihan 1: SELECT Builder Sederhana & Method Chaining
 * Modul Praktikum Minggu 12
 */

class SimpleSelectBuilder
{
    private string $table = '';
    private array $columns = ['*'];

    public function table(string $table): self
    {
        $this->table = $table;
        return $this;
    }

    public function select(array $columns): self
    {
        $this->columns = $columns;
        return $this;
    }

    public function toSql(): string
    {
        return "SELECT " . implode(', ', $this->columns) . " FROM " . $this->table;
    }
}

// Simulasi Perakitan SQL
$builder1 = (new SimpleSelectBuilder())
    ->table("kegiatan");

$builder2 = (new SimpleSelectBuilder())
    ->table("kegiatan")
    ->select(["kode", "nama", "tanggal"]);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Latihan 1 - SELECT Builder</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f8fafc; padding: 30px; }
        .card { max-width: 650px; margin: 0 auto 16px; background: white; border-radius: 10px; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.06); }
        code { background: #0f172a; color: #38bdf8; padding: 6px 10px; border-radius: 4px; display: block; margin-top: 8px; font-family: monospace; font-size: 14px; }
    </style>
</head>
<body>

    <div class="card">
        <h3>1. Query Default (Select All):</h3>
        <code><?= htmlspecialchars($builder1->toSql()) ?></code>
    </div>

    <div class="card">
        <h3>2. Query Kolom Spesifik:</h3>
        <code><?= htmlspecialchars($builder2->toSql()) ?></code>
    </div>

</body>
</html>
