<section class="hero" style="min-height:60vh;" id="ppdb">
  <div class="container py-5">
    <div class="text-center" data-aos="fade-up">
      <h1 class="display-5 fw-bold">Penerimaan Peserta Didik Baru</h1>
      <?php if ($ppdb): ?>
        <p class="lead fw-bold">📅 Tahun Pelajaran <?= htmlspecialchars($ppdb['tahun_ajaran']) ?></p>

        <?php if ($kuotaPenuh): ?>
          <div class="alert alert-dark d-inline-block px-5 py-3 fw-bold mt-3">KUOTA TELAH PENUH</div>
        <?php else: ?>
          <div id="countdown" class="d-flex justify-content-center gap-3 my-4"></div>
          <p class="mb-1">Kuota terisi: <strong><?= $terdaftar ?></strong> / <?= (int) $ppdb['kuota'] ?></p>
          <div class="progress mx-auto mb-4" style="max-width:400px;height:10px;">
            <div class="progress-bar bg-warning" style="width:<?= min(100, round($terdaftar / max(1,$ppdb['kuota']) * 100)) ?>%"></div>
          </div>
          <a href="<?= $this->appConfig['base_url'] ?>/ppdb/daftar" class="btn btn-accent btn-lg px-5">Daftar Sekarang</a>
        <?php endif; ?>
        <div>
          <a href="<?= $this->appConfig['base_url'] ?>/ppdb/cekStatus" class="btn btn-outline-dark btn-sm mt-3">Cek Status Pendaftaran</a>
        </div>
      <?php else: ?>
        <div class="alert alert-dark d-inline-block px-5 py-3 fw-bold mt-3">PPDB TELAH DITUTUP</div>
      <?php endif; ?>
    </div>
  </div>
</section>

