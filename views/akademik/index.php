<section id="akademik">
  <div class="container">
    <h1 class="fw-bold text-center mb-2 font-display" data-aos="fade-up">🎨 Kegiatan Belajar Si Kecil</h1>
    <p class="text-center text-muted mb-4">Serunya belajar sambil bermain di TK IT Quantum School</p>

    <ul class="nav nav-pills justify-content-center mb-4" id="akademikTab">
      <li class="nav-item"><a class="nav-link active" data-bs-toggle="pill" href="#tabKalender">🗓️ Kalender</a></li>
      <li class="nav-item"><a class="nav-link" data-bs-toggle="pill" href="#tabPrestasi">🏆 Prestasi</a></li>
      <li class="nav-item"><a class="nav-link" data-bs-toggle="pill" href="#tabFasilitas">🏫 Fasilitas</a></li>
    </ul>

    <div class="tab-content">
      <div class="tab-pane fade show active" id="tabKalender">
        <div class="row g-3">
          <?php foreach ($kalender as $k): ?>
          <div class="col-md-4" data-aos="fade-up">
            <div class="glass-card p-3 d-flex gap-3">
              <i class="fa-solid fa-calendar-check text-warning fa-lg mt-1"></i>
              <div>
                <h6 class="fw-bold mb-1"><?= htmlspecialchars($k['judul']) ?></h6>
                <small class="text-muted"><?= date('d M Y', strtotime($k['tanggal_mulai'])) ?><?= !empty($k['tanggal_selesai']) ? ' - ' . date('d M Y', strtotime($k['tanggal_selesai'])) : '' ?></small>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
          <?php if (empty($kalender)): ?><p class="text-center text-muted">Kalender akademik belum tersedia.</p><?php endif; ?>
        </div>
      </div>

      <div class="tab-pane fade" id="tabPrestasi">
        <div class="row g-3">
          <?php foreach ($prestasi as $p): ?>
          <div class="col-md-4" data-aos="fade-up">
            <div class="card h-100 border-0 shadow-sm">
              <img src="<?= htmlspecialchars($this->appConfig['base_url'] . '/' . ($p['foto'] ?: '')) ?>" onerror="this.src='https://placehold.co/400x220'" class="card-img-top" style="height:160px;object-fit:cover;">
              <div class="card-body">
                <span class="badge bg-warning text-dark mb-2"><?= htmlspecialchars($p['tingkat'] ?? '-') ?> &middot; <?= htmlspecialchars((string) $p['tahun']) ?></span>
                <h6 class="fw-bold"><?= htmlspecialchars($p['judul']) ?></h6>
                <p class="small text-muted mb-0"><?= htmlspecialchars($p['deskripsi']) ?></p>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
          <?php if (empty($prestasi)): ?><p class="text-center text-muted">Belum ada prestasi tercatat.</p><?php endif; ?>
        </div>
      </div>

      <div class="tab-pane fade" id="tabFasilitas">
        <div class="row g-3">
          <?php foreach ($fasilitas as $f): ?>
          <div class="col-md-3" data-aos="fade-up">
            <div class="glass-card text-center p-3 h-100">
              <img src="<?= htmlspecialchars($this->appConfig['base_url'] . '/' . ($f['foto'] ?: '')) ?>" onerror="this.src='https://placehold.co/150x100'" class="rounded-3 mb-2" style="height:100px;width:100%;object-fit:cover;">
              <h6 class="fw-bold mb-0"><?= htmlspecialchars($f['nama']) ?></h6>
            </div>
          </div>
          <?php endforeach; ?>
          <?php if (empty($fasilitas)): ?><p class="text-center text-muted">Belum ada data fasilitas.</p><?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</section>
