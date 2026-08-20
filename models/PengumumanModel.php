<?php

class PengumumanModel extends Model
{
    protected string $table = 'pengumuman';

    public function getPublished(int $limit = null): array
    {
        $sql = "SELECT * FROM pengumuman WHERE status = 'publish' AND tanggal_publish <= NOW() ORDER BY tanggal_publish DESC";
        if ($limit) $sql .= " LIMIT " . (int) $limit;
        return $this->raw($sql)->fetchAll();
    }

    public function findBySlug(string $slug): ?array
    {
        $row = $this->raw("SELECT * FROM pengumuman WHERE slug = :slug LIMIT 1", ['slug' => $slug])->fetch();
        return $row ?: null;
    }
}
