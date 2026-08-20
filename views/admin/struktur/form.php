<div class="card stat-card p-4">
  <h5 class="fw-bold mb-3"><?= $item ? 'Edit Struktur' : 'Tambah Struktur' ?></h5>
  <form method="POST" enctype="multipart/form-data">
    <?= Security::csrfField() ?>
    <div class="row">
      <div class="col-md-6 mb-3">
        <label class="form-label small">Nama</label>
        <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($item['nama'] ?? '') ?>" required>
      </div>
      <div class="col-md-6 mb-3">
        <label class="form-label small">Jabatan</label>
        <input type="text" name="jabatan" class="form-control" value="<?= htmlspecialchars($item['jabatan'] ?? '') ?>" required>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6 mb-3">
        <label class="form-label small">Atasan Langsung (opsional)</label>
        <select name="parent_id" class="form-select">
          <option value="">- Tidak Ada (Puncak) -</option>
          <?php foreach ($daftar as $d): ?>
            <?php if (!$item || $d['id'] != $item['id']): ?>
              <option value="<?= $d['id'] ?>" <?= (($item['parent_id'] ?? null) == $d['id']) ? 'selected' : '' ?>><?= htmlspecialchars($d['nama']) ?></option>
            <?php endif; ?>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-6 mb-3">
        <label class="form-label small">Urutan Tampil</label>
        <input type="number" name="urutan" class="form-control" value="<?= (int) ($item['urutan'] ?? 0) ?>">
      </div>
    </div>
    <div class="mb-3">
      <label class="form-label small">Foto <?= $item ? '(kosongkan jika tidak diubah)' : '' ?></label>
      <input type="file" name="foto" class="form-control" accept="image/*">
          <small class="text-muted d-block mt-1">Maks. 4MB — format JPG, PNG, atau WEBP</small>
    </div>
    <button type="submit" class="btn btn-warning fw-semibold">Simpan</button>
    <a href="<?= $this->appConfig['base_url'] ?>/adminstruktur" class="btn btn-outline-secondary">Batal</a>
  </form>
</div>
