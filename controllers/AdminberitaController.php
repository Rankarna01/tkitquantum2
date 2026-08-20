<?php

class AdminberitaController extends Controller
{
    private BeritaModel $beritaModel;
    private KategoriBeritaModel $kategoriModel;

    public function __construct()
    {
        parent::__construct();
        $this->requireAuth();
        $this->requireRole(['superadmin', 'admin', 'operator']);
        $this->beritaModel = new BeritaModel();
        $this->kategoriModel = new KategoriBeritaModel();
    }

    public function index(): void
    {
        $this->view('admin/berita/index', [
            'daftar' => $this->beritaModel->all('created_at DESC'),
        ], layout: 'layouts/admin');
    }

    public function tambah(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->simpan();
            return;
        }
        $this->view('admin/berita/form', [
            'berita' => null,
            'kategoriList' => $this->kategoriModel->all('nama_kategori'),
            'fotoTambahan' => [],
        ], layout: 'layouts/admin');
    }

    public function edit(string $id = ''): void
    {
        $berita = $this->beritaModel->find((int) $id);
        if (!$berita) $this->abort(404, 'Berita tidak ditemukan');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->simpan((int) $id, $berita);
            return;
        }

        try {
            $fotoTambahan = (new BeritaGaleriModel())->getByBerita((int) $id);
        } catch (PDOException $e) {
            $fotoTambahan = [];
        }

        $this->view('admin/berita/form', [
            'berita' => $berita,
            'kategoriList' => $this->kategoriModel->all('nama_kategori'),
            'fotoTambahan' => $fotoTambahan,
        ], layout: 'layouts/admin');
    }

    private function simpan(?int $id = null, ?array $existing = null): void
    {
        $this->validateCsrf();

        $judul = Security::clean($_POST['judul'] ?? '');
        $kategoriId = (int) ($_POST['kategori_id'] ?? 0) ?: null;
        $ringkasan = Security::clean($_POST['ringkasan'] ?? '');
        $isi = $_POST['isi'] ?? ''; // konten CKEditor - disimpan apa adanya, wajib di-purify di produksi
        $status = in_array($_POST['status'] ?? '', ['draft', 'publish'], true) ? $_POST['status'] : 'draft';

        if ($judul === '' || $isi === '') {
            $_SESSION['flash_error'] = 'Judul dan isi berita wajib diisi.';
            $this->redirect($id ? "/adminberita/edit/{$id}" : '/adminberita/tambah');
        }

        $db = Database::getInstance();
        $slug = Str::uniqueSlug($db, 'berita', $judul, $id);

        $thumbnail = $existing['thumbnail'] ?? null;
        if (!empty($_FILES['thumbnail']['name'])) {
            try {
                $filename = Security::handleUpload(
                    $_FILES['thumbnail'],
                    dirname(__DIR__) . '/public/uploads/berita',
                    ['image/jpeg', 'image/png', 'image/webp'],
                    $this->appConfig['upload_max_size']
                );
                if ($filename) $thumbnail = 'uploads/berita/' . $filename;
            } catch (RuntimeException $e) {
                $_SESSION['flash_error'] = $e->getMessage();
                $this->redirect($id ? "/adminberita/edit/{$id}" : '/adminberita/tambah');
            }
        }

        $data = [
            'kategori_id' => $kategoriId,
            'user_id' => $_SESSION['user_id'],
            'judul' => $judul,
            'slug' => $slug,
            'thumbnail' => $thumbnail,
            'ringkasan' => $ringkasan,
            'isi' => $isi,
            'status' => $status,
            'tanggal_publish' => $status === 'publish' ? date('Y-m-d H:i:s') : null,
        ];

        if ($id) {
            $this->beritaModel->update($id, $data);
            Security::logActivity((int) $_SESSION['user_id'], "Mengubah berita: {$judul}", 'berita');
            $_SESSION['flash_success'] = 'Berita berhasil diperbarui.';
        } else {
            $newId = $this->beritaModel->insert($data);
            $id = $newId;
            Security::logActivity((int) $_SESSION['user_id'], "Menambah berita: {$judul}", 'berita');
            $_SESSION['flash_success'] = 'Berita berhasil ditambahkan.';
        }

        // Foto tambahan (jika 2 atau lebih total foto, otomatis tampil sebagai slide hero di halaman detail)
        if (!empty($_FILES['foto_tambahan']['name'][0])) {
            try {
                $galeriModel = new BeritaGaleriModel();
                $files = $_FILES['foto_tambahan'];
                foreach ($files['name'] as $i => $name) {
                    if ($name === '') continue;
                    $file = [
                        'name' => $files['name'][$i],
                        'type' => $files['type'][$i],
                        'tmp_name' => $files['tmp_name'][$i],
                        'error' => $files['error'][$i],
                        'size' => $files['size'][$i],
                    ];
                    $filename = Security::handleUpload(
                        $file, dirname(__DIR__) . '/public/uploads/berita',
                        ['image/jpeg', 'image/png', 'image/webp'], $this->appConfig['upload_max_size']
                    );
                    if ($filename) {
                        $galeriModel->insert(['berita_id' => $id, 'file' => 'uploads/berita/' . $filename, 'urutan' => $i]);
                    }
                }
            } catch (RuntimeException $e) {
                $_SESSION['flash_error'] = 'Sebagian foto tambahan gagal diunggah: ' . $e->getMessage();
            } catch (PDOException $e) {
                $_SESSION['flash_error'] = 'Tabel berita_galeri belum ada di database. Jalankan migrasi CREATE TABLE berita_galeri terlebih dahulu.';
            }
        }

        $this->redirect('/adminberita');
    }

    public function hapusFotoTambahan(string $id = ''): void
    {
        $galeriModel = new BeritaGaleriModel();
        $foto = $galeriModel->find((int) $id);
        if ($foto) {
            $beritaId = $foto['berita_id'];
            $galeriModel->delete((int) $id);
            $path = dirname(__DIR__) . '/public/' . $foto['file'];
            if (is_file($path)) @unlink($path);
            $_SESSION['flash_success'] = 'Foto tambahan berhasil dihapus.';
            $this->redirect('/adminberita/edit/' . $beritaId);
        }
        $this->redirect('/adminberita');
    }

    public function hapus(string $id = ''): void
    {
        $berita = $this->beritaModel->find((int) $id);
        if ($berita) {
            $this->beritaModel->delete((int) $id);
            Security::logActivity((int) $_SESSION['user_id'], "Menghapus berita: {$berita['judul']}", 'berita');
            $_SESSION['flash_success'] = 'Berita berhasil dihapus.';
        }
        $this->redirect('/adminberita');
    }
}
