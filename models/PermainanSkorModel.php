<?php

class PermainanSkorModel extends Model
{
    protected string $table = 'permainan_skor';

    public function ensureTable(): void
    {
        try {
            $this->db->exec("
                CREATE TABLE IF NOT EXISTS `permainan_skor` (
                    `id` INT AUTO_INCREMENT PRIMARY KEY,
                    `game_slug` VARCHAR(50) NOT NULL,
                    `nama_pemain` VARCHAR(50) NOT NULL,
                    `skor` INT NOT NULL DEFAULT 0,
                    `detail` VARCHAR(100) NULL,
                    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
            ");
        } catch (PDOException $e) {
            // Abaikan jika database user tidak memiliki izin CREATE
        }
    }

    public function getTop(string $slug, int $limit = 10): array
    {
        try {
            return $this->raw(
                "SELECT * FROM permainan_skor WHERE game_slug = :slug ORDER BY skor DESC, created_at ASC LIMIT " . (int) $limit,
                ['slug' => $slug]
            )->fetchAll();
        } catch (PDOException $e) {
            $this->ensureTable();
            try {
                return $this->raw(
                    "SELECT * FROM permainan_skor WHERE game_slug = :slug ORDER BY skor DESC, created_at ASC LIMIT " . (int) $limit,
                    ['slug' => $slug]
                )->fetchAll();
            } catch (PDOException $e2) {
                return [];
            }
        }
    }

    public function hapusByGame(string $slug): void
    {
        try {
            $this->raw("DELETE FROM permainan_skor WHERE game_slug = :slug", ['slug' => $slug]);
        } catch (PDOException $e) {
            $this->ensureTable();
        }
    }
}
