<div class="card stat-card p-4">
  <h5 class="fw-bold mb-3"><?= $item ? 'Edit Pengumuman' : 'Tambah Pengumuman' ?></h5>
  <form method="POST" enctype="multipart/form-data">
    <?= Security::csrfField() ?>
    <div class="mb-3">
      <label class="form-label small">Judul</label>
      <input type="text" name="judul" class="form-control" value="<?= htmlspecialchars($item['judul'] ?? '') ?>" required>
    </div>
    <div class="mb-3">
      <label class="form-label small">Status</label>
      <select name="status" class="form-select">
        <option value="draft" <?= (($item['status'] ?? '') === 'draft') ? 'selected' : '' ?>>Draft</option>
        <option value="publish" <?= (($item['status'] ?? '') === 'publish') ? 'selected' : '' ?>>Publish</option>
      </select>
    </div>
    <div class="mb-3">
      <label class="form-label small">Isi Singkat</label>
      <textarea name="isi_singkat" class="form-control" rows="2" maxlength="500"><?= htmlspecialchars($item['isi_singkat'] ?? '') ?></textarea>
    </div>
    <div class="mb-3">
      <label class="form-label small">Lampiran (gambar/PDF, opsional)</label>
      <input type="file" name="lampiran" class="form-control">
          <small class="text-muted d-block mt-1">Maks. 4MB — format JPG, PNG, atau WEBP</small>
    </div>
    <div class="mb-3">
      <label class="form-label small">Isi Lengkap</label>
      <textarea name="isi" id="editorIsi" class="form-control" rows="8"><?= $item['isi'] ?? '' ?></textarea>
    </div>
    <button type="submit" class="btn btn-warning fw-semibold">Simpan</button>
    <a href="<?= $this->appConfig['base_url'] ?>/adminpengumuman" class="btn btn-outline-secondary">Batal</a>
  </form>
</div>
<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
<script>CKEDITOR.replace('editorIsi');</script>
