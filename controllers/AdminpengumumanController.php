<?php

class AdminpengumumanController extends Controller
{
    private PengumumanModel $model;

    public function __construct()
    {
        parent::__construct();
        $this->requireAuth();
        $this->requireRole(['superadmin', 'admin', 'operator']);
        $this->model = new PengumumanModel();
    }

    public function index(): void
    {
        $this->view('admin/pengumuman/index', ['daftar' => $this->model->all('created_at DESC')], layout: 'layouts/admin');
    }

    public function tambah(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') { $this->simpan(); return; }
        $this->view('admin/pengumuman/form', ['item' => null], layout: 'layouts/admin');
    }

    public function edit(string $id = ''): void
    {
        $item = $this->model->find((int) $id);
        if (!$item) $this->abort(404, 'Pengumuman tidak ditemukan');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') { $this->simpan((int) $id, $item); return; }
        $this->view('admin/pengumuman/form', ['item' => $item], layout: 'layouts/admin');
    }

    private function simpan(?int $id = null, ?array $existing = null): void
    {
        $this->validateCsrf();
        $judul = Security::clean($_POST['judul'] ?? '');
        $isiSingkat = Security::clean($_POST['isi_singkat'] ?? '');
        $isi = $_POST['isi'] ?? '';
        $status = in_array($_POST['status'] ?? '', ['draft', 'publish'], true) ? $_POST['status'] : 'draft';

        if ($judul === '' || $isi === '') {
            $_SESSION['flash_error'] = 'Judul dan isi wajib diisi.';
            $this->redirect($id ? "/adminpengumuman/edit/{$id}" : '/adminpengumuman/tambah');
        }

        $db = Database::getInstance();
        $slug = Str::uniqueSlug($db, 'pengumuman', $judul, $id);

        $lampiran = $existing['lampiran'] ?? null;
        if (!empty($_FILES['lampiran']['name'])) {
            try {
                $filename = Security::handleUpload(
                    $_FILES['lampiran'], dirname(__DIR__) . '/public/uploads/berita',
                    ['image/jpeg', 'image/png', 'application/pdf'], $this->appConfig['upload_max_size']
                );
                if ($filename) $lampiran = 'uploads/berita/' . $filename;
            } catch (RuntimeException $e) {
                $_SESSION['flash_error'] = $e->getMessage();
                $this->redirect($id ? "/adminpengumuman/edit/{$id}" : '/adminpengumuman/tambah');
            }
        }

        $data = [
            'user_id' => $_SESSION['user_id'], 'judul' => $judul, 'slug' => $slug,
            'isi_singkat' => $isiSingkat, 'isi' => $isi, 'lampiran' => $lampiran,
            'status' => $status, 'tanggal_publish' => $status === 'publish' ? date('Y-m-d H:i:s') : null,
        ];

        if ($id) {
            $this->model->update($id, $data);
            $_SESSION['flash_success'] = 'Pengumuman berhasil diperbarui.';
        } else {
            $this->model->insert($data);
            $_SESSION['flash_success'] = 'Pengumuman berhasil ditambahkan.';
        }
        Security::logActivity((int) $_SESSION['user_id'], "Menyimpan pengumuman: {$judul}", 'pengumuman');
        $this->redirect('/adminpengumuman');
    }

    public function hapus(string $id = ''): void
    {
        $item = $this->model->find((int) $id);
        if ($item) {
            $this->model->delete((int) $id);
            Security::logActivity((int) $_SESSION['user_id'], "Menghapus pengumuman: {$item['judul']}", 'pengumuman');
            $_SESSION['flash_success'] = 'Pengumuman berhasil dihapus.';
        }
        $this->redirect('/adminpengumuman');
    }
}
