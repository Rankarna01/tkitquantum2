<?php

class GuruController extends Controller
{
    private GuruModel $model;
    private PengaturanModel $pengaturanModel;

    public function __construct()
    {
        parent::__construct();
        $this->model = new GuruModel();
        $this->pengaturanModel = new PengaturanModel();
    }

    public function index(): void
    {
        $this->view('guru/index', [
            'pengaturan' => $this->pengaturanModel->get(),
            'daftar' => $this->model->getActive(),
        ]);
    }
}
