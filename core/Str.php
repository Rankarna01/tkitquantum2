<?php

class Str
{
    public static function slug(string $text): string
    {
        $text = strtolower(trim($text));
        $text = preg_replace('/[^a-z0-9]+/', '-', $text);
        return trim($text, '-');
    }

    /** Menghasilkan slug unik dengan mengecek ke tabel tertentu */
    public static function uniqueSlug(PDO $db, string $table, string $base, ?int $excludeId = null): string
    {
        $slug = self::slug($base) ?: bin2hex(random_bytes(4));
        $original = $slug;
        $i = 1;
        while (true) {
            $sql = "SELECT COUNT(*) FROM {$table} WHERE slug = :slug";
            $params = ['slug' => $slug];
            if ($excludeId) {
                $sql .= " AND id != :id";
                $params['id'] = $excludeId;
            }
            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            if ((int) $stmt->fetchColumn() === 0) {
                return $slug;
            }
            $slug = $original . '-' . (++$i);
        }
    }
}
