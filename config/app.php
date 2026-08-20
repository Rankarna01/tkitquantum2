<?php
/**
 * Konfigurasi Aplikasi - TK IT Quantum School
 */

// Auto-detect baseUrl secara cerdas (mendukung hosting root domain maupun localhost subfolder)
$baseUrl = '';
if (isset($_SERVER['HTTP_HOST'])) {
    $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
    $baseDir = preg_replace('#/public$#', '', $scriptDir);
    $baseUrl = ($baseDir === '/' || $baseDir === '.' || $baseDir === '') ? '' : rtrim($baseDir, '/');
}

return [
    'app_name'   => 'TK IT Quantum School',
    /**
     * base_url: otomatis mendeteksi path root hosting atau subfolder.
     * Jika ingin dioverride manual di hosting utama: isi '' (string kosong).
     */
    'base_url'   => $baseUrl,
    'env'        => (isset($_SERVER['HTTP_HOST']) && in_array($_SERVER['HTTP_HOST'], ['localhost', '127.0.0.1'])) ? 'development' : 'production',
    'session_lifetime' => 1800,    // 30 menit auto logout
    'login_max_attempts' => 5,
    'login_lockout_minutes' => 15,
    'upload_max_size' => 4 * 1024 * 1024, // 4MB
    'upload_allowed_mimes' => [
        'image/jpeg', 'image/png', 'image/webp', 'application/pdf'
    ],
];
