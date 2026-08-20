<?php

class AdmintestimoniController extends Controller
{
    private TestimoniAlumniModel $model;

    public function __construct()
    {
        parent::__construct();
        $this->requireAuth();
        $this->requireRole(['superadmin', 'admin']);
        $this->model = new TestimoniAlumniModel();
    }

    public function index(): void
    {
        $this->view('admin/testimoni/index', ['daftar' => $this->model->all('created_at DESC')], layout: 'layouts/admin');
    }

    public function tambah(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') { $this->simpan(); return; }
        $this->view('admin/testimoni/form', ['item' => null], layout: 'layouts/admin');
    }

    public function edit(string $id = ''): void
    {
        $item = $this->model->find((int) $id);
        if (!$item) $this->abort(404, 'Data tidak ditemukan');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') { $this->simpan((int) $id, $item); return; }
        $this->view('admin/testimoni/form', ['item' => $item], layout: 'layouts/admin');
    }

    private function simpan(?int $id = null, ?array $existing = null): void
    {
        $this->validateCsrf();
        $nama = Security::clean($_POST['nama'] ?? '');
        $isi = Security::clean($_POST['isi_testimoni'] ?? '');
        if ($nama === '' || $isi === '') {
            $_SESSION['flash_error'] = 'Nama dan isi testimoni wajib diisi.';
            $this->redirect($id ? "/admintestimoni/edit/{$id}" : '/admintestimoni/tambah');
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
                $this->redirect($id ? "/admintestimoni/edit/{$id}" : '/admintestimoni/tambah');
            }
        }

        $data = [
            'nama' => $nama,
            'angkatan' => Security::clean($_POST['angkatan'] ?? ''),
            'profesi' => Security::clean($_POST['profesi'] ?? ''),
            'foto' => $foto,
            'isi_testimoni' => $isi,
            'status' => in_array($_POST['status'] ?? '', ['draft', 'publish'], true) ? $_POST['status'] : 'publish',
        ];

        if ($id) {
            $this->model->update($id, $data);
            $_SESSION['flash_success'] = 'Testimoni berhasil diperbarui.';
        } else {
            $this->model->insert($data);
            $_SESSION['flash_success'] = 'Testimoni berhasil ditambahkan.';
        }
        Security::logActivity((int) $_SESSION['user_id'], "Menyimpan testimoni alumni: {$nama}", 'testimoni');
        $this->redirect('/admintestimoni');
    }

    public function hapus(string $id = ''): void
    {
        $item = $this->model->find((int) $id);
        if ($item) {
            $this->model->delete((int) $id);
            Security::logActivity((int) $_SESSION['user_id'], "Menghapus testimoni alumni: {$item['nama']}", 'testimoni');
            $_SESSION['flash_success'] = 'Data berhasil dihapus.';
        }
        $this->redirect('/admintestimoni');
    }
}
