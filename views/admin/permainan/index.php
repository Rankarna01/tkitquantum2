<h5 class="fw-bold mb-1">🎮 Kelola Zona Permainan</h5>
<p class="text-muted small">Atur menu permainan yang tampil di website, dan pantau papan skor tiap game.</p>

<div class="alert alert-<?= ($pengaturan['tampil_menu'] ?? 'ya') === 'ya' ? 'success' : 'secondary' ?> d-flex justify-content-between align-items-center flex-wrap gap-2">
  <span>Menu "🎮 Main Yuk!" di navbar saat ini: <strong><?= ($pengaturan['tampil_menu'] ?? 'ya') === 'ya' ? 'DITAMPILKAN' : 'DISEMBUNYIKAN' ?></strong></span>
  <form method="POST" action="<?= $this->appConfig['base_url'] ?>/adminpermainan/toggleMenu" class="m-0">
    <?= Security::csrfField() ?>
    <button type="submit" class="btn btn-sm fw-semibold <?= ($pengaturan['tampil_menu'] ?? 'ya') === 'ya' ? 'btn-danger' : 'btn-success' ?>">
      <?= ($pengaturan['tampil_menu'] ?? 'ya') === 'ya' ? '🙈 Sembunyikan Menu Permainan' : '👁️ Tampilkan Menu Permainan' ?>
    </button>
  </form>
</div>

<hr class="my-4">
<h6 class="fw-bold mb-2">🎵 Musik Latar Game (berlaku untuk semua game)</h6>
<p class="text-muted small">Musik ini akan diputar otomatis di setiap halaman game begitu pemain menekan tombol "Mulai Main" — berlaku sama untuk semua game, tidak perlu diatur satu-satu.</p>
<div class="card stat-card p-3">
  <form method="POST" action="<?= $this->appConfig['base_url'] ?>/adminpermainan/simpanMusik" enctype="multipart/form-data" class="row g-2 align-items-end">
    <?= Security::csrfField() ?>
    <div class="col-md-8">
      <label class="form-label small">File Musik (MP3/OGG/WAV)</label>
      <input type="file" name="musik_game" class="form-control" accept="audio/mpeg,audio/mp3,audio/ogg,audio/wav">
      <small class="text-muted d-block mt-1">Maks. 4MB</small>
    </div>
    <div class="col-md-4">
      <button type="submit" class="btn btn-warning w-100 fw-semibold">Simpan Musik</button>
    </div>
  </form>
  <?php if (!empty($pengaturan['musik_game'])): ?>
  <div class="d-flex align-items-center gap-2 mt-3">
    <audio controls class="flex-grow-1"><source src="<?= $this->appConfig['base_url'] ?>/<?= htmlspecialchars($pengaturan['musik_game']) ?>"></audio>
    <button class="btn btn-sm btn-outline-danger btn-hapus" data-url="<?= $this->appConfig['base_url'] ?>/adminpermainan/hapusMusik" data-title="Hapus musik latar game?"><i class="fa-solid fa-trash"></i></button>
  </div>
  <?php endif; ?>
</div>

<div class="row g-3 mt-2">
  <?php foreach ($daftarGame as $g): ?>
  <div class="col-md-4">
    <div class="card stat-card p-3 h-100">
      <div class="d-flex justify-content-between align-items-start mb-2">
        <div>
          <span style="font-size:1.8rem;"><?= htmlspecialchars($g['ikon'] ?: '🎮') ?></span>
          <h6 class="fw-bold mt-1 mb-0"><?= htmlspecialchars($g['nama']) ?></h6>
          <small class="text-muted"><?= htmlspecialchars($g['deskripsi'] ?? '') ?></small>
        </div>
        <span class="badge bg-<?= $g['status'] === 'aktif' ? 'success' : 'secondary' ?>"><?= $g['status'] === 'aktif' ? 'Aktif' : 'Nonaktif' ?></span>
      </div>
      <div class="d-flex gap-2 mt-auto">
        <form method="POST" action="<?= $this->appConfig['base_url'] ?>/adminpermainan/toggleGame/<?= $g['id'] ?>" class="flex-grow-1 m-0">
          <?= Security::csrfField() ?>
          <button type="submit" class="btn btn-sm w-100 <?= $g['status'] === 'aktif' ? 'btn-outline-danger' : 'btn-outline-success' ?>">
            <?= $g['status'] === 'aktif' ? 'Sembunyikan' : 'Tampilkan' ?>
          </button>
        </form>
        <button class="btn btn-sm btn-outline-secondary btn-hapus" data-url="<?= $this->appConfig['base_url'] ?>/adminpermainan/resetSkor/<?= htmlspecialchars($g['slug']) ?>" data-title="Kosongkan papan skor <?= htmlspecialchars($g['nama']) ?>?">
          <i class="fa-solid fa-broom"></i>
        </button>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
  <?php if (empty($daftarGame)): ?><p class="text-muted">Belum ada data game.</p><?php endif; ?>
</div>
