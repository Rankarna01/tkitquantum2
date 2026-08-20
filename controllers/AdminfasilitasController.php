<?php

class AdminfasilitasController extends Controller
{
    private FasilitasModel $model;

    public function __construct()
    {
        parent::__construct();
        $this->requireAuth();
        $this->requireRole(['superadmin', 'admin']);
        $this->model = new FasilitasModel();
    }

    public function index(): void
    {
        $this->view('admin/fasilitas/index', ['daftar' => $this->model->all('nama ASC')], layout: 'layouts/admin');
    }

    public function tambah(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') { $this->simpan(); return; }
        $this->view('admin/fasilitas/form', ['item' => null], layout: 'layouts/admin');
    }

    public function edit(string $id = ''): void
    {
        $item = $this->model->find((int) $id);
        if (!$item) $this->abort(404, 'Data tidak ditemukan');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') { $this->simpan((int) $id, $item); return; }
        $this->view('admin/fasilitas/form', ['item' => $item], layout: 'layouts/admin');
    }

    private function simpan(?int $id = null, ?array $existing = null): void
    {
        $this->validateCsrf();
        $nama = Security::clean($_POST['nama'] ?? '');
        if ($nama === '') {
            $_SESSION['flash_error'] = 'Nama fasilitas wajib diisi.';
            $this->redirect($id ? "/adminfasilitas/edit/{$id}" : '/adminfasilitas/tambah');
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
                $this->redirect($id ? "/adminfasilitas/edit/{$id}" : '/adminfasilitas/tambah');
            }
        }

        $data = ['nama' => $nama, 'foto' => $foto, 'deskripsi' => Security::clean($_POST['deskripsi'] ?? '')];

        if ($id) {
            $this->model->update($id, $data);
            $_SESSION['flash_success'] = 'Fasilitas berhasil diperbarui.';
        } else {
            $this->model->insert($data);
            $_SESSION['flash_success'] = 'Fasilitas berhasil ditambahkan.';
        }
        Security::logActivity((int) $_SESSION['user_id'], "Menyimpan fasilitas: {$nama}", 'fasilitas');
        $this->redirect('/adminfasilitas');
    }

    public function hapus(string $id = ''): void
    {
        $item = $this->model->find((int) $id);
        if ($item) {
            $this->model->delete((int) $id);
            Security::logActivity((int) $_SESSION['user_id'], "Menghapus fasilitas: {$item['nama']}", 'fasilitas');
            $_SESSION['flash_success'] = 'Data berhasil dihapus.';
        }
        $this->redirect('/adminfasilitas');
    }
}
