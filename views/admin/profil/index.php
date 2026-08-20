<h5 class="fw-bold mb-3">Profil Sekolah</h5>
<div class="card stat-card p-4">
  <form method="POST" action="<?= $this->appConfig['base_url'] ?>/adminprofil/simpan" enctype="multipart/form-data">
    <?= Security::csrfField() ?>
    <div class="mb-3">
      <label class="form-label small">Sejarah</label>
      <textarea name="sejarah" class="form-control" rows="4"><?= htmlspecialchars($profil['sejarah'] ?? '') ?></textarea>
    </div>
    <div class="mb-3">
      <label class="form-label small">Foto Sekolah (ditampilkan di samping teks Sejarah)</label>
      <input type="file" name="foto_sejarah" class="form-control" accept="image/*">
      <small class="text-muted d-block mt-1">Maks. 4MB — format JPG, PNG, atau WEBP</small>
      <?php if (!empty($profil['foto_sejarah'])): ?>
        <img src="<?= $this->appConfig['base_url'] ?>/<?= htmlspecialchars($profil['foto_sejarah']) ?>" class="mt-2 rounded-3" style="max-height:100px;">
      <?php endif; ?>
    </div>
    <div class="mb-3">
      <label class="form-label small">Visi</label>
      <textarea name="visi" class="form-control" rows="2"><?= htmlspecialchars($profil['visi'] ?? '') ?></textarea>
    </div>
    <div class="mb-3">
      <label class="form-label small">Misi (satu baris = satu poin)</label>
      <textarea name="misi" class="form-control" rows="4"><?= htmlspecialchars($profil['misi'] ?? '') ?></textarea>
    </div>
    <div class="mb-3">
      <label class="form-label small">Tujuan</label>
      <textarea name="tujuan" class="form-control" rows="3"><?= htmlspecialchars($profil['tujuan'] ?? '') ?></textarea>
    </div>
    <div class="row">
      <div class="col-md-6 mb-3">
        <label class="form-label small">Nama Kepala Sekolah</label>
        <input type="text" name="nama_kepsek" class="form-control" value="<?= htmlspecialchars($profil['nama_kepsek'] ?? '') ?>">
      </div>
    </div>
    <div class="mb-3">
      <label class="form-label small">Sambutan Kepala Sekolah</label>
      <textarea name="sambutan_kepsek" class="form-control" rows="4"><?= htmlspecialchars($profil['sambutan_kepsek'] ?? '') ?></textarea>
    </div>
    <small class="text-muted d-block mb-2">Untuk mengubah foto kepala sekolah, gunakan menu <strong>Logo & Branding</strong>.</small>
    <button type="submit" class="btn btn-warning fw-semibold">Simpan</button>
  </form>
</div>
