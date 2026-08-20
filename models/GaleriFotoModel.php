<?php

class GaleriFotoModel extends Model
{
    protected string $table = 'galeri_foto';

    public function allWithKategori(): array
    {
        return $this->raw(
            "SELECT g.*, k.nama_kategori FROM galeri_foto g
             LEFT JOIN galeri_kategori k ON k.id = g.kategori_id
             ORDER BY g.created_at DESC"
        )->fetchAll();
    }
}
