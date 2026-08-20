<?php
/**
 * Konfigurasi Aplikasi - TK IT Quantum School
 */
return [
    'app_name'   => 'TK IT Quantum School',
    // Sesuaikan jika folder project diletakkan di dalam subfolder htdocs, contoh: '/tkitquantum'
    'base_url'   => '/tkitquantum/public',
    'env'        => 'development', // development | production
    'session_lifetime' => 1800,    // 30 menit auto logout
    'login_max_attempts' => 5,
    'login_lockout_minutes' => 15,
    'upload_max_size' => 4 * 1024 * 1024, // 4MB
    'upload_allowed_mimes' => [
        'image/jpeg', 'image/png', 'image/webp', 'application/pdf'
    ],
];
