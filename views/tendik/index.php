<section>
  <div class="container">
    <h1 class="fw-bold text-center mb-2" data-aos="fade-up">Tenaga Kependidikan</h1>
    <p class="text-center text-muted mb-5" data-aos="fade-up">Tim pendukung operasional TK IT Quantum School</p>
    <div class="row g-4">
      <?php foreach ($daftar as $t): ?>
      <div class="col-6 col-md-3" data-aos="fade-up">
        <div class="glass-card text-center p-3 h-100">
          <img src="<?= htmlspecialchars($this->appConfig['base_url'] . '/' . ($t['foto'] ?: '')) ?>" onerror="this.src='https://placehold.co/150x150'" class="rounded-circle mb-3" style="width:100px;height:100px;object-fit:cover;">
          <h6 class="fw-bold mb-1"><?= htmlspecialchars($t['nama_lengkap']) ?></h6>
          <span class="badge bg-warning text-dark"><?= htmlspecialchars($t['jabatan'] ?? '-') ?></span>
        </div>
      </div>
      <?php endforeach; ?>
      <?php if (empty($daftar)): ?><p class="text-center text-muted">Data belum tersedia.</p><?php endif; ?>
    </div>
  </div>
</section>
