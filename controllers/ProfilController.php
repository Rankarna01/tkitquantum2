<?php

class ProfilController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(): void
    {
        $struktur = (new StrukturOrganisasiModel())->allOrdered();
        $lingkunganKategori = (new GaleriKategoriModel())->findBy('nama_kategori', 'Lingkungan Sekolah');
        $fotoLingkungan = $lingkunganKategori
            ? (new GaleriFotoModel())->where('kategori_id', $lingkunganKategori['id'], 'created_at DESC')
            : [];
        $this->view('profil/index', [
            'pengaturan' => (new PengaturanModel())->get(),
            'profil' => (new ProfilSekolahModel())->get(),
            'struktur' => $struktur,
            'fotoLingkungan' => array_slice($fotoLingkungan, 0, 8),
        ]);
    }
}
