<?php

class PermainanGameModel extends Model
{
    protected string $table = 'permainan_game';

    public static function defaultGames(): array
    {
        return [
            ['slug' => 'cocok-kartu', 'nama' => 'Cocokkan Kartunya', 'ikon' => '🐬', 'deskripsi' => 'Latih ingatan dengan mencocokkan kartu bergambar', 'status' => 'aktif', 'urutan' => 1],
            ['slug' => 'tangkap-bintang', 'nama' => 'Tangkap Bintang', 'ikon' => '⭐', 'deskripsi' => 'Klik bintang secepat mungkin sebelum menghilang', 'status' => 'aktif', 'urutan' => 2],
            ['slug' => 'puzzle-angka', 'nama' => 'Puzzle Angka', 'ikon' => '🔢', 'deskripsi' => 'Susun ulang angka 1-8 dengan langkah sesedikit mungkin', 'status' => 'aktif', 'urutan' => 3],
            ['slug' => 'tebak-angka', 'nama' => 'Tebak Angka', 'ikon' => '🎯', 'deskripsi' => 'Tebak angka rahasia 1-100 dengan petunjuk lebih besar/kecil', 'status' => 'aktif', 'urutan' => 4],
            ['slug' => 'ketuk-warna', 'nama' => 'Ketuk Warna', 'ikon' => '🎨', 'deskripsi' => 'Ingat & ulangi urutan warna yang menyala, makin lama makin panjang!', 'status' => 'aktif', 'urutan' => 5],
            ['slug' => 'hitung-cepat', 'nama' => 'Hitung Cepat', 'ikon' => '➕', 'deskripsi' => 'Jawab soal tambah & kurang sebanyak mungkin dalam 60 detik', 'status' => 'aktif', 'urutan' => 6],
            ['slug' => 'tebak-kata', 'nama' => 'Tebak Kata', 'ikon' => '🔤', 'deskripsi' => 'Tebak huruf untuk menemukan kata tersembunyi', 'status' => 'aktif', 'urutan' => 7],
            ['slug' => 'uji-reaksi', 'nama' => 'Uji Reaksi', 'ikon' => '⚡', 'deskripsi' => 'Klik secepat mungkin begitu warna berubah hijau', 'status' => 'aktif', 'urutan' => 8],
            ['slug' => 'balap-ketik', 'nama' => 'Balap Ketik', 'ikon' => '⌨️', 'deskripsi' => 'Ketik kata yang muncul secepat & setepat mungkin dalam 30 detik', 'status' => 'aktif', 'urutan' => 9],
            ['slug' => 'tebak-silang', 'nama' => 'Tebak Silang', 'ikon' => '❌', 'deskripsi' => 'Main tic-tac-toe (X-O) melawan komputer', 'status' => 'aktif', 'urutan' => 10],
            ['slug' => 'tebak-emoji', 'nama' => 'Tebak Emoji', 'ikon' => '😄', 'deskripsi' => 'Tebak kata dari rangkaian emoji yang ditampilkan', 'status' => 'aktif', 'urutan' => 11],
            ['slug' => 'klik-bentuk', 'nama' => 'Klik Bentuk Sama', 'ikon' => '🔺', 'deskripsi' => 'Klik bentuk yang sesuai target secepat mungkin', 'status' => 'aktif', 'urutan' => 12],
            ['slug' => 'labirin-ceria', 'nama' => 'Labirin Ceria', 'ikon' => '🧩', 'deskripsi' => 'Bantu karakter menemukan jalan keluar labirin', 'status' => 'aktif', 'urutan' => 13],
            ['slug' => 'piano-ceria', 'nama' => 'Piano Ceria', 'ikon' => '🎹', 'deskripsi' => 'Ingat & mainkan ulang nada yang berbunyi', 'status' => 'aktif', 'urutan' => 14],
            ['slug' => 'lompat-kodok', 'nama' => 'Lompat Kodok', 'ikon' => '🐸', 'deskripsi' => 'Klik tepat saat penunjuk masuk area hijau', 'status' => 'aktif', 'urutan' => 15],
        ];
    }

    public function ensureTable(): void
    {
        try {
            $this->db->exec("
                CREATE TABLE IF NOT EXISTS `permainan_game` (
                    `id` INT AUTO_INCREMENT PRIMARY KEY,
                    `slug` VARCHAR(50) NOT NULL UNIQUE,
                    `nama` VARCHAR(100) NOT NULL,
                    `ikon` VARCHAR(10) NULL,
                    `deskripsi` VARCHAR(255) NULL,
                    `status` ENUM('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
                    `urutan` INT DEFAULT 0
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
            ");
            $count = (int) $this->db->query("SELECT COUNT(*) FROM `permainan_game`")->fetchColumn();
            if ($count === 0) {
                $stmt = $this->db->prepare("
                    INSERT IGNORE INTO `permainan_game` (`slug`, `nama`, `ikon`, `deskripsi`, `status`, `urutan`)
                    VALUES (:slug, :nama, :ikon, :deskripsi, :status, :urutan)
                ");
                foreach (self::defaultGames() as $g) {
                    $stmt->execute([
                        'slug' => $g['slug'],
                        'nama' => $g['nama'],
                        'ikon' => $g['ikon'],
                        'deskripsi' => $g['deskripsi'],
                        'status' => $g['status'],
                        'urutan' => $g['urutan'],
                    ]);
                }
            }
        } catch (PDOException $e) {
            // Abaikan jika database user tidak memiliki izin CREATE
        }
    }

    public function getAktif(): array
    {
        try {
            $result = $this->raw("SELECT * FROM permainan_game WHERE status = 'aktif' ORDER BY urutan ASC, id ASC")->fetchAll();
            return !empty($result) ? $result : self::defaultGames();
        } catch (PDOException $e) {
            $this->ensureTable();
            try {
                $result = $this->raw("SELECT * FROM permainan_game WHERE status = 'aktif' ORDER BY urutan ASC, id ASC")->fetchAll();
                return !empty($result) ? $result : self::defaultGames();
            } catch (PDOException $e2) {
                return self::defaultGames();
            }
        }
    }

    public function getBySlug(string $slug): ?array
    {
        try {
            $row = $this->raw("SELECT * FROM permainan_game WHERE slug = :slug LIMIT 1", ['slug' => $slug])->fetch();
            if ($row) return $row;
        } catch (PDOException $e) {
            $this->ensureTable();
            try {
                $row = $this->raw("SELECT * FROM permainan_game WHERE slug = :slug LIMIT 1", ['slug' => $slug])->fetch();
                if ($row) return $row;
            } catch (PDOException $e2) {}
        }

        foreach (self::defaultGames() as $g) {
            if ($g['slug'] === $slug) return $g;
        }
        return null;
    }
}
