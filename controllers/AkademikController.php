<?php

class AkademikController extends Controller
{
    public function index(): void
    {
        $this->view('akademik/index', [
            'pengaturan' => (new PengaturanModel())->get(),
            'kalender' => (new KalenderAkademikModel())->getAll(),
            'prestasi' => (new PrestasiModel())->getAll(),
            'fasilitas' => (new FasilitasModel())->all('nama ASC'),
        ]);
    }
}
