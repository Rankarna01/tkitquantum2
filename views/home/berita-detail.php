<?php
  // Gabungkan thumbnail utama + foto tambahan menjadi satu daftar galeri
  $semuaFoto = [];
  if (!empty($berita['thumbnail'])) {
    $semuaFoto[] = $berita['thumbnail'];
  }
  foreach ($galeriBerita as $gb) {
    $file = $gb['file'];
    // File tambahan disimpan relatif (uploads/berita/...), thumbnail lama mungkin path penuh — normalisasi ringan
    $semuaFoto[] = (strpos($file, 'http') === 0) ? $file : $this->appConfig['base_url'] . '/' . $file;
  }
  $semuaFoto = array_values(array_unique($semuaFoto));
?>
<section>
  <div class="container" style="max-width:800px;">
    <h1 class="fw-bold"><?= htmlspecialchars($berita['judul']) ?></h1>
    <p class="text-muted small">
      <i class="fa-solid fa-calendar-days me-1"></i>
      <?= !empty($berita['tanggal_publish']) ? date('d F Y', strtotime($berita['tanggal_publish'])) : date('d F Y', strtotime($berita['created_at'])) ?>
      &middot; <?= (int) $berita['dilihat'] ?> dilihat
    </p>

    <?php if (count($semuaFoto) >= 2): ?>
      <div id="beritaHeroCarousel" class="carousel slide rounded-4 overflow-hidden my-4" data-bs-ride="carousel">
        <div class="carousel-inner">
          <?php foreach ($semuaFoto as $i => $foto): ?>
          <div class="carousel-item <?= $i === 0 ? 'active' : '' ?>">
            <a href="<?= htmlspecialchars($foto) ?>" data-lightbox="berita-foto" data-title="<?= htmlspecialchars($berita['judul']) ?>">
              <img src="<?= htmlspecialchars($foto) ?>" style="width:100%;height:420px;object-fit:cover;">
            </a>
          </div>
          <?php endforeach; ?>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#beritaHeroCarousel" data-bs-slide="prev">
          <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#beritaHeroCarousel" data-bs-slide="next">
          <span class="carousel-control-next-icon"></span>
        </button>
        <div class="carousel-indicators">
          <?php foreach ($semuaFoto as $i => $foto): ?>
            <button type="button" data-bs-target="#beritaHeroCarousel" data-bs-slide-to="<?= $i ?>" class="<?= $i === 0 ? 'active' : '' ?>"></button>
          <?php endforeach; ?>
        </div>
      </div>
    <?php elseif (count($semuaFoto) === 1): ?>
      <a href="<?= htmlspecialchars($semuaFoto[0]) ?>" data-lightbox="berita-foto">
        <img src="<?= htmlspecialchars($semuaFoto[0]) ?>" class="img-fluid rounded-4 my-4">
      </a>
    <?php endif; ?>

    <div class="content"><?= $berita['isi'] /* Catatan: sanitasi output CKEditor dengan HTML Purifier di lingkungan produksi */ ?></div>
  </div>
</section>
