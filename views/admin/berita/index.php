<div class="d-flex justify-content-between align-items-center mb-3">
  <h5 class="fw-bold mb-0">Kelola Berita</h5>
  <a href="<?= $this->appConfig['base_url'] ?>/adminberita/tambah" class="btn btn-warning fw-semibold"><i class="fa-solid fa-plus me-1"></i> Tambah Berita</a>
</div>

<div class="card stat-card p-3">
  <table id="tabelBerita" class="table table-hover align-middle w-100">
    <thead>
      <tr><th>Thumbnail</th><th>Judul</th><th>Kategori</th><th>Status</th><th>Dilihat</th><th>Aksi</th></tr>
    </thead>
    <tbody>
      <?php foreach ($daftar as $b): ?>
      <tr>
        <td><img src="<?= htmlspecialchars($this->appConfig['base_url'] . '/' . ($b['thumbnail'] ?: '')) ?>" onerror="this.src='https://placehold.co/60x40'" style="width:60px;height:40px;object-fit:cover;border-radius:6px;"></td>
        <td><?= htmlspecialchars($b['judul']) ?></td>
        <td><?= htmlspecialchars($b['kategori_id'] ?? '-') ?></td>
        <td><span class="badge bg-<?= $b['status'] === 'publish' ? 'success' : 'secondary' ?>"><?= htmlspecialchars($b['status']) ?></span></td>
        <td><?= (int) $b['dilihat'] ?></td>
        <td>
          <a href="<?= $this->appConfig['base_url'] ?>/adminberita/edit/<?= $b['id'] ?>" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-pen"></i></a>
          <button class="btn btn-sm btn-outline-danger btn-hapus" data-url="<?= $this->appConfig['base_url'] ?>/adminberita/hapus/<?= $b['id'] ?>"><i class="fa-solid fa-trash"></i></button>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
