<section>
  <div class="container">
    <p class="section-eyebrow text-center mb-2" data-aos="fade-up">Sumber Daya Manusia</p>
    <h1 class="fw-bold font-display text-center mb-2" data-aos="fade-up">Guru &amp; Tenaga Kependidikan</h1>
    <p class="text-center text-muted mb-4" data-aos="fade-up">Tim pendidik dan pendukung operasional TK IT Quantum School</p>

    <ul class="nav nav-pills justify-content-center mb-4" data-aos="fade-up">
      <li class="nav-item"><a class="nav-link active" data-bs-toggle="pill" href="#tabGuru">Guru</a></li>
      <li class="nav-item"><a class="nav-link" data-bs-toggle="pill" href="#tabTendik">Tenaga Kependidikan</a></li>
    </ul>

    <div class="tab-content">
      <div class="tab-pane fade show active" id="tabGuru">
        <div class="row g-4">
          <?php foreach ($guru as $g): ?>
          <div class="col-6 col-md-3" data-aos="fade-up">
            <div class="glass-card text-center p-3 h-100">
              <img src="<?= htmlspecialchars($this->appConfig['base_url'] . '/' . ($g['foto'] ?: '')) ?>" onerror="this.src='https://placehold.co/150x150'" class="rounded-circle mb-3" style="width:100px;height:100px;object-fit:cover;">
              <h6 class="fw-bold mb-1"><?= htmlspecialchars($g['nama_lengkap']) ?></h6>
              <p class="small text-muted mb-1"><?= htmlspecialchars($g['mata_pelajaran'] ?? '-') ?></p>
              <span class="badge badge-gold"><?= htmlspecialchars($g['jabatan'] ?? 'Guru') ?></span>
            </div>
          </div>
          <?php endforeach; ?>
          <?php if (empty($guru)): ?><p class="text-center text-muted">Data guru belum tersedia.</p><?php endif; ?>
        </div>
      </div>

      <div class="tab-pane fade" id="tabTendik">
        <div class="row g-4">
          <?php foreach ($tendik as $t): ?>
          <div class="col-6 col-md-3" data-aos="fade-up">
            <div class="glass-card text-center p-3 h-100">
              <img src="<?= htmlspecialchars($this->appConfig['base_url'] . '/' . ($t['foto'] ?: '')) ?>" onerror="this.src='https://placehold.co/150x150'" class="rounded-circle mb-3" style="width:100px;height:100px;object-fit:cover;">
              <h6 class="fw-bold mb-1"><?= htmlspecialchars($t['nama_lengkap']) ?></h6>
              <span class="badge badge-gold"><?= htmlspecialchars($t['jabatan'] ?? '-') ?></span>
            </div>
          </div>
          <?php endforeach; ?>
          <?php if (empty($tendik)): ?><p class="text-center text-muted">Data belum tersedia.</p><?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</section>
