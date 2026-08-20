<?php

class PermainanSkorModel extends Model
{
    protected string $table = 'permainan_skor';

    public function getTop(string $slug, int $limit = 10): array
    {
        return $this->raw(
            "SELECT * FROM permainan_skor WHERE game_slug = :slug ORDER BY skor DESC, created_at ASC LIMIT " . (int) $limit,
            ['slug' => $slug]
        )->fetchAll();
    }

    public function hapusByGame(string $slug): void
    {
        $this->raw("DELETE FROM permainan_skor WHERE game_slug = :slug", ['slug' => $slug]);
    }
}
