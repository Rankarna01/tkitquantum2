<?php

class AdminbrandingController extends Controller
{
    private PengaturanModel $model;

    public function __construct()
    {
        parent::__construct();
        $this->requireAuth();
        $this->requireRole(['superadmin', 'admin']);
        $this->model = new PengaturanModel();
    }

    public function index(): void
    {
        $this->view('admin/branding/index', ['pengaturan' => $this->model->get()], layout: 'layouts/admin');
    }

    public function simpan(): void
    {
        $this->validateCsrf();
        $existing = $this->model->get();

        $data = [
            'nama_sekolah' => Security::clean($_POST['nama_sekolah'] ?? ''),
            'tagline' => Security::clean($_POST['tagline'] ?? ''),
            'alamat' => Security::clean($_POST['alamat'] ?? ''),
            'email' => filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL) ?: ($existing['email'] ?? null),
            'telepon' => Security::clean($_POST['telepon'] ?? ''),
            'warna_primary' => $this->cleanColor($_POST['warna_primary'] ?? '', $existing['warna_primary'] ?? '#FFC107'),
            'warna_secondary' => $this->cleanColor($_POST['warna_secondary'] ?? '', $existing['warna_secondary'] ?? '#FFF8E1'),
            'warna_accent' => $this->cleanColor($_POST['warna_accent'] ?? '', $existing['warna_accent'] ?? '#FF9800'),
            'facebook' => filter_var($_POST['facebook'] ?? '', FILTER_VALIDATE_URL) ?: null,
            'instagram' => filter_var($_POST['instagram'] ?? '', FILTER_VALIDATE_URL) ?: null,
            'youtube' => filter_var($_POST['youtube'] ?? '', FILTER_VALIDATE_URL) ?: null,
            'tiktok' => filter_var($_POST['tiktok'] ?? '', FILTER_VALIDATE_URL) ?: null,
            'maps_embed' => $_POST['maps_embed'] ?? null, // src iframe Google Maps, disaring saat ditampilkan
            'musik_aktif' => ($_POST['musik_aktif'] ?? '') === 'ya' ? 'ya' : 'tidak',
        ];

        $uploadFields = [
            'logo' => ['image/png', 'image/jpeg', 'image/webp', 'image/svg+xml'],
            'favicon' => ['image/png', 'image/x-icon', 'image/vnd.microsoft.icon'],
            'logo_login' => ['image/png', 'image/jpeg', 'image/webp'],
            'logo_navbar' => ['image/png', 'image/jpeg', 'image/webp'],
            'logo_footer' => ['image/png', 'image/jpeg', 'image/webp'],
            'foto_kepsek' => ['image/jpeg', 'image/png', 'image/webp'],
            'banner_hero' => ['image/jpeg', 'image/png', 'image/webp'],
        ];

        foreach ($uploadFields as $field => $mimes) {
            $data[$field] = $existing[$field] ?? null;
            if (!empty($_FILES[$field]['name'])) {
                try {
                    // favicon/svg tidak selalu dikenali finfo dengan andal; batasi jenis yang benar-benar didukung upload umum
                    $allowed = $field === 'favicon' ? ['image/png', 'image/x-icon'] : ['image/jpeg', 'image/png', 'image/webp'];
                    $filename = Security::handleUpload(
                        $_FILES[$field], dirname(__DIR__) . '/public/uploads/branding',
                        $allowed, $this->appConfig['upload_max_size']
                    );
                    if ($filename) $data[$field] = 'uploads/branding/' . $filename;
                } catch (RuntimeException $e) {
                    $_SESSION['flash_error'] = "{$field}: " . $e->getMessage();
                    $this->redirect('/adminbranding');
                }
            }
        }

        try {
            if ($existing) {
                $this->model->update($existing['id'], $data);
            } else {
                $this->model->insert($data);
            }
        } catch (PDOException $e) {
            unset($data['musik_aktif']);
            if ($existing) { $this->model->update($existing['id'], $data); } else { $this->model->insert($data); }
            $_SESSION['flash_error'] = 'Sebagian tersimpan, tapi kolom musik_aktif belum ada di database. Jalankan migrasi ALTER TABLE pengaturan_website terlebih dahulu.';
            $this->redirect('/adminbranding');
        }

        if (!empty($_FILES['musik_latar']['name'])) {
            try {
                $filename = Security::handleUpload(
                    $_FILES['musik_latar'], dirname(__DIR__) . '/public/uploads/musik',
                    ['audio/mpeg', 'audio/mp3', 'audio/ogg', 'audio/wav'], $this->appConfig['upload_max_size']
                );
                if ($filename) {
                    $latest = $this->model->get();
                    $this->model->update($latest['id'], ['musik_latar' => 'uploads/musik/' . $filename]);
                }
            } catch (RuntimeException $e) {
                $_SESSION['flash_error'] = 'Musik latar: ' . $e->getMessage();
                $this->redirect('/adminbranding');
            } catch (PDOException $e) {
                $_SESSION['flash_error'] = 'Kolom musik_latar/musik_aktif belum ada di database. Jalankan migrasi ALTER TABLE pengaturan_website terlebih dahulu.';
                $this->redirect('/adminbranding');
            }
        }

        Security::logActivity((int) $_SESSION['user_id'], 'Memperbarui logo & branding sekolah', 'branding');
        $_SESSION['flash_success'] = 'Pengaturan logo & branding berhasil disimpan.';
        $this->redirect('/adminbranding');
    }

    private function cleanColor(string $value, string $fallback): string
    {
        return preg_match('/^#[0-9A-Fa-f]{6}$/', $value) ? $value : $fallback;
    }
}
