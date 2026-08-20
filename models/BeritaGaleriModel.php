<?php

class BeritaGaleriModel extends Model
{
    protected string $table = 'berita_galeri';

    public function getByBerita(int $beritaId): array
    {
        return $this->raw(
            "SELECT * FROM berita_galeri WHERE berita_id = :id ORDER BY urutan ASC, id ASC",
            ['id' => $beritaId]
        )->fetchAll();
    }
}
