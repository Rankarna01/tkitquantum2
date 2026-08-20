<?php

class AdminstrukturController extends Controller
{
    private StrukturOrganisasiModel $model;

    public function __construct()
    {
        parent::__construct();
        $this->requireAuth();
        $this->requireRole(['superadmin', 'admin']);
        $this->model = new StrukturOrganisasiModel();
    }

    public function index(): void
    {
        $this->view('admin/struktur/index', ['daftar' => $this->model->allOrdered()], layout: 'layouts/admin');
    }

    public function tambah(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') { $this->simpan(); return; }
        $this->view('admin/struktur/form', ['item' => null, 'daftar' => $this->model->allOrdered()], layout: 'layouts/admin');
    }

    public function edit(string $id = ''): void
    {
        $item = $this->model->find((int) $id);
        if (!$item) $this->abort(404, 'Data tidak ditemukan');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') { $this->simpan((int) $id, $item); return; }
        $this->view('admin/struktur/form', ['item' => $item, 'daftar' => $this->model->allOrdered()], layout: 'layouts/admin');
    }

    private function simpan(?int $id = null, ?array $existing = null): void
    {
        $this->validateCsrf();
        $nama = Security::clean($_POST['nama'] ?? '');
        $jabatan = Security::clean($_POST['jabatan'] ?? '');

        if ($nama === '' || $jabatan === '') {
            $_SESSION['flash_error'] = 'Nama dan jabatan wajib diisi.';
            $this->redirect($id ? "/adminstruktur/edit/{$id}" : '/adminstruktur/tambah');
        }

        $foto = $existing['foto'] ?? null;
        if (!empty($_FILES['foto']['name'])) {
            try {
                $filename = Security::handleUpload(
                    $_FILES['foto'], dirname(__DIR__) . '/public/uploads/guru',
                    ['image/jpeg', 'image/png', 'image/webp'], $this->appConfig['upload_max_size']
                );
                if ($filename) $foto = 'uploads/guru/' . $filename;
            } catch (RuntimeException $e) {
                $_SESSION['flash_error'] = $e->getMessage();
                $this->redirect($id ? "/adminstruktur/edit/{$id}" : '/adminstruktur/tambah');
            }
        }

        $data = [
            'nama' => $nama,
            'jabatan' => $jabatan,
            'foto' => $foto,
            'urutan' => (int) ($_POST['urutan'] ?? 0),
            'parent_id' => (int) ($_POST['parent_id'] ?? 0) ?: null,
        ];

        if ($id) {
            $this->model->update($id, $data);
            $_SESSION['flash_success'] = 'Data struktur organisasi berhasil diperbarui.';
        } else {
            $this->model->insert($data);
            $_SESSION['flash_success'] = 'Data struktur organisasi berhasil ditambahkan.';
        }
        Security::logActivity((int) $_SESSION['user_id'], "Menyimpan struktur organisasi: {$nama}", 'struktur');
        $this->redirect('/adminstruktur');
    }

    public function hapus(string $id = ''): void
    {
        $item = $this->model->find((int) $id);
        if ($item) {
            $this->model->delete((int) $id);
            Security::logActivity((int) $_SESSION['user_id'], "Menghapus struktur organisasi: {$item['nama']}", 'struktur');
            $_SESSION['flash_success'] = 'Data berhasil dihapus.';
        }
        $this->redirect('/adminstruktur');
    }
}
