<?php

class AdminbackupController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->requireAuth();
        $this->requireRole(['superadmin']); // hanya superadmin yang boleh backup/restore
    }

    public function index(): void
    {
        $backupDir = dirname(__DIR__) . '/storage/backup';
        $files = [];
        if (is_dir($backupDir)) {
            foreach (scandir($backupDir) as $f) {
                if (str_ends_with($f, '.sql')) {
                    $files[] = ['nama' => $f, 'ukuran' => filesize($backupDir . '/' . $f), 'tanggal' => filemtime($backupDir . '/' . $f)];
                }
            }
            usort($files, fn($a, $b) => $b['tanggal'] <=> $a['tanggal']);
        }
        $this->view('admin/backup/index', ['files' => $files], layout: 'layouts/admin');
    }

    /** Backup database murni dengan PDO (tanpa exec/shell_exec demi keamanan hosting shared) */
    public function backup(): void
    {
        $this->validateCsrf();

        $config = require dirname(__DIR__) . '/config/database.php';
        $db = Database::getInstance();

        $tables = $db->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
        $sql = "-- Backup Aragi School — " . date('Y-m-d H:i:s') . "\n";
        $sql .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

        foreach ($tables as $table) {
            $createStmt = $db->query("SHOW CREATE TABLE `{$table}`")->fetch();
            $sql .= "DROP TABLE IF EXISTS `{$table}`;\n" . $createStmt['Create Table'] . ";\n\n";

            $rows = $db->query("SELECT * FROM `{$table}`")->fetchAll(PDO::FETCH_ASSOC);
            foreach ($rows as $row) {
                $cols = '`' . implode('`, `', array_keys($row)) . '`';
                $vals = implode(', ', array_map(function ($v) use ($db) {
                    return $v === null ? 'NULL' : $db->quote((string) $v);
                }, array_values($row)));
                $sql .= "INSERT INTO `{$table}` ({$cols}) VALUES ({$vals});\n";
            }
            $sql .= "\n";
        }
        $sql .= "SET FOREIGN_KEY_CHECKS=1;\n";

        $filename = 'backup_' . date('Ymd_His') . '.sql';
        $backupDir = dirname(__DIR__) . '/storage/backup';
        if (!is_dir($backupDir)) mkdir($backupDir, 0755, true);
        file_put_contents($backupDir . '/' . $filename, $sql);

        Security::logActivity((int) $_SESSION['user_id'], "Membuat backup database: {$filename}", 'backup');
        $_SESSION['flash_success'] = 'Backup database berhasil dibuat: ' . $filename;
        $this->redirect('/adminbackup');
    }

    public function unduh(string $nama = ''): void
    {
        $nama = basename($nama); // cegah path traversal
        $path = dirname(__DIR__) . '/storage/backup/' . $nama;
        if (!is_file($path) || !str_ends_with($nama, '.sql')) {
            $this->abort(404, 'File backup tidak ditemukan.');
        }
        header('Content-Type: application/sql');
        header('Content-Disposition: attachment; filename="' . $nama . '"');
        header('Content-Length: ' . filesize($path));
        readfile($path);
        exit;
    }

    public function hapus(string $nama = ''): void
    {
        $nama = basename($nama);
        $path = dirname(__DIR__) . '/storage/backup/' . $nama;
        if (is_file($path) && str_ends_with($nama, '.sql')) {
            unlink($path);
            Security::logActivity((int) $_SESSION['user_id'], "Menghapus file backup: {$nama}", 'backup');
            $_SESSION['flash_success'] = 'File backup berhasil dihapus.';
        }
        $this->redirect('/adminbackup');
    }

    /** Restore dari file .sql yang diunggah — dijalankan sebagai statement PDO satu per satu (bukan shell_exec) */
    public function restore(): void
    {
        $this->validateCsrf();

        if (empty($_FILES['file_sql']['name']) || $_FILES['file_sql']['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['flash_error'] = 'Pilih file .sql yang valid untuk direstore.';
            $this->redirect('/adminbackup');
        }

        $tmpPath = $_FILES['file_sql']['tmp_name'];
        $originalName = $_FILES['file_sql']['name'];

        if (!str_ends_with(strtolower($originalName), '.sql')) {
            $_SESSION['flash_error'] = 'File harus berformat .sql.';
            $this->redirect('/adminbackup');
        }
        if ($_FILES['file_sql']['size'] > 20 * 1024 * 1024) { // 20MB
            $_SESSION['flash_error'] = 'Ukuran file backup terlalu besar (maks 20MB).';
            $this->redirect('/adminbackup');
        }

        $content = file_get_contents($tmpPath);
        $db = Database::getInstance();

        try {
            $db->beginTransaction();
            // Pisahkan berdasarkan titik koma di akhir baris (pendekatan sederhana untuk dump standar tanpa DELIMITER khusus)
            $statements = array_filter(array_map('trim', explode(";\n", $content)));
            foreach ($statements as $stmt) {
                $stmt = trim($stmt);
                if ($stmt === '' || str_starts_with($stmt, '--')) continue;
                $db->exec($stmt);
            }
            $db->commit();
            Security::logActivity((int) $_SESSION['user_id'], "Merestore database dari file: {$originalName}", 'restore');
            $_SESSION['flash_success'] = 'Database berhasil direstore dari ' . $originalName;
        } catch (Throwable $e) {
            $db->rollBack();
            error_log('Restore error: ' . $e->getMessage());
            $_SESSION['flash_error'] = 'Restore gagal: pastikan file backup valid. Detail dicatat di log server.';
        }

        $this->redirect('/adminbackup');
    }

    public function logAktivitas(): void
    {
        $db = Database::getInstance();
        $daftar = $db->query(
            "SELECT l.*, u.nama_lengkap FROM activity_logs l
             LEFT JOIN users u ON u.id = l.user_id
             ORDER BY l.created_at DESC LIMIT 500"
        )->fetchAll();

        $this->view('admin/backup/log-aktivitas', ['daftar' => $daftar], layout: 'layouts/admin');
    }
}
