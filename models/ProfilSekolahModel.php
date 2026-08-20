<?php

class ProfilSekolahModel extends Model
{
    protected string $table = 'profil_sekolah';

    public function get(): array
    {
        $row = $this->raw("SELECT * FROM profil_sekolah ORDER BY id LIMIT 1")->fetch();
        return $row ?: [];
    }
}
