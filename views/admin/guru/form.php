<div class="card stat-card p-4">
  <h5 class="fw-bold mb-3"><?= $item ? 'Edit Guru' : 'Tambah Guru' ?></h5>
  <form method="POST" enctype="multipart/form-data">
    <?= Security::csrfField() ?>
    <div class="row">
      <div class="col-md-6 mb-3">
        <label class="form-label small">Nama Lengkap</label>
        <input type="text" name="nama_lengkap" class="form-control" value="<?= htmlspecialchars($item['nama_lengkap'] ?? '') ?>" required>
      </div>
      <div class="col-md-6 mb-3">
        <label class="form-label small">NIP</label>
        <input type="text" name="nip" class="form-control" value="<?= htmlspecialchars($item['nip'] ?? '') ?>">
      </div>
    </div>
    <div class="row">
      <div class="col-md-6 mb-3">
        <label class="form-label small">Mata Pelajaran</label>
        <input type="text" name="mata_pelajaran" class="form-control" value="<?= htmlspecialchars($item['mata_pelajaran'] ?? '') ?>">
      </div>
      <div class="col-md-6 mb-3">
        <label class="form-label small">Jabatan</label>
        <input type="text" name="jabatan" class="form-control" value="<?= htmlspecialchars($item['jabatan'] ?? '') ?>" placeholder="Guru Mapel / Wali Kelas / dsb">
      </div>
    </div>
    <div class="row">
      <div class="col-md-6 mb-3">
        <label class="form-label small">Pendidikan Terakhir</label>
        <input type="text" name="pendidikan_terakhir" class="form-control" value="<?= htmlspecialchars($item['pendidikan_terakhir'] ?? '') ?>" placeholder="S1 Pendidikan Matematika">
      </div>
      <div class="col-md-6 mb-3">
        <label class="form-label small">Status</label>
        <select name="status" class="form-select">
          <option value="aktif" <?= (($item['status'] ?? 'aktif') === 'aktif') ? 'selected' : '' ?>>Aktif</option>
          <option value="nonaktif" <?= (($item['status'] ?? '') === 'nonaktif') ? 'selected' : '' ?>>Nonaktif</option>
        </select>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6 mb-3">
        <label class="form-label small">Email</label>
        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($item['email'] ?? '') ?>">
      </div>
      <div class="col-md-6 mb-3">
        <label class="form-label small">No. HP</label>
        <input type="text" name="no_hp" class="form-control" value="<?= htmlspecialchars($item['no_hp'] ?? '') ?>">
      </div>
    </div>
    <div class="mb-3">
      <label class="form-label small">Foto <?= $item ? '(kosongkan jika tidak diubah)' : '' ?></label>
      <input type="file" name="foto" class="form-control" accept="image/*">
          <small class="text-muted d-block mt-1">Maks. 4MB — format JPG, PNG, atau WEBP</small>
    </div>
    <div class="mb-3">
      <label class="form-label small">Riwayat Singkat</label>
      <textarea name="riwayat_singkat" class="form-control" rows="3"><?= htmlspecialchars($item['riwayat_singkat'] ?? '') ?></textarea>
    </div>
    <button type="submit" class="btn btn-warning fw-semibold">Simpan</button>
    <a href="<?= $this->appConfig['base_url'] ?>/adminguru" class="btn btn-outline-secondary">Batal</a>
  </form>
</div>
