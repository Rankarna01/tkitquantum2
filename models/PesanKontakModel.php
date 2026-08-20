<?php

class PesanKontakModel extends Model
{
    protected string $table = 'pesan_kontak';

    public function countBaru(): int
    {
        return (int) $this->raw("SELECT COUNT(*) c FROM pesan_kontak WHERE status = 'baru'")->fetch()['c'];
    }
}
