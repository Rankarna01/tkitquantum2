<?php
/**
 * TK IT Quantum School - Front Controller
 */

// --- Security headers ---
header('X-Frame-Options: SAMEORIGIN');
header('X-Content-Type-Options: nosniff');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');
header("Content-Security-Policy: frame-ancestors 'self'");

// --- Session dengan pengaturan aman ---
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_samesite', 'Lax');
session_start();

// --- Error reporting sesuai environment ---
$appConfig = require dirname(__DIR__) . '/config/app.php';
if ($appConfig['env'] === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// --- Autoload sederhana (tanpa Composer) ---
spl_autoload_register(function ($class) {
    $dirs = ['core', 'controllers', 'models'];
    foreach ($dirs as $dir) {
        $file = dirname(__DIR__) . "/{$dir}/{$class}.php";
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// --- Regenerasi session id berkala (mitigasi session fixation) ---
if (empty($_SESSION['created_at'])) {
    $_SESSION['created_at'] = time();
} elseif (time() - $_SESSION['created_at'] > 900) {
    session_regenerate_id(true);
    $_SESSION['created_at'] = time();
}

require dirname(__DIR__) . '/core/App.php';
new App();
