<?php

class KontakController extends Controller
{
    private PengaturanModel $pengaturanModel;
    private PesanKontakModel $pesanModel;

    public function __construct()
    {
        parent::__construct();
        $this->pengaturanModel = new PengaturanModel();
        $this->pesanModel = new PesanKontakModel();
    }

    public function index(): void
    {
        $this->view('kontak/index', [
            'pengaturan' => $this->pengaturanModel->get(),
        ]);
    }

    public function kirim(): void
    {
        $this->validateCsrf();

        $nama = Security::clean($_POST['nama'] ?? '');
        $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
        $noHp = Security::clean($_POST['no_hp'] ?? '');
        $pesan = Security::clean($_POST['pesan'] ?? '');

        if ($nama === '' || !$email || $pesan === '') {
            $_SESSION['flash_error'] = 'Nama, email, dan pesan wajib diisi dengan benar.';
            $this->redirect('/kontak');
        }

        try {
            $this->pesanModel->insert([
                'nama' => $nama,
                'email' => $email,
                'no_hp' => $noHp,
                'pesan' => $pesan,
            ]);
        } catch (PDOException $e) {
            $_SESSION['flash_error'] = 'Maaf, fitur kontak sedang belum siap. Silakan hubungi kami langsung lewat WhatsApp/Instagram untuk sementara.';
            $this->redirect('/kontak');
        }

        $_SESSION['flash_success'] = 'Terima kasih! Pesan Anda sudah kami terima, admin akan segera merespons.';
        $this->redirect('/kontak');
    }
}
