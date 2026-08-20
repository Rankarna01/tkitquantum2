<div class="card stat-card p-4">
  <h5 class="fw-bold mb-3"><?= $item ? 'Edit Prestasi' : 'Tambah Prestasi' ?></h5>
  <form method="POST" enctype="multipart/form-data">
    <?= Security::csrfField() ?>
    <div class="mb-3">
      <label class="form-label small">Judul Prestasi</label>
      <input type="text" name="judul" class="form-control" value="<?= htmlspecialchars($item['judul'] ?? '') ?>" required>
    </div>
    <div class="row">
      <div class="col-md-4 mb-3">
        <label class="form-label small">Kategori</label>
        <select name="kategori" class="form-select">
          <option value="akademik" <?= (($item['kategori'] ?? '') === 'akademik') ? 'selected' : '' ?>>Akademik</option>
          <option value="non-akademik" <?= (($item['kategori'] ?? '') === 'non-akademik') ? 'selected' : '' ?>>Non-Akademik</option>
        </select>
      </div>
      <div class="col-md-4 mb-3">
        <label class="form-label small">Tingkat</label>
        <input type="text" name="tingkat" class="form-control" value="<?= htmlspecialchars($item['tingkat'] ?? '') ?>" placeholder="Sekolah/Kota/Provinsi/Nasional">
      </div>
      <div class="col-md-4 mb-3">
        <label class="form-label small">Tahun</label>
        <input type="number" name="tahun" class="form-control" value="<?= htmlspecialchars((string) ($item['tahun'] ?? date('Y'))) ?>">
      </div>
    </div>
    <div class="mb-3">
      <label class="form-label small">Foto <?= $item ? '(kosongkan jika tidak diubah)' : '' ?></label>
      <input type="file" name="foto" class="form-control" accept="image/*">
          <small class="text-muted d-block mt-1">Maks. 4MB — format JPG, PNG, atau WEBP</small>
    </div>
    <div class="mb-3">
      <label class="form-label small">Deskripsi</label>
      <textarea name="deskripsi" class="form-control" rows="3"><?= htmlspecialchars($item['deskripsi'] ?? '') ?></textarea>
    </div>
    <button type="submit" class="btn btn-warning fw-semibold">Simpan</button>
    <a href="<?= $this->appConfig['base_url'] ?>/adminprestasi" class="btn btn-outline-secondary">Batal</a>
  </form>
</div>
