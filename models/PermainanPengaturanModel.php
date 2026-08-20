<?php

class PermainanPengaturanModel extends Model
{
    protected string $table = 'permainan_pengaturan';

    public function get(): array
    {
        $row = $this->raw("SELECT * FROM permainan_pengaturan ORDER BY id LIMIT 1")->fetch();
        return $row ?: ['tampil_menu' => 'ya'];
    }
}
