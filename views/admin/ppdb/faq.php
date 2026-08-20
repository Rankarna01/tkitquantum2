<div class="d-flex justify-content-between align-items-center mb-3">
  <h5 class="fw-bold mb-0">Kelola FAQ PPDB</h5>
  <a href="<?= $this->appConfig['base_url'] ?>/adminppdb" class="btn btn-outline-dark btn-sm"><i class="fa-solid fa-arrow-left me-1"></i> Kembali</a>
</div>

<div class="card stat-card p-4 mb-3">
  <form method="POST" class="row g-2 align-items-end">
    <?= Security::csrfField() ?>
    <div class="col-md-4">
      <label class="form-label small">Pertanyaan</label>
      <input type="text" name="pertanyaan" class="form-control" required>
    </div>
    <div class="col-md-5">
      <label class="form-label small">Jawaban</label>
      <input type="text" name="jawaban" class="form-control" required>
    </div>
    <div class="col-md-1">
      <label class="form-label small">Urutan</label>
      <input type="number" name="urutan" class="form-control" value="0">
    </div>
    <div class="col-md-2">
      <button type="submit" class="btn btn-warning w-100 fw-semibold">Tambah</button>
    </div>
  </form>
</div>

<div class="card stat-card p-3">
  <table class="table table-hover align-middle">
    <thead><tr><th>Pertanyaan</th><th>Jawaban</th><th>Aksi</th></tr></thead>
    <tbody>
      <?php foreach ($daftar as $f): ?>
      <tr>
        <td><?= htmlspecialchars($f['pertanyaan']) ?></td>
        <td class="small"><?= htmlspecialchars($f['jawaban']) ?></td>
        <td><a href="<?= $this->appConfig['base_url'] ?>/adminppdb/hapusFaq/<?= $f['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus FAQ ini?')"><i class="fa-solid fa-trash"></i></a></td>
      </tr>
      <?php endforeach; ?>
      <?php if (empty($daftar)): ?><tr><td colspan="3" class="text-center text-muted">Belum ada FAQ.</td></tr><?php endif; ?>
    </tbody>
  </table>
</div>
