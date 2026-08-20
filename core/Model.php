<?php

abstract class Model
{
    protected PDO $db;
    protected string $table;
    protected string $primaryKey = 'id';

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function all(?string $orderBy = null): array
    {
        $sql = "SELECT * FROM {$this->table}";
        if ($orderBy) $sql .= " ORDER BY {$orderBy}";
        return $this->db->query($sql)->fetchAll();
    }

    public function find($id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function findBy(string $column, $value): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE {$column} = :val LIMIT 1");
        $stmt->execute(['val' => $value]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function where(string $column, $value, ?string $orderBy = null): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$column} = :val";
        if ($orderBy) $sql .= " ORDER BY {$orderBy}";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['val' => $value]);
        return $stmt->fetchAll();
    }

    public function insert(array $data): string
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($data);
        return $this->db->lastInsertId();
    }

    public function update($id, array $data): bool
    {
        $set = implode(', ', array_map(fn($col) => "{$col} = :{$col}", array_keys($data)));
        $sql = "UPDATE {$this->table} SET {$set} WHERE {$this->primaryKey} = :pk_id";
        $data['pk_id'] = $id;
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    public function delete($id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function paginate(int $page = 1, int $perPage = 10, ?string $orderBy = null): array
    {
        $page = max(1, $page);
        $offset = ($page - 1) * $perPage;

        $total = (int) $this->db->query("SELECT COUNT(*) FROM {$this->table}")->fetchColumn();

        $sql = "SELECT * FROM {$this->table}";
        if ($orderBy) $sql .= " ORDER BY {$orderBy}";
        $sql .= " LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return [
            'data' => $stmt->fetchAll(),
            'total' => $total,
            'page' => $page,
            'last_page' => (int) ceil($total / $perPage),
        ];
    }

    /** Untuk query custom yang tidak tercakup helper di atas — TETAP gunakan prepared statement di Model turunan. */
    protected function raw(string $sql, array $params = []): PDOStatement
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
}
