<div class="card stat-card p-4">
  <h5 class="fw-bold mb-3"><?= $item ? 'Edit Mata Pelajaran' : 'Tambah Mata Pelajaran' ?></h5>
  <form method="POST">
    <?= Security::csrfField() ?>
    <div class="row">
      <div class="col-md-8 mb-3">
        <label class="form-label small">Nama Mata Pelajaran</label>
        <input type="text" name="nama_mapel" class="form-control" value="<?= htmlspecialchars($item['nama_mapel'] ?? '') ?>" required>
      </div>
      <div class="col-md-4 mb-3">
        <label class="form-label small">Kode</label>
        <input type="text" name="kode" class="form-control" value="<?= htmlspecialchars($item['kode'] ?? '') ?>" placeholder="MTK">
      </div>
    </div>
    <div class="mb-3">
      <label class="form-label small">Deskripsi</label>
      <textarea name="deskripsi" class="form-control" rows="3"><?= htmlspecialchars($item['deskripsi'] ?? '') ?></textarea>
    </div>
    <button type="submit" class="btn btn-warning fw-semibold">Simpan</button>
    <a href="<?= $this->appConfig['base_url'] ?>/adminmapel" class="btn btn-outline-secondary">Batal</a>
  </form>
</div>
