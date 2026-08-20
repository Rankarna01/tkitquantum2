<?php

class AdminagendaController extends Controller
{
    private AgendaModel $model;

    public function __construct()
    {
        parent::__construct();
        $this->requireAuth();
        $this->requireRole(['superadmin', 'admin', 'operator']);
        $this->model = new AgendaModel();
    }

    public function index(): void
    {
        $this->view('admin/agenda/index', ['daftar' => $this->model->all('tanggal_mulai DESC')], layout: 'layouts/admin');
    }

    public function tambah(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') { $this->simpan(); return; }
        $this->view('admin/agenda/form', ['item' => null], layout: 'layouts/admin');
    }

    public function edit(string $id = ''): void
    {
        $item = $this->model->find((int) $id);
        if (!$item) $this->abort(404, 'Agenda tidak ditemukan');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') { $this->simpan((int) $id); return; }
        $this->view('admin/agenda/form', ['item' => $item], layout: 'layouts/admin');
    }

    private function simpan(?int $id = null): void
    {
        $this->validateCsrf();
        $judul = Security::clean($_POST['judul'] ?? '');
        $mulai = $_POST['tanggal_mulai'] ?? '';
        $selesai = $_POST['tanggal_selesai'] ?? null;
        $lokasi = Security::clean($_POST['lokasi'] ?? '');
        $deskripsi = Security::clean($_POST['deskripsi'] ?? '');

        if ($judul === '' || $mulai === '') {
            $_SESSION['flash_error'] = 'Judul dan tanggal mulai wajib diisi.';
            $this->redirect($id ? "/adminagenda/edit/{$id}" : '/adminagenda/tambah');
        }

        $data = [
            'judul' => $judul, 'tanggal_mulai' => $mulai, 'tanggal_selesai' => $selesai ?: null,
            'lokasi' => $lokasi, 'deskripsi' => $deskripsi,
        ];

        if ($id) {
            $this->model->update($id, $data);
            $_SESSION['flash_success'] = 'Agenda berhasil diperbarui.';
        } else {
            $this->model->insert($data);
            $_SESSION['flash_success'] = 'Agenda berhasil ditambahkan.';
        }
        Security::logActivity((int) $_SESSION['user_id'], "Menyimpan agenda: {$judul}", 'agenda');
        $this->redirect('/adminagenda');
    }

    public function hapus(string $id = ''): void
    {
        $item = $this->model->find((int) $id);
        if ($item) {
            $this->model->delete((int) $id);
            Security::logActivity((int) $_SESSION['user_id'], "Menghapus agenda: {$item['judul']}", 'agenda');
            $_SESSION['flash_success'] = 'Agenda berhasil dihapus.';
        }
        $this->redirect('/adminagenda');
    }
}
