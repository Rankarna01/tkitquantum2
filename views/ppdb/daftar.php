<section>
  <div class="container" style="max-width:800px;">
    <h1 class="fw-bold text-center mb-4" data-aos="fade-up">Formulir Pendaftaran PPDB</h1>
    <p class="text-center text-muted mb-4">Tahun Pelajaran <?= htmlspecialchars($ppdb['tahun_ajaran']) ?></p>

    <div class="glass-card p-3 mb-4" data-aos="fade-up">
      <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <span class="small"><i class="fa-solid fa-money-bill-wave text-warning me-2"></i>Biaya Pendaftaran: <strong>Rp<?= number_format((float)($ppdb['biaya_pendaftaran'] ?? 0), 0, ',', '.') ?></strong></span>
        <?php if (!empty($ppdb['promo_nama']) && (float)($ppdb['promo_potongan'] ?? 0) > 0): ?>
          <span class="badge badge-gold">🏷️ <?= htmlspecialchars($ppdb['promo_nama']) ?> — potongan Rp<?= number_format((float)$ppdb['promo_potongan'], 0, ',', '.') ?></span>
        <?php endif; ?>
      </div>
    </div>

    <?php if (!empty($_SESSION['flash_error'])): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['flash_error']) ?></div>
      <?php unset($_SESSION['flash_error']); ?>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="glass-card p-4">
      <?= Security::csrfField() ?>

      <h6 class="fw-bold text-warning">Data Calon Siswa</h6>
      <div class="row">
        <div class="col-md-6 mb-3"><label class="form-label small">NIK</label><input type="text" name="nik" class="form-control" maxlength="20" required></div>
        <div class="col-md-6 mb-3"><label class="form-label small">NISN</label><input type="text" name="nisn" class="form-control" maxlength="20"></div>
      </div>
      <div class="mb-3"><label class="form-label small">Nama Lengkap</label><input type="text" name="nama" class="form-control" required></div>
      <div class="row">
        <div class="col-md-6 mb-3"><label class="form-label small">Tempat Lahir</label><input type="text" name="tempat_lahir" class="form-control"></div>
        <div class="col-md-6 mb-3"><label class="form-label small">Tanggal Lahir</label><input type="date" name="tanggal_lahir" class="form-control"></div>
      </div>
      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label small">Jenis Kelamin</label>
          <select name="jenis_kelamin" class="form-select" required>
            <option value="L">Laki-laki</option>
            <option value="P">Perempuan</option>
          </select>
        </div>
        <div class="col-md-6 mb-3"><label class="form-label small">Agama</label><input type="text" name="agama" class="form-control"></div>
      </div>
      <div class="mb-3"><label class="form-label small">Alamat</label><textarea name="alamat" class="form-control" rows="2"></textarea></div>
      <div class="row">
        <div class="col-md-6 mb-3"><label class="form-label small">No. HP</label><input type="text" name="no_hp" class="form-control"></div>
        <div class="col-md-6 mb-3"><label class="form-label small">Email</label><input type="email" name="email" class="form-control"></div>
      </div>
      <div class="mb-3"><label class="form-label small">Asal Sekolah</label><input type="text" name="asal_sekolah" class="form-control"></div>

      <h6 class="fw-bold text-warning mt-4">Data Orang Tua</h6>
      <div class="row">
        <div class="col-md-6 mb-3"><label class="form-label small">Nama Ayah</label><input type="text" name="nama_ayah" class="form-control"></div>
        <div class="col-md-6 mb-3"><label class="form-label small">Nama Ibu</label><input type="text" name="nama_ibu" class="form-control"></div>
      </div>
      <div class="row">
        <div class="col-md-6 mb-3"><label class="form-label small">Pekerjaan Orang Tua</label><input type="text" name="pekerjaan_ortu" class="form-control"></div>
        <div class="col-md-6 mb-3"><label class="form-label small">Penghasilan Orang Tua</label><input type="text" name="penghasilan_ortu" class="form-control"></div>
      </div>

      <h6 class="fw-bold text-warning mt-4">Berkas Persyaratan</h6>
      <div class="row">
        <div class="col-md-6 mb-3"><label class="form-label small">Kartu Keluarga (PDF/JPG/PNG)</label><input type="file" name="file_kk" class="form-control"></div>
        <div class="col-md-6 mb-3"><label class="form-label small">Akta Kelahiran (PDF/JPG/PNG)</label><input type="file" name="file_akta" class="form-control"></div>
      </div>
      <div class="row">
        <div class="col-md-6 mb-3"><label class="form-label small">Pas Foto (JPG/PNG)</label><input type="file" name="file_foto" class="form-control" accept="image/*"></div>
        <div class="col-md-6 mb-3"><label class="form-label small">Rapor Terakhir (PDF/JPG/PNG)</label><input type="file" name="file_rapor" class="form-control"></div>
      </div>

      <button type="submit" class="btn btn-accent w-100 py-2 fw-semibold mt-3">Kirim Pendaftaran</button>
    </form>
  </div>
</section>
