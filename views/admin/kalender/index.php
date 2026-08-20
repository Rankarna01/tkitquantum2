<div class="d-flex justify-content-between align-items-center mb-3">
  <h5 class="fw-bold mb-0">Kalender Akademik</h5>
  <a href="<?= $this->appConfig['base_url'] ?>/adminkalender/tambah" class="btn btn-warning fw-semibold"><i class="fa-solid fa-plus me-1"></i> Tambah</a>
</div>
<div class="card stat-card p-3">
  <table class="table table-hover align-middle">
    <thead><tr><th>Judul</th><th>Mulai</th><th>Selesai</th><th>Keterangan</th><th>Aksi</th></tr></thead>
    <tbody>
      <?php foreach ($daftar as $d): ?>
      <tr>
        <td><?= htmlspecialchars($d['judul']) ?></td>
        <td><?= htmlspecialchars($d['tanggal_mulai']) ?></td>
        <td><?= htmlspecialchars($d['tanggal_selesai'] ?? '-') ?></td>
        <td class="small"><?= htmlspecialchars($d['keterangan'] ?? '-') ?></td>
        <td>
          <a href="<?= $this->appConfig['base_url'] ?>/adminkalender/edit/<?= $d['id'] ?>" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-pen"></i></a>
          <a href="<?= $this->appConfig['base_url'] ?>/adminkalender/hapus/<?= $d['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus data ini?')"><i class="fa-solid fa-trash"></i></a>
        </td>
      </tr>
      <?php endforeach; ?>
      <?php if (empty($daftar)): ?><tr><td colspan="5" class="text-center text-muted">Belum ada data.</td></tr><?php endif; ?>
    </tbody>
  </table>
</div>
