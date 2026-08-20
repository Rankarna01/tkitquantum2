<section class="d-flex align-items-center" style="min-height:70vh;">
  <div class="container" style="max-width:500px;">
    <h2 class="fw-bold text-center mb-4">Cek Status Pendaftaran</h2>
    <form method="POST" class="glass-card p-4 mb-4">
      <?= Security::csrfField() ?>
      <label class="form-label small">Nomor Pendaftaran</label>
      <input type="text" name="no_pendaftaran" class="form-control mb-3" placeholder="Contoh: PPDB20260001" required>
      <button type="submit" class="btn btn-accent w-100 fw-semibold">Cek Status</button>
    </form>

    <?php if ($dicari): ?>
      <?php if ($hasil): ?>
        <?php
          $statusInfo = [
            'menunggu' => ['secondary', 'Menunggu Verifikasi'],
            'diverifikasi' => ['info', 'Sedang Diverifikasi'],
            'diterima' => ['success', 'Diterima'],
            'ditolak' => ['danger', 'Ditolak'],
          ];
          [$color, $label] = $statusInfo[$hasil['status']] ?? ['secondary', $hasil['status']];
        ?>
        <div class="glass-card p-4 text-center">
          <h5 class="fw-bold"><?= htmlspecialchars($hasil['nama']) ?></h5>
          <p class="small text-muted mb-2">No. Pendaftaran: <?= htmlspecialchars($hasil['no_pendaftaran']) ?></p>
          <span class="badge bg-<?= $color ?> px-3 py-2"><?= $label ?></span>
          <?php if (!empty($hasil['catatan_admin'])): ?>
            <p class="small mt-3 mb-0"><em>Catatan: <?= htmlspecialchars($hasil['catatan_admin']) ?></em></p>
          <?php endif; ?>
        </div>
      <?php else: ?>
        <div class="alert alert-warning text-center">Nomor pendaftaran tidak ditemukan.</div>
      <?php endif; ?>
    <?php endif; ?>
  </div>
</section>
