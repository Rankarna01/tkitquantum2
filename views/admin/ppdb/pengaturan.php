<div class="d-flex justify-content-between align-items-center mb-3">
  <h5 class="fw-bold mb-0">🎓 Dashboard PPDB</h5>
  <div>
    <a href="<?= $this->appConfig['base_url'] ?>/adminppdb/pendaftar" class="btn btn-outline-dark btn-sm"><i class="fa-solid fa-users me-1"></i> Data Pendaftar</a>
    <a href="<?= $this->appConfig['base_url'] ?>/adminppdb/faq" class="btn btn-outline-dark btn-sm"><i class="fa-solid fa-question me-1"></i> Kelola FAQ</a>
  </div>
</div>

<?php if ($ppdb): ?>
<div class="row g-3 mb-3">
  <div class="col-6 col-md-3">
    <div class="card stat-card p-3 text-center h-100">
      <div class="fs-4 fw-bold text-warning"><?= $terdaftar ?></div>
      <small class="text-muted">Pendaftar Masuk</small>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="card stat-card p-3 text-center h-100">
      <div class="fs-4 fw-bold text-warning"><?= (int) $ppdb['kuota'] ?></div>
      <small class="text-muted">Total Kuota</small>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="card stat-card p-3 text-center h-100">
      <div class="fs-4 fw-bold text-warning"><?= max(0, (int) $ppdb['kuota'] - $terdaftar) ?></div>
      <small class="text-muted">Sisa Kuota</small>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="card stat-card p-3 text-center h-100">
      <div class="fs-5 fw-bold <?= $ppdb['status'] === 'aktif' ? 'text-success' : 'text-secondary' ?>"><?= $ppdb['status'] === 'aktif' ? 'AKTIF' : 'TUTUP' ?></div>
      <small class="text-muted">Status PPDB</small>
    </div>
  </div>
</div>

<div class="alert alert-<?= $ppdb['status'] === 'aktif' ? 'success' : 'secondary' ?> d-flex justify-content-between align-items-center flex-wrap gap-2">
  <span>Tahun Ajaran <strong><?= htmlspecialchars($ppdb['tahun_ajaran']) ?></strong> — kuota terisi <?= $terdaftar ?> dari <?= (int) $ppdb['kuota'] ?></span>
  <form method="POST" action="<?= $this->appConfig['base_url'] ?>/adminppdb/toggleStatus" class="m-0">
    <?= Security::csrfField() ?>
    <button type="submit" class="btn btn-sm fw-semibold <?= $ppdb['status'] === 'aktif' ? 'btn-danger' : 'btn-success' ?>">
      <?= $ppdb['status'] === 'aktif' ? '⏸ Nonaktifkan PPDB' : '▶ Aktifkan PPDB' ?>
    </button>
  </form>
</div>
<?php endif; ?>

