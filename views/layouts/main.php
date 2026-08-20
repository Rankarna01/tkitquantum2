<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($pengaturan['nama_sekolah'] ?? 'TK IT Quantum School') ?></title>
<meta name="description" content="<?= htmlspecialchars($pengaturan['tagline'] ?? '') ?>">
<?php if (!empty($pengaturan['favicon'])): ?>
<link rel="icon" href="<?= $this->appConfig['base_url'] ?>/<?= htmlspecialchars($pengaturan['favicon']) ?>">
<?php endif; ?>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@500;600;700;800&family=Quicksand:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/lightbox2@2.11.4/dist/css/lightbox.min.css">
<style>
  :root{
    --ink: #2B2A4A;
    --ink-soft: #5B5A78;
    --paper: #FFFDF7;
    --paper-alt: #EAF7FF;
    --gold: <?= htmlspecialchars($pengaturan['warna_primary'] ?? '#4FC3F7') ?>;      /* biru langit ceria */
    --gold-deep: <?= htmlspecialchars($pengaturan['warna_accent'] ?? '#FF6FA5') ?>;  /* pink hangat */
    --gold-pale: <?= htmlspecialchars($pengaturan['warna_secondary'] ?? '#FFF9E5') ?>;
    --sun: #FFD93D;   /* kuning matahari */
    --leaf: #6BD98F;  /* hijau daun */
    --line: rgba(43,42,74,.10);
  }
  html{scroll-behavior:smooth;}
  body{font-family:'Quicksand',sans-serif; font-weight:500; color:var(--ink); background:linear-gradient(160deg, #EAF7FF 0%, #FFF9E5 35%, #FFF0F6 70%, #F0FFF4 100%); background-attachment:fixed; overflow-x:hidden;}
  h1,h2,h3,h4,h5,h6,.font-display{font-family:'Baloo 2',sans-serif; letter-spacing:0;}
  section{padding:70px 0; scroll-margin-top:76px; position:relative;}
  section.bg-alt{background:linear-gradient(180deg, var(--paper-alt) 0%, #FFF9EC 100%);}

  .text-gradient-gold{background:linear-gradient(90deg, var(--gold-deep), var(--gold)); -webkit-background-clip:text; background-clip:text; color:transparent;}

  /* Dekorasi mengambang ala anak-anak: awan, bintang, balon */
  @keyframes floaty{ 0%,100%{transform:translateY(0) rotate(0deg);} 50%{transform:translateY(-18px) rotate(4deg);} }
  @keyframes floaty-slow{ 0%,100%{transform:translateY(0);} 50%{transform:translateY(-12px);} }
  @keyframes spin-slow{ from{transform:rotate(0deg);} to{transform:rotate(360deg);} }
  .blob{position:absolute; border-radius:50%; filter:blur(50px); opacity:.35; z-index:0; animation:floaty 9s ease-in-out infinite; pointer-events:none;}
  .blob-gold{background:radial-gradient(circle, var(--gold), transparent 70%);}
  .deco-icon{position:absolute; z-index:1; pointer-events:none; opacity:.85; animation:floaty 6s ease-in-out infinite;}
  .deco-icon.slow{animation:floaty-slow 8s ease-in-out infinite;}
  .deco-icon.spin{animation:spin-slow 14s linear infinite;}
  .deco-star{color:var(--sun); text-shadow:0 4px 10px rgba(0,0,0,.08);}
  .deco-balloon{color:var(--gold-deep);}
  .deco-cloud{color:#fff; filter:drop-shadow(0 6px 10px rgba(43,42,74,.08));}

  /* Overlay dekoratif tetap (moon, stars, astronaut, dll) — tampil di semua halaman */
  .site-deco{position:fixed; inset:0; z-index:0; pointer-events:none; overflow:hidden;}
  .site-deco .deco-item{position:absolute; opacity:.28; filter:drop-shadow(0 4px 8px rgba(43,42,74,.12)); animation:floaty 7s ease-in-out infinite;}
  .deco-swim{animation:swim 11s ease-in-out infinite !important;}
  .deco-jump{animation:jump 3.6s ease-in-out infinite !important;}
  @keyframes swim{ 0%,100%{transform:translateX(0) rotate(0deg);} 50%{transform:translateX(50px) rotate(10deg);} }
  @keyframes jump{ 0%,55%,100%{transform:translateY(0) rotate(0deg) scale(1);} 25%{transform:translateY(-36px) rotate(-8deg) scale(1.12);} }
  @media (max-width:768px){ .site-deco{display:none;} }

  /* Navbar */
  .navbar{background:#fff!important; box-shadow:0 3px 0 var(--gold-pale); padding:12px 0;}
  .navbar-brand{font-family:'Baloo 2',sans-serif; font-size:1.35rem;}
  .navbar .nav-link{color:var(--ink); font-weight:600; position:relative;}
  .nav-link-stacked{display:flex; flex-direction:column; align-items:center; line-height:1.15; gap:1px;}
  .nav-link-stacked-icon{font-size:1.1rem;}
  .nav-link-stacked-text{font-size:.72rem; white-space:nowrap;}
  .navbar .nav-link::after{content:''; position:absolute; left:12px; right:12px; bottom:2px; height:3px; border-radius:3px; background:linear-gradient(90deg,var(--gold),var(--gold-deep)); transform:scaleX(0); transform-origin:left; transition:transform .25s;}
  .navbar .nav-link:hover{color:var(--gold-deep);}
  .navbar .nav-link:hover::after{transform:scaleX(1);}

  /* Buttons */
  .btn-accent{background:linear-gradient(135deg, var(--gold), var(--gold-deep)); background-size:160% 160%; background-position:0% 50%; color:#fff; border:none; font-weight:700; border-radius:999px; transition:background-position .4s, transform .2s, box-shadow .2s;}
  .btn-accent:hover{background-position:100% 50%; color:#fff; transform:translateY(-2px) scale(1.03); box-shadow:0 10px 24px rgba(255,111,165,.35);}
  .btn-ghost-light{border:2px solid rgba(255,255,255,.85); color:#fff; font-weight:700; border-radius:999px; transition:.2s;}
  .btn-ghost-light:hover{background:#fff; color:var(--ink); transform:translateY(-2px);}

  /* Hero carousel — ceria, sudut membulat */
  .hero-carousel{background:var(--ink); overflow:hidden;}
  .hero-carousel .carousel-item{height:520px;}
  .hero-carousel .carousel-item img{width:100%; height:520px; object-fit:cover; filter:brightness(.8) saturate(1.15); transform:scale(1.03); animation:kenburns 9s ease-in-out infinite alternate;}
  @keyframes kenburns{ from{transform:scale(1.0);} to{transform:scale(1.08);} }
  .hero-carousel::after{content:''; position:absolute; inset:0; background:linear-gradient(180deg, rgba(43,42,74,.05) 0%, rgba(43,42,74,.55) 100%); pointer-events:none;}
  .hero-carousel::before{content:''; position:absolute; left:0; right:0; bottom:-2px; height:60px; background:var(--paper); border-radius:50% 50% 0 0 / 100% 100% 0 0; transform:scaleX(1.4); z-index:4;}
  .hero-caption{position:absolute; left:6%; bottom:56px; max-width:460px; background:rgba(255,253,248,.98); border-radius:24px; padding:28px 30px; box-shadow:0 20px 45px rgba(0,0,0,.25); z-index:5; border-top:5px solid var(--gold-deep);}
  .hero-caption .hero-eyebrow{letter-spacing:.06em; text-transform:uppercase; font-size:.75rem; font-weight:700; color:var(--gold-deep);}
  @media (max-width:768px){ .hero-carousel .carousel-item, .hero-carousel .carousel-item img{height:420px;} .hero-caption{position:static; margin-top:-56px; max-width:100%;} }

  /* Stat bar */
  .stat-pill{background:#fff; border:2px dashed var(--gold-pale); border-radius:20px; box-shadow:0 4px 14px rgba(43,42,74,.06); transition:.25s;}
  .stat-pill:hover{transform:translateY(-4px) rotate(-1deg); box-shadow:0 12px 26px rgba(43,42,74,.1); border-color:var(--gold);}
  .stat-num{font-family:'Baloo 2',sans-serif; font-size:2rem; font-weight:700; background:linear-gradient(90deg, var(--gold-deep), var(--gold)); -webkit-background-clip:text; background-clip:text; color:transparent;}

  /* Cards */
  .glass-card{background:#fff; border:1px solid var(--line); border-radius:22px; box-shadow:0 6px 20px rgba(43,42,74,.06); transition:.3s;}
  .glass-card:hover{box-shadow:0 16px 36px rgba(255,111,165,.18); transform:translateY(-4px) rotate(-0.5deg); border-color:rgba(79,195,247,.4);}
  .badge-gold{background:var(--gold-pale); color:var(--gold-deep); font-weight:700; border-radius:999px;}
  .section-eyebrow{color:var(--gold-deep); font-weight:700; letter-spacing:.06em; text-transform:uppercase; font-size:.8rem;}
  .card{border-radius:22px; overflow:hidden;}

  footer{background:linear-gradient(180deg, var(--ink), #201f3a); color:rgba(255,253,248,.8);}
  footer h5, footer h6{color:#fff; font-family:'Baloo 2',sans-serif;}
  footer a{color:rgba(255,253,248,.8); transition:.2s;}
  footer a:hover{color:var(--sun); transform:translateY(-2px); display:inline-block;}

  /* Panel PPDB terpadu — narasi & banner menyatu dalam satu kartu */
  .ppdb-panel{display:flex; flex-wrap:wrap; align-items:center; background:linear-gradient(135deg, var(--ink), #2E2C55); border-radius:26px; overflow:hidden; box-shadow:0 20px 45px rgba(43,42,74,.25);}
  .ppdb-panel-text{flex:1 1 380px; padding:2.6rem 2.2rem; display:flex; flex-direction:column; justify-content:center;}
  .ppdb-panel-banner{flex:1 1 380px; position:relative; display:flex; align-items:center; justify-content:center; padding:1.2rem; background:#12112a; align-self:stretch;}
  .ppdb-panel-banner img{width:100%; height:auto; max-height:640px; object-fit:contain; display:block; border-radius:10px;}
  .ppdb-panel-tag{position:absolute; top:16px; right:16px; background:var(--gold-deep); color:#fff; font-weight:700; font-size:.75rem; padding:6px 14px; border-radius:999px; box-shadow:0 6px 14px rgba(0,0,0,.2); z-index:2;}
  @media (max-width:767px){ .ppdb-panel-banner{min-height:260px;} }
  .ppdb-panel-full{border-radius:0;}
  @media (min-width:768px){ .ppdb-panel-full{border-radius:26px;} }
  .ppdb-panel-full .ppdb-panel-text{padding:3rem 3rem;}
  .ppdb-panel-full .ppdb-panel-banner img{max-height:720px;}
  .ppdb-panel-year-badge{font-size:1rem !important; padding:.5rem 1.1rem !important; letter-spacing:.03em;}

  /* Tombol musik latar */
  .music-toggle{position:fixed; bottom:22px; right:22px; z-index:1050; width:52px; height:52px; border-radius:50%; background:linear-gradient(135deg,var(--gold),var(--gold-deep)); color:#fff; border:none; box-shadow:0 8px 20px rgba(43,42,74,.25); display:flex; align-items:center; justify-content:center; font-size:1.2rem; animation:floaty 3s ease-in-out infinite;}
  .music-toggle:hover{transform:scale(1.08);}

  /* Teks berjalan (islami) */
  .marquee-wrap{background:linear-gradient(90deg, var(--gold-deep), var(--gold), var(--gold-deep)); background-size:200% 100%; animation:gradientShift 6s ease infinite; overflow:hidden; white-space:nowrap; padding:11px 0; position:relative; z-index:2; box-shadow:0 2px 10px rgba(43,42,74,.15);}
  @keyframes gradientShift{ 0%,100%{background-position:0% 50%;} 50%{background-position:100% 50%;} }
  .marquee-track{display:inline-flex; align-items:center; animation:marquee 34s linear infinite;}
  .marquee-item{display:inline-flex; align-items:center; color:#fff; font-weight:700; font-family:'Baloo 2',sans-serif; font-size:1rem; padding:0 2.5rem; flex-shrink:0; text-shadow:0 1px 3px rgba(0,0,0,.15);}
  .marquee-item .marquee-sep{opacity:.6; margin:0 2.5rem 0 0; font-size:.8rem;}
  @keyframes marquee{ from{transform:translateX(0);} to{transform:translateX(-50%);} }
  .marquee-wrap:hover .marquee-track{animation-play-state:paused;}
</style>
</head>
<body>

<?php
  try {
    $tampilMenuPermainan = (new PermainanPengaturanModel())->get()['tampil_menu'] ?? 'ya';
  } catch (PDOException $e) {
    $tampilMenuPermainan = 'ya'; // fallback aman jika migrasi belum dijalankan
  }
?>
<nav class="navbar navbar-expand-lg sticky-top" id="mainNavbar">
  <div class="container">
    <a class="navbar-brand fw-bold font-display d-flex align-items-center gap-2" href="<?= $this->appConfig['base_url'] ?>/">
      <?php if (!empty($pengaturan['logo_navbar'])): ?>
        <img src="<?= $this->appConfig['base_url'] ?>/<?= htmlspecialchars($pengaturan['logo_navbar']) ?>" height="40" style="border-radius:50%;">
      <?php else: ?>
        <span style="font-size:1.5rem;">🌈</span>
      <?php endif; ?>
      <?= htmlspecialchars($pengaturan['nama_sekolah'] ?? 'TK IT Quantum School') ?>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1">
        <li class="nav-item"><a class="nav-link px-3" href="<?= $this->appConfig['base_url'] ?>/">🏠 Beranda</a></li>
        <li class="nav-item"><a class="nav-link px-3" href="<?= $this->appConfig['base_url'] ?>/profil">📖 Profil</a></li>
        <li class="nav-item"><a class="nav-link px-3" href="<?= $this->appConfig['base_url'] ?>/guru">🧑‍🏫 Guru</a></li>
        <li class="nav-item"><a class="nav-link px-3" href="<?= $this->appConfig['base_url'] ?>/akademik">🎨 Kegiatan</a></li>
        <li class="nav-item"><a class="nav-link px-3" href="<?= $this->appConfig['base_url'] ?>/#galeri">📸 Galeri</a></li>
        <li class="nav-item"><a class="nav-link px-3" href="<?= $this->appConfig['base_url'] ?>/kontak">✉️ Kontak</a></li>
        <?php if ($tampilMenuPermainan === 'ya'): ?>
        <li class="nav-item"><a class="nav-link px-3 nav-link-stacked" href="<?= $this->appConfig['base_url'] ?>/permainan"><span class="nav-link-stacked-icon">🎮</span><span class="nav-link-stacked-text">Main Yuk!</span></a></li>
        <?php endif; ?>
        <li class="nav-item ms-lg-2"><a class="btn btn-accent px-4 rounded-pill" href="<?= $this->appConfig['base_url'] ?>/ppdb">✨ Daftar PPDB</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="site-deco" aria-hidden="true">
  <span class="deco-item" style="top:8%; left:3%; font-size:2.2rem; animation-delay:0s;">🌙</span>
  <span class="deco-item" style="top:22%; right:4%; font-size:1.7rem; animation-delay:.6s;">⭐</span>
  <span class="deco-item" style="top:45%; left:2%; font-size:2rem; animation-delay:1.2s;">🚀</span>
  <span class="deco-item" style="top:65%; right:3%; font-size:2.1rem; animation-delay:1.8s;">👨‍🚀</span>
  <span class="deco-item" style="top:80%; left:4%; font-size:1.9rem; animation-delay:2.4s;">👮</span>
  <span class="deco-item" style="top:35%; right:2%; font-size:1.9rem; animation-delay:.3s;">⚕️</span>
  <span class="deco-item" style="top:92%; right:6%; font-size:1.6rem; animation-delay:1.5s;">✨</span>

  <!-- Lumba-lumba, paus & baby shark berenang/melompat -- termasuk di tengah layar -->
  <span class="deco-item deco-swim" style="top:12%; left:40%; font-size:2.3rem; animation-delay:.4s;">🐬</span>
  <span class="deco-item deco-jump" style="top:50%; left:48%; font-size:2.6rem; animation-delay:1s;">🐬</span>
  <span class="deco-item deco-swim" style="top:75%; left:55%; font-size:2.4rem; animation-delay:2s; filter:hue-rotate(200deg) drop-shadow(0 4px 8px rgba(43,42,74,.12));">🐳</span>
  <span class="deco-item deco-jump" style="top:30%; right:30%; font-size:2.2rem; animation-delay:.7s;">🐳</span>
  <span class="deco-item deco-swim" style="top:60%; left:25%; font-size:2rem; animation-delay:1.4s; filter:hue-rotate(320deg) saturate(1.6) drop-shadow(0 4px 8px rgba(43,42,74,.12));">🦈</span>
  <span class="deco-item deco-swim" style="top:18%; right:42%; font-size:1.9rem; animation-delay:2.1s; filter:hue-rotate(90deg) saturate(1.8) drop-shadow(0 4px 8px rgba(43,42,74,.12));">🦈</span>
  <span class="deco-item deco-jump" style="top:85%; left:45%; font-size:1.8rem; animation-delay:1.8s; filter:hue-rotate(180deg) saturate(1.7) drop-shadow(0 4px 8px rgba(43,42,74,.12));">🦈</span>
  <span class="deco-item deco-swim" style="top:40%; left:8%; font-size:1.7rem; animation-delay:.9s; filter:hue-rotate(260deg) saturate(1.5) drop-shadow(0 4px 8px rgba(43,42,74,.12));">🦈</span>
</div>

<?php if (!empty($_SESSION['flash_success'])): ?>
  <div class="container mt-3 position-relative" style="z-index:2;">
    <div class="alert alert-success rounded-4 shadow-sm"><?= htmlspecialchars($_SESSION['flash_success']) ?></div>
  </div>
  <?php unset($_SESSION['flash_success']); ?>
<?php endif; ?>
<?php if (!empty($_SESSION['flash_error'])): ?>
  <div class="container mt-3 position-relative" style="z-index:2;">
    <div class="alert alert-danger rounded-4 shadow-sm"><?= htmlspecialchars($_SESSION['flash_error']) ?></div>
  </div>
  <?php unset($_SESSION['flash_error']); ?>
<?php endif; ?>

<?= $content ?>

<?php
  $mapsUrl = $pengaturan['maps_embed'] ?? '';
  $isValidMaps = $mapsUrl && preg_match('#^https://www\.google\.com/maps/embed#', $mapsUrl);
  $mapsLinkOut = null;
  if (!empty($pengaturan['alamat'])) {
    $mapsLinkOut = 'https://www.google.com/maps/search/?api=1&query=' . urlencode($pengaturan['alamat']);
  }
  if (!$isValidMaps && !empty($pengaturan['alamat'])) {
    // Fallback otomatis: embed peta langsung dari alamat sekolah (tanpa perlu API key)
    $mapsUrl = 'https://www.google.com/maps?q=' . urlencode($pengaturan['alamat']) . '&output=embed';
    $isValidMaps = true;
  }
  $base = $this->appConfig['base_url'];
?>
<footer class="py-5 mt-5" id="lokasi">
  <div class="container">
    <div class="row g-4">
      <div class="col-md-3">
        <h5 class="fw-bold font-display d-flex align-items-center gap-2">
          <?php if (!empty($pengaturan['logo_footer'])): ?>
            <img src="<?= $base ?>/<?= htmlspecialchars($pengaturan['logo_footer']) ?>" height="32">
          <?php endif; ?>
          <?= htmlspecialchars($pengaturan['nama_sekolah'] ?? 'TK IT Quantum School') ?>
        </h5>
        <p class="small mb-2"><?= htmlspecialchars($pengaturan['tagline'] ?? '') ?></p>
        <p class="small mb-1"><i class="fa-solid fa-location-dot me-2"></i><?= htmlspecialchars($pengaturan['alamat'] ?? '') ?></p>
        <p class="small mb-1"><i class="fa-solid fa-phone me-2"></i><?= htmlspecialchars($pengaturan['telepon'] ?? '') ?></p>
        <p class="small mb-0"><i class="fa-solid fa-envelope me-2"></i><?= htmlspecialchars($pengaturan['email'] ?? '') ?></p>
      </div>

      <div class="col-6 col-md-3">
        <h6>Menu</h6>
        <ul class="list-unstyled small">
          <li class="mb-2"><a href="<?= $base ?>/profil">Profil</a></li>
          <li class="mb-2"><a href="<?= $base ?>/akademik">Kegiatan</a></li>
          <li class="mb-2"><a href="<?= $base ?>/ppdb">PPDB Online</a></li>
          <li class="mb-2"><a href="<?= $base ?>/#berita">Berita</a></li>
        </ul>
      </div>

      <div class="col-6 col-md-3">
        <h6>Lainnya</h6>
        <ul class="list-unstyled small">
          <li class="mb-2"><a href="<?= $base ?>/#galeri">Galeri</a></li>
          <li class="mb-2"><a href="<?= $base ?>/guru">Guru</a></li>
          <li class="mb-2"><a href="<?= $base ?>/kontak">Kontak</a></li>
          <?php if ($tampilMenuPermainan === 'ya'): ?>
          <li class="mb-2"><a href="<?= $base ?>/permainan">🎮 Main Yuk!</a></li>
          <?php endif; ?>
          <li class="mb-2"><a href="<?= htmlspecialchars($pengaturan['instagram'] ?? 'https://www.instagram.com/tkitquantum.school/') ?>" target="_blank">Instagram 🎈</a></li>
        </ul>
      </div>

      <div class="col-md-3">
        <h6 class="mb-2">Lokasi Kami</h6>
        <?php if ($isValidMaps): ?>
        <div class="rounded-3 overflow-hidden mb-2" style="height:120px; filter:grayscale(.1);">
          <iframe src="<?= htmlspecialchars($mapsUrl) ?>" style="border:0;width:100%;height:100%;" loading="lazy"></iframe>
        </div>
        <?php if ($mapsLinkOut): ?>
        <a href="<?= htmlspecialchars($mapsLinkOut) ?>" target="_blank" class="small text-decoration-none">
          Buka di Google Maps <i class="fa-solid fa-up-right-from-square ms-1"></i>
        </a>
        <?php endif; ?>
        <?php else: ?>
          <p class="small mb-0">Peta lokasi belum diatur oleh admin.</p>
        <?php endif; ?>
      </div>
    </div>

    <hr style="border-color:rgba(255,255,255,.12);">
    <p class="text-center small mb-0">&copy; <?= date('Y') ?> <?= htmlspecialchars($pengaturan['nama_sekolah'] ?? 'TK IT Quantum School') ?>. Seluruh hak cipta dilindungi.</p>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script src="https://cdn.jsdelivr.net/npm/lightbox2@2.11.4/dist/js/lightbox.min.js"></script>
<script>
  AOS.init({ duration: 600, once: true, offset: 120 });

  // Animasi hitung angka statistik saat elemen masuk viewport
  const counters = document.querySelectorAll('[data-count]');
  const counterObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (!entry.isIntersecting) return;
      const el = entry.target;
      const target = parseInt(el.dataset.count, 10);
      const suffix = el.dataset.suffix || '';
      const duration = 1200;
      const start = performance.now();
      function tick(now){
        const progress = Math.min((now - start) / duration, 1);
        const eased = 1 - Math.pow(1 - progress, 3);
        el.textContent = Math.round(eased * target) + suffix;
        if (progress < 1) requestAnimationFrame(tick);
      }
      requestAnimationFrame(tick);
      counterObserver.unobserve(el);
    });
  }, { threshold: 0.4 });
  counters.forEach(el => counterObserver.observe(el));
</script>
<?php if (!empty($pengaturan['musik_latar']) && ($pengaturan['musik_aktif'] ?? 'tidak') === 'ya'): ?>
<audio id="bgMusic" loop preload="auto">
  <source src="<?= $this->appConfig['base_url'] ?>/<?= htmlspecialchars($pengaturan['musik_latar']) ?>">
</audio>
<button id="musicToggle" class="music-toggle" title="Putar musik latar" aria-label="Putar musik latar">
  <i class="fa-solid fa-music"></i>
</button>
<script>
(function () {
  var audio = document.getElementById('bgMusic');
  var btn = document.getElementById('musicToggle');
  var playing = false;

  function updateIcon() {
    btn.innerHTML = playing ? '<i class="fa-solid fa-volume-high"></i>' : '<i class="fa-solid fa-music"></i>';
  }

  // Coba autoplay senyap dulu (kebijakan browser mengizinkan autoplay tanpa suara)
  audio.muted = true;
  audio.play().catch(function () {});

  btn.addEventListener('click', function () {
    audio.muted = false;
    if (!playing) {
      audio.play().then(function () { playing = true; updateIcon(); }).catch(function () {});
    } else {
      audio.pause();
      playing = false;
      updateIcon();
    }
  });
})();
</script>
<?php endif; ?>
</body>
</html>
