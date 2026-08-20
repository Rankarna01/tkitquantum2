<?php if (!empty($islamiList)): ?>
<div class="marquee-wrap">
  <div class="marquee-track">
    <?php
      // duplikat 2x agar loop terlihat menyambung mulus
      for ($rep = 0; $rep < 2; $rep++):
        foreach ($islamiList as $ii):
    ?>
      <span class="marquee-item">
        <i class="fa-solid fa-moon me-2"></i><?= htmlspecialchars($ii['teks']) ?><?= !empty($ii['sumber']) ? ' <em style="font-style:normal;opacity:.85;">— ' . htmlspecialchars($ii['sumber']) . '</em>' : '' ?>
        <span class="marquee-sep">✦</span>
      </span>
    <?php endforeach; endfor; ?>
  </div>
</div>
<?php endif; ?>

<section class="hero-carousel position-relative" id="beranda">
  <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="4500">
    <div class="carousel-inner">
      <?php if (!empty($heroSlides)): ?>
        <?php foreach ($heroSlides as $i => $hs): ?>
        <div class="carousel-item <?= $i === 0 ? 'active' : '' ?>">
          <img src="<?= $this->appConfig['base_url'] ?>/<?= htmlspecialchars($hs['gambar']) ?>" alt="<?= htmlspecialchars($hs['judul'] ?: 'TK IT Quantum School') ?>">
        </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="carousel-item active">
          <img src="https://images.unsplash.com/photo-1587616211892-b407747f8a9b?w=1600&q=80" alt="Anak-anak TK bermain bersama">
        </div>
        <div class="carousel-item">
          <img src="https://images.unsplash.com/photo-1503454537195-1dcabb73ffb9?w=1600&q=80" alt="Kegiatan belajar sambil bermain">
        </div>
        <div class="carousel-item">
          <img src="https://images.unsplash.com/photo-1526634332515-d56c5fd16991?w=1600&q=80" alt="Anak-anak TK ceria di kelas">
        </div>
        <div class="carousel-item">
          <img src="https://images.unsplash.com/photo-1544776193-352d25ca82cd?w=1600&q=80" alt="Kegiatan kreativitas anak">
        </div>
      <?php endif; ?>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
      <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
      <span class="carousel-control-next-icon"></span>
    </button>
  </div>

  <div class="hero-caption" data-aos="fade-up">
    <p class="hero-eyebrow mb-2">🎒 TK Islam Terpadu • Medan Krio</p>
    <h1 class="fw-bold font-display mb-2" style="font-size:1.9rem;">Bahagia Bermain, Bahagia Belajar! 🌟</h1>
    <p class="text-muted small mb-3">TK IT Quantum School menghadirkan suasana belajar yang ceria, aman, dan penuh kasih sayang untuk tumbuh kembang si kecil.</p>
    <div class="d-flex flex-wrap gap-2">
      <a href="<?= $this->appConfig['base_url'] ?>/ppdb" class="btn btn-accent btn-sm px-3 rounded-pill">✨ Daftar PPDB</a>
      <a href="<?= $this->appConfig['base_url'] ?>/profil" class="btn btn-outline-dark btn-sm px-3 rounded-pill">📖 Lihat Profil</a>
    </div>
  </div>
</section>

<section class="py-5 position-relative overflow-hidden">
  <div class="blob blob-gold" style="width:260px;height:260px;top:-80px;right:5%;"></div>
  <div class="container position-relative">
    <div class="row g-3 text-center">
      <div class="col-6 col-md-3"><div class="stat-pill p-3" data-aos="fade-up"><div class="stat-num" data-count="120" data-suffix="+">0</div><small class="text-muted">👦👧 Siswa</small></div></div>
      <div class="col-6 col-md-3"><div class="stat-pill p-3" data-aos="fade-up" data-aos-delay="50"><div class="stat-num" data-count="12">0</div><small class="text-muted">🧑‍🏫 Guru</small></div></div>
      <div class="col-6 col-md-3"><div class="stat-pill p-3" data-aos="fade-up" data-aos-delay="100"><div class="stat-num" data-count="6">0</div><small class="text-muted">🧑‍💼 Tendik</small></div></div>
      <div class="col-6 col-md-3"><div class="stat-pill p-3" data-aos="fade-up" data-aos-delay="200"><div class="stat-num" data-count="16">0</div><small class="text-muted">🏆 Prestasi</small></div></div>
    </div>
  </div>
