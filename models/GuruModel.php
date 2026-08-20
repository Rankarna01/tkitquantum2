<?php

class GuruModel extends Model
{
    protected string $table = 'guru';

    public function getActive(): array
    {
        return $this->raw("SELECT * FROM guru WHERE status = 'aktif' ORDER BY nama_lengkap ASC")->fetchAll();
    }
}
