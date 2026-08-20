<?php

class PermainanGameModel extends Model
{
    protected string $table = 'permainan_game';

    public function getAktif(): array
    {
        return $this->raw("SELECT * FROM permainan_game WHERE status = 'aktif' ORDER BY urutan ASC, id ASC")->fetchAll();
    }

    public function getBySlug(string $slug): ?array
    {
        $row = $this->raw("SELECT * FROM permainan_game WHERE slug = :slug LIMIT 1", ['slug' => $slug])->fetch();
        return $row ?: null;
    }
}
