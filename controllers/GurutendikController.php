<?php

class GurutendikController extends Controller
{
    public function index(): void
    {
        $this->view('guru-tendik/index', [
            'pengaturan' => (new PengaturanModel())->get(),
            'guru' => (new GuruModel())->getActive(),
            'tendik' => (new TenagaKependidikanModel())->getActive(),
        ]);
    }
}
