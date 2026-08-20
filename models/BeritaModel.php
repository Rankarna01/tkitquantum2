<?php

class BeritaModel extends Model
{
    protected string $table = 'berita';

    public function getPublished(int $limit = null): array
    {
        $sql = "SELECT b.*, k.nama_kategori FROM berita b
                LEFT JOIN kategori_berita k ON k.id = b.kategori_id
                WHERE b.status = 'publish' AND b.tanggal_publish <= NOW()
                ORDER BY b.tanggal_publish DESC";
        if ($limit) $sql .= " LIMIT " . (int) $limit;
        return $this->raw($sql)->fetchAll();
    }

    public function findBySlug(string $slug): ?array
    {
        $row = $this->raw("SELECT * FROM berita WHERE slug = :slug LIMIT 1", ['slug' => $slug])->fetch();
        return $row ?: null;
    }

    public function incrementViews(int $id): void
    {
        $this->raw("UPDATE berita SET dilihat = dilihat + 1 WHERE id = :id", ['id' => $id]);
    }
}
