<?php

class PrestasiModel extends Model
{
    protected string $table = 'prestasi';

    public function getAll(int $limit = null): array
    {
        $sql = "SELECT * FROM prestasi ORDER BY tahun DESC, created_at DESC";
        if ($limit) $sql .= " LIMIT " . (int) $limit;
        return $this->raw($sql)->fetchAll();
    }
}
