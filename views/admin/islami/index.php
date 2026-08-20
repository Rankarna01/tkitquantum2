<h5 class="fw-bold mb-1">🌙 Teks Islami Berjalan (Beranda)</h5>
<p class="text-muted small">Teks ini tampil berjalan (marquee) di landing page. Bisa berupa ayat, hadits, atau kata mutiara islami — mudah diganti kapan saja.</p>

<div class="card stat-card p-3 mb-4">
  <form method="POST" action="<?= $this->appConfig['base_url'] ?>/adminislami/tambah" class="row g-2 align-items-end">
    <?= Security::csrfField() ?>
    <div class="col-md-6">
      <label class="form-label small">Teks (ayat/hadits/kata mutiara)</label>
      <input type="text" name="teks" class="form-control" placeholder="Contoh: Rabbi zidni ilma — Ya Tuhanku, tambahkanlah ilmu kepadaku." required>
    </div>
    <div class="col-md-3">
      <label class="form-label small">Sumber (opsional)</label>
      <input type="text" name="sumber" class="form-control" placeholder="Contoh: QS. Thaha: 114">
    </div>
    <div class="col-md-2">
      <label class="form-label small">Urutan</label>
      <input type="number" name="urutan" class="form-control" value="0">
    </div>
    <div class="col-md-1">
      <button type="submit" class="btn btn-warning w-100 fw-semibold">+</button>
    </div>
  </form>
</div>

<div class="row g-3">
  <?php foreach ($daftar as $d): ?>
  <div class="col-md-6">
    <div class="card stat-card p-3 h-100">
      <p class="small mb-1">"<?= htmlspecialchars($d['teks']) ?>"</p>
      <small class="text-muted">— <?= htmlspecialchars($d['sumber'] ?: 'Tanpa sumber') ?></small>
      <div class="d-flex justify-content-between align-items-center mt-2">
        <a href="<?= $this->appConfig['base_url'] ?>/adminislami/toggleStatus/<?= $d['id'] ?>" class="badge <?= $d['status'] === 'aktif' ? 'bg-success' : 'bg-secondary' ?> text-decoration-none">
          <?= $d['status'] === 'aktif' ? 'Aktif' : 'Nonaktif' ?> — klik untuk ubah
        </a>
        <button class="btn btn-sm btn-outline-danger btn-hapus" data-url="<?= $this->appConfig['base_url'] ?>/adminislami/hapus/<?= $d['id'] ?>" data-title="Hapus teks ini?"><i class="fa-solid fa-trash"></i></button>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
  <?php if (empty($daftar)): ?><p class="text-muted">Belum ada teks. Tambahkan minimal satu agar muncul di beranda.</p><?php endif; ?>
</div>
