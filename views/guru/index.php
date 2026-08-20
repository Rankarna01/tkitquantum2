<section>
  <div class="container">
    <h1 class="fw-bold text-center mb-2" data-aos="fade-up">Guru TK IT Quantum School</h1>
    <p class="text-center text-muted mb-5" data-aos="fade-up">Tenaga pendidik profesional dan berdedikasi</p>
    <div class="row g-3 justify-content-center">
      <?php foreach ($daftar as $g): ?>
      <div class="col-6 col-md-4" data-aos="fade-up">
        <div class="glass-card text-center p-2 h-100">
          <img src="<?= htmlspecialchars($this->appConfig['base_url'] . '/' . ($g['foto'] ?: '')) ?>" onerror="this.src='https://placehold.co/150x150'" class="rounded-circle mb-2" style="width:130px;height:130px;object-fit:cover;">
          <h6 class="fw-bold mb-1"><?= htmlspecialchars($g['nama_lengkap']) ?></h6>
          <p class="small text-muted mb-1"><?= htmlspecialchars($g['mata_pelajaran'] ?? '-') ?></p>
          <span class="badge bg-warning text-dark"><?= htmlspecialchars($g['jabatan'] ?? 'Guru') ?></span>
        </div>
      </div>
      <?php endforeach; ?>
      <?php if (empty($daftar)): ?><p class="text-center text-muted">Data guru belum tersedia.</p><?php endif; ?>
    </div>
  </div>
</section>