</section>

<section id="galeri" class="bg-alt">
  <div class="container">
    <h2 class="fw-bold font-display text-center mb-2" data-aos="fade-up">📸 Galeri Kegiatan Siswa</h2>
    <p class="text-center text-muted mb-5">Momen keseruan si kecil belajar dan bermain setiap hari</p>

    <h5 class="fw-bold mb-3" data-aos="fade-up">🖼️ Foto</h5>
    <div class="row g-3 mb-5">
      <?php foreach ($galeriFoto as $g): ?>
      <div class="col-6 col-md-3" data-aos="zoom-in">
        <a href="<?= $this->appConfig['base_url'] ?>/<?= htmlspecialchars($g['file']) ?>" data-lightbox="galeri">
          <img src="<?= $this->appConfig['base_url'] ?>/<?= htmlspecialchars($g['file']) ?>" class="img-fluid rounded-3" style="height:150px;width:100%;object-fit:cover;">
        </a>
      </div>
      <?php endforeach; ?>
      <?php if (empty($galeriFoto)): ?><p class="text-center text-muted">Belum ada foto.</p><?php endif; ?>
    </div>

    <h5 class="fw-bold mb-3" data-aos="fade-up">🎬 Video</h5>
    <div class="row g-3">
      <?php foreach ($galeriVideo as $v): ?>
      <?php
        $embed = null;
        if (preg_match('#(?:youtu\.be/|youtube\.com/(?:watch\?v=|embed/|shorts/))([A-Za-z0-9_-]{6,})#', $v['url_youtube'], $m)) {
          $embed = 'https://www.youtube.com/embed/' . $m[1];
        }
      ?>
      <div class="col-md-6" data-aos="fade-up">
        <div class="glass-card p-2 h-100">
          <?php if ($embed): ?>
            <div class="ratio ratio-16x9 rounded-3 overflow-hidden">
              <iframe src="<?= htmlspecialchars($embed) ?>" allowfullscreen loading="lazy" style="border:0;"></iframe>
            </div>
          <?php elseif (!empty($v['thumbnail'])): ?>
            <a href="<?= htmlspecialchars($v['url_youtube']) ?>" target="_blank" class="d-block position-relative rounded-3 overflow-hidden text-decoration-none">
              <img src="<?= $this->appConfig['base_url'] ?>/<?= htmlspecialchars($v['thumbnail']) ?>" class="w-100" style="height:180px;object-fit:cover;">
              <span class="position-absolute top-50 start-50 translate-middle d-flex align-items-center justify-content-center rounded-circle" style="width:52px;height:52px;background:rgba(255,255,255,.92);">
                <i class="fa-solid fa-play text-gradient-gold" style="color:var(--gold-deep);"></i>
              </span>
              <span class="position-absolute bottom-0 end-0 m-2 badge badge-gold"><i class="fa-brands <?= match($v['platform'] ?? 'YouTube') { 'Instagram' => 'fa-instagram', 'TikTok' => 'fa-tiktok', 'Facebook' => 'fa-facebook', default => 'fa-youtube' } ?> me-1"></i><?= htmlspecialchars($v['platform'] ?? 'YouTube') ?></span>
            </a>
          <?php else: ?>
            <a href="<?= htmlspecialchars($v['url_youtube']) ?>" target="_blank" class="d-flex align-items-center gap-3 p-3 text-decoration-none">
              <i class="fa-brands <?= match($v['platform'] ?? 'YouTube') { 'Instagram' => 'fa-instagram', 'TikTok' => 'fa-tiktok', 'Facebook' => 'fa-facebook', default => 'fa-youtube' } ?> fa-2x text-gradient-gold"></i>
              <span class="fw-bold small text-dark">Tonton di <?= htmlspecialchars($v['platform'] ?? 'YouTube') ?> ↗</span>
            </a>
          <?php endif; ?>
          <p class="small fw-bold px-2 pt-2 mb-1"><?= htmlspecialchars($v['judul']) ?> <span class="badge badge-gold ms-1"><?= htmlspecialchars($v['platform'] ?? 'YouTube') ?></span></p>
        </div>
      </div>
      <?php endforeach; ?>
      <?php if (empty($galeriVideo)): ?><p class="text-center text-muted">Belum ada video.</p><?php endif; ?>
    </div>
  </div>
