<?php

class KalenderAkademikModel extends Model
{
    protected string $table = 'kalender_akademik';

    public function getAll(): array
    {
        return $this->all('tanggal_mulai ASC');
    }
}
