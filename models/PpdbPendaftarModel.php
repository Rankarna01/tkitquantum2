<?php

class PpdbPendaftarModel extends Model
{
    protected string $table = 'ppdb_pendaftar';

    public function generateNoPendaftaran(): string
    {
        $tahun = date('Y');
        $prefix = "PPDB{$tahun}";
        $last = $this->raw(
            "SELECT no_pendaftaran FROM ppdb_pendaftar WHERE no_pendaftaran LIKE :prefix ORDER BY id DESC LIMIT 1",
            ['prefix' => $prefix . '%']
        )->fetch();

        $urut = 1;
        if ($last) {
            $urut = (int) substr($last['no_pendaftaran'], -4) + 1;
        }
        return $prefix . str_pad((string) $urut, 4, '0', STR_PAD_LEFT);
    }

    public function findByNoPendaftaran(string $no): ?array
    {
        $row = $this->raw("SELECT * FROM ppdb_pendaftar WHERE no_pendaftaran = :no LIMIT 1", ['no' => $no])->fetch();
        return $row ?: null;
    }

    public function byPpdb(int $ppdbId): array
    {
        return $this->raw(
            "SELECT * FROM ppdb_pendaftar WHERE ppdb_id = :id ORDER BY created_at DESC",
            ['id' => $ppdbId]
        )->fetchAll();
    }
}
