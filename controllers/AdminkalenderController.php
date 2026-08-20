<?php

class AdminkalenderController extends Controller
{
    private KalenderAkademikModel $model;

    public function __construct()
    {
        parent::__construct();
        $this->requireAuth();
        $this->requireRole(['superadmin', 'admin']);
        $this->model = new KalenderAkademikModel();
    }

    public function index(): void
    {
        $this->view('admin/kalender/index', ['daftar' => $this->model->getAll()], layout: 'layouts/admin');
    }

    public function tambah(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') { $this->simpan(); return; }
        $this->view('admin/kalender/form', ['item' => null], layout: 'layouts/admin');
    }

    public function edit(string $id = ''): void
    {
        $item = $this->model->find((int) $id);
        if (!$item) $this->abort(404, 'Data tidak ditemukan');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') { $this->simpan((int) $id); return; }
        $this->view('admin/kalender/form', ['item' => $item], layout: 'layouts/admin');
    }

    private function simpan(?int $id = null): void
    {
        $this->validateCsrf();
        $judul = Security::clean($_POST['judul'] ?? '');
        $mulai = $_POST['tanggal_mulai'] ?? '';

        if ($judul === '' || $mulai === '') {
            $_SESSION['flash_error'] = 'Judul dan tanggal mulai wajib diisi.';
            $this->redirect($id ? "/adminkalender/edit/{$id}" : '/adminkalender/tambah');
        }

        $data = [
            'judul' => $judul,
            'tanggal_mulai' => $mulai,
            'tanggal_selesai' => $_POST['tanggal_selesai'] ?: null,
            'keterangan' => Security::clean($_POST['keterangan'] ?? ''),
        ];

        if ($id) {
            $this->model->update($id, $data);
            $_SESSION['flash_success'] = 'Kalender akademik berhasil diperbarui.';
        } else {
            $this->model->insert($data);
            $_SESSION['flash_success'] = 'Kalender akademik berhasil ditambahkan.';
        }
        Security::logActivity((int) $_SESSION['user_id'], "Menyimpan kalender akademik: {$judul}", 'kalender');
        $this->redirect('/adminkalender');
    }

    public function hapus(string $id = ''): void
    {
        $item = $this->model->find((int) $id);
        if ($item) {
            $this->model->delete((int) $id);
            Security::logActivity((int) $_SESSION['user_id'], "Menghapus kalender akademik: {$item['judul']}", 'kalender');
            $_SESSION['flash_success'] = 'Data berhasil dihapus.';
        }
        $this->redirect('/adminkalender');
    }
}
