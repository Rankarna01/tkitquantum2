<?php

class AdminprestasiController extends Controller
{
    private PrestasiModel $model;

    public function __construct()
    {
        parent::__construct();
        $this->requireAuth();
        $this->requireRole(['superadmin', 'admin', 'operator']);
        $this->model = new PrestasiModel();
    }

    public function index(): void
    {
        $this->view('admin/prestasi/index', ['daftar' => $this->model->getAll()], layout: 'layouts/admin');
    }

    public function tambah(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') { $this->simpan(); return; }
        $this->view('admin/prestasi/form', ['item' => null], layout: 'layouts/admin');
    }

    public function edit(string $id = ''): void
    {
        $item = $this->model->find((int) $id);
        if (!$item) $this->abort(404, 'Data tidak ditemukan');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') { $this->simpan((int) $id, $item); return; }
        $this->view('admin/prestasi/form', ['item' => $item], layout: 'layouts/admin');
    }

    private function simpan(?int $id = null, ?array $existing = null): void
    {
        $this->validateCsrf();
        $judul = Security::clean($_POST['judul'] ?? '');
        if ($judul === '') {
            $_SESSION['flash_error'] = 'Judul prestasi wajib diisi.';
            $this->redirect($id ? "/adminprestasi/edit/{$id}" : '/adminprestasi/tambah');
        }

        $foto = $existing['foto'] ?? null;
        if (!empty($_FILES['foto']['name'])) {
            try {
                $filename = Security::handleUpload(
                    $_FILES['foto'], dirname(__DIR__) . '/public/uploads/berita',
                    ['image/jpeg', 'image/png', 'image/webp'], $this->appConfig['upload_max_size']
                );
                if ($filename) $foto = 'uploads/berita/' . $filename;
            } catch (RuntimeException $e) {
                $_SESSION['flash_error'] = $e->getMessage();
                $this->redirect($id ? "/adminprestasi/edit/{$id}" : '/adminprestasi/tambah');
            }
        }

        $data = [
            'judul' => $judul,
            'kategori' => $_POST['kategori'] === 'non-akademik' ? 'non-akademik' : 'akademik',
            'tingkat' => Security::clean($_POST['tingkat'] ?? ''),
            'tahun' => (int) ($_POST['tahun'] ?? date('Y')),
            'foto' => $foto,
            'deskripsi' => Security::clean($_POST['deskripsi'] ?? ''),
        ];

        if ($id) {
            $this->model->update($id, $data);
            $_SESSION['flash_success'] = 'Prestasi berhasil diperbarui.';
        } else {
            $this->model->insert($data);
            $_SESSION['flash_success'] = 'Prestasi berhasil ditambahkan.';
        }
        Security::logActivity((int) $_SESSION['user_id'], "Menyimpan prestasi: {$judul}", 'prestasi');
        $this->redirect('/adminprestasi');
    }

    public function hapus(string $id = ''): void
    {
        $item = $this->model->find((int) $id);
        if ($item) {
            $this->model->delete((int) $id);
            Security::logActivity((int) $_SESSION['user_id'], "Menghapus prestasi: {$item['judul']}", 'prestasi');
            $_SESSION['flash_success'] = 'Prestasi berhasil dihapus.';
        }
        $this->redirect('/adminprestasi');
    }
}
