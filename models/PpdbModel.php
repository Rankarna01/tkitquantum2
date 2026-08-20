<?php

class PpdbModel extends Model
{
    protected string $table = 'ppdb_pengaturan';

    public function getAktif(): ?array
    {
        $row = $this->raw("SELECT * FROM ppdb_pengaturan WHERE status = 'aktif' ORDER BY id DESC LIMIT 1")->fetch();
        return $row ?: null;
    }

    public function getLatest(): ?array
    {
        $row = $this->raw("SELECT * FROM ppdb_pengaturan ORDER BY id DESC LIMIT 1")->fetch();
        return $row ?: null;
    }

    public function countPendaftar(int $ppdbId): int
    {
        return (int) $this->raw(
            "SELECT COUNT(*) FROM ppdb_pendaftar WHERE ppdb_id = :id AND status != 'ditolak'",
            ['id' => $ppdbId]
        )->fetchColumn();
    }
}
