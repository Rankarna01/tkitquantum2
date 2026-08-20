<div class="card stat-card p-4">
  <h5 class="fw-bold mb-3"><?= $item ? 'Edit Ekstrakurikuler' : 'Tambah Ekstrakurikuler' ?></h5>
  <form method="POST" enctype="multipart/form-data">
    <?= Security::csrfField() ?>
    <div class="row">
      <div class="col-md-6 mb-3">
        <label class="form-label small">Nama Ekstrakurikuler</label>
        <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($item['nama'] ?? '') ?>" required>
      </div>
      <div class="col-md-6 mb-3">
        <label class="form-label small">Pembina</label>
        <input type="text" name="pembina" class="form-control" value="<?= htmlspecialchars($item['pembina'] ?? '') ?>">
      </div>
    </div>
    <div class="mb-3">
      <label class="form-label small">Jadwal</label>
      <input type="text" name="jadwal" class="form-control" value="<?= htmlspecialchars($item['jadwal'] ?? '') ?>" placeholder="Setiap Sabtu, 08.00 - 10.00">
    </div>
    <div class="mb-3">
      <label class="form-label small">Foto <?= $item ? '(kosongkan jika tidak diubah)' : '' ?></label>
      <input type="file" name="foto" class="form-control" accept="image/*">
    </div>
    <div class="mb-3">
      <label class="form-label small">Deskripsi</label>
      <textarea name="deskripsi" class="form-control" rows="3"><?= htmlspecialchars($item['deskripsi'] ?? '') ?></textarea>
    </div>
    <button type="submit" class="btn btn-warning fw-semibold">Simpan</button>
    <a href="<?= $this->appConfig['base_url'] ?>/adminekskul" class="btn btn-outline-secondary">Batal</a>
  </form>
</div>
