<?php

class HomeController extends Controller
{
    private BeritaModel $beritaModel;
    private PengaturanModel $pengaturanModel;
    private PengumumanModel $pengumumanModel;
    private AgendaModel $agendaModel;
    private GaleriFotoModel $galeriFotoModel;
    private GaleriVideoModel $galeriVideoModel;
    private PrestasiModel $prestasiModel;
    private GuruModel $guruModel;
    private FasilitasModel $fasilitasModel;
    private TestimoniAlumniModel $testimoniModel;
    private PpdbModel $ppdbModel;
    private HeroSlideModel $heroSlideModel;
    private KontenIslamiModel $islamiModel;

    public function __construct()
    {
        parent::__construct();
        $this->beritaModel = new BeritaModel();
        $this->pengaturanModel = new PengaturanModel();
        $this->pengumumanModel = new PengumumanModel();
        $this->agendaModel = new AgendaModel();
        $this->galeriFotoModel = new GaleriFotoModel();
        $this->galeriVideoModel = new GaleriVideoModel();
        $this->prestasiModel = new PrestasiModel();
        $this->guruModel = new GuruModel();
        $this->fasilitasModel = new FasilitasModel();
        $this->testimoniModel = new TestimoniAlumniModel();
        $this->ppdbModel = new PpdbModel();
        $this->heroSlideModel = new HeroSlideModel();
        $this->islamiModel = new KontenIslamiModel();
    }

    public function index(): void
    {
        // Beberapa tabel (hero_slide, konten_islami) mungkin baru ditambahkan lewat migrasi.
        // Jika migrasi belum dijalankan di server, jangan sampai seluruh beranda ikut error —
        // cukup tampilkan beranda tanpa fitur tersebut.
        try {
            $heroSlides = $this->heroSlideModel->all('urutan ASC, id ASC');
        } catch (PDOException $e) {
            $heroSlides = [];
        }
        try {
            $islamiList = $this->islamiModel->getActive();
        } catch (PDOException $e) {
            $islamiList = [];
        }

        $pengumumanRaw = $this->pengumumanModel->getPublished(6);
        $agendaRaw = $this->agendaModel->getUpcoming(6);
        $pengumumanGabungan = [];
        foreach ($pengumumanRaw as $p) {
            $pengumumanGabungan[] = [
                'judul' => $p['judul'],
                'isi_singkat' => $p['isi_singkat'],
                'tanggal' => $p['tanggal_publish'] ?? $p['created_at'],
                'tipe' => 'pengumuman',
            ];
        }
        foreach ($agendaRaw as $a) {
            $pengumumanGabungan[] = [
                'judul' => $a['judul'],
                'isi_singkat' => trim(($a['deskripsi'] ?? '') . (!empty($a['lokasi']) ? ' 📍 ' . $a['lokasi'] : '')),
                'tanggal' => $a['tanggal_mulai'],
                'tipe' => 'agenda',
            ];
        }
        usort($pengumumanGabungan, fn($a, $b) => strtotime($b['tanggal']) <=> strtotime($a['tanggal']));
        $pengumumanGabungan = array_slice($pengumumanGabungan, 0, 6);

        $this->view('home/index', [
            'pengaturan' => $this->pengaturanModel->get(),
            'beritaTerbaru' => $this->beritaModel->getPublished(3),
            'pengumumanTerbaru' => $pengumumanGabungan,
            'galeriFoto' => array_slice($this->galeriFotoModel->allWithKategori(), 0, 8),
            'galeriVideo' => array_slice($this->galeriVideoModel->all('created_at DESC'), 0, 4),
            'prestasiTerbaru' => $this->prestasiModel->getAll(4),
            'guruMengajar' => $this->guruModel->getActive(),
            'fasilitasList' => $this->fasilitasModel->all('nama ASC'),
            'testimoniList' => $this->testimoniModel->getPublished(3),
            'ppdb' => $this->ppdbModel->getLatest(),
            'heroSlides' => $heroSlides,
            'islamiList' => $islamiList,
        ]);
    }

    public function berita(string $slug = ''): void
    {
        if ($slug === '') {
            $this->abort(404, 'Berita tidak ditemukan');
        }
        $berita = $this->beritaModel->findBySlug($slug);
        if (!$berita) {
            $this->abort(404, 'Berita tidak ditemukan');
        }
        $this->beritaModel->incrementViews((int) $berita['id']);

        try {
            $galeriBerita = (new BeritaGaleriModel())->getByBerita((int) $berita['id']);
        } catch (PDOException $e) {
            $galeriBerita = [];
        }

        $this->view('home/berita-detail', [
            'pengaturan' => $this->pengaturanModel->get(),
            'berita' => $berita,
            'galeriBerita' => $galeriBerita,
        ]);
    }
}
