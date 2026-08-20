<div class="card stat-card p-4">
  <h5 class="fw-bold mb-3"><?= $item ? 'Edit Kalender' : 'Tambah Kalender' ?></h5>
  <form method="POST">
    <?= Security::csrfField() ?>
    <div class="mb-3">
      <label class="form-label small">Judul Kegiatan</label>
      <input type="text" name="judul" class="form-control" value="<?= htmlspecialchars($item['judul'] ?? '') ?>" required>
    </div>
    <div class="row">
      <div class="col-md-6 mb-3">
        <label class="form-label small">Tanggal Mulai</label>
        <input type="date" name="tanggal_mulai" class="form-control" value="<?= htmlspecialchars($item['tanggal_mulai'] ?? '') ?>" required>
      </div>
      <div class="col-md-6 mb-3">
        <label class="form-label small">Tanggal Selesai (opsional)</label>
        <input type="date" name="tanggal_selesai" class="form-control" value="<?= htmlspecialchars($item['tanggal_selesai'] ?? '') ?>">
      </div>
    </div>
    <div class="mb-3">
      <label class="form-label small">Keterangan</label>
      <textarea name="keterangan" class="form-control" rows="3"><?= htmlspecialchars($item['keterangan'] ?? '') ?></textarea>
    </div>
    <button type="submit" class="btn btn-warning fw-semibold">Simpan</button>
    <a href="<?= $this->appConfig['base_url'] ?>/adminkalender" class="btn btn-outline-secondary">Batal</a>
  </form>
</div>
