<?php

class AuthController extends Controller
{
    private UserModel $userModel;

    public function __construct()
    {
        parent::__construct();
        $this->userModel = new UserModel();
    }

    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleLogin();
            return;
        }

        if (!empty($_SESSION['user_id'])) {
            $this->redirect('/admin');
        }

        $this->view('auth/login', [
            'pengaturan' => (new PengaturanModel())->get(),
            'error' => $_SESSION['login_error'] ?? null,
        ], layout: null);
        unset($_SESSION['login_error']);
    }

    private function handleLogin(): void
    {
        $this->validateCsrf();

        $identity = Security::clean($_POST['identity'] ?? '');
        $password = (string) ($_POST['password'] ?? '');

        $user = $this->userModel->findByUsernameOrEmail($identity);

        if (!$user) {
            $this->failLogin('Username/email atau password salah.');
            return;
        }

        if ($this->userModel->isLocked($user)) {
            $this->failLogin('Akun terkunci sementara karena terlalu banyak percobaan gagal. Coba lagi nanti.');
            return;
        }

        if ($user['status'] !== 'aktif') {
            $this->failLogin('Akun Anda tidak aktif. Hubungi administrator.');
            return;
        }

        if (!password_verify($password, $user['password'])) {
            $this->userModel->incrementFailedAttempts((int) $user['id']);
            $maxAttempts = $this->appConfig['login_max_attempts'];
            if (($user['failed_attempts'] + 1) >= $maxAttempts) {
                $this->userModel->lockAccount((int) $user['id'], $this->appConfig['login_lockout_minutes']);
                Security::logActivity((int) $user['id'], 'Akun terkunci karena percobaan login gagal berulang', 'auth');
                $this->failLogin('Terlalu banyak percobaan gagal. Akun dikunci selama ' . $this->appConfig['login_lockout_minutes'] . ' menit.');
                return;
            }
            $this->failLogin('Username/email atau password salah.');
            return;
        }

        // Login sukses
        $this->userModel->resetFailedAttempts((int) $user['id']);
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
        $_SESSION['role'] = $user['nama_role'];
        $_SESSION['last_activity'] = time();

        Security::logActivity((int) $user['id'], 'Login berhasil', 'auth');
        $this->redirect('/admin');
    }

    private function failLogin(string $message): void
    {
        $_SESSION['login_error'] = $message;
        $this->redirect('/auth/login');
    }

    public function logout(): void
    {
        if (!empty($_SESSION['user_id'])) {
            Security::logActivity((int) $_SESSION['user_id'], 'Logout', 'auth');
        }
        session_unset();
        session_destroy();
        $this->redirect('/auth/login');
    }

    public function gantiPassword(): void
    {
        $this->requireAuth();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrf();

            $lama = (string) ($_POST['password_lama'] ?? '');
            $baru = (string) ($_POST['password_baru'] ?? '');
            $konfirmasi = (string) ($_POST['password_konfirmasi'] ?? '');

            $user = $this->userModel->find($_SESSION['user_id']);

            if (!password_verify($lama, $user['password'])) {
                $_SESSION['flash_error'] = 'Password lama tidak sesuai.';
            } elseif (strlen($baru) < 8) {
                $_SESSION['flash_error'] = 'Password baru minimal 8 karakter.';
            } elseif ($baru !== $konfirmasi) {
                $_SESSION['flash_error'] = 'Konfirmasi password tidak cocok.';
            } else {
                $this->userModel->updatePassword((int) $user['id'], password_hash($baru, PASSWORD_BCRYPT));
                Security::logActivity((int) $user['id'], 'Mengganti password', 'auth');
                $_SESSION['flash_success'] = 'Password berhasil diubah.';
            }
            $this->redirect('/auth/gantiPassword');
        }

        $this->view('auth/gantiPassword', [], layout: 'layouts/admin');
    }
}
