<section class="py-5">
  <div class="container" style="max-width:900px;">
    <p class="section-eyebrow text-center mb-2" data-aos="fade-up">HUBUNGI KAMI</p>
    <h1 class="fw-bold font-display text-center mb-2" data-aos="fade-up">✉️ Kontak &amp; Tanya Jawab</h1>
    <p class="text-center text-muted mb-5">Ada pertanyaan seputar pendaftaran atau kegiatan sekolah? Silakan tulis pesan Anda, kami akan segera merespons.</p>

    <div class="row g-4">
      <div class="col-md-5" data-aos="fade-right">
        <div class="glass-card p-4 h-100">
          <h6 class="fw-bold mb-3">Informasi Kontak</h6>
          <p class="small mb-2"><i class="fa-solid fa-location-dot text-warning me-2"></i><?= htmlspecialchars($pengaturan['alamat'] ?? '') ?></p>
          <p class="small mb-2"><i class="fa-solid fa-envelope text-warning me-2"></i><?= htmlspecialchars($pengaturan['email'] ?? '') ?></p>
          <p class="small mb-2"><i class="fa-solid fa-phone text-warning me-2"></i><?= htmlspecialchars($pengaturan['telepon'] ?? '') ?></p>
          <p class="small mb-0"><i class="fa-brands fa-instagram text-warning me-2"></i>
            <a href="<?= htmlspecialchars($pengaturan['instagram'] ?? '#') ?>" target="_blank" class="text-decoration-none">Instagram TK IT Quantum School</a>
          </p>
        </div>
      </div>
      <div class="col-md-7" data-aos="fade-left">
        <div class="glass-card p-4">
          <form method="POST" action="<?= $this->appConfig['base_url'] ?>/kontak/kirim">
            <?= Security::csrfField() ?>
            <div class="mb-3">
              <label class="form-label small">Nama Lengkap</label>
              <input type="text" name="nama" class="form-control" required>
            </div>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label small">Email</label>
                <input type="email" name="email" class="form-control" required>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label small">No. HP / WhatsApp (opsional)</label>
                <input type="text" name="no_hp" class="form-control">
              </div>
            </div>
            <div class="mb-3">
              <label class="form-label small">Pertanyaan / Pesan</label>
              <textarea name="pesan" class="form-control" rows="4" required></textarea>
            </div>
            <button type="submit" class="btn btn-accent rounded-pill px-4">📨 Kirim Pesan</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
