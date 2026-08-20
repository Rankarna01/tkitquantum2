<div class="card stat-card p-4">
  <h5 class="fw-bold mb-3"><?= $item ? 'Edit Agenda' : 'Tambah Agenda' ?></h5>
  <form method="POST">
    <?= Security::csrfField() ?>
    <div class="mb-3">
      <label class="form-label small">Judul</label>
      <input type="text" name="judul" class="form-control" value="<?= htmlspecialchars($item['judul'] ?? '') ?>" required>
    </div>
    <div class="row">
      <div class="col-md-6 mb-3">
        <label class="form-label small">Tanggal &amp; Waktu Mulai</label>
        <input type="datetime-local" name="tanggal_mulai" class="form-control" value="<?= isset($item['tanggal_mulai']) ? date('Y-m-d\TH:i', strtotime($item['tanggal_mulai'])) : '' ?>" required>
      </div>
      <div class="col-md-6 mb-3">
        <label class="form-label small">Tanggal &amp; Waktu Selesai (opsional)</label>
        <input type="datetime-local" name="tanggal_selesai" class="form-control" value="<?= !empty($item['tanggal_selesai']) ? date('Y-m-d\TH:i', strtotime($item['tanggal_selesai'])) : '' ?>">
      </div>
    </div>
    <div class="mb-3">
      <label class="form-label small">Lokasi</label>
      <input type="text" name="lokasi" class="form-control" value="<?= htmlspecialchars($item['lokasi'] ?? '') ?>">
    </div>
    <div class="mb-3">
      <label class="form-label small">Deskripsi</label>
      <textarea name="deskripsi" class="form-control" rows="4"><?= htmlspecialchars($item['deskripsi'] ?? '') ?></textarea>
    </div>
    <button type="submit" class="btn btn-warning fw-semibold">Simpan</button>
    <a href="<?= $this->appConfig['base_url'] ?>/adminagenda" class="btn btn-outline-secondary">Batal</a>
  </form>
</div>