<?php if ($ppdb): ?>
<section>
  <div class="container">
    <?php if (!empty($ppdb['banner']) || !empty($ppdb['informasi'])): ?>
    <div class="ppdb-panel mb-2" data-aos="fade-up">
      <?php if (!empty($ppdb['banner'])): ?>
      <div class="ppdb-panel-banner" style="flex-basis:480px;">
        <img src="<?= $this->appConfig['base_url'] . '/' . htmlspecialchars($ppdb['banner']) ?>">
        <span class="ppdb-panel-tag">📌 Brosur Resmi</span>
      </div>
      <?php endif; ?>
      <div class="ppdb-panel-text">
        <span class="badge badge-gold ppdb-panel-year-badge mb-2 align-self-start">📅 Tahun Pelajaran <?= htmlspecialchars($ppdb['tahun_ajaran']) ?></span>
        <h5 class="fw-bold text-white mb-3"><i class="fa-solid fa-circle-info me-2"></i>Informasi Pendaftaran</h5>
        <p class="small text-white-50 mb-0"><?= nl2br(htmlspecialchars($ppdb['informasi'] ?? '')) ?></p>
      </div>
    </div>
    <?php if (!empty($ppdb['banner'])): ?>
    <div class="text-end mb-4">
      <a href="<?= $this->appConfig['base_url'] ?>/<?= htmlspecialchars($ppdb['banner']) ?>" target="_blank" class="small text-decoration-none">Lihat brosur ukuran penuh <i class="fa-solid fa-up-right-from-square ms-1"></i></a>
    </div>
    <?php endif; ?>
    <?php endif; ?>

    <div class="row g-4">
      <div class="col-md-6" data-aos="fade-up">
        <div class="glass-card p-4 h-100">
          <h5 class="fw-bold mb-3"><i class="fa-solid fa-money-bill-wave text-warning me-2"></i>Biaya &amp; Promo Pendaftaran</h5>
          <p class="small mb-2">Biaya Pendaftaran: <strong>Rp<?= number_format((float)($ppdb['biaya_pendaftaran'] ?? 0), 0, ',', '.') ?></strong></p>
          <?php if (!empty($ppdb['promo_nama']) && (float)($ppdb['promo_potongan'] ?? 0) > 0): ?>
          <div class="alert alert-warning small py-2 mb-0">🏷️ Promo <strong><?= htmlspecialchars($ppdb['promo_nama']) ?></strong> — potongan Rp<?= number_format((float)$ppdb['promo_potongan'], 0, ',', '.') ?>
            <?php if (!empty($ppdb['promo_mulai']) || !empty($ppdb['promo_selesai'])): ?>
              <br><small>Periode: <?= !empty($ppdb['promo_mulai']) ? date('d M Y', strtotime($ppdb['promo_mulai'])) : '-' ?> s/d <?= !empty($ppdb['promo_selesai']) ? date('d M Y', strtotime($ppdb['promo_selesai'])) : '-' ?></small>
            <?php endif; ?>
          </div>
          <?php else: ?>
            <p class="small text-muted mb-0">Belum ada promo yang berlaku saat ini.</p>
          <?php endif; ?>
        </div>
      </div>
      <div class="col-md-6" data-aos="fade-up">
        <div class="glass-card p-4 h-100">
          <h5 class="fw-bold mb-3"><i class="fa-solid fa-list-check text-warning me-2"></i>Persyaratan</h5>
          <p class="small"><?= nl2br(htmlspecialchars($ppdb['persyaratan'] ?? '- Fotokopi Kartu Keluarga
- Fotokopi Akta Kelahiran
- Pas Foto 3x4
- Fotokopi Rapor Terakhir')) ?></p>
        </div>
      </div>
      <div class="col-md-6" data-aos="fade-up">
        <div class="glass-card p-4 h-100">
          <h5 class="fw-bold mb-3"><i class="fa-solid fa-route text-warning me-2"></i>Alur Pendaftaran</h5>
          <p class="small"><?= nl2br(htmlspecialchars($ppdb['alur_pendaftaran'] ?? '1. Isi formulir pendaftaran online
2. Unggah dokumen persyaratan
3. Simpan nomor pendaftaran
4. Tunggu verifikasi dari panitia PPDB')) ?></p>
        </div>
      </div>
      <div class="col-md-6" data-aos="fade-up">
        <div class="glass-card p-4 h-100">
          <h5 class="fw-bold mb-3"><i class="fa-solid fa-calendar text-warning me-2"></i>Jadwal</h5>
          <p class="small mb-1"><strong>Mulai:</strong> <?= !empty($ppdb['tanggal_mulai']) ? date('d F Y', strtotime($ppdb['tanggal_mulai'])) : '-' ?></p>
          <p class="small mb-0"><strong>Selesai:</strong> <?= !empty($ppdb['tanggal_selesai']) ? date('d F Y', strtotime($ppdb['tanggal_selesai'])) : '-' ?></p>
        </div>
      </div>
    </div>

    <?php if (!empty($faq)): ?>
    <h5 class="fw-bold text-center mt-5 mb-3" data-aos="fade-up">Pertanyaan Umum</h5>
    <div class="accordion mx-auto" style="max-width:700px;" id="faqAccordion" data-aos="fade-up">
      <?php foreach ($faq as $i => $f): ?>
      <div class="accordion-item">
        <h2 class="accordion-header">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq<?= $i ?>">
            <?= htmlspecialchars($f['pertanyaan']) ?>
          </button>
        </h2>
        <div id="faq<?= $i ?>" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
          <div class="accordion-body small"><?= htmlspecialchars($f['jawaban']) ?></div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>
</section>

<?php if (!$kuotaPenuh): ?>
<script>
const target = new Date("<?= !empty($ppdb['tanggal_selesai']) ? date('Y-m-d', strtotime($ppdb['tanggal_selesai'])) : '' ?>T23:59:59").getTime();
const el = document.getElementById('countdown');
function tick(){
  const now = new Date().getTime();
  const diff = target - now;
  if (isNaN(target) || diff < 0) { el.innerHTML = ''; return; }
  const d = Math.floor(diff/(1000*60*60*24));
  const h = Math.floor((diff%(1000*60*60*24))/(1000*60*60));
  const m = Math.floor((diff%(1000*60*60))/(1000*60));
  const s = Math.floor((diff%(1000*60))/1000);
  el.innerHTML = [['Hari',d],['Jam',h],['Menit',m],['Detik',s]].map(([l,v])=>`
    <div class="glass-card px-3 py-2 text-center"><div class="fw-bold fs-4">${v}</div><small>${l}</small></div>`).join('');
}
if (!isNaN(target)) { tick(); setInterval(tick, 1000); }
</script>
<?php endif; ?>
<?php endif; ?>
