<?php
/**
 * Latihan 2: WHERE Chaining, Allowlist Identifier & Parameter Binding
 * Modul Praktikum Minggu 12
 */

class WhereQueryBuilder
{
    private string $table = '';
    private array $columns = ['*'];
    private array $wheres = [];
    private array $bindings = [];

    private array $allowedOperators = ['=', '!=', '<', '<=', '>', '>=', 'LIKE', 'NOT LIKE'];

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

    public function where(string $column, string $operator, mixed $value): self
    {
        $operator = strtoupper($operator);
        if (!in_array($operator, $this->allowedOperators)) {
            throw new InvalidArgumentException("Operator SQL tidak valid: {$operator}");
        }

        if (!preg_match('/^[a-zA-Z0-9_]+$/', $column)) {
            throw new InvalidArgumentException("Nama kolom mengandung karakter tidak diizinkan: {$column}");
        }

        $this->wheres[] = "{$column} {$operator} ?";
        $this->bindings[] = $value;
        return $this;
    }

    public function toSql(): string
    {
        $sql = "SELECT " . implode(', ', $this->columns) . " FROM {$this->table}";
        if (!empty($this->wheres)) {
            $sql .= " WHERE " . implode(' AND ', $this->wheres);
        }
        return $sql;
    }

    public function getBindings(): array
    {
        return $this->bindings;
    }
}

// Uji coba membangun query terfilter
$builder = (new WhereQueryBuilder())
    ->table("kegiatan")
    ->select(["kode", "nama", "tanggal", "kuota"])
    ->where("status", "=", "terjadwal")
    ->where("kuota", ">=", 50)
    ->where("lokasi", "LIKE", "%Lab%");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Latihan 2 - WHERE Chaining</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f8fafc; padding: 30px; }
        .card { max-width: 700px; margin: 0 auto; background: white; border-radius: 12px; padding: 24px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
        .sql-box { background: #0f172a; color: #38bdf8; font-family: monospace; font-size: 14px; padding: 12px; border-radius: 6px; margin: 10px 0; }
        .params-box { background: #f1f5f9; padding: 12px; border-radius: 6px; font-family: monospace; font-size: 13px; }
    </style>
</head>
<body>

    <div class="card">
        <h2>Perakitan Query SELECT dengan Multi-Kondisi WHERE</h2>

        <p><strong>Query SQL yang Dihasilkan:</strong></p>
        <div class="sql-box">
            <?= htmlspecialchars($builder->toSql()) ?>
        </div>

        <p><strong>Parameter Binding (Terpisah & Aman dari SQLi):</strong></p>
        <div class="params-box">
            <pre><?= htmlspecialchars(print_r($builder->getBindings(), true)) ?></pre>
        </div>
    </div>

</body>
</html>
