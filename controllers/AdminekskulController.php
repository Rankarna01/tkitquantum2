<?php

class AdminekskulController extends Controller
{
    private EkstrakurikulerModel $model;

    public function __construct()
    {
        parent::__construct();
        $this->requireAuth();
        $this->requireRole(['superadmin', 'admin']);
        $this->model = new EkstrakurikulerModel();
    }

    public function index(): void
    {
        $this->view('admin/ekskul/index', ['daftar' => $this->model->all('nama ASC')], layout: 'layouts/admin');
    }

    public function tambah(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') { $this->simpan(); return; }
        $this->view('admin/ekskul/form', ['item' => null], layout: 'layouts/admin');
    }

    public function edit(string $id = ''): void
    {
        $item = $this->model->find((int) $id);
        if (!$item) $this->abort(404, 'Data tidak ditemukan');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') { $this->simpan((int) $id, $item); return; }
        $this->view('admin/ekskul/form', ['item' => $item], layout: 'layouts/admin');
    }

    private function simpan(?int $id = null, ?array $existing = null): void
    {
        $this->validateCsrf();
        $nama = Security::clean($_POST['nama'] ?? '');
        if ($nama === '') {
            $_SESSION['flash_error'] = 'Nama ekstrakurikuler wajib diisi.';
            $this->redirect($id ? "/adminekskul/edit/{$id}" : '/adminekskul/tambah');
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
                $this->redirect($id ? "/adminekskul/edit/{$id}" : '/adminekskul/tambah');
            }
        }

        $data = [
            'nama' => $nama,
            'pembina' => Security::clean($_POST['pembina'] ?? ''),
            'foto' => $foto,
            'deskripsi' => Security::clean($_POST['deskripsi'] ?? ''),
            'jadwal' => Security::clean($_POST['jadwal'] ?? ''),
        ];

        if ($id) {
            $this->model->update($id, $data);
            $_SESSION['flash_success'] = 'Ekstrakurikuler berhasil diperbarui.';
        } else {
            $this->model->insert($data);
            $_SESSION['flash_success'] = 'Ekstrakurikuler berhasil ditambahkan.';
        }
        Security::logActivity((int) $_SESSION['user_id'], "Menyimpan ekstrakurikuler: {$nama}", 'ekskul');
        $this->redirect('/adminekskul');
    }

    public function hapus(string $id = ''): void
    {
        $item = $this->model->find((int) $id);
        if ($item) {
            $this->model->delete((int) $id);
            Security::logActivity((int) $_SESSION['user_id'], "Menghapus ekstrakurikuler: {$item['nama']}", 'ekskul');
            $_SESSION['flash_success'] = 'Data berhasil dihapus.';
        }
        $this->redirect('/adminekskul');
    }
}
