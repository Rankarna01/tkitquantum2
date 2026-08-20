<?php

class PengaturanModel extends Model
{
    protected string $table = 'pengaturan_website';

    public function get(): array
    {
        $row = $this->raw("SELECT * FROM pengaturan_website ORDER BY id LIMIT 1")->fetch();
        return $row ?: [];
    }
}
