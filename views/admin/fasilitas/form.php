<div class="card stat-card p-4">
  <h5 class="fw-bold mb-3"><?= $item ? 'Edit Fasilitas' : 'Tambah Fasilitas' ?></h5>
  <form method="POST" enctype="multipart/form-data">
    <?= Security::csrfField() ?>
    <div class="mb-3">
      <label class="form-label small">Nama Fasilitas</label>
      <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($item['nama'] ?? '') ?>" required>
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
    <a href="<?= $this->appConfig['base_url'] ?>/adminfasilitas" class="btn btn-outline-secondary">Batal</a>
  </form>
</div>
