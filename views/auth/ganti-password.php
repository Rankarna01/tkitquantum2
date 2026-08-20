<div class="card stat-card p-4" style="max-width:480px;">
  <h5 class="fw-bold mb-3">Ganti Password</h5>
  <form method="POST" action="<?= $this->appConfig['base_url'] ?>/auth/gantiPassword">
    <?= Security::csrfField() ?>
    <div class="mb-3">
      <label class="form-label small">Password Lama</label>
      <input type="password" name="password_lama" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label small">Password Baru (min. 8 karakter)</label>
      <input type="password" name="password_baru" class="form-control" minlength="8" required>
    </div>
    <div class="mb-3">
      <label class="form-label small">Konfirmasi Password Baru</label>
      <input type="password" name="password_konfirmasi" class="form-control" minlength="8" required>
    </div>
    <button type="submit" class="btn btn-warning fw-semibold">Simpan Perubahan</button>
  </form>
</div>
