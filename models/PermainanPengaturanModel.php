<?php

class PermainanPengaturanModel extends Model
{
    protected string $table = 'permainan_pengaturan';

    public function ensureTable(): void
    {
        try {
            $this->db->exec("
                CREATE TABLE IF NOT EXISTS `permainan_pengaturan` (
                    `id` INT AUTO_INCREMENT PRIMARY KEY,
                    `tampil_menu` ENUM('ya','tidak') NOT NULL DEFAULT 'ya',
                    `musik_game` VARCHAR(255) NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
            ");
            $count = (int) $this->db->query("SELECT COUNT(*) FROM `permainan_pengaturan`")->fetchColumn();
            if ($count === 0) {
                $this->db->exec("INSERT INTO `permainan_pengaturan` (`tampil_menu`) VALUES ('ya')");
            }
        } catch (PDOException $e) {
            // Abaikan jika database user tidak memiliki izin CREATE
        }
    }

    public function get(): array
    {
        try {
            $row = $this->raw("SELECT * FROM permainan_pengaturan ORDER BY id LIMIT 1")->fetch();
            return $row ?: ['tampil_menu' => 'ya', 'musik_game' => null];
        } catch (PDOException $e) {
            $this->ensureTable();
            try {
                $row = $this->raw("SELECT * FROM permainan_pengaturan ORDER BY id LIMIT 1")->fetch();
                return $row ?: ['tampil_menu' => 'ya', 'musik_game' => null];
            } catch (PDOException $e2) {
                return ['tampil_menu' => 'ya', 'musik_game' => null];
            }
        }
    }
}