</section>

<section id="berita">
  <div class="container">
    <h2 class="fw-bold font-display text-center mb-5" data-aos="fade-up">📰 Berita Terbaru</h2>
    <div class="row g-4">
      <?php foreach ($beritaTerbaru as $b): ?>
      <div class="col-md-4" data-aos="fade-up">
        <div class="card h-100 border-0 shadow-sm">
          <img src="<?= !empty($b['thumbnail']) ? $this->appConfig['base_url'] . '/' . htmlspecialchars($b['thumbnail']) : 'https://placehold.co/400x220' ?>" class="card-img-top" style="height:200px;object-fit:cover;">
          <div class="card-body">
            <small class="text-muted d-block mb-1"><i class="fa-solid fa-calendar-days me-1"></i><?= !empty($b['tanggal_publish']) ? date('d M Y', strtotime($b['tanggal_publish'])) : date('d M Y', strtotime($b['created_at'])) ?></small>
            <h5 class="card-title"><?= htmlspecialchars($b['judul']) ?></h5>
            <p class="card-text small text-muted"><?= htmlspecialchars($b['ringkasan']) ?></p>
            <a href="<?= $this->appConfig['base_url'] ?>/home/berita/<?= htmlspecialchars($b['slug']) ?>" class="btn btn-sm btn-accent">Baca Selengkapnya</a>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
      <?php if (empty($beritaTerbaru)): ?>
        <p class="text-center text-muted">Belum ada berita.</p>
      <?php endif; ?>
    </div>
  </div>
</section>

<section id="prestasi" class="bg-alt position-relative overflow-hidden">
  <div class="blob blob-gold" style="width:320px;height:320px;bottom:-100px;left:-60px;"></div>
  <div class="container position-relative">
    <p class="section-eyebrow text-center mb-2" data-aos="fade-up">Pencapaian</p>
    <h2 class="fw-bold font-display text-center mb-5" data-aos="fade-up">🏆 Prestasi <span class="text-gradient-gold">Sekolah</span></h2>
    <div class="row g-3">
      <?php foreach ($prestasiTerbaru as $p): ?>
      <div class="col-6 col-md-3" data-aos="fade-up">
        <div class="glass-card text-center p-3 h-100">
          <i class="fa-solid fa-trophy text-warning fa-2x mb-2"></i>
          <h6 class="fw-bold small mb-1"><?= htmlspecialchars($p['judul']) ?></h6>
          <small class="text-muted"><?= htmlspecialchars(($p['tingkat'] ?? '-') . ' • ' . $p['tahun']) ?></small>
        </div>
      </div>
      <?php endforeach; ?>
      <?php if (empty($prestasiTerbaru)): ?><p class="text-center text-muted">Belum ada prestasi tercatat.</p><?php endif; ?>
    </div>
  </div>
</section>

<section id="ekskul">
  <div class="container">
    <p class="section-eyebrow text-center mb-1" data-aos="fade-up">TEAM PENGAJAR</p>
    <h2 class="fw-bold font-display text-center mb-5" data-aos="fade-up">🧑‍🏫 Guru yang Mengajar</h2>
    <div class="row g-3 justify-content-center">
      <?php foreach ($guruMengajar as $g): ?>
      <div class="col-6 col-md-4" data-aos="fade-up">
        <div class="glass-card text-center p-2 h-100">
          <img src="<?= htmlspecialchars($this->appConfig['base_url'] . '/' . ($g['foto'] ?? '')) ?>" onerror="this.src='https://placehold.co/150x150'" class="rounded-circle mb-2" style="width:120px;height:120px;object-fit:cover;">
          <h6 class="fw-bold small mb-0"><?= htmlspecialchars($g['nama_lengkap']) ?></h6>
          <small class="text-muted"><?= htmlspecialchars($g['jabatan'] ?: ($g['mata_pelajaran'] ?: 'Guru')) ?></small>
        </div>
      </div>
      <?php endforeach; ?>
      <?php if (empty($guruMengajar)): ?><p class="text-center text-muted">Belum ada data guru.</p><?php endif; ?>
    </div>
  </div>