<div class="card stat-card p-4">
  <form method="POST" action="<?= $this->appConfig['base_url'] ?>/adminppdb/simpanPengaturan" enctype="multipart/form-data">
    <?= Security::csrfField() ?>
    <h6 class="fw-bold text-warning mb-3">📋 Pengaturan Umum</h6>
    <div class="row">
      <div class="col-md-6 mb-3">
        <label class="form-label small">Tahun Ajaran</label>
        <input type="text" name="tahun_ajaran" class="form-control" value="<?= htmlspecialchars($ppdb['tahun_ajaran'] ?? '') ?>" placeholder="2026/2027" required>
      </div>
      <div class="col-md-6 mb-3">
        <label class="form-label small">Status Pendaftaran</label>
        <select name="status" class="form-select">
          <option value="aktif" <?= (($ppdb['status'] ?? '') === 'aktif') ? 'selected' : '' ?>>Aktif (Dibuka)</option>
          <option value="nonaktif" <?= (($ppdb['status'] ?? 'nonaktif') === 'nonaktif') ? 'selected' : '' ?>>Nonaktif (Ditutup)</option>
        </select>
      </div>
    </div>
    <div class="row">
      <div class="col-md-4 mb-3">
        <label class="form-label small">Kuota Siswa</label>
        <input type="number" name="kuota" class="form-control" min="0" value="<?= (int) ($ppdb['kuota'] ?? 0) ?>" required>
      </div>
      <div class="col-md-4 mb-3">
        <label class="form-label small">Tanggal Mulai</label>
        <input type="date" name="tanggal_mulai" class="form-control" value="<?= htmlspecialchars($ppdb['tanggal_mulai'] ?? '') ?>">
      </div>
      <div class="col-md-4 mb-3">
        <label class="form-label small">Tanggal Selesai</label>
        <input type="date" name="tanggal_selesai" class="form-control" value="<?= htmlspecialchars($ppdb['tanggal_selesai'] ?? '') ?>">
      </div>
    </div>
    <div class="mb-3">
      <label class="form-label small">Informasi Umum</label>
      <textarea name="informasi" class="form-control" rows="3"><?= htmlspecialchars($ppdb['informasi'] ?? '') ?></textarea>
    </div>
    <div class="mb-3">
      <label class="form-label small">Persyaratan (satu baris = satu poin)</label>
      <textarea name="persyaratan" class="form-control" rows="4"><?= htmlspecialchars($ppdb['persyaratan'] ?? '') ?></textarea>
    </div>
    <div class="mb-3">
      <label class="form-label small">Alur Pendaftaran (satu baris = satu poin)</label>
      <textarea name="alur_pendaftaran" class="form-control" rows="4"><?= htmlspecialchars($ppdb['alur_pendaftaran'] ?? '') ?></textarea>
    </div>

    <hr>
    <h6 class="fw-bold text-warning mb-3">🎈 Brosur &amp; Biaya Pendaftaran</h6>
    <div class="row">
      <div class="col-md-4 mb-3">
        <label class="form-label small">Biaya Pendaftaran (Rp)</label>
        <input type="number" step="0.01" name="biaya_pendaftaran" class="form-control" value="<?= htmlspecialchars($ppdb['biaya_pendaftaran'] ?? 0) ?>">
      </div>
      <div class="col-md-8 mb-3">
        <label class="form-label small">Banner / Brosur PPDB (ditampilkan di beranda)</label>
        <input type="file" name="banner" class="form-control" accept="image/*">
              <small class="text-muted d-block mt-1">Maks. 4MB — format JPG, PNG, atau WEBP</small>
        <?php if (!empty($ppdb['banner'])): ?>
          <img src="<?= $this->appConfig['base_url'] . '/' . htmlspecialchars($ppdb['banner']) ?>" class="mt-2 rounded" style="max-height:100px;">
        <?php endif; ?>
      </div>
    </div>

    <hr>
    <h6 class="fw-bold text-warning mb-3">🏷️ Periode Promo / Potongan Harga (opsional)</h6>
    <div class="row">
      <div class="col-md-4 mb-3">
        <label class="form-label small">Nama Promo</label>
        <input type="text" name="promo_nama" class="form-control" placeholder="Contoh: Early Bird Juli" value="<?= htmlspecialchars($ppdb['promo_nama'] ?? '') ?>">
      </div>
      <div class="col-md-2 mb-3">
        <label class="form-label small">Potongan (Rp)</label>
        <input type="number" step="0.01" name="promo_potongan" class="form-control" value="<?= htmlspecialchars($ppdb['promo_potongan'] ?? 0) ?>">
      </div>
      <div class="col-md-3 mb-3">
        <label class="form-label small">Promo Mulai</label>
        <input type="date" name="promo_mulai" class="form-control" value="<?= htmlspecialchars($ppdb['promo_mulai'] ?? '') ?>">
      </div>
      <div class="col-md-3 mb-3">
        <label class="form-label small">Promo Selesai</label>
        <input type="date" name="promo_selesai" class="form-control" value="<?= htmlspecialchars($ppdb['promo_selesai'] ?? '') ?>">
      </div>
    </div>

    <hr>
    <h6 class="fw-bold text-warning mb-3">🖥️ Tampilan di Beranda</h6>
    <div class="row align-items-center">
      <div class="col-md-4 mb-3">
        <label class="form-label small">Tampilkan Blok PPDB di Beranda?</label>
        <select name="tampil_beranda" class="form-select">
          <option value="ya" <?= ($ppdb['tampil_beranda'] ?? 'ya') === 'ya' ? 'selected' : '' ?>>Ya, tampilkan</option>
          <option value="tidak" <?= ($ppdb['tampil_beranda'] ?? 'ya') === 'tidak' ? 'selected' : '' ?>>Sembunyikan</option>
        </select>
        <small class="text-muted d-block mt-1">Kalau disembunyikan, blok PPDB tidak muncul di beranda (halaman /ppdb tetap bisa diakses langsung).</small>
      </div>
      <div class="col-md-4 mb-3">
        <label class="form-label small">Judul CTA (banner ajakan daftar)</label>
        <input type="text" name="cta_judul" class="form-control" placeholder="Yuk, Daftarkan Si Kecil Sekarang!" value="<?= htmlspecialchars($ppdb['cta_judul'] ?? '') ?>">
      </div>
      <div class="col-md-4 mb-3">
        <label class="form-label small">Sub-judul CTA</label>
        <input type="text" name="cta_subjudul" class="form-control" placeholder="Kuota terbatas untuk Tahun Ajaran 2026/2027" value="<?= htmlspecialchars($ppdb['cta_subjudul'] ?? '') ?>">
      </div>
    </div>

    <button type="submit" class="btn btn-warning fw-semibold">Simpan Pengaturan</button>
  </form>
</div>
