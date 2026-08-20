<?php

class TenagaKependidikanModel extends Model
{
    protected string $table = 'tenaga_kependidikan';

    public function getActive(): array
    {
        return $this->raw("SELECT * FROM tenaga_kependidikan WHERE status = 'aktif' ORDER BY nama_lengkap ASC")->fetchAll();
    }
}
