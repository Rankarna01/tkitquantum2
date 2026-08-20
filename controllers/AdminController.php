<?php

class AdminController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->requireAuth();
    }

    public function index(): void
    {
        $db = Database::getInstance();
        $stats = [
            'guru' => (int) $db->query("SELECT COUNT(*) FROM guru")->fetchColumn(),
            'tendik' => (int) $db->query("SELECT COUNT(*) FROM tenaga_kependidikan")->fetchColumn(),
            'berita' => (int) $db->query("SELECT COUNT(*) FROM berita")->fetchColumn(),
            'pengumuman' => (int) $db->query("SELECT COUNT(*) FROM pengumuman")->fetchColumn(),
            'agenda' => (int) $db->query("SELECT COUNT(*) FROM agenda")->fetchColumn(),
            'galeri' => (int) $db->query("SELECT COUNT(*) FROM galeri_foto")->fetchColumn(),
            'pendaftar' => (int) $db->query("SELECT COUNT(*) FROM ppdb_pendaftar")->fetchColumn(),
        ];

        $this->view('admin/dashboard', ['stats' => $stats], layout: 'layouts/admin');
    }
}
