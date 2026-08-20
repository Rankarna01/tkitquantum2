<?php

class AdminprofilController extends Controller
{
    private ProfilSekolahModel $model;

    public function __construct()
    {
        parent::__construct();
        $this->requireAuth();
        $this->requireRole(['superadmin', 'admin']);
        $this->model = new ProfilSekolahModel();
    }

    public function index(): void
    {
        $this->view('admin/profil/index', ['profil' => $this->model->get()], layout: 'layouts/admin');
    }

    public function simpan(): void
    {
        $this->validateCsrf();
        $existing = $this->model->get();

        $data = [
            'sejarah' => Security::clean($_POST['sejarah'] ?? ''),
            'visi' => Security::clean($_POST['visi'] ?? ''),
            'misi' => Security::clean($_POST['misi'] ?? ''),
            'tujuan' => Security::clean($_POST['tujuan'] ?? ''),
            'sambutan_kepsek' => Security::clean($_POST['sambutan_kepsek'] ?? ''),
            'nama_kepsek' => Security::clean($_POST['nama_kepsek'] ?? ''),
        ];

        if (!empty($_FILES['foto_sejarah']['name'])) {
            try {
                $filename = Security::handleUpload(
                    $_FILES['foto_sejarah'], dirname(__DIR__) . '/public/uploads/branding',
                    ['image/jpeg', 'image/png', 'image/webp'], $this->appConfig['upload_max_size']
                );
                if ($filename) $data['foto_sejarah'] = 'uploads/branding/' . $filename;
            } catch (RuntimeException $e) {
                $_SESSION['flash_error'] = $e->getMessage();
                $this->redirect('/adminprofil');
            }
        }

        try {
            if ($existing) {
                $this->model->update($existing['id'], $data);
            } else {
                $this->model->insert($data);
            }
        } catch (PDOException $e) {
            unset($data['foto_sejarah']);
            if ($existing) { $this->model->update($existing['id'], $data); } else { $this->model->insert($data); }
            $_SESSION['flash_error'] = 'Teks profil tersimpan, tapi foto sejarah gagal disimpan: kolom foto_sejarah belum ada di database. Jalankan migrasi: ALTER TABLE profil_sekolah ADD COLUMN foto_sejarah VARCHAR(255) NULL.';
            $this->redirect('/adminprofil');
        }

        Security::logActivity((int) $_SESSION['user_id'], 'Memperbarui profil sekolah', 'profil');
        $_SESSION['flash_success'] = 'Profil sekolah berhasil disimpan.';
        $this->redirect('/adminprofil');
    }
}
