<?php

class PermainanController extends Controller
{
    private PengaturanModel $pengaturanModel;
    private PermainanPengaturanModel $permainanPengaturanModel;
    private PermainanGameModel $gameModel;
    private PermainanSkorModel $skorModel;

    public function __construct()
    {
        parent::__construct();
        $this->pengaturanModel = new PengaturanModel();
        $this->permainanPengaturanModel = new PermainanPengaturanModel();
        $this->gameModel = new PermainanGameModel();
        $this->skorModel = new PermainanSkorModel();
    }

    /** Hub: daftar semua game yang aktif */
    public function index(): void
    {
        try {
            $pengaturanMenu = $this->permainanPengaturanModel->get();
        } catch (Throwable $e) {
            $pengaturanMenu = ['tampil_menu' => 'ya'];
        }

        if (($pengaturanMenu['tampil_menu'] ?? 'ya') !== 'ya') {
            $this->abort(404, 'Halaman tidak ditemukan');
        }

        try {
            $daftarGame = $this->gameModel->getAktif();
        } catch (Throwable $e) {
            $daftarGame = PermainanGameModel::defaultGames();
        }

        try {
            $pengaturan = $this->pengaturanModel->get();
        } catch (Throwable $e) {
            $pengaturan = [];
        }

        $this->view('permainan/index', [
            'pengaturan' => $pengaturan,
            'daftarGame' => $daftarGame,
        ]);
    }

    /** Halaman satu game spesifik + leaderboard-nya */
    public function main(string $slug = ''): void
    {
        try {
            $pengaturanMenu = $this->permainanPengaturanModel->get();
        } catch (Throwable $e) {
            $pengaturanMenu = ['tampil_menu' => 'ya'];
        }

        if (($pengaturanMenu['tampil_menu'] ?? 'ya') !== 'ya') {
            $this->abort(404, 'Halaman tidak ditemukan');
        }

        try {
            $game = $this->gameModel->getBySlug($slug);
        } catch (Throwable $e) {
            $game = null;
        }

        if (!$game) {
            foreach (PermainanGameModel::defaultGames() as $dg) {
                if ($dg['slug'] === $slug) {
                    $game = $dg;
                    break;
                }
            }
        }

        if (!$game || ($game['status'] ?? 'aktif') !== 'aktif') {
            $this->abort(404, 'Game tidak ditemukan atau sedang tidak aktif');
        }

        $viewMap = [
            'cocok-kartu' => 'permainan/cocok-kartu',
            'tangkap-bintang' => 'permainan/tangkap-bintang',
            'puzzle-angka' => 'permainan/puzzle-angka',
            'tebak-angka' => 'permainan/tebak-angka',
            'ketuk-warna' => 'permainan/ketuk-warna',
            'hitung-cepat' => 'permainan/hitung-cepat',
            'tebak-kata' => 'permainan/tebak-kata',
            'uji-reaksi' => 'permainan/uji-reaksi',
            'balap-ketik' => 'permainan/balap-ketik',
            'tebak-silang' => 'permainan/tebak-silang',
            'tebak-emoji' => 'permainan/tebak-emoji',
            'klik-bentuk' => 'permainan/klik-bentuk',
            'labirin-ceria' => 'permainan/labirin-ceria',
            'piano-ceria' => 'permainan/piano-ceria',
            'lompat-kodok' => 'permainan/lompat-kodok',
        ];
        if (!isset($viewMap[$slug])) {
            $this->abort(404, 'Game tidak ditemukan');
        }

        try {
            $musikGame = $this->permainanPengaturanModel->get()['musik_game'] ?? null;
        } catch (Throwable $e) {
            $musikGame = null;
        }

        try {
            $leaderboard = $this->skorModel->getTop($slug, 10);
        } catch (Throwable $e) {
            $leaderboard = [];
        }

        try {
            $pengaturan = $this->pengaturanModel->get();
        } catch (Throwable $e) {
            $pengaturan = [];
        }

        $this->view($viewMap[$slug], [
            'pengaturan' => $pengaturan,
            'game' => $game,
            'leaderboard' => $leaderboard,
            'musikGame' => $musikGame,
        ]);
    }

    /** Endpoint AJAX untuk simpan skor ke leaderboard */
    public function simpanSkor(): void
    {
        $slug = Security::clean($_POST['game_slug'] ?? '');
        $nama = trim(Security::clean($_POST['nama_pemain'] ?? 'Anonim'));
        $skor = (int) ($_POST['skor'] ?? 0);
        $detail = Security::clean($_POST['detail'] ?? '');

        try {
            $game = $this->gameModel->getBySlug($slug);
        } catch (Throwable $e) {
            $game = null;
        }

        if (!$game) {
            $this->json(['success' => false, 'message' => 'Game tidak valid'], 400);
        }
        if ($nama === '') $nama = 'Anonim';
        $nama = mb_substr($nama, 0, 50);

        try {
            $this->skorModel->insert([
                'game_slug' => $slug,
                'nama_pemain' => $nama,
                'skor' => max(0, $skor),
                'detail' => $detail,
            ]);
            $this->json(['success' => true]);
        } catch (Throwable $e) {
            try {
                $this->skorModel->ensureTable();
                $this->skorModel->insert([
                    'game_slug' => $slug,
                    'nama_pemain' => $nama,
                    'skor' => max(0, $skor),
                    'detail' => $detail,
                ]);
                $this->json(['success' => true]);
            } catch (Throwable $e2) {
                $this->json(['success' => false, 'message' => 'Gagal menyimpan skor.'], 500);
            }
        }
    }
}
