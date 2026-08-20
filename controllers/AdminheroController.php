<?php

class AdminheroController extends Controller
{
    private HeroSlideModel $heroModel;

    public function __construct()
    {
        parent::__construct();
        $this->requireAuth();
        $this->requireRole(['superadmin', 'admin', 'operator']);
        $this->heroModel = new HeroSlideModel();
    }

    public function index(): void
    {
        try {
            $slides = $this->heroModel->all('urutan ASC, id ASC');
        } catch (PDOException $e) {
            $slides = [];
            $_SESSION['flash_error'] = 'Tabel hero_slide belum ada di database. Jalankan migrasi SQL untuk tabel hero_slide terlebih dahulu.';
        }
        $this->view('admin/hero/index', [
            'slides' => $slides,
        ], layout: 'layouts/admin');
    }

    public function upload(): void
    {
        $this->validateCsrf();

        if (empty($_FILES['gambar']['name'])) {
            $_SESSION['flash_error'] = 'Pilih gambar hero terlebih dahulu.';
            $this->redirect('/adminhero');
        }

        try {
            $filename = Security::handleUpload(
                $_FILES['gambar'], dirname(__DIR__) . '/public/uploads/hero',
                ['image/jpeg', 'image/png', 'image/webp'], $this->appConfig['upload_max_size']
            );
            $this->heroModel->insert([
                'gambar' => 'uploads/hero/' . $filename,
                'judul' => Security::clean($_POST['judul'] ?? ''),
                'urutan' => (int) ($_POST['urutan'] ?? 0),
            ]);
            Security::logActivity((int) $_SESSION['user_id'], 'Menambah slide hero beranda', 'hero');
            $_SESSION['flash_success'] = 'Gambar hero berhasil diunggah.';
        } catch (RuntimeException $e) {
            $_SESSION['flash_error'] = $e->getMessage();
        }

        $this->redirect('/adminhero');
    }

    public function hapus(string $id = ''): void
    {
        $item = $this->heroModel->find((int) $id);
        if ($item) {
            $this->heroModel->delete((int) $id);
            $path = dirname(__DIR__) . '/public/' . $item['gambar'];
            if (is_file($path)) @unlink($path);
            Security::logActivity((int) $_SESSION['user_id'], 'Menghapus slide hero beranda', 'hero');
            $_SESSION['flash_success'] = 'Gambar hero berhasil dihapus.';
        }
        $this->redirect('/adminhero');
    }
}
