<div class="d-flex justify-content-between align-items-center mb-3">
  <h5 class="fw-bold mb-0">Struktur Organisasi</h5>
  <a href="<?= $this->appConfig['base_url'] ?>/adminstruktur/tambah" class="btn btn-warning fw-semibold"><i class="fa-solid fa-plus me-1"></i> Tambah</a>
</div>
<div class="card stat-card p-3">
  <table class="table table-hover align-middle">
    <thead><tr><th>Urutan</th><th>Foto</th><th>Nama</th><th>Jabatan</th><th>Atasan</th><th>Aksi</th></tr></thead>
    <tbody>
      <?php foreach ($daftar as $d): ?>
      <tr>
        <td><?= (int) $d['urutan'] ?></td>
        <td><img src="<?= htmlspecialchars($this->appConfig['base_url'] . '/' . ($d['foto'] ?: '')) ?>" onerror="this.src='https://placehold.co/40x40'" style="width:36px;height:36px;object-fit:cover;border-radius:50%;"></td>
        <td><?= htmlspecialchars($d['nama']) ?></td>
        <td><?= htmlspecialchars($d['jabatan']) ?></td>
        <td>
          <?php
            $atasan = '-';
            foreach ($daftar as $a) { if ($a['id'] == $d['parent_id']) { $atasan = $a['nama']; break; } }
            echo htmlspecialchars($atasan);
          ?>
        </td>
        <td>
          <a href="<?= $this->appConfig['base_url'] ?>/adminstruktur/edit/<?= $d['id'] ?>" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-pen"></i></a>
          <a href="<?= $this->appConfig['base_url'] ?>/adminstruktur/hapus/<?= $d['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus data ini?')"><i class="fa-solid fa-trash"></i></a>
        </td>
      </tr>
      <?php endforeach; ?>
      <?php if (empty($daftar)): ?><tr><td colspan="6" class="text-center text-muted">Belum ada data.</td></tr><?php endif; ?>
    </tbody>
  </table>
</div>
