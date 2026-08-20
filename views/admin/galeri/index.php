<ul class="nav nav-tabs mb-3">
  <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#tabFoto">Foto</a></li>
  <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tabVideo">Video</a></li>
</ul>

<div class="tab-content">
  <div class="tab-pane fade show active" id="tabFoto">
    <div class="card stat-card p-3 mb-3">
      <form method="POST" action="<?= $this->appConfig['base_url'] ?>/admingaleri/uploadFoto" enctype="multipart/form-data" class="row g-2 align-items-end">
        <?= Security::csrfField() ?>
        <div class="col-md-3">
          <label class="form-label small">Judul</label>
          <input type="text" name="judul" class="form-control" required>
        </div>
        <div class="col-md-3">
          <label class="form-label small">Kategori</label>
          <select name="kategori_id" class="form-select">
            <option value="">- Tanpa Kategori -</option>
            <?php foreach ($kategoriList as $k): ?>
              <option value="<?= $k['id'] ?>"><?= htmlspecialchars($k['nama_kategori']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-4">
          <label class="form-label small">File Foto</label>
          <input type="file" name="file" class="form-control" accept="image/*" required>
              <small class="text-muted d-block mt-1">Maks. 4MB — format JPG, PNG, atau WEBP</small>
        </div>
        <div class="col-md-2">
          <button type="submit" class="btn btn-warning w-100 fw-semibold">Unggah</button>
        </div>
      </form>
    </div>

    <div class="row g-3">
      <?php foreach ($foto as $f): ?>
      <div class="col-6 col-md-3">
        <div class="card stat-card">
          <img src="<?= $this->appConfig['base_url'] ?>/<?= htmlspecialchars($f['file']) ?>" class="card-img-top" style="height:140px;object-fit:cover;">
          <div class="card-body p-2 d-flex justify-content-between align-items-center">
            <small class="text-truncate"><?= htmlspecialchars($f['judul'] ?: '-') ?></small>
            <button class="btn btn-sm btn-outline-danger btn-hapus" data-url="<?= $this->appConfig['base_url'] ?>/admingaleri/hapusFoto/<?= $f['id'] ?>"><i class="fa-solid fa-trash"></i></button>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
      <?php if (empty($foto)): ?><p class="text-muted">Belum ada foto.</p><?php endif; ?>
    </div>
  </div>

  <div class="tab-pane fade" id="tabVideo">
    <div class="card stat-card p-3 mb-3">
      <form method="POST" action="<?= $this->appConfig['base_url'] ?>/admingaleri/tambahVideo" enctype="multipart/form-data" class="row g-2 align-items-end">
        <?= Security::csrfField() ?>
        <div class="col-md-3">
          <label class="form-label small">Judul Video</label>
          <input type="text" name="judul" class="form-control" required>
        </div>
        <div class="col-md-2">
          <label class="form-label small">Platform</label>
          <select name="platform" class="form-select">
            <option value="YouTube">YouTube</option>
            <option value="Instagram">Instagram</option>
            <option value="TikTok">TikTok</option>
            <option value="Facebook">Facebook</option>
            <option value="Lainnya">Lainnya</option>
          </select>
        </div>
        <div class="col-md-4">
          <label class="form-label small">URL Video</label>
          <input type="url" name="url_youtube" class="form-control" placeholder="https://youtube.com/watch?v=... atau link Instagram/TikTok/Facebook" required>
        </div>
        <div class="col-md-2">
          <label class="form-label small">Thumbnail (opsional)</label>
          <input type="file" name="thumbnail" class="form-control form-control-sm" accept="image/*">
        </div>
        <div class="col-md-1">
          <button type="submit" class="btn btn-warning w-100 fw-semibold">+</button>
        </div>
      </form>
      <small class="text-muted mt-2 d-block">Bisa tempel link dari YouTube, Instagram Reels, TikTok, atau Facebook. YouTube otomatis diputar langsung di halaman Galeri. Untuk platform lain, disarankan unggah <strong>thumbnail</strong> (screenshot video) supaya tampilannya menarik — bukan cuma kotak ikon. Maks. 4MB.</small>
    </div>

    <div class="row g-3">
      <?php foreach ($video as $v): ?>
      <?php
        $icon = match($v['platform'] ?? 'YouTube') {
          'Instagram' => 'fa-instagram text-danger',
          'TikTok' => 'fa-tiktok text-dark',
          'Facebook' => 'fa-facebook text-primary',
          default => 'fa-youtube text-danger',
        };
      ?>
      <div class="col-md-4">
        <div class="card stat-card p-2">
          <?php if (!empty($v['thumbnail'])): ?>
            <img src="<?= $this->appConfig['base_url'] ?>/<?= htmlspecialchars($v['thumbnail']) ?>" class="rounded-3 mb-2" style="height:100px;width:100%;object-fit:cover;">
          <?php elseif (($v['platform'] ?? 'YouTube') !== 'YouTube'): ?>
            <div class="rounded-3 mb-2 d-flex align-items-center justify-content-center bg-light" style="height:100px;">
              <i class="fa-brands <?= $icon ?> fa-2x text-muted"></i>
            </div>
          <?php endif; ?>
          <div class="d-flex justify-content-between align-items-center">
            <span class="text-truncate"><i class="fa-brands <?= $icon ?> me-2"></i><?= htmlspecialchars($v['judul']) ?> <span class="badge bg-light text-dark border ms-1"><?= htmlspecialchars($v['platform'] ?? 'YouTube') ?></span></span>
            <button class="btn btn-sm btn-outline-danger btn-hapus" data-url="<?= $this->appConfig['base_url'] ?>/admingaleri/hapusVideo/<?= $v['id'] ?>"><i class="fa-solid fa-trash"></i></button>
          </div>
          <?php if (($v['platform'] ?? 'YouTube') !== 'YouTube'): ?>
          <form method="POST" action="<?= $this->appConfig['base_url'] ?>/admingaleri/updateThumbnail/<?= $v['id'] ?>" enctype="multipart/form-data" class="d-flex gap-1 mt-2">
            <?= Security::csrfField() ?>
            <input type="file" name="thumbnail" class="form-control form-control-sm" accept="image/*" required>
            <button type="submit" class="btn btn-sm btn-outline-warning flex-shrink-0"><?= !empty($v['thumbnail']) ? 'Ganti' : 'Set' ?> Thumbnail</button>
          </form>
          <?php endif; ?>
        </div>
      </div>
      <?php endforeach; ?>
      <?php if (empty($video)): ?><p class="text-muted">Belum ada video.</p><?php endif; ?>
    </div>
  </div>
</div>

