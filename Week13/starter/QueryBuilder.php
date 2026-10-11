<?php
/**
 * QueryBuilder: Full-Featured Custom Database Query Builder
 * Modul Praktikum Minggu 13
 */

class QueryBuilder
{
    private string $table = '';
    private array $columns = ['*'];
    private array $wheres = [];
    private array $bindings = [];
    private array $orders = [];
    private ?int $limit = null;

    private array $allowedOperators = ['=', '!=', '<', '<=', '>', '>=', 'LIKE', 'NOT LIKE'];

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
        if (!in_array($operator, $this->allowedOperators)) {
            throw new InvalidArgumentException("Operator SQL tidak valid: {$operator}");
        }
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $column)) {
            throw new InvalidArgumentException("Nama kolom mengandung karakter terlarang: {$column}");
        }

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

    public function limit(int $count): self
    {
        if ($count <= 0) {
            throw new InvalidArgumentException("Nilai limit harus berupa bilangan positif.");
        }
        $this->limit = $count;
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
        if ($this->limit !== null) {
            $sql .= " LIMIT {$this->limit}";
        }
        return $sql;
    }

    public function get(): array
    {
        $sql = $this->toSql();
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($this->bindings);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->reset();
        return $results;
    }

    public function first(): ?array
    {
        $this->limit(1);
        $results = $this->get();
        return $results[0] ?? null;
    }

    public function insert(array $data): bool
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";

        $stmt = $this->pdo->prepare($sql);
        $sukses = $stmt->execute(array_values($data));
        $this->reset();
        return $sukses;
    }

    public function update(array $data): int
    {
        $setClauses = [];
        $updateValues = [];
        foreach ($data as $col => $val) {
            $setClauses[] = "{$col} = ?";
            $updateValues[] = $val;
        }

        $sql = "UPDATE {$this->table} SET " . implode(', ', $setClauses);
        if (!empty($this->wheres)) {
            $sql .= " WHERE " . implode(' AND ', $this->wheres);
        }

        $allBindings = array_merge($updateValues, $this->bindings);
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($allBindings);
        $affected = $stmt->rowCount();
        $this->reset();
        return $affected;
    }

    public function delete(): int
    {
        if (empty($this->wheres)) {
            throw new RuntimeException("Operasi DELETE tanpa klausul WHERE ditolak untuk mencegah penghapusan massal.");
        }

        $sql = "DELETE FROM {$this->table} WHERE " . implode(' AND ', $this->wheres);
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($this->bindings);
        $affected = $stmt->rowCount();
        $this->reset();
        return $affected;
    }

    private function reset(): void
    {
        $this->table = '';
        $this->columns = ['*'];
        $this->wheres = [];
        $this->bindings = [];
        $this->orders = [];
        $this->limit = null;
    }
}
