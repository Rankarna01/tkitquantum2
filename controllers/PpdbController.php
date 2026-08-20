<?php

class PpdbController extends Controller
{
    private PpdbModel $ppdbModel;
    private PpdbPendaftarModel $pendaftarModel;
    private PengaturanModel $pengaturanModel;

    public function __construct()
    {
        parent::__construct();
        $this->ppdbModel = new PpdbModel();
        $this->pendaftarModel = new PpdbPendaftarModel();
        $this->pengaturanModel = new PengaturanModel();
    }

    public function index(): void
    {
        $ppdb = $this->ppdbModel->getAktif();
        $kuotaPenuh = false;
        $terdaftar = 0;

        if ($ppdb) {
            $terdaftar = $this->ppdbModel->countPendaftar((int) $ppdb['id']);
            $kuotaPenuh = $terdaftar >= (int) $ppdb['kuota'];
        }

        $this->view('ppdb/index', [
            'pengaturan' => $this->pengaturanModel->get(),
            'ppdb' => $ppdb,
            'kuotaPenuh' => $kuotaPenuh,
            'terdaftar' => $terdaftar,
            'faq' => (new PpdbFaqModel())->all('urutan ASC'),
        ]);
    }

    public function daftar(): void
    {
        $ppdb = $this->ppdbModel->getAktif();

        if (!$ppdb) {
            $this->abort(404, 'PPDB belum dibuka.');
        }

        $terdaftar = $this->ppdbModel->countPendaftar((int) $ppdb['id']);
        $kuotaPenuh = $terdaftar >= (int) $ppdb['kuota'];

        if ($kuotaPenuh) {
            $this->redirect('/ppdb#ppdb');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->prosesDaftar($ppdb);
            return;
        }

        $this->view('ppdb/daftar', [
            'pengaturan' => $this->pengaturanModel->get(),
            'ppdb' => $ppdb,
        ]);
    }

    private function prosesDaftar(array $ppdb): void
    {
        $this->validateCsrf();

        $required = ['nik', 'nama', 'jenis_kelamin'];
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                $_SESSION['flash_error'] = 'Mohon lengkapi data wajib (NIK, Nama, Jenis Kelamin).';
                $this->redirect('/ppdb/daftar');
            }
        }

        $uploadDir = dirname(__DIR__) . '/public/uploads/ppdb';
        $allowedDoc = ['image/jpeg', 'image/png', 'application/pdf'];
        $maxSize = $this->appConfig['upload_max_size'];

        try {
            $fileKk = Security::handleUpload($_FILES['file_kk'] ?? [], $uploadDir, $allowedDoc, $maxSize);
            $fileAkta = Security::handleUpload($_FILES['file_akta'] ?? [], $uploadDir, $allowedDoc, $maxSize);
            $fileFoto = Security::handleUpload($_FILES['file_foto'] ?? [], $uploadDir, ['image/jpeg', 'image/png'], $maxSize);
            $fileRapor = Security::handleUpload($_FILES['file_rapor'] ?? [], $uploadDir, $allowedDoc, $maxSize);
        } catch (RuntimeException $e) {
            $_SESSION['flash_error'] = $e->getMessage();
            $this->redirect('/ppdb/daftar');
        }

        $model = new PpdbPendaftarModel();
        $noPendaftaran = $model->generateNoPendaftaran();

        $data = [
            'ppdb_id' => $ppdb['id'],
            'no_pendaftaran' => $noPendaftaran,
            'nik' => Security::clean($_POST['nik']),
            'nisn' => Security::clean($_POST['nisn'] ?? ''),
            'nama' => Security::clean($_POST['nama']),
            'tempat_lahir' => Security::clean($_POST['tempat_lahir'] ?? ''),
            'tanggal_lahir' => $_POST['tanggal_lahir'] ?? null,
            'jenis_kelamin' => $_POST['jenis_kelamin'] === 'P' ? 'P' : 'L',
            'agama' => Security::clean($_POST['agama'] ?? ''),
            'alamat' => Security::clean($_POST['alamat'] ?? ''),
            'no_hp' => Security::clean($_POST['no_hp'] ?? ''),
            'email' => filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL) ?: null,
            'asal_sekolah' => Security::clean($_POST['asal_sekolah'] ?? ''),
            'nama_ayah' => Security::clean($_POST['nama_ayah'] ?? ''),
            'nama_ibu' => Security::clean($_POST['nama_ibu'] ?? ''),
            'pekerjaan_ortu' => Security::clean($_POST['pekerjaan_ortu'] ?? ''),
            'penghasilan_ortu' => Security::clean($_POST['penghasilan_ortu'] ?? ''),
            'file_kk' => $fileKk ? 'uploads/ppdb/' . $fileKk : null,
            'file_akta' => $fileAkta ? 'uploads/ppdb/' . $fileAkta : null,
            'file_foto' => $fileFoto ? 'uploads/ppdb/' . $fileFoto : null,
            'file_rapor' => $fileRapor ? 'uploads/ppdb/' . $fileRapor : null,
            'status' => 'menunggu',
        ];

        $model->insert($data);

        $this->view('ppdb/sukses', [
            'pengaturan' => $this->pengaturanModel->get(),
            'noPendaftaran' => $noPendaftaran,
            'nama' => $data['nama'],
        ]);
    }

    public function cekStatus(): void
    {
        $hasil = null;
        $dicari = false;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrf();
            $dicari = true;
            $model = new PpdbPendaftarModel();
            $hasil = $model->findByNoPendaftaran(Security::clean($_POST['no_pendaftaran'] ?? ''));
        }

        $this->view('ppdb/cek-status', [
            'pengaturan' => $this->pengaturanModel->get(),
            'hasil' => $hasil,
            'dicari' => $dicari,
        ]);
    }
}
