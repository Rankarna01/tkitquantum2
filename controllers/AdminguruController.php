<?php

class AdminguruController extends Controller
{
    private GuruModel $model;
    private TenagaKependidikanModel $tendikModel;

    public function __construct()
    {
        parent::__construct();
        $this->requireAuth();
        $this->requireRole(['superadmin', 'admin']);
        $this->model = new GuruModel();
        $this->tendikModel = new TenagaKependidikanModel();
    }

    public function index(): void
    {
        $this->view('admin/guru/index', [
            'daftar' => $this->model->all('nama_lengkap ASC'),
            'daftarTendik' => $this->tendikModel->all('nama_lengkap ASC'),
        ], layout: 'layouts/admin');
    }

    public function tambah(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') { $this->simpan(); return; }
        $this->view('admin/guru/form', ['item' => null], layout: 'layouts/admin');
    }

    public function edit(string $id = ''): void
    {
        $item = $this->model->find((int) $id);
        if (!$item) $this->abort(404, 'Data guru tidak ditemukan');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') { $this->simpan((int) $id, $item); return; }
        $this->view('admin/guru/form', ['item' => $item], layout: 'layouts/admin');
    }

    private function simpan(?int $id = null, ?array $existing = null): void
    {
        $this->validateCsrf();

        $nama = Security::clean($_POST['nama_lengkap'] ?? '');
        $nip = Security::clean($_POST['nip'] ?? '');
        $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL) ?: null;

        if ($nama === '') {
            $_SESSION['flash_error'] = 'Nama lengkap wajib diisi.';
            $this->redirect($id ? "/adminguru/edit/{$id}" : '/adminguru/tambah');
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
                $this->redirect($id ? "/adminguru/edit/{$id}" : '/adminguru/tambah');
            }
        }

        $data = [
            'nip' => $nip ?: null,
            'nama_lengkap' => $nama,
            'foto' => $foto,
            'mata_pelajaran' => Security::clean($_POST['mata_pelajaran'] ?? ''),
            'pendidikan_terakhir' => Security::clean($_POST['pendidikan_terakhir'] ?? ''),
            'jabatan' => Security::clean($_POST['jabatan'] ?? ''),
            'email' => $email,
            'no_hp' => Security::clean($_POST['no_hp'] ?? ''),
            'riwayat_singkat' => Security::clean($_POST['riwayat_singkat'] ?? ''),
            'status' => in_array($_POST['status'] ?? '', ['aktif', 'nonaktif'], true) ? $_POST['status'] : 'aktif',
        ];

        if ($id) {
            $this->model->update($id, $data);
            $_SESSION['flash_success'] = 'Data guru berhasil diperbarui.';
        } else {
            $this->model->insert($data);
            $_SESSION['flash_success'] = 'Data guru berhasil ditambahkan.';
        }
        Security::logActivity((int) $_SESSION['user_id'], "Menyimpan data guru: {$nama}", 'guru');
        $this->redirect('/adminguru');
    }

    public function hapus(string $id = ''): void
    {
        $item = $this->model->find((int) $id);
        if ($item) {
            $this->model->delete((int) $id);
            Security::logActivity((int) $_SESSION['user_id'], "Menghapus data guru: {$item['nama_lengkap']}", 'guru');
            $_SESSION['flash_success'] = 'Data guru berhasil dihapus.';
        }
        $this->redirect('/adminguru');
    }
}
