<?php

class AdminkontakController extends Controller
{
    private PesanKontakModel $model;

    public function __construct()
    {
        parent::__construct();
        $this->requireAuth();
        $this->requireRole(['superadmin', 'admin', 'operator']);
        $this->model = new PesanKontakModel();
    }

    public function index(): void
    {
        try {
            $pesan = $this->model->all('created_at DESC');
        } catch (PDOException $e) {
            $pesan = [];
            $_SESSION['flash_error'] = 'Tabel pesan_kontak belum ada di database. Jalankan migrasi SQL untuk tabel pesan_kontak terlebih dahulu.';
        }
        $this->view('admin/kontak/index', [
            'pesan' => $pesan,
        ], layout: 'layouts/admin');
    }

    public function tandaiDibaca(string $id = ''): void
    {
        $item = $this->model->find((int) $id);
        if ($item) {
            $this->model->update((int) $id, ['status' => 'dibaca']);
        }
        $this->redirect('/adminkontak');
    }

    public function hapus(string $id = ''): void
    {
        $item = $this->model->find((int) $id);
        if ($item) {
            $this->model->delete((int) $id);
            Security::logActivity((int) $_SESSION['user_id'], 'Menghapus pesan kontak', 'kontak');
            $_SESSION['flash_success'] = 'Pesan berhasil dihapus.';
        }
        $this->redirect('/adminkontak');
    }
}
