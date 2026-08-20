<div class="d-flex justify-content-between align-items-center mb-3">
  <h5 class="fw-bold mb-0">Kelola Tenaga Kependidikan</h5>
  <a href="<?= $this->appConfig['base_url'] ?>/admintendik/tambah" class="btn btn-warning fw-semibold"><i class="fa-solid fa-plus me-1"></i> Tambah</a>
</div>
<div class="card stat-card p-3">
  <table id="tabelData" class="table table-hover align-middle w-100">
    <thead><tr><th>Foto</th><th>Nama</th><th>NIP</th><th>Jabatan</th><th>Status</th><th>Aksi</th></tr></thead>
    <tbody>
      <?php foreach ($daftar as $t): ?>
      <tr>
        <td><img src="<?= htmlspecialchars($this->appConfig['base_url'] . '/' . ($t['foto'] ?: '')) ?>" onerror="this.src='https://placehold.co/50x50'" style="width:44px;height:44px;object-fit:cover;border-radius:50%;"></td>
        <td><?= htmlspecialchars($t['nama_lengkap']) ?></td>
        <td><?= htmlspecialchars($t['nip'] ?? '-') ?></td>
        <td><?= htmlspecialchars($t['jabatan'] ?? '-') ?></td>
        <td><span class="badge bg-<?= $t['status'] === 'aktif' ? 'success' : 'secondary' ?>"><?= htmlspecialchars($t['status']) ?></span></td>
        <td>
          <a href="<?= $this->appConfig['base_url'] ?>/admintendik/edit/<?= $t['id'] ?>" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-pen"></i></a>
          <button class="btn btn-sm btn-outline-danger btn-hapus" data-url="<?= $this->appConfig['base_url'] ?>/admintendik/hapus/<?= $t['id'] ?>"><i class="fa-solid fa-trash"></i></button>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
