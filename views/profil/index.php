<section id="profil">
  <div class="container">
    <h1 class="fw-bold text-center mb-5" data-aos="fade-up">Profil Sekolah</h1>

    <div class="row g-4 mb-4 align-items-stretch">
      <div class="col-md-7" data-aos="fade-right">
        <div class="glass-card p-4 h-100">
          <h5 class="fw-bold text-warning">Sejarah</h5>
          <p class="small mb-0"><?= nl2br(htmlspecialchars($profil['sejarah'] ?? '')) ?></p>
        </div>
      </div>
      <div class="col-md-5" data-aos="fade-left">
        <div class="glass-card p-2 h-100 d-flex align-items-center justify-content-center overflow-hidden">
          <img src="<?= !empty($profil['foto_sejarah']) ? $this->appConfig['base_url'] . '/' . htmlspecialchars($profil['foto_sejarah']) : 'https://images.unsplash.com/photo-1580582932707-520aed937b7b?w=800&q=80' ?>" class="img-fluid rounded-3 w-100" style="object-fit:cover; max-height:260px;">
        </div>
      </div>
    </div>

    <div class="row g-4 mb-5">
      <div class="col-md-4" data-aos="fade-up">
        <div class="glass-card p-4 h-100">
          <h5 class="fw-bold text-warning">Visi</h5>
          <p class="small"><?= nl2br(htmlspecialchars($profil['visi'] ?? '')) ?></p>
        </div>
      </div>
      <div class="col-md-4" data-aos="fade-up">
        <div class="glass-card p-4 h-100">
          <h5 class="fw-bold text-warning">Tujuan</h5>
          <p class="small"><?= nl2br(htmlspecialchars($profil['tujuan'] ?? '')) ?></p>
        </div>
      </div>
      <div class="col-md-4" data-aos="fade-up">
        <div class="glass-card p-4 h-100">
          <h5 class="fw-bold text-warning">Misi</h5>
          <p class="small mb-0"><?= nl2br(htmlspecialchars($profil['misi'] ?? '')) ?></p>
        </div>
      </div>
    </div>

    <div class="row align-items-center mb-5" data-aos="fade-up">
      <div class="col-md-3 text-center">
        <img src="<?= !empty($pengaturan['foto_kepsek']) ? $this->appConfig['base_url'] . '/' . htmlspecialchars($pengaturan['foto_kepsek']) : 'https://placehold.co/200x200' ?>" class="rounded-4 img-fluid mb-2">
        <p class="fw-bold mb-0"><?= htmlspecialchars($profil['nama_kepsek'] ?? '') ?></p>
        <small class="text-muted">Kepala Sekolah</small>
      </div>
      <div class="col-md-9">
        <div class="glass-card p-4">
          <h5 class="fw-bold text-warning">Sambutan Kepala Sekolah</h5>
          <p class="small mb-0"><?= nl2br(htmlspecialchars($profil['sambutan_kepsek'] ?? '')) ?></p>
        </div>
      </div>
    </div>

    <?php if (!empty($fotoLingkungan)): ?>
    <h3 class="fw-bold text-center mb-4" data-aos="fade-up">🏡 Lingkungan Sekolah</h3>
    <div class="row g-3 mb-5">
      <?php foreach ($fotoLingkungan as $fl): ?>
      <div class="col-6 col-md-3" data-aos="zoom-in">
        <a href="<?= $this->appConfig['base_url'] ?>/<?= htmlspecialchars($fl['file']) ?>" data-lightbox="lingkungan">
          <img src="<?= $this->appConfig['base_url'] ?>/<?= htmlspecialchars($fl['file']) ?>" class="img-fluid rounded-3" style="height:150px;width:100%;object-fit:cover;">
        </a>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <h3 class="fw-bold text-center mb-4" data-aos="fade-up">Struktur Organisasi</h3>
    <div class="row g-3 justify-content-center mb-5">
      <?php foreach ($struktur as $s): ?>
      <div class="col-6 col-md-2" data-aos="zoom-in">
        <div class="glass-card text-center p-3">
          <img src="<?= htmlspecialchars($this->appConfig['base_url'] . '/' . ($s['foto'] ?: '')) ?>" onerror="this.src='https://placehold.co/100x100'" class="rounded-circle mb-2" style="width:70px;height:70px;object-fit:cover;">
          <h6 class="small fw-bold mb-0"><?= htmlspecialchars($s['nama']) ?></h6>
          <small class="text-muted"><?= htmlspecialchars($s['jabatan']) ?></small>
        </div>
      </div>
      <?php endforeach; ?>
      <?php if (empty($struktur)): ?><p class="text-center text-muted">Struktur organisasi belum tersedia.</p><?php endif; ?>
    </div>
  </div>
</section>
