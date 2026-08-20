<div class="d-flex justify-content-between align-items-center mb-3">
  <h5 class="fw-bold mb-0">Log Aktivitas</h5>
  <a href="<?= $this->appConfig['base_url'] ?>/adminbackup" class="btn btn-outline-dark btn-sm"><i class="fa-solid fa-arrow-left me-1"></i> Kembali</a>
</div>

<div class="card stat-card p-3">
  <table id="tabelLog" class="table table-hover align-middle w-100">
    <thead><tr><th>Waktu</th><th>Pengguna</th><th>Aktivitas</th><th>Modul</th><th>IP</th></tr></thead>
    <tbody>
      <?php foreach ($daftar as $l): ?>
      <tr>
        <td class="text-nowrap"><?= htmlspecialchars($l['created_at']) ?></td>
        <td><?= htmlspecialchars($l['nama_lengkap'] ?? 'Sistem') ?></td>
        <td><?= htmlspecialchars($l['aktivitas']) ?></td>
        <td><span class="badge bg-secondary"><?= htmlspecialchars($l['modul'] ?? '-') ?></span></td>
        <td class="small text-muted"><?= htmlspecialchars($l['ip_address'] ?? '-') ?></td>
      </tr>
      <?php endforeach; ?>
      <?php if (empty($daftar)): ?><tr><td colspan="5" class="text-center text-muted">Belum ada aktivitas tercatat.</td></tr><?php endif; ?>
    </tbody>
  </table>
</div>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<script>$(document).ready(function(){ $('#tabelLog').DataTable({ order: [[0, 'desc']] }); });</script>
