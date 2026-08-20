<div class="d-flex justify-content-between align-items-center mb-3">
  <h5 class="fw-bold mb-0">Data Pendaftar PPDB</h5>
  <a href="<?= $this->appConfig['base_url'] ?>/adminppdb" class="btn btn-outline-dark btn-sm"><i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Pengaturan</a>
</div>
<div class="card stat-card p-3">
  <table id="tabelData" class="table table-hover align-middle w-100">
    <thead><tr><th>No. Pendaftaran</th><th>Nama</th><th>NIK</th><th>Asal Sekolah</th><th>Status</th><th>Aksi</th></tr></thead>
    <tbody>
      <?php foreach ($daftar as $d): ?>
      <?php
        $statusInfo = ['menunggu' => 'secondary', 'diverifikasi' => 'info', 'diterima' => 'success', 'ditolak' => 'danger'];
        $color = $statusInfo[$d['status']] ?? 'secondary';
      ?>
      <tr>
        <td><?= htmlspecialchars($d['no_pendaftaran']) ?></td>
        <td><?= htmlspecialchars($d['nama']) ?></td>
        <td><?= htmlspecialchars($d['nik']) ?></td>
        <td><?= htmlspecialchars($d['asal_sekolah'] ?? '-') ?></td>
        <td><span class="badge bg-<?= $color ?>"><?= htmlspecialchars($d['status']) ?></span></td>
        <td><a href="<?= $this->appConfig['base_url'] ?>/adminppdb/detailPendaftar/<?= $d['id'] ?>" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-eye"></i> Detail</a></td>
      </tr>
      <?php endforeach; ?>
      <?php if (empty($daftar)): ?><tr><td colspan="6" class="text-center text-muted">Belum ada pendaftar.</td></tr><?php endif; ?>
    </tbody>
  </table>
</div>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<script>$(document).ready(function(){ $('#tabelData').DataTable(); });</script>
