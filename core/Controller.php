<?php

abstract class Controller
{
    protected array $appConfig;

    public function __construct()
    {
        $this->appConfig = require dirname(__DIR__) . '/config/app.php';
    }

    protected function view(string $viewPath, array $data = [], ?string $layout = 'layouts/main'): void
    {
        extract($data);
        $viewFile = dirname(__DIR__) . '/views/' . $viewPath . '.php';
        if (!file_exists($viewFile)) {
            $this->abort(500, 'View tidak ditemukan: ' . $viewPath);
        }

        if ($layout) {
            $layoutFile = dirname(__DIR__) . '/views/' . $layout . '.php';
            ob_start();
            require $viewFile;
            $content = ob_get_clean();
            require $layoutFile;
        } else {
            require $viewFile;
        }
    }

    protected function json(array $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
        exit;
    }

    protected function redirect(string $path): void
    {
        header('Location: ' . $this->appConfig['base_url'] . $path);
        exit;
    }

    protected function abort(int $code, string $message = ''): void
    {
        http_response_code($code);
        $errorView = dirname(__DIR__) . "/views/errors/{$code}.php";
        if (file_exists($errorView)) {
            require $errorView;
        } else {
            echo "Error {$code}: {$message}";
        }
        exit;
    }

    /** Wajib dipanggil di awal controller yang butuh login */
    protected function requireAuth(): void
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('/auth/login');
        }
        // Auto logout karena idle
        $lifetime = $this->appConfig['session_lifetime'];
        if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $lifetime) {
            session_unset();
            session_destroy();
            $this->redirect('/auth/login?expired=1');
        }
        $_SESSION['last_activity'] = time();
    }

    /** Wajib dipanggil setelah requireAuth() untuk membatasi role tertentu (RBAC) */
    protected function requireRole(array $allowedRoles): void
    {
        if (!in_array($_SESSION['role'] ?? '', $allowedRoles, true)) {
            $this->abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }
    }

    protected function validateCsrf(): void
    {
        $token = $_POST['csrf_token'] ?? '';
        if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
            $this->abort(403, 'CSRF token tidak valid.');
        }
    }

    protected function old(string $key, $default = '')
    {
        return htmlspecialchars($_SESSION['old_input'][$key] ?? $default, ENT_QUOTES, 'UTF-8');
    }
}
