<?php

class AgendaModel extends Model
{
    protected string $table = 'agenda';

    public function getUpcoming(int $limit = null): array
    {
        $sql = "SELECT * FROM agenda WHERE tanggal_mulai >= NOW() ORDER BY tanggal_mulai ASC";
        if ($limit) $sql .= " LIMIT " . (int) $limit;
        return $this->raw($sql)->fetchAll();
    }
}
