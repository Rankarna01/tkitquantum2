<?php

class AdminpermainanController extends Controller
{
    private PermainanPengaturanModel $pengaturanModel;
    private PermainanGameModel $gameModel;
    private PermainanSkorModel $skorModel;

    public function __construct()
    {
        parent::__construct();
        $this->requireAuth();
        $this->requireRole(['superadmin', 'admin', 'operator']);
        $this->pengaturanModel = new PermainanPengaturanModel();
        $this->gameModel = new PermainanGameModel();
        $this->skorModel = new PermainanSkorModel();
    }

    public function index(): void
    {
        try {
            $pengaturan = $this->pengaturanModel->get();
            $daftarGame = $this->gameModel->all('urutan ASC, id ASC');
        } catch (PDOException $e) {
            $pengaturan = ['tampil_menu' => 'ya'];
            $daftarGame = [];
            $_SESSION['flash_error'] = 'Tabel permainan belum ada di database. Jalankan migrasi permainan_pengaturan / permainan_game / permainan_skor terlebih dahulu.';
        }

        $this->view('admin/permainan/index', [
            'pengaturan' => $pengaturan,
            'daftarGame' => $daftarGame,
        ], layout: 'layouts/admin');
    }

    /** Tampilkan/sembunyikan menu Permainan secara keseluruhan */
    public function toggleMenu(): void
    {
        $this->validateCsrf();
        $existing = $this->pengaturanModel->get();
        $baru = ($existing['tampil_menu'] ?? 'ya') === 'ya' ? 'tidak' : 'ya';

        if (!empty($existing['id'])) {
            $this->pengaturanModel->update($existing['id'], ['tampil_menu' => $baru]);
        } else {
            $this->pengaturanModel->insert(['tampil_menu' => $baru]);
        }

        Security::logActivity((int) $_SESSION['user_id'], "Mengubah tampilan menu Permainan menjadi {$baru}", 'permainan');
        $_SESSION['flash_success'] = 'Menu Permainan sekarang ' . ($baru === 'ya' ? 'ditampilkan' : 'disembunyikan') . '.';
        $this->redirect('/adminpermainan');
    }

    /** Unggah/ganti musik latar yang berlaku untuk semua game */
    public function simpanMusik(): void
    {
        $this->validateCsrf();
        $existing = $this->pengaturanModel->get();

        if (empty($_FILES['musik_game']['name'])) {
            $_SESSION['flash_error'] = 'Pilih file musik terlebih dahulu.';
            $this->redirect('/adminpermainan');
        }

        try {
            $filename = Security::handleUpload(
                $_FILES['musik_game'], dirname(__DIR__) . '/public/uploads/musik-game',
                ['audio/mpeg', 'audio/mp3', 'audio/ogg', 'audio/wav'], $this->appConfig['upload_max_size']
            );
            if ($filename) {
                $data = ['musik_game' => 'uploads/musik-game/' . $filename];
                if (!empty($existing['id'])) {
                    $this->pengaturanModel->update($existing['id'], $data);
                } else {
                    $data['tampil_menu'] = $existing['tampil_menu'] ?? 'ya';
                    $this->pengaturanModel->insert($data);
                }
                Security::logActivity((int) $_SESSION['user_id'], 'Mengganti musik latar game', 'permainan');
                $_SESSION['flash_success'] = 'Musik latar game berhasil disimpan dan berlaku untuk semua game.';
            }
        } catch (RuntimeException $e) {
            $_SESSION['flash_error'] = $e->getMessage();
        } catch (PDOException $e) {
            $_SESSION['flash_error'] = 'Kolom musik_game belum ada di database. Jalankan migrasi ALTER TABLE permainan_pengaturan ADD COLUMN musik_game terlebih dahulu.';
        }

        $this->redirect('/adminpermainan');
    }

    public function hapusMusik(): void
    {
        $existing = $this->pengaturanModel->get();
        if (!empty($existing['id'])) {
            if (!empty($existing['musik_game'])) {
                $path = dirname(__DIR__) . '/public/' . $existing['musik_game'];
                if (is_file($path)) @unlink($path);
            }
            $this->pengaturanModel->update($existing['id'], ['musik_game' => null]);
            $_SESSION['flash_success'] = 'Musik latar game berhasil dihapus.';
        }
        $this->redirect('/adminpermainan');
    }

    /** Aktifkan/nonaktifkan satu game tertentu */
    public function toggleGame(string $id = ''): void
    {
        $game = $this->gameModel->find((int) $id);
        if ($game) {
            $baru = $game['status'] === 'aktif' ? 'nonaktif' : 'aktif';
            $this->gameModel->update((int) $id, ['status' => $baru]);
            Security::logActivity((int) $_SESSION['user_id'], "Mengubah status game {$game['nama']} menjadi {$baru}", 'permainan');
            $_SESSION['flash_success'] = "Game \"{$game['nama']}\" sekarang " . ($baru === 'aktif' ? 'ditampilkan' : 'disembunyikan') . '.';
        }
        $this->redirect('/adminpermainan');
    }

    /** Kosongkan papan skor untuk satu game (opsional, kalau ingin reset) */
    public function resetSkor(string $slug = ''): void
    {
        $game = $this->gameModel->getBySlug($slug);
        if ($game) {
            $this->skorModel->hapusByGame($slug);
            Security::logActivity((int) $_SESSION['user_id'], "Mereset papan skor game {$game['nama']}", 'permainan');
            $_SESSION['flash_success'] = "Papan skor \"{$game['nama']}\" berhasil dikosongkan.";
        }
        $this->redirect('/adminpermainan');
    }
}
