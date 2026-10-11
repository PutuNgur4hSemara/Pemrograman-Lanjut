<?php
/**
 * Latihan 3: Integrasi Lengkap SELECT Builder, ORDER BY, dan Eksekusi (get())
 * Modul Praktikum Minggu 12
 */

class SelectQueryBuilder
{
    private string $table = '';
    private array $columns = ['*'];
    private array $wheres = [];
    private array $bindings = [];
    private array $orders = [];

    public function __construct(private PDO $pdo) {}

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
        $this->wheres[] = "{$column} {$operator} ?";
        $this->bindings[] = $value;
        return $this;
    }

    public function orderBy(string $column, string $direction = 'ASC'): self
    {
        $dir = strtoupper($direction) === 'DESC' ? 'DESC' : 'ASC';
        $this->orders[] = "{$column} {$dir}";
        return $this;
    }

    public function toSql(): string
    {
        $sql = "SELECT " . implode(', ', $this->columns) . " FROM {$this->table}";
        if (!empty($this->wheres)) {
            $sql .= " WHERE " . implode(' AND ', $this->wheres);
        }
        if (!empty($this->orders)) {
            $sql .= " ORDER BY " . implode(', ', $this->orders);
        }
        return $sql;
    }

    public function get(): array
    {
        $stmt = $this->pdo->prepare($this->toSql());
        $stmt->execute($this->bindings);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

// Inisialisasi PDO
$pdo = new PDO("mysql:host=127.0.0.1;dbname=pemrograman_lanjut;charset=utf8mb4", "root", "", [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

// Eksekusi Menggunakan Fluent Query Builder
$builder = (new SelectQueryBuilder($pdo))
    ->table("kegiatan")
    ->select(["kode", "nama", "lokasi", "tanggal", "kuota", "status"])
    ->where("kuota", ">=", 40)
    ->orderBy("tanggal", "ASC");

$hasilQuery = $builder->get();
$generatedSql = $builder->toSql();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Latihan 3 - Eksekusi Query Builder</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f8fafc; padding: 30px; }
        .container { max-width: 850px; margin: 0 auto; }
        .card { background: white; border-radius: 12px; padding: 24px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; font-size: 14px; }
        th, td { padding: 10px 12px; border-bottom: 1px solid #e2e8f0; text-align: left; }
        th { background: #0f172a; color: white; }
    </style>
</head>
<body>

<div class="container">
    <div class="card">
        <h2>Eksekusi Query Builder SELECT Lengkap</h2>
        <p style="font-size: 13px; color: #64748b;">SQL: <code><?= htmlspecialchars($generatedSql) ?></code></p>

        <table>
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama Kegiatan</th>
                    <th>Lokasi</th>
                    <th>Tanggal</th>
                    <th>Kuota</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($hasilQuery as $k): ?>
                    <tr>
                        <td><code><?= htmlspecialchars($k['kode']) ?></code></td>
                        <td><strong><?= htmlspecialchars($k['nama']) ?></strong></td>
                        <td><?= htmlspecialchars($k['lokasi']) ?></td>
                        <td><?= $k['tanggal'] ?></td>
                        <td><?= $k['kuota'] ?> orang</td>
                        <td><?= htmlspecialchars($k['status']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
