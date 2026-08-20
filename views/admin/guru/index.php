<div class="d-flex justify-content-between align-items-center mb-3">
  <h5 class="fw-bold mb-0">Kelola Guru &amp; Tenaga Kependidikan</h5>
  <div class="dropdown">
    <button class="btn btn-warning fw-semibold dropdown-toggle" data-bs-toggle="dropdown">
      <i class="fa-solid fa-plus me-1"></i> Tambah
    </button>
    <ul class="dropdown-menu dropdown-menu-end">
      <li><a class="dropdown-item" href="<?= $this->appConfig['base_url'] ?>/adminguru/tambah"><i class="fa-solid fa-chalkboard-user me-2"></i>Guru (Pengajar)</a></li>
      <li><a class="dropdown-item" href="<?= $this->appConfig['base_url'] ?>/admintendik/tambah"><i class="fa-solid fa-people-group me-2"></i>Tenaga Kependidikan (Staff)</a></li>
    </ul>
  </div>
</div>

<ul class="nav nav-pills mb-3">
  <li class="nav-item"><a class="nav-link active" data-bs-toggle="pill" href="#tabGuru">🧑‍🏫 Guru (Pengajar)</a></li>
  <li class="nav-item"><a class="nav-link" data-bs-toggle="pill" href="#tabTendik">🧑‍💼 Tenaga Kependidikan</a></li>
</ul>

<div class="tab-content">
  <div class="tab-pane fade show active" id="tabGuru">
    <div class="card stat-card p-3">
      <table id="tabelGuru" class="table table-hover align-middle w-100">
        <thead><tr><th>Foto</th><th>Nama</th><th>NIP</th><th>Mapel</th><th>Jabatan</th><th>Status</th><th>Aksi</th></tr></thead>
        <tbody>
          <?php foreach ($daftar as $g): ?>
          <tr>
            <td><img src="<?= htmlspecialchars($this->appConfig['base_url'] . '/' . ($g['foto'] ?: '')) ?>" onerror="this.src='https://placehold.co/50x50'" style="width:44px;height:44px;object-fit:cover;border-radius:50%;"></td>
            <td><?= htmlspecialchars($g['nama_lengkap']) ?></td>
            <td><?= htmlspecialchars($g['nip'] ?? '-') ?></td>
            <td><?= htmlspecialchars($g['mata_pelajaran'] ?? '-') ?></td>
            <td><?= htmlspecialchars($g['jabatan'] ?? '-') ?></td>
            <td><span class="badge bg-<?= $g['status'] === 'aktif' ? 'success' : 'secondary' ?>"><?= htmlspecialchars($g['status']) ?></span></td>
            <td>
              <a href="<?= $this->appConfig['base_url'] ?>/adminguru/edit/<?= $g['id'] ?>" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-pen"></i></a>
              <button class="btn btn-sm btn-outline-danger btn-hapus" data-url="<?= $this->appConfig['base_url'] ?>/adminguru/hapus/<?= $g['id'] ?>" data-title="Hapus data guru ini?"><i class="fa-solid fa-trash"></i></button>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <div class="tab-pane fade" id="tabTendik">
    <div class="card stat-card p-3">
      <table id="tabelTendik" class="table table-hover align-middle w-100">
        <thead><tr><th>Foto</th><th>Nama</th><th>NIP</th><th>Jabatan</th><th>Status</th><th>Aksi</th></tr></thead>
        <tbody>
          <?php foreach ($daftarTendik as $t): ?>
          <tr>
            <td><img src="<?= htmlspecialchars($this->appConfig['base_url'] . '/' . ($t['foto'] ?: '')) ?>" onerror="this.src='https://placehold.co/50x50'" style="width:44px;height:44px;object-fit:cover;border-radius:50%;"></td>
            <td><?= htmlspecialchars($t['nama_lengkap']) ?></td>
            <td><?= htmlspecialchars($t['nip'] ?? '-') ?></td>
            <td><?= htmlspecialchars($t['jabatan'] ?? '-') ?></td>
            <td><span class="badge bg-<?= $t['status'] === 'aktif' ? 'success' : 'secondary' ?>"><?= htmlspecialchars($t['status']) ?></span></td>
            <td>
              <a href="<?= $this->appConfig['base_url'] ?>/admintendik/edit/<?= $t['id'] ?>" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-pen"></i></a>
              <button class="btn btn-sm btn-outline-danger btn-hapus" data-url="<?= $this->appConfig['base_url'] ?>/admintendik/hapus/<?= $t['id'] ?>" data-title="Hapus data tenaga kependidikan ini?"><i class="fa-solid fa-trash"></i></button>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<script>
$(function () {
  $('#tabelGuru').DataTable();
  $('#tabelTendik').DataTable();
});
</script>
