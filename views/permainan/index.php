<section class="py-5">
  <div class="container text-center" style="max-width:900px;">
    <p class="section-eyebrow mb-2" data-aos="fade-up">YUK BERMAIN</p>
    <h1 class="fw-bold font-display mb-2" data-aos="fade-up">🎮 Zona Permainan</h1>
    <p class="text-muted mb-5" data-aos="fade-up">Pilih game favoritmu, latih otak sambil seru-seruan! ✨</p>

    <div class="row g-4 justify-content-center">
      <?php foreach ($daftarGame as $g): ?>
      <div class="col-6 col-md-4" data-aos="fade-up">
        <a href="<?= $this->appConfig['base_url'] ?>/permainan/main/<?= htmlspecialchars($g['slug']) ?>" class="text-decoration-none">
          <div class="glass-card p-4 h-100 text-center">
            <div style="font-size:3rem;" class="mb-2"><?= htmlspecialchars($g['ikon'] ?: '🎮') ?></div>
            <h5 class="fw-bold text-dark mb-1"><?= htmlspecialchars($g['nama']) ?></h5>
            <p class="small text-muted mb-0"><?= htmlspecialchars($g['deskripsi'] ?? '') ?></p>
          </div>
        </a>
      </div>
      <?php endforeach; ?>
      <?php if (empty($daftarGame)): ?>
        <p class="text-muted">Belum ada game yang tersedia saat ini.</p>
      <?php endif; ?>
    </div>
  </div>
</section>
