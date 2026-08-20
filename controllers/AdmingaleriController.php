<?php

class AdmingaleriController extends Controller
{
    private GaleriFotoModel $fotoModel;
    private GaleriVideoModel $videoModel;
    private GaleriKategoriModel $kategoriModel;

    public function __construct()
    {
        parent::__construct();
        $this->requireAuth();
        $this->requireRole(['superadmin', 'admin', 'operator']);
        $this->fotoModel = new GaleriFotoModel();
        $this->videoModel = new GaleriVideoModel();
        $this->kategoriModel = new GaleriKategoriModel();
    }

    public function index(): void
    {
        $this->view('admin/galeri/index', [
            'foto' => $this->fotoModel->allWithKategori(),
            'video' => $this->videoModel->all('created_at DESC'),
            'kategoriList' => $this->kategoriModel->all('nama_kategori'),
        ], layout: 'layouts/admin');
    }

    public function uploadFoto(): void
    {
        $this->validateCsrf();

        if (empty($_FILES['file']['name'])) {
            $_SESSION['flash_error'] = 'Pilih file foto terlebih dahulu.';
            $this->redirect('/admingaleri');
        }

        try {
            $filename = Security::handleUpload(
                $_FILES['file'], dirname(__DIR__) . '/public/uploads/galeri',
                ['image/jpeg', 'image/png', 'image/webp'], $this->appConfig['upload_max_size']
            );
            $this->fotoModel->insert([
                'kategori_id' => (int) ($_POST['kategori_id'] ?? 0) ?: null,
                'judul' => Security::clean($_POST['judul'] ?? ''),
                'file' => 'uploads/galeri/' . $filename,
            ]);
            Security::logActivity((int) $_SESSION['user_id'], 'Menambah foto galeri', 'galeri');
            $_SESSION['flash_success'] = 'Foto berhasil diunggah.';
        } catch (RuntimeException $e) {
            $_SESSION['flash_error'] = $e->getMessage();
        }

        $this->redirect('/admingaleri');
    }

    public function hapusFoto(string $id = ''): void
    {
        $item = $this->fotoModel->find((int) $id);
        if ($item) {
            $this->fotoModel->delete((int) $id);
            $path = dirname(__DIR__) . '/public/' . $item['file'];
            if (is_file($path)) @unlink($path);
            Security::logActivity((int) $_SESSION['user_id'], 'Menghapus foto galeri', 'galeri');
            $_SESSION['flash_success'] = 'Foto berhasil dihapus.';
        }
        $this->redirect('/admingaleri');
    }

    public function tambahVideo(): void
    {
        $this->validateCsrf();
        $judul = Security::clean($_POST['judul'] ?? '');
        $url = filter_var($_POST['url_youtube'] ?? '', FILTER_VALIDATE_URL);
        $platform = Security::clean($_POST['platform'] ?? 'YouTube');
        $allowedPlatform = ['YouTube', 'Instagram', 'TikTok', 'Facebook', 'Lainnya'];
        if (!in_array($platform, $allowedPlatform, true)) $platform = 'Lainnya';

        if ($judul === '' || !$url) {
            $_SESSION['flash_error'] = 'Judul dan URL video yang valid wajib diisi.';
            $this->redirect('/admingaleri');
        }

        $data = ['judul' => $judul, 'url_youtube' => $url, 'platform' => $platform];

        if (!empty($_FILES['thumbnail']['name'])) {
            try {
                $filename = Security::handleUpload(
                    $_FILES['thumbnail'], dirname(__DIR__) . '/public/uploads/galeri',
                    ['image/jpeg', 'image/png', 'image/webp'], $this->appConfig['upload_max_size']
                );
                if ($filename) $data['thumbnail'] = 'uploads/galeri/' . $filename;
            } catch (RuntimeException $e) {
                $_SESSION['flash_error'] = $e->getMessage();
                $this->redirect('/admingaleri');
            }
        }

        try {
            $this->videoModel->insert($data);
        } catch (PDOException $e) {
            unset($data['thumbnail'], $data['platform']);
            try {
                $this->videoModel->insert($data);
                $_SESSION['flash_error'] = 'Video tersimpan, tapi kolom platform/thumbnail belum ada di database. Jalankan migrasi ALTER TABLE galeri_video untuk fitur lengkap.';
                $this->redirect('/admingaleri');
            } catch (PDOException $e2) {
                $_SESSION['flash_error'] = 'Gagal menyimpan video: struktur tabel galeri_video belum lengkap. Jalankan skrip migrasi database di phpMyAdmin, lalu coba lagi.';
                $this->redirect('/admingaleri');
            }
        }
        Security::logActivity((int) $_SESSION['user_id'], 'Menambah video galeri', 'galeri');
        $_SESSION['flash_success'] = 'Video berhasil ditambahkan.';
        $this->redirect('/admingaleri');
    }

    public function hapusVideo(string $id = ''): void
    {
        $item = $this->videoModel->find((int) $id);
        if ($item) {
            $this->videoModel->delete((int) $id);
            Security::logActivity((int) $_SESSION['user_id'], 'Menghapus video galeri', 'galeri');
            $_SESSION['flash_success'] = 'Video berhasil dihapus.';
        }
        $this->redirect('/admingaleri');
    }

    public function updateThumbnail(string $id = ''): void
    {
        $this->validateCsrf();
        $item = $this->videoModel->find((int) $id);
        if (!$item) {
            $_SESSION['flash_error'] = 'Video tidak ditemukan.';
            $this->redirect('/admingaleri');
        }

        if (empty($_FILES['thumbnail']['name'])) {
            $_SESSION['flash_error'] = 'Pilih gambar thumbnail terlebih dahulu.';
            $this->redirect('/admingaleri');
        }

        try {
            $filename = Security::handleUpload(
                $_FILES['thumbnail'], dirname(__DIR__) . '/public/uploads/galeri',
                ['image/jpeg', 'image/png', 'image/webp'], $this->appConfig['upload_max_size']
            );
            if ($filename) {
                $this->videoModel->update((int) $id, ['thumbnail' => 'uploads/galeri/' . $filename]);
                Security::logActivity((int) $_SESSION['user_id'], 'Memperbarui thumbnail video galeri', 'galeri');
                $_SESSION['flash_success'] = 'Thumbnail video berhasil diperbarui.';
            }
        } catch (RuntimeException $e) {
            $_SESSION['flash_error'] = $e->getMessage();
        } catch (PDOException $e) {
            $_SESSION['flash_error'] = 'Gagal menyimpan: kolom thumbnail belum ada di database. Jalankan migrasi ALTER TABLE galeri_video ADD COLUMN thumbnail VARCHAR(255) NULL.';
        }

        $this->redirect('/admingaleri');
    }
}
