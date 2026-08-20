<div class="d-flex justify-content-between align-items-center mb-3">
  <h5 class="fw-bold mb-0">Detail Pendaftar — <?= htmlspecialchars($item['no_pendaftaran']) ?></h5>
  <a href="<?= $this->appConfig['base_url'] ?>/adminppdb/pendaftar" class="btn btn-outline-dark btn-sm"><i class="fa-solid fa-arrow-left me-1"></i> Kembali</a>
</div>

<div class="row g-3">
  <div class="col-md-8">
    <div class="card stat-card p-4">
      <h6 class="fw-bold text-warning">Data Calon Siswa</h6>
      <table class="table table-sm">
        <tr><th width="200">Nama</th><td><?= htmlspecialchars($item['nama']) ?></td></tr>
        <tr><th>NIK</th><td><?= htmlspecialchars($item['nik']) ?></td></tr>
        <tr><th>NISN</th><td><?= htmlspecialchars($item['nisn'] ?? '-') ?></td></tr>
        <tr><th>Tempat, Tanggal Lahir</th><td><?= htmlspecialchars(($item['tempat_lahir'] ?? '-') . ', ' . ($item['tanggal_lahir'] ?? '-')) ?></td></tr>
        <tr><th>Jenis Kelamin</th><td><?= $item['jenis_kelamin'] === 'L' ? 'Laki-laki' : 'Perempuan' ?></td></tr>
        <tr><th>Agama</th><td><?= htmlspecialchars($item['agama'] ?? '-') ?></td></tr>
        <tr><th>Alamat</th><td><?= htmlspecialchars($item['alamat'] ?? '-') ?></td></tr>
        <tr><th>No. HP / Email</th><td><?= htmlspecialchars(($item['no_hp'] ?? '-') . ' / ' . ($item['email'] ?? '-')) ?></td></tr>
        <tr><th>Asal Sekolah</th><td><?= htmlspecialchars($item['asal_sekolah'] ?? '-') ?></td></tr>
      </table>

      <h6 class="fw-bold text-warning mt-3">Data Orang Tua</h6>
      <table class="table table-sm">
        <tr><th width="200">Nama Ayah / Ibu</th><td><?= htmlspecialchars(($item['nama_ayah'] ?? '-') . ' / ' . ($item['nama_ibu'] ?? '-')) ?></td></tr>
        <tr><th>Pekerjaan</th><td><?= htmlspecialchars($item['pekerjaan_ortu'] ?? '-') ?></td></tr>
        <tr><th>Penghasilan</th><td><?= htmlspecialchars($item['penghasilan_ortu'] ?? '-') ?></td></tr>
      </table>

      <h6 class="fw-bold text-warning mt-3">Berkas</h6>
      <div class="d-flex gap-2 flex-wrap">
        <?php foreach (['file_kk' => 'Kartu Keluarga', 'file_akta' => 'Akta Kelahiran', 'file_foto' => 'Pas Foto', 'file_rapor' => 'Rapor'] as $field => $label): ?>
          <?php if (!empty($item[$field])): ?>
            <a href="<?= $this->appConfig['base_url'] ?>/<?= htmlspecialchars($item[$field]) ?>" target="_blank" class="btn btn-sm btn-outline-secondary"><i class="fa-solid fa-file me-1"></i><?= $label ?></a>
          <?php else: ?>
            <span class="btn btn-sm btn-outline-secondary disabled"><?= $label ?> (kosong)</span>
          <?php endif; ?>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <div class="col-md-4">
    <div class="card stat-card p-4">
      <h6 class="fw-bold mb-3">Verifikasi</h6>
      <form method="POST">
        <?= Security::csrfField() ?>
        <div class="mb-3">
          <label class="form-label small">Status</label>
          <select name="status" class="form-select">
            <?php foreach (['menunggu' => 'Menunggu', 'diverifikasi' => 'Diverifikasi', 'diterima' => 'Diterima', 'ditolak' => 'Ditolak'] as $val => $label): ?>
              <option value="<?= $val ?>" <?= $item['status'] === $val ? 'selected' : '' ?>><?= $label ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label small">Catatan Admin</label>
          <textarea name="catatan_admin" class="form-control" rows="4"><?= htmlspecialchars($item['catatan_admin'] ?? '') ?></textarea>
        </div>
        <button type="submit" class="btn btn-warning w-100 fw-semibold">Simpan Status</button>
      </form>
    </div>
  </div>
</div>
