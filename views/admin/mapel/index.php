<div class="d-flex justify-content-between align-items-center mb-3">
  <h5 class="fw-bold mb-0">Mata Pelajaran</h5>
  <a href="<?= $this->appConfig['base_url'] ?>/adminmapel/tambah" class="btn btn-warning fw-semibold"><i class="fa-solid fa-plus me-1"></i> Tambah</a>
</div>
<div class="card stat-card p-3">
  <table class="table table-hover align-middle">
    <thead><tr><th>Kode</th><th>Nama Mata Pelajaran</th><th>Deskripsi</th><th>Aksi</th></tr></thead>
    <tbody>
      <?php foreach ($daftar as $d): ?>
      <tr>
        <td><?= htmlspecialchars($d['kode'] ?? '-') ?></td>
        <td><?= htmlspecialchars($d['nama_mapel']) ?></td>
        <td class="small text-muted"><?= htmlspecialchars($d['deskripsi'] ?? '-') ?></td>
        <td>
          <a href="<?= $this->appConfig['base_url'] ?>/adminmapel/edit/<?= $d['id'] ?>" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-pen"></i></a>
          <a href="<?= $this->appConfig['base_url'] ?>/adminmapel/hapus/<?= $d['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus data ini?')"><i class="fa-solid fa-trash"></i></a>
        </td>
      </tr>
      <?php endforeach; ?>
      <?php if (empty($daftar)): ?><tr><td colspan="4" class="text-center text-muted">Belum ada data.</td></tr><?php endif; ?>
    </tbody>
  </table>
</div>
