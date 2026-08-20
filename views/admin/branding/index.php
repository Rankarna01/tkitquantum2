<h5 class="fw-bold mb-3">Logo & Branding</h5>

<form method="POST" action="<?= $this->appConfig['base_url'] ?>/adminbranding/simpan" enctype="multipart/form-data">
  <?= Security::csrfField() ?>

  <div class="card stat-card p-4 mb-3">
    <h6 class="fw-bold text-warning mb-3">Identitas Sekolah</h6>
    <div class="row">
      <div class="col-md-6 mb-3">
        <label class="form-label small">Nama Sekolah</label>
        <input type="text" name="nama_sekolah" class="form-control" value="<?= htmlspecialchars($pengaturan['nama_sekolah'] ?? '') ?>" required>
      </div>
      <div class="col-md-6 mb-3">
        <label class="form-label small">Tagline</label>
        <input type="text" name="tagline" class="form-control" value="<?= htmlspecialchars($pengaturan['tagline'] ?? '') ?>">
      </div>
    </div>
    <div class="mb-3">
      <label class="form-label small">Alamat</label>
      <textarea name="alamat" class="form-control" rows="2"><?= htmlspecialchars($pengaturan['alamat'] ?? '') ?></textarea>
    </div>
    <div class="row">
      <div class="col-md-6 mb-3">
        <label class="form-label small">Email</label>
        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($pengaturan['email'] ?? '') ?>">
      </div>
      <div class="col-md-6 mb-3">
        <label class="form-label small">Telepon</label>
        <input type="text" name="telepon" class="form-control" value="<?= htmlspecialchars($pengaturan['telepon'] ?? '') ?>">
      </div>
    </div>
  </div>

  <div class="card stat-card p-4 mb-3">
    <h6 class="fw-bold text-warning mb-3">Logo & Gambar</h6>
    <div class="row g-4">
      <?php
      $images = [
        'logo' => 'Logo Utama', 'favicon' => 'Favicon', 'logo_login' => 'Logo Halaman Login',
        'logo_navbar' => 'Logo Navbar', 'logo_footer' => 'Logo Footer', 'foto_kepsek' => 'Foto Kepala Sekolah',
        'banner_hero' => 'Banner Hero',
      ];
      foreach ($images as $field => $label): ?>
      <div class="col-md-3 text-center">
        <label class="form-label small d-block"><?= $label ?></label>
        <img src="<?= !empty($pengaturan[$field]) ? $this->appConfig['base_url'] . '/' . htmlspecialchars($pengaturan[$field]) : 'https://placehold.co/120x80?text=Belum+Ada' ?>" class="img-thumbnail mb-2" style="height:80px;object-fit:contain;">
        <input type="file" name="<?= $field ?>" class="form-control form-control-sm" accept="image/*">
        <small class="text-muted d-block mt-1">Maks. 4MB — format JPG, PNG, atau WEBP</small>
      </div>
      <?php endforeach; ?>
    </div>
  </div>

  <div class="card stat-card p-4 mb-3">
    <h6 class="fw-bold text-warning mb-3">Warna Website</h6>
    <div class="row">
      <div class="col-md-4 mb-3">
        <label class="form-label small">Warna Primer</label>
        <input type="color" name="warna_primary" class="form-control form-control-color w-100" value="<?= htmlspecialchars($pengaturan['warna_primary'] ?? '#FFC107') ?>">
      </div>
      <div class="col-md-4 mb-3">
        <label class="form-label small">Warna Sekunder</label>
        <input type="color" name="warna_secondary" class="form-control form-control-color w-100" value="<?= htmlspecialchars($pengaturan['warna_secondary'] ?? '#FFF8E1') ?>">
      </div>
      <div class="col-md-4 mb-3">
        <label class="form-label small">Warna Aksen</label>
        <input type="color" name="warna_accent" class="form-control form-control-color w-100" value="<?= htmlspecialchars($pengaturan['warna_accent'] ?? '#FF9800') ?>">
      </div>
    </div>
  </div>

  <div class="card stat-card p-4 mb-3">
    <h6 class="fw-bold text-warning mb-3">Sosial Media & Lokasi</h6>
    <div class="row">
      <div class="col-md-6 mb-3"><label class="form-label small">Facebook</label><input type="url" name="facebook" class="form-control" value="<?= htmlspecialchars($pengaturan['facebook'] ?? '') ?>"></div>
      <div class="col-md-6 mb-3"><label class="form-label small">Instagram</label><input type="url" name="instagram" class="form-control" value="<?= htmlspecialchars($pengaturan['instagram'] ?? '') ?>"></div>
      <div class="col-md-6 mb-3"><label class="form-label small">YouTube</label><input type="url" name="youtube" class="form-control" value="<?= htmlspecialchars($pengaturan['youtube'] ?? '') ?>"></div>
      <div class="col-md-6 mb-3"><label class="form-label small">TikTok</label><input type="url" name="tiktok" class="form-control" value="<?= htmlspecialchars($pengaturan['tiktok'] ?? '') ?>"></div>
    </div>
    <div class="mb-1">
      <label class="form-label small">Kode Embed Google Maps (atribut src iframe)</label>
      <textarea name="maps_embed" class="form-control" rows="2" placeholder="https://www.google.com/maps/embed?..."><?= htmlspecialchars($pengaturan['maps_embed'] ?? '') ?></textarea>
      <small class="text-muted">Tempel URL dari tombol "Bagikan &rarr; Sematkan peta" di Google Maps.</small>
    </div>

    <hr>
    <h6 class="fw-bold text-warning mb-3">🎵 Musik Latar Website</h6>
    <div class="row align-items-center">
      <div class="col-md-5 mb-3">
        <label class="form-label small">Putar Musik Otomatis Saat Website Dibuka?</label>
        <select name="musik_aktif" class="form-select">
          <option value="tidak" <?= ($pengaturan['musik_aktif'] ?? 'tidak') === 'tidak' ? 'selected' : '' ?>>Tidak</option>
          <option value="ya" <?= ($pengaturan['musik_aktif'] ?? 'tidak') === 'ya' ? 'selected' : '' ?>>Ya</option>
        </select>
        <small class="text-muted d-block mt-1">Browser membatasi audio otomatis dengan suara — musik akan diputar senyap dulu, lalu pengunjung tinggal klik tombol 🔊 yang muncul di pojok layar untuk menyalakan suara.</small>
      </div>
      <div class="col-md-7 mb-3">
        <label class="form-label small">File Musik (MP3/OGG/WAV, instrumental anak-anak disarankan)</label>
        <input type="file" name="musik_latar" class="form-control" accept="audio/mpeg,audio/mp3,audio/ogg,audio/wav">
        <small class="text-muted d-block mt-1">Maks. 4MB</small>
        <?php if (!empty($pengaturan['musik_latar'])): ?>
          <audio controls class="mt-2 w-100"><source src="<?= $this->appConfig['base_url'] ?>/<?= htmlspecialchars($pengaturan['musik_latar']) ?>"></audio>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <button type="submit" class="btn btn-warning fw-semibold px-4">Simpan Semua Perubahan</button>
</form>