</section>

<section id="fasilitas" class="bg-alt">
  <div class="container">
    <h2 class="fw-bold font-display text-center mb-5" data-aos="fade-up">🏫 Fasilitas Sekolah</h2>
    <div class="row g-3">
      <?php foreach ($fasilitasList as $f): ?>
      <div class="col-6 col-md-3" data-aos="fade-up">
        <div class="glass-card text-center p-3 h-100">
          <img src="<?= htmlspecialchars($this->appConfig['base_url'] . '/' . ($f['foto'] ?: '')) ?>" onerror="this.src='https://placehold.co/150x100'" class="rounded-3 mb-2" style="height:90px;width:100%;object-fit:cover;">
          <h6 class="fw-bold small mb-0"><?= htmlspecialchars($f['nama']) ?></h6>
        </div>
      </div>
      <?php endforeach; ?>
      <?php if (empty($fasilitasList)): ?><p class="text-center text-muted">Belum ada data fasilitas.</p><?php endif; ?>
    </div>
  </div>
</section>

<section id="pengumuman">
  <div class="container">
    <h2 class="fw-bold font-display text-center mb-5" data-aos="fade-up">📢 Pengumuman &amp; Agenda</h2>
    <div class="row g-3">
      <?php foreach ($pengumumanTerbaru as $p): ?>
      <div class="col-md-6" data-aos="fade-up">
        <div class="glass-card p-3 d-flex gap-3 align-items-start">
          <div class="text-center flex-shrink-0" style="min-width:58px;">
            <?php if (!empty($p['tanggal'])): ?>
              <div class="fw-bold text-warning small"><?= date('d M', strtotime($p['tanggal'])) ?></div>
              <div class="small text-muted"><?= date('Y', strtotime($p['tanggal'])) ?></div>
            <?php endif; ?>
          </div>
          <div>
            <h6 class="fw-bold mb-1">
              <?= $p['tipe'] === 'agenda' ? '🗓️' : '📢' ?> <?= htmlspecialchars($p['judul']) ?>
            </h6>
            <p class="small text-muted mb-0"><?= htmlspecialchars($p['isi_singkat']) ?></p>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
      <?php if (empty($pengumumanTerbaru)): ?><p class="text-center text-muted">Belum ada pengumuman.</p><?php endif; ?>
    </div>
  </div>
</section>

