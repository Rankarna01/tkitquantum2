<?php

class AdminppdbController extends Controller
{
    private PpdbModel $ppdbModel;
    private PpdbPendaftarModel $pendaftarModel;
    private PpdbFaqModel $faqModel;

    public function __construct()
    {
        parent::__construct();
        $this->requireAuth();
        $this->requireRole(['superadmin', 'admin']);
        $this->ppdbModel = new PpdbModel();
        $this->pendaftarModel = new PpdbPendaftarModel();
        $this->faqModel = new PpdbFaqModel();
    }

    public function index(): void
    {
        $ppdb = $this->ppdbModel->getLatest();
        $this->view('admin/ppdb/pengaturan', [
            'ppdb' => $ppdb,
            'terdaftar' => $ppdb ? $this->ppdbModel->countPendaftar((int) $ppdb['id']) : 0,
        ], layout: 'layouts/admin');
    }

    public function simpanPengaturan(): void
    {
        $this->validateCsrf();

        $data = [
            'tahun_ajaran' => Security::clean($_POST['tahun_ajaran'] ?? ''),
            'status' => ($_POST['status'] ?? '') === 'aktif' ? 'aktif' : 'nonaktif',
            'kuota' => max(0, (int) ($_POST['kuota'] ?? 0)),
            'tanggal_mulai' => $_POST['tanggal_mulai'] ?: null,
            'tanggal_selesai' => $_POST['tanggal_selesai'] ?: null,
            'biaya_pendaftaran' => (float) ($_POST['biaya_pendaftaran'] ?? 0),
            'promo_nama' => Security::clean($_POST['promo_nama'] ?? ''),
            'promo_potongan' => (float) ($_POST['promo_potongan'] ?? 0),
            'promo_mulai' => $_POST['promo_mulai'] ?: null,
            'promo_selesai' => $_POST['promo_selesai'] ?: null,
            'informasi' => Security::clean($_POST['informasi'] ?? ''),
            'persyaratan' => Security::clean($_POST['persyaratan'] ?? ''),
            'alur_pendaftaran' => Security::clean($_POST['alur_pendaftaran'] ?? ''),
            'tampil_beranda' => ($_POST['tampil_beranda'] ?? '') === 'ya' ? 'ya' : 'tidak',
            'cta_judul' => Security::clean($_POST['cta_judul'] ?? ''),
            'cta_subjudul' => Security::clean($_POST['cta_subjudul'] ?? ''),
        ];

        $existing = $this->ppdbModel->getLatest();

        if (!empty($_FILES['banner']['name'])) {
            try {
                $filename = Security::handleUpload(
                    $_FILES['banner'], dirname(__DIR__) . '/public/uploads/ppdb',
                    ['image/jpeg', 'image/png', 'image/webp'], $this->appConfig['upload_max_size']
                );
                $data['banner'] = 'uploads/ppdb/' . $filename;
            } catch (RuntimeException $e) {
                $_SESSION['flash_error'] = $e->getMessage();
            }
        }

        try {
            if ($existing) {
                $this->ppdbModel->update($existing['id'], $data);
            } else {
                $this->ppdbModel->insert($data);
            }
        } catch (PDOException $e) {
            // Kolom promo/banner belum ada — kemungkinan skrip migrasi database belum dijalankan.
            $_SESSION['flash_error'] = 'Gagal menyimpan: struktur tabel PPDB belum lengkap. Silakan jalankan skrip migrasi database (ALTER TABLE ppdb_pengaturan ...) di phpMyAdmin, lalu coba lagi.';
            $this->redirect('/adminppdb');
        }

        Security::logActivity((int) $_SESSION['user_id'], 'Memperbarui pengaturan PPDB', 'ppdb');
        $_SESSION['flash_success'] = 'Pengaturan PPDB berhasil disimpan.';
        $this->redirect('/adminppdb');
    }

    public function toggleStatus(): void
    {
        $this->validateCsrf();
        $existing = $this->ppdbModel->getLatest();
        if ($existing) {
            $newStatus = $existing['status'] === 'aktif' ? 'nonaktif' : 'aktif';
            $this->ppdbModel->update($existing['id'], ['status' => $newStatus]);
            Security::logActivity((int) $_SESSION['user_id'], "Mengubah status PPDB menjadi {$newStatus}", 'ppdb');
            $_SESSION['flash_success'] = 'Status PPDB diubah menjadi ' . strtoupper($newStatus) . '.';
        }
        $this->redirect('/adminppdb');
    }

    public function pendaftar(): void
    {
        $ppdb = $this->ppdbModel->getLatest();
        $daftar = $ppdb ? $this->pendaftarModel->byPpdb((int) $ppdb['id']) : [];
        $this->view('admin/ppdb/pendaftar', ['daftar' => $daftar], layout: 'layouts/admin');
    }

    public function detailPendaftar(string $id = ''): void
    {
        $item = $this->pendaftarModel->find((int) $id);
        if (!$item) $this->abort(404, 'Data pendaftar tidak ditemukan');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrf();
            $status = $_POST['status'] ?? '';
            if (in_array($status, ['menunggu', 'diverifikasi', 'diterima', 'ditolak'], true)) {
                $this->pendaftarModel->update((int) $id, [
                    'status' => $status,
                    'catatan_admin' => Security::clean($_POST['catatan_admin'] ?? ''),
                ]);
                Security::logActivity((int) $_SESSION['user_id'], "Mengubah status pendaftar PPDB #{$item['no_pendaftaran']} menjadi {$status}", 'ppdb');
                $_SESSION['flash_success'] = 'Status pendaftar berhasil diperbarui.';
            }
            $this->redirect('/adminppdb/detailPendaftar/' . $id);
        }

        $this->view('admin/ppdb/detail-pendaftar', ['item' => $item], layout: 'layouts/admin');
    }

    public function faq(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrf();
            $this->faqModel->insert([
                'pertanyaan' => Security::clean($_POST['pertanyaan'] ?? ''),
                'jawaban' => Security::clean($_POST['jawaban'] ?? ''),
                'urutan' => (int) ($_POST['urutan'] ?? 0),
            ]);
            $_SESSION['flash_success'] = 'FAQ berhasil ditambahkan.';
            $this->redirect('/adminppdb/faq');
        }
        $this->view('admin/ppdb/faq', ['daftar' => $this->faqModel->all('urutan ASC')], layout: 'layouts/admin');
    }

    public function hapusFaq(string $id = ''): void
    {
        $this->faqModel->delete((int) $id);
        $_SESSION['flash_success'] = 'FAQ berhasil dihapus.';
        $this->redirect('/adminppdb/faq');
    }
}
