<?php

class TestimoniAlumniModel extends Model
{
    protected string $table = 'testimoni_alumni';

    public function getPublished(int $limit = null): array
    {
        $sql = "SELECT * FROM testimoni_alumni WHERE status = 'publish' ORDER BY created_at DESC";
        if ($limit) $sql .= " LIMIT " . (int) $limit;
        return $this->raw($sql)->fetchAll();
    }
}
