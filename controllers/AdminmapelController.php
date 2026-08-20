<?php

class AdminmapelController extends Controller
{
    private MataPelajaranModel $model;

    public function __construct()
    {
        parent::__construct();
        $this->requireAuth();
        $this->requireRole(['superadmin', 'admin']);
        $this->model = new MataPelajaranModel();
    }

    public function index(): void
    {
        $this->view('admin/mapel/index', ['daftar' => $this->model->all('nama_mapel ASC')], layout: 'layouts/admin');
    }

    public function tambah(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') { $this->simpan(); return; }
        $this->view('admin/mapel/form', ['item' => null], layout: 'layouts/admin');
    }

    public function edit(string $id = ''): void
    {
        $item = $this->model->find((int) $id);
        if (!$item) $this->abort(404, 'Data tidak ditemukan');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') { $this->simpan((int) $id); return; }
        $this->view('admin/mapel/form', ['item' => $item], layout: 'layouts/admin');
    }

    private function simpan(?int $id = null): void
    {
        $this->validateCsrf();
        $nama = Security::clean($_POST['nama_mapel'] ?? '');
        if ($nama === '') {
            $_SESSION['flash_error'] = 'Nama mata pelajaran wajib diisi.';
            $this->redirect($id ? "/adminmapel/edit/{$id}" : '/adminmapel/tambah');
        }

        $data = [
            'nama_mapel' => $nama,
            'kode' => Security::clean($_POST['kode'] ?? ''),
            'deskripsi' => Security::clean($_POST['deskripsi'] ?? ''),
        ];

        if ($id) {
            $this->model->update($id, $data);
            $_SESSION['flash_success'] = 'Mata pelajaran berhasil diperbarui.';
        } else {
            $this->model->insert($data);
            $_SESSION['flash_success'] = 'Mata pelajaran berhasil ditambahkan.';
        }
        Security::logActivity((int) $_SESSION['user_id'], "Menyimpan mata pelajaran: {$nama}", 'mapel');
        $this->redirect('/adminmapel');
    }

    public function hapus(string $id = ''): void
    {
        $item = $this->model->find((int) $id);
        if ($item) {
            $this->model->delete((int) $id);
            Security::logActivity((int) $_SESSION['user_id'], "Menghapus mata pelajaran: {$item['nama_mapel']}", 'mapel');
            $_SESSION['flash_success'] = 'Data berhasil dihapus.';
        }
        $this->redirect('/adminmapel');
    }
}
