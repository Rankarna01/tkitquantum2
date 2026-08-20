<div class="d-flex justify-content-between align-items-center mb-3">
  <h5 class="fw-bold mb-0">Kelola Prestasi</h5>
  <a href="<?= $this->appConfig['base_url'] ?>/adminprestasi/tambah" class="btn btn-warning fw-semibold"><i class="fa-solid fa-plus me-1"></i> Tambah</a>
</div>
<div class="card stat-card p-3">
  <table id="tabelData" class="table table-hover align-middle w-100">
    <thead><tr><th>Judul</th><th>Kategori</th><th>Tingkat</th><th>Tahun</th><th>Aksi</th></tr></thead>
    <tbody>
      <?php foreach ($daftar as $d): ?>
      <tr>
        <td><?= htmlspecialchars($d['judul']) ?></td>
        <td><span class="badge bg-<?= $d['kategori'] === 'akademik' ? 'primary' : 'success' ?>"><?= htmlspecialchars($d['kategori']) ?></span></td>
        <td><?= htmlspecialchars($d['tingkat'] ?? '-') ?></td>
        <td><?= htmlspecialchars((string) $d['tahun']) ?></td>
        <td>
          <a href="<?= $this->appConfig['base_url'] ?>/adminprestasi/edit/<?= $d['id'] ?>" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-pen"></i></a>
          <button class="btn btn-sm btn-outline-danger btn-hapus" data-url="<?= $this->appConfig['base_url'] ?>/adminprestasi/hapus/<?= $d['id'] ?>"><i class="fa-solid fa-trash"></i></button>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
