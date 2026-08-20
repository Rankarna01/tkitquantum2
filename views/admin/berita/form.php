<div class="card stat-card p-4">
  <h5 class="fw-bold mb-3"><?= $berita ? 'Edit Berita' : 'Tambah Berita' ?></h5>
  <form method="POST" enctype="multipart/form-data">
    <?= Security::csrfField() ?>
    <div class="mb-3">
      <label class="form-label small">Judul</label>
      <input type="text" name="judul" class="form-control" value="<?= htmlspecialchars($berita['judul'] ?? '') ?>" required>
    </div>
    <div class="row">
      <div class="col-md-6 mb-3">
        <label class="form-label small">Kategori</label>
        <select name="kategori_id" class="form-select">
          <option value="">- Pilih Kategori -</option>
          <?php foreach ($kategoriList as $k): ?>
            <option value="<?= $k['id'] ?>" <?= (($berita['kategori_id'] ?? null) == $k['id']) ? 'selected' : '' ?>><?= htmlspecialchars($k['nama_kategori']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-6 mb-3">
        <label class="form-label small">Status</label>
        <select name="status" class="form-select">
          <option value="draft" <?= (($berita['status'] ?? '') === 'draft') ? 'selected' : '' ?>>Draft</option>
          <option value="publish" <?= (($berita['status'] ?? '') === 'publish') ? 'selected' : '' ?>>Publish</option>
        </select>
      </div>
    </div>
    <div class="mb-3">
      <label class="form-label small">Ringkasan</label>
      <textarea name="ringkasan" class="form-control" rows="2" maxlength="500"><?= htmlspecialchars($berita['ringkasan'] ?? '') ?></textarea>
    </div>
    <div class="mb-3">
      <label class="form-label small">Thumbnail <?= $berita ? '(kosongkan jika tidak diubah)' : '' ?></label>
      <input type="file" name="thumbnail" class="form-control" accept="image/*">
          <small class="text-muted d-block mt-1">Maks. 4MB — format JPG, PNG, atau WEBP</small>
    </div>

    <div class="mb-3">
      <label class="form-label small">Foto Tambahan (opsional)</label>
      <input type="file" name="foto_tambahan[]" class="form-control" accept="image/*" multiple>
      <small class="text-muted d-block mt-1">Bisa pilih beberapa foto sekaligus. Maks. 4MB per foto. Kalau total foto (thumbnail + tambahan) berjumlah 2 atau lebih, otomatis tampil sebagai <strong>slide hero</strong> yang bisa digeser di halaman detail berita.</small>
      <?php if (!empty($fotoTambahan)): ?>
      <div class="row g-2 mt-2">
        <?php foreach ($fotoTambahan as $ft): ?>
        <div class="col-auto">
          <div class="position-relative">
            <img src="<?= $this->appConfig['base_url'] ?>/<?= htmlspecialchars($ft['file']) ?>" style="width:80px;height:80px;object-fit:cover;border-radius:8px;">
            <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 p-0 btn-hapus" style="width:20px;height:20px;line-height:1;" data-url="<?= $this->appConfig['base_url'] ?>/adminberita/hapusFotoTambahan/<?= $ft['id'] ?>" data-title="Hapus foto ini?"><i class="fa-solid fa-xmark" style="font-size:.6rem;"></i></button>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    </div>
    <div class="mb-3">
      <label class="form-label small">Isi Berita</label>
      <textarea name="isi" id="editorIsi" class="form-control" rows="8"><?= $berita['isi'] ?? '' ?></textarea>
    </div>
    <button type="submit" class="btn btn-warning fw-semibold">Simpan</button>
    <a href="<?= $this->appConfig['base_url'] ?>/adminberita" class="btn btn-outline-secondary">Batal</a>
  </form>
</div>

<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
<script>CKEDITOR.replace('editorIsi');</script>