<?php if ($ppdb && ($ppdb['tampil_beranda'] ?? 'ya') === 'ya'): ?>
<section id="ppdb-brosur">
  <div class="container">
    <h2 class="fw-bold font-display text-center mb-2" data-aos="fade-up">✨ Penerimaan Peserta Didik Baru</h2>
    <p class="text-center text-muted mb-5">Yuk daftarkan si kecil, kuota terbatas!</p>
  </div>

  <div class="container-fluid px-0 px-md-4" style="max-width:1600px; margin:0 auto;">
    <div class="ppdb-panel ppdb-panel-full" data-aos="fade-up">
      <div class="ppdb-panel-text">
        <span class="badge badge-gold ppdb-panel-year-badge mb-2">📅 Tahun Pelajaran <?= htmlspecialchars($ppdb['tahun_ajaran']) ?></span>
        <h4 class="fw-bold font-display text-white mb-2"><?= $ppdb['status'] === 'aktif' ? '🎉 Pendaftaran Sedang Dibuka!' : 'Pendaftaran Belum Dibuka' ?></h4>
        <p class="small text-white-50"><?= nl2br(htmlspecialchars($ppdb['informasi'] ?? '')) ?></p>
        <?php if (!empty($ppdb['promo_nama']) && (float)($ppdb['promo_potongan'] ?? 0) > 0): ?>
        <div class="alert alert-warning small py-2 mb-2">🏷️ Promo <strong><?= htmlspecialchars($ppdb['promo_nama']) ?></strong> — potongan Rp<?= number_format((float)$ppdb['promo_potongan'], 0, ',', '.') ?>!</div>
        <?php endif; ?>
        <p class="small text-white-50 mb-1"><i class="fa-solid fa-money-bill-wave text-warning me-1"></i> Biaya Pendaftaran: <strong class="text-white">Rp<?= number_format((float)($ppdb['biaya_pendaftaran'] ?? 0), 0, ',', '.') ?></strong></p>
        <p class="small text-white-50 mb-3"><i class="fa-solid fa-calendar text-warning me-1"></i> Periode: <?= !empty($ppdb['tanggal_mulai']) ? date('d M Y', strtotime($ppdb['tanggal_mulai'])) : '-' ?> s/d <?= !empty($ppdb['tanggal_selesai']) ? date('d M Y', strtotime($ppdb['tanggal_selesai'])) : '-' ?></p>
        <a href="<?= $this->appConfig['base_url'] ?>/ppdb" class="btn btn-accent rounded-pill px-4">✨ Daftar Sekarang</a>
      </div>
      <div class="ppdb-panel-banner">
        <img src="<?= !empty($ppdb['banner']) ? $this->appConfig['base_url'] . '/' . htmlspecialchars($ppdb['banner']) : 'https://images.unsplash.com/photo-1541692641319-981cc79ee10a?w=800&q=80' ?>">
        <?php if (!empty($ppdb['status']) && $ppdb['status'] === 'aktif'): ?>
        <span class="ppdb-panel-tag">✨ Kuota Terbatas!</span>
        <?php endif; ?>
      </div>
    </div>
    <?php if (!empty($ppdb['banner'])): ?>
    <div class="text-end mt-2">
      <a href="<?= $this->appConfig['base_url'] ?>/<?= htmlspecialchars($ppdb['banner']) ?>" target="_blank" class="small text-decoration-none">Lihat brosur ukuran penuh <i class="fa-solid fa-up-right-from-square ms-1"></i></a>
    </div>
    <?php endif; ?>
  </div>
</section>

<section class="py-5 position-relative overflow-hidden" style="background:linear-gradient(90deg,#FF9A6C,var(--gold-deep)); z-index:1;">
  <div class="container text-center position-relative">
    <h3 class="fw-bold font-display text-white mb-1" data-aos="fade-up">
      <?= htmlspecialchars($ppdb['cta_judul'] ?: 'Yuk, Daftarkan Si Kecil Sekarang!') ?> 🎈
    </h3>
    <p class="text-white-50 mb-3" data-aos="fade-up">
      <?= htmlspecialchars($ppdb['cta_subjudul'] ?: ('Kuota terbatas untuk Tahun Ajaran ' . ($ppdb['tahun_ajaran'] ?? ''))) ?>
    </p>
    <a href="<?= $this->appConfig['base_url'] ?>/ppdb" class="btn btn-light rounded-pill px-4 fw-bold" data-aos="fade-up">Daftar PPDB Online →</a>
  </div>
</section>
<?php endif; ?>

<?php if (!empty($testimoniList)): ?>
<section id="testimoni" class="bg-alt">
  <div class="container">
    <h2 class="fw-bold font-display text-center mb-5" data-aos="fade-up">💬 Kata Orang Tua Kami</h2>
    <div class="row g-3">
      <?php foreach ($testimoniList as $t): ?>
      <div class="col-md-4" data-aos="fade-up">
        <div class="glass-card p-4 h-100">
          <p class="small"><i class="fa-solid fa-quote-left text-warning me-1"></i> <?= htmlspecialchars($t['isi_testimoni']) ?></p>
          <div class="fw-bold small mt-3"><?= htmlspecialchars($t['nama']) ?></div>
          <small class="text-muted"><?= htmlspecialchars(($t['profesi'] ?? '') . ' • Angkatan ' . ($t['angkatan'] ?? '-')) ?></small>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>
