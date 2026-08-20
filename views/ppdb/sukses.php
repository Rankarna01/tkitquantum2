<section class="d-flex align-items-center" style="min-height:70vh;">
  <div class="container text-center" style="max-width:600px;">
    <i class="fa-solid fa-circle-check text-success" style="font-size:4rem;"></i>
    <h2 class="fw-bold mt-3">Pendaftaran Berhasil!</h2>
    <p class="text-muted">Terima kasih, <strong><?= htmlspecialchars($nama) ?></strong>. Simpan nomor pendaftaran Anda untuk mengecek status.</p>
    <div class="glass-card d-inline-block px-5 py-3 my-3">
      <div class="small text-muted">Nomor Pendaftaran</div>
      <div class="fs-3 fw-bold text-warning"><?= htmlspecialchars($noPendaftaran) ?></div>
    </div>
    <div>
      <a href="<?= $this->appConfig['base_url'] ?>/ppdb/cekStatus" class="btn btn-accent px-4">Cek Status Pendaftaran</a>
      <a href="<?= $this->appConfig['base_url'] ?>/" class="btn btn-outline-dark px-4">Kembali ke Beranda</a>
    </div>
  </div>
</section>
