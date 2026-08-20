<div class="d-flex justify-content-between align-items-center mb-3">
  <h5 class="fw-bold mb-0">🖼️ Hero Section (Banner Beranda)</h5>
</div>
<p class="text-muted small">Gambar di bawah ini tampil bergantian (carousel) di bagian paling atas landing page. Disarankan ukuran lebar minimal 1600px, orientasi landscape.</p>

<div class="card stat-card p-3 mb-4">
  <form method="POST" action="<?= $this->appConfig['base_url'] ?>/adminhero/upload" enctype="multipart/form-data" class="row g-2 align-items-end">
    <?= Security::csrfField() ?>
    <div class="col-md-4">
      <label class="form-label small">Judul / Keterangan (opsional)</label>
      <input type="text" name="judul" class="form-control" placeholder="Contoh: Kegiatan Outdoor">
    </div>
    <div class="col-md-2">
      <label class="form-label small">Urutan</label>
      <input type="number" name="urutan" class="form-control" value="0">
    </div>
    <div class="col-md-4">
      <label class="form-label small">File Gambar</label>
      <input type="file" name="gambar" class="form-control" accept="image/*" required>
          <small class="text-muted d-block mt-1">Maks. 4MB — format JPG, PNG, atau WEBP</small>
    </div>
    <div class="col-md-2">
      <button type="submit" class="btn btn-warning w-100 fw-semibold">Unggah</button>
    </div>
  </form>
</div>

<div class="row g-3">
  <?php foreach ($slides as $s): ?>
  <div class="col-6 col-md-3">
    <div class="card stat-card">
      <img src="<?= $this->appConfig['base_url'] ?>/<?= htmlspecialchars($s['gambar']) ?>" class="card-img-top" style="height:140px;object-fit:cover;">
      <div class="card-body p-2">
        <small class="text-truncate d-block"><?= htmlspecialchars($s['judul'] ?: '-') ?></small>
        <div class="d-flex justify-content-between align-items-center mt-1">
          <span class="badge bg-light text-dark border">Urutan <?= (int) $s['urutan'] ?></span>
          <button class="btn btn-sm btn-outline-danger btn-hapus" data-url="<?= $this->appConfig['base_url'] ?>/adminhero/hapus/<?= $s['id'] ?>"><i class="fa-solid fa-trash"></i></button>
        </div>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
  <?php if (empty($slides)): ?><p class="text-muted">Belum ada gambar hero. Tanpa gambar, beranda akan menampilkan foto contoh bawaan.</p><?php endif; ?>
</div>

