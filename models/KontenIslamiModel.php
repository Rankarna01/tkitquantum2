<?php

class KontenIslamiModel extends Model
{
    protected string $table = 'konten_islami';

    public function getActive(): array
    {
        return $this->raw("SELECT * FROM konten_islami WHERE status = 'aktif' ORDER BY urutan ASC, id ASC")->fetchAll();
    }
}
