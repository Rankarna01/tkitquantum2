<div class="card stat-card p-4">
  <h5 class="fw-bold mb-3"><?= $item ? 'Edit Testimoni' : 'Tambah Testimoni' ?></h5>
  <form method="POST" enctype="multipart/form-data">
    <?= Security::csrfField() ?>
    <div class="row">
      <div class="col-md-6 mb-3">
        <label class="form-label small">Nama Alumni</label>
        <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($item['nama'] ?? '') ?>" required>
      </div>
      <div class="col-md-3 mb-3">
        <label class="form-label small">Angkatan</label>
        <input type="text" name="angkatan" class="form-control" value="<?= htmlspecialchars($item['angkatan'] ?? '') ?>" placeholder="2020">
      </div>
      <div class="col-md-3 mb-3">
        <label class="form-label small">Status</label>
        <select name="status" class="form-select">
          <option value="publish" <?= (($item['status'] ?? 'publish') === 'publish') ? 'selected' : '' ?>>Publish</option>
          <option value="draft" <?= (($item['status'] ?? '') === 'draft') ? 'selected' : '' ?>>Draft</option>
        </select>
      </div>
    </div>
    <div class="mb-3">
      <label class="form-label small">Profesi Saat Ini</label>
      <input type="text" name="profesi" class="form-control" value="<?= htmlspecialchars($item['profesi'] ?? '') ?>">
    </div>
    <div class="mb-3">
      <label class="form-label small">Foto <?= $item ? '(kosongkan jika tidak diubah)' : '' ?></label>
      <input type="file" name="foto" class="form-control" accept="image/*">
          <small class="text-muted d-block mt-1">Maks. 4MB — format JPG, PNG, atau WEBP</small>
    </div>
    <div class="mb-3">
      <label class="form-label small">Isi Testimoni</label>
      <textarea name="isi_testimoni" class="form-control" rows="4" required><?= htmlspecialchars($item['isi_testimoni'] ?? '') ?></textarea>
    </div>
    <button type="submit" class="btn btn-warning fw-semibold">Simpan</button>
    <a href="<?= $this->appConfig['base_url'] ?>/admintestimoni" class="btn btn-outline-secondary">Batal</a>
  </form>
</div>
