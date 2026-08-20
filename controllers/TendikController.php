<?php

class TendikController extends Controller
{
    private TenagaKependidikanModel $model;
    private PengaturanModel $pengaturanModel;

    public function __construct()
    {
        parent::__construct();
        $this->model = new TenagaKependidikanModel();
        $this->pengaturanModel = new PengaturanModel();
    }

    public function index(): void
    {
        $this->view('tendik/index', [
            'pengaturan' => $this->pengaturanModel->get(),
            'daftar' => $this->model->getActive(),
        ]);
    }
}
