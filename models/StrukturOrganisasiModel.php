<?php

class StrukturOrganisasiModel extends Model
{
    protected string $table = 'struktur_organisasi';

    public function allOrdered(): array
    {
        return $this->all('urutan ASC, id ASC');
    }
}
