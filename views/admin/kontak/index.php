<h5 class="fw-bold mb-3">✉️ Pesan Kontak dari Orang Tua/Pengunjung</h5>

<div class="row g-3">
  <?php foreach ($pesan as $p): ?>
  <div class="col-md-6">
    <div class="card stat-card p-3 h-100 <?= $p['status'] === 'baru' ? 'border-warning' : '' ?>">
      <div class="d-flex justify-content-between align-items-start mb-2">
        <div>
          <h6 class="fw-bold mb-0"><?= htmlspecialchars($p['nama']) ?> <?php if ($p['status'] === 'baru'): ?><span class="badge bg-warning text-dark ms-1">Baru</span><?php endif; ?></h6>
          <small class="text-muted"><?= htmlspecialchars($p['email']) ?><?= !empty($p['no_hp']) ? ' • ' . htmlspecialchars($p['no_hp']) : '' ?></small>
        </div>
        <small class="text-muted"><?= date('d M Y H:i', strtotime($p['created_at'])) ?></small>
      </div>
      <p class="small mb-3"><?= nl2br(htmlspecialchars($p['pesan'])) ?></p>
      <div class="d-flex gap-2">
        <?php if ($p['status'] === 'baru'): ?>
          <a href="<?= $this->appConfig['base_url'] ?>/adminkontak/tandaiDibaca/<?= $p['id'] ?>" class="btn btn-sm btn-outline-success">Tandai Dibaca</a>
        <?php endif; ?>
        <a href="mailto:<?= htmlspecialchars($p['email']) ?>" class="btn btn-sm btn-outline-primary">Balas Email</a>
        <button class="btn btn-sm btn-outline-danger btn-hapus" data-url="<?= $this->appConfig['base_url'] ?>/adminkontak/hapus/<?= $p['id'] ?>" data-title="Hapus pesan ini?"><i class="fa-solid fa-trash"></i></button>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
  <?php if (empty($pesan)): ?><p class="text-muted">Belum ada pesan masuk.</p><?php endif; ?>
</div>
