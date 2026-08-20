<div class="d-flex justify-content-between align-items-center mb-3">
  <h5 class="fw-bold mb-0">Backup & Restore Database</h5>
  <a href="<?= $this->appConfig['base_url'] ?>/adminbackup/logAktivitas" class="btn btn-outline-dark btn-sm"><i class="fa-solid fa-clock-rotate-left me-1"></i> Log Aktivitas</a>
</div>

<div class="row g-3 mb-3">
  <div class="col-md-6">
    <div class="card stat-card p-4 h-100">
      <h6 class="fw-bold text-warning mb-2"><i class="fa-solid fa-database me-2"></i>Buat Backup Baru</h6>
      <p class="small text-muted">Menghasilkan file .sql lengkap dari seluruh tabel dan data saat ini.</p>
      <form method="POST" action="<?= $this->appConfig['base_url'] ?>/adminbackup/backup" onsubmit="return confirm('Buat backup database sekarang?')">
        <?= Security::csrfField() ?>
        <button type="submit" class="btn btn-warning fw-semibold"><i class="fa-solid fa-download me-1"></i> Backup Sekarang</button>
      </form>
    </div>
  </div>
  <div class="col-md-6">
    <div class="card stat-card p-4 h-100">
      <h6 class="fw-bold text-danger mb-2"><i class="fa-solid fa-triangle-exclamation me-2"></i>Restore Database</h6>
      <p class="small text-muted">Mengunggah file .sql akan menjalankan ulang seluruh perintah SQL di dalamnya. <strong>Pastikan Anda sudah backup data saat ini terlebih dahulu.</strong></p>
      <form method="POST" action="<?= $this->appConfig['base_url'] ?>/adminbackup/restore" enctype="multipart/form-data" onsubmit="return confirm('PERINGATAN: Restore akan menimpa data yang sudah ada. Lanjutkan?')">
        <?= Security::csrfField() ?>
        <input type="file" name="file_sql" accept=".sql" class="form-control mb-2" required>
        <button type="submit" class="btn btn-outline-danger fw-semibold"><i class="fa-solid fa-upload me-1"></i> Restore dari File</button>
      </form>
    </div>
  </div>
</div>

<div class="card stat-card p-3">
  <h6 class="fw-bold mb-3">Riwayat Backup</h6>
  <table class="table table-hover align-middle">
    <thead><tr><th>Nama File</th><th>Ukuran</th><th>Tanggal</th><th>Aksi</th></tr></thead>
    <tbody>
      <?php foreach ($files as $f): ?>
      <tr>
        <td><?= htmlspecialchars($f['nama']) ?></td>
        <td><?= number_format($f['ukuran'] / 1024, 1) ?> KB</td>
        <td><?= date('d M Y H:i', $f['tanggal']) ?></td>
        <td>
          <a href="<?= $this->appConfig['base_url'] ?>/adminbackup/unduh/<?= urlencode($f['nama']) ?>" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-download"></i></a>
          <a href="<?= $this->appConfig['base_url'] ?>/adminbackup/hapus/<?= urlencode($f['nama']) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus file backup ini?')"><i class="fa-solid fa-trash"></i></a>
        </td>
      </tr>
      <?php endforeach; ?>
      <?php if (empty($files)): ?><tr><td colspan="4" class="text-center text-muted">Belum ada file backup.</td></tr><?php endif; ?>
    </tbody>
  </table>
</div>
