<?php

class Security
{
    public static function csrfToken(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    public static function csrfField(): string
    {
        return '<input type="hidden" name="csrf_token" value="' . self::csrfToken() . '">';
    }

    public static function clean(string $value): string
    {
        return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
    }

    /**
     * Validasi & simpan file upload dengan aman:
     * - Cek MIME asli (bukan cuma ekstensi)
     * - Nama file diacak
     * - Ukuran dibatasi
     */
    public static function handleUpload(array $file, string $destDir, array $allowedMimes, int $maxSize): ?string
    {
        if ($file['error'] === UPLOAD_ERR_INI_SIZE || $file['error'] === UPLOAD_ERR_FORM_SIZE) {
            throw new RuntimeException('Ukuran file melebihi batas maksimum server (cek upload_max_filesize di php.ini).');
        }
        if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
            return null;
        }
        if ($file['size'] > $maxSize) {
            $limitMb = round($maxSize / 1024 / 1024, 1);
            throw new RuntimeException("Ukuran file melebihi batas maksimum {$limitMb} MB.");
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mime, $allowedMimes, true)) {
            throw new RuntimeException('Tipe file tidak diizinkan.');
        }

        $extMap = [
            'image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp', 'application/pdf' => 'pdf',
        ];
        $ext = $extMap[$mime] ?? 'bin';
        $filename = bin2hex(random_bytes(16)) . '.' . $ext;

        if (!is_dir($destDir)) {
            mkdir($destDir, 0755, true);
        }

        if (!move_uploaded_file($file['tmp_name'], rtrim($destDir, '/') . '/' . $filename)) {
            throw new RuntimeException('Gagal menyimpan file.');
        }

        return $filename;
    }

    public static function logActivity(?int $userId, string $aktivitas, string $modul = ''): void
    {
        try {
            $db = Database::getInstance();
            $stmt = $db->prepare(
                "INSERT INTO activity_logs (user_id, aktivitas, modul, ip_address, user_agent) VALUES (:uid, :akt, :mod, :ip, :ua)"
            );
            $stmt->execute([
                'uid' => $userId,
                'akt' => $aktivitas,
                'mod' => $modul,
                'ip'  => $_SERVER['REMOTE_ADDR'] ?? '',
                'ua'  => substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 250),
            ]);
        } catch (Throwable $e) {
            error_log('Activity log error: ' . $e->getMessage());
        }
    }
}
