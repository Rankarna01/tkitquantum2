<div class="card stat-card p-4">
  <h5 class="fw-bold mb-3"><?= $item ? 'Edit Data' : 'Tambah Tenaga Kependidikan' ?></h5>
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
        <label class="form-label small">Jabatan</label>
        <select name="jabatan" class="form-select">
          <?php
          $pilihan = ['Kepala Tata Usaha', 'Staff TU', 'Bendahara', 'Operator Sekolah', 'Pustakawan', 'Laboran', 'Teknisi', 'Satpam', 'Petugas Kebersihan'];
          $current = $item['jabatan'] ?? '';
          if ($current && !in_array($current, $pilihan, true)) $pilihan[] = $current;
          foreach ($pilihan as $p): ?>
            <option value="<?= htmlspecialchars($p) ?>" <?= $current === $p ? 'selected' : '' ?>><?= htmlspecialchars($p) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-6 mb-3">
        <label class="form-label small">Pendidikan Terakhir</label>
        <input type="text" name="pendidikan_terakhir" class="form-control" value="<?= htmlspecialchars($item['pendidikan_terakhir'] ?? '') ?>">
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
      <label class="form-label small">Status</label>
      <select name="status" class="form-select">
        <option value="aktif" <?= (($item['status'] ?? 'aktif') === 'aktif') ? 'selected' : '' ?>>Aktif</option>
        <option value="nonaktif" <?= (($item['status'] ?? '') === 'nonaktif') ? 'selected' : '' ?>>Nonaktif</option>
      </select>
    </div>
    <div class="mb-3">
      <label class="form-label small">Foto <?= $item ? '(kosongkan jika tidak diubah)' : '' ?></label>
      <input type="file" name="foto" class="form-control" accept="image/*">
          <small class="text-muted d-block mt-1">Maks. 4MB — format JPG, PNG, atau WEBP</small>
    </div>
    <div class="mb-3">
      <label class="form-label small">Deskripsi Singkat</label>
      <textarea name="deskripsi_singkat" class="form-control" rows="3"><?= htmlspecialchars($item['deskripsi_singkat'] ?? '') ?></textarea>
    </div>
    <button type="submit" class="btn btn-warning fw-semibold">Simpan</button>
    <a href="<?= $this->appConfig['base_url'] ?>/admintendik" class="btn btn-outline-secondary">Batal</a>
  </form>
</div>
