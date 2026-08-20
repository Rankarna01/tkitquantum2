<?php

class AdminislamiController extends Controller
{
    private KontenIslamiModel $model;

    public function __construct()
    {
        parent::__construct();
        $this->requireAuth();
        $this->requireRole(['superadmin', 'admin', 'operator']);
        $this->model = new KontenIslamiModel();
    }

    public function index(): void
    {
        try {
            $daftar = $this->model->all('urutan ASC, id ASC');
        } catch (PDOException $e) {
            $daftar = [];
            $_SESSION['flash_error'] = 'Tabel konten_islami belum ada di database. Jalankan migrasi SQL untuk tabel konten_islami terlebih dahulu.';
        }
        $this->view('admin/islami/index', [
            'daftar' => $daftar,
        ], layout: 'layouts/admin');
    }

    public function tambah(): void
    {
        $this->validateCsrf();
        $teks = Security::clean($_POST['teks'] ?? '');
        if ($teks === '') {
            $_SESSION['flash_error'] = 'Teks tidak boleh kosong.';
            $this->redirect('/adminislami');
        }

        $this->model->insert([
            'teks' => $teks,
            'sumber' => Security::clean($_POST['sumber'] ?? ''),
            'status' => 'aktif',
            'urutan' => (int) ($_POST['urutan'] ?? 0),
        ]);
        Security::logActivity((int) $_SESSION['user_id'], 'Menambah konten islami berjalan', 'islami');
        $_SESSION['flash_success'] = 'Teks berhasil ditambahkan.';
        $this->redirect('/adminislami');
    }

    public function toggleStatus(string $id = ''): void
    {
        $item = $this->model->find((int) $id);
        if ($item) {
            $newStatus = $item['status'] === 'aktif' ? 'nonaktif' : 'aktif';
            $this->model->update((int) $id, ['status' => $newStatus]);
        }
        $this->redirect('/adminislami');
    }

    public function hapus(string $id = ''): void
    {
        $item = $this->model->find((int) $id);
        if ($item) {
            $this->model->delete((int) $id);
            Security::logActivity((int) $_SESSION['user_id'], 'Menghapus konten islami berjalan', 'islami');
            $_SESSION['flash_success'] = 'Teks berhasil dihapus.';
        }
        $this->redirect('/adminislami');
    }
}
