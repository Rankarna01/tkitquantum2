<div class="d-flex justify-content-between align-items-center mb-3">
  <h5 class="fw-bold mb-0">Kelola Fasilitas</h5>
  <a href="<?= $this->appConfig['base_url'] ?>/adminfasilitas/tambah" class="btn btn-warning fw-semibold"><i class="fa-solid fa-plus me-1"></i> Tambah</a>
</div>
<div class="row g-3">
  <?php foreach ($daftar as $d): ?>
  <div class="col-md-3">
    <div class="card stat-card">
      <img src="<?= htmlspecialchars($this->appConfig['base_url'] . '/' . ($d['foto'] ?: '')) ?>" onerror="this.src='https://placehold.co/300x150'" style="height:120px;object-fit:cover;" class="card-img-top">
      <div class="card-body p-2">
        <h6 class="mb-2"><?= htmlspecialchars($d['nama']) ?></h6>
        <a href="<?= $this->appConfig['base_url'] ?>/adminfasilitas/edit/<?= $d['id'] ?>" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-pen"></i></a>
        <a href="<?= $this->appConfig['base_url'] ?>/adminfasilitas/hapus/<?= $d['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus data ini?')"><i class="fa-solid fa-trash"></i></a>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
  <?php if (empty($daftar)): ?><p class="text-muted">Belum ada data.</p><?php endif; ?>
</div>
