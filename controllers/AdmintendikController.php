<?php

class AdmintendikController extends Controller
{
    private TenagaKependidikanModel $model;

    public function __construct()
    {
        parent::__construct();
        $this->requireAuth();
        $this->requireRole(['superadmin', 'admin']);
        $this->model = new TenagaKependidikanModel();
    }

    public function index(): void
    {
        $this->view('admin/tendik/index', ['daftar' => $this->model->all('nama_lengkap ASC')], layout: 'layouts/admin');
    }

    public function tambah(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') { $this->simpan(); return; }
        $this->view('admin/tendik/form', ['item' => null], layout: 'layouts/admin');
    }

    public function edit(string $id = ''): void
    {
        $item = $this->model->find((int) $id);
        if (!$item) $this->abort(404, 'Data tidak ditemukan');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') { $this->simpan((int) $id, $item); return; }
        $this->view('admin/tendik/form', ['item' => $item], layout: 'layouts/admin');
    }

    private function simpan(?int $id = null, ?array $existing = null): void
    {
        $this->validateCsrf();

        $nama = Security::clean($_POST['nama_lengkap'] ?? '');
        $nip = Security::clean($_POST['nip'] ?? '');
        $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL) ?: null;

        if ($nama === '') {
            $_SESSION['flash_error'] = 'Nama lengkap wajib diisi.';
            $this->redirect($id ? "/admintendik/edit/{$id}" : '/admintendik/tambah');
        }

        $foto = $existing['foto'] ?? null;
        if (!empty($_FILES['foto']['name'])) {
            try {
                $filename = Security::handleUpload(
                    $_FILES['foto'], dirname(__DIR__) . '/public/uploads/tendik',
                    ['image/jpeg', 'image/png', 'image/webp'], $this->appConfig['upload_max_size']
                );
                if ($filename) $foto = 'uploads/tendik/' . $filename;
            } catch (RuntimeException $e) {
                $_SESSION['flash_error'] = $e->getMessage();
                $this->redirect($id ? "/admintendik/edit/{$id}" : '/admintendik/tambah');
            }
        }

        $data = [
            'nip' => $nip ?: null,
            'nama_lengkap' => $nama,
            'foto' => $foto,
            'jabatan' => Security::clean($_POST['jabatan'] ?? ''),
            'pendidikan_terakhir' => Security::clean($_POST['pendidikan_terakhir'] ?? ''),
            'email' => $email,
            'no_hp' => Security::clean($_POST['no_hp'] ?? ''),
            'deskripsi_singkat' => Security::clean($_POST['deskripsi_singkat'] ?? ''),
            'status' => in_array($_POST['status'] ?? '', ['aktif', 'nonaktif'], true) ? $_POST['status'] : 'aktif',
        ];

        if ($id) {
            $this->model->update($id, $data);
            $_SESSION['flash_success'] = 'Data berhasil diperbarui.';
        } else {
            $this->model->insert($data);
            $_SESSION['flash_success'] = 'Data berhasil ditambahkan.';
        }
        Security::logActivity((int) $_SESSION['user_id'], "Menyimpan data tenaga kependidikan: {$nama}", 'tendik');
        $this->redirect('/admintendik');
    }

    public function hapus(string $id = ''): void
    {
        $item = $this->model->find((int) $id);
        if ($item) {
            $this->model->delete((int) $id);
            Security::logActivity((int) $_SESSION['user_id'], "Menghapus data tenaga kependidikan: {$item['nama_lengkap']}", 'tendik');
            $_SESSION['flash_success'] = 'Data berhasil dihapus.';
        }
        $this->redirect('/admintendik');
    }
}
