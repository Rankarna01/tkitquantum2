<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard — TK IT Quantum School</title>
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
  :root{ --gold:#E8A33D; --gold-deep:#B8791F; --ink:#1C1B19; }
  body{background:#F6F5F1; font-family:'Inter',sans-serif; color:var(--ink);}
  .font-display{font-family:'Sora',sans-serif;}

  .sidebar{width:264px; min-height:100vh; background:var(--ink); color:#eee; position:fixed; overflow-y:auto; z-index:20;}
  .sidebar-brand{padding:22px 20px; border-bottom:1px solid rgba(255,255,255,.08);}
  .sidebar .nav-group-title{font-size:.7rem; text-transform:uppercase; letter-spacing:.08em; color:rgba(255,255,255,.4); padding:16px 20px 6px;}
  .sidebar a.nav-link-item{color:#ccc; text-decoration:none; display:flex; align-items:center; gap:10px; padding:9px 20px; margin:1px 10px; border-radius:8px; font-size:.92rem; transition:.15s;}
  .sidebar a.nav-link-item:hover{background:rgba(255,255,255,.06); color:#fff;}
  .sidebar a.nav-link-item.active{background:var(--gold); color:var(--ink); font-weight:600;}
  .sidebar .group-toggle{color:#ddd; text-decoration:none; display:flex; align-items:center; justify-content:space-between; padding:10px 20px; margin:1px 10px; border-radius:8px; font-size:.92rem; font-weight:500; cursor:pointer;}
  .sidebar .group-toggle:hover{background:rgba(255,255,255,.06); color:#fff;}
  .sidebar .group-toggle .chevron{transition:transform .25s; font-size:.75rem;}
  .sidebar .group-toggle[aria-expanded="true"] .chevron{transform:rotate(180deg);}
  .sidebar .group-toggle i.fa-fw{width:18px;}

  .main-content{margin-left:264px; padding:24px;}
  .topbar{background:#fff; border-radius:14px; box-shadow:0 2px 10px rgba(28,27,25,.06); padding:14px 20px; margin-bottom:20px;}
  .stat-card{border:none; border-radius:16px; box-shadow:0 4px 14px rgba(28,27,25,.06);}
  .user-menu-btn{border:none; background:var(--gold); color:var(--ink); font-weight:600; border-radius:999px; padding:8px 16px; display:flex; align-items:center; gap:8px;}
  .user-menu-btn:hover{background:var(--gold-deep); color:#fff;}

  @media (max-width: 991px){
    .sidebar{transform:translateX(-100%); transition:transform .25s;}
    .sidebar.open{transform:translateX(0);}
    .main-content{margin-left:0;}
  }
</style>
</head>
<body>

<?php
// Daftar menu terkelompok — dipakai untuk render sidebar dan menandai menu aktif
$currentPath = trim($_GET['url'] ?? '', '/');
$isSuperadmin = ($_SESSION['role'] ?? '') === 'superadmin';
$base = $this->appConfig['base_url'];

$menuGroups = [
  ['type' => 'single', 'label' => 'Dashboard', 'icon' => 'chart-line', 'url' => '/admin'],
  ['type' => 'group', 'label' => 'Konten', 'icon' => 'newspaper', 'items' => [
      ['label' => 'Hero Section', 'icon' => 'image', 'url' => '/adminhero'],
      ['label' => 'Teks Islami Berjalan', 'icon' => 'moon', 'url' => '/adminislami'],
      ['label' => 'Berita', 'icon' => 'newspaper', 'url' => '/adminberita'],
      ['label' => 'Pengumuman', 'icon' => 'bullhorn', 'url' => '/adminpengumuman'],
      ['label' => 'Galeri', 'icon' => 'images', 'url' => '/admingaleri'],
      ['label' => 'Pesan Kontak', 'icon' => 'envelope', 'url' => '/adminkontak'],
      ['label' => 'Zona Permainan', 'icon' => 'gamepad', 'url' => '/adminpermainan'],
  ]],
  ['type' => 'group', 'label' => 'SDM Sekolah', 'icon' => 'people-group', 'items' => [
      ['label' => 'Guru', 'icon' => 'chalkboard-user', 'url' => '/adminguru'],
      ['label' => 'Struktur Organisasi', 'icon' => 'sitemap', 'url' => '/adminstruktur'],
  ]],
  ['type' => 'group', 'label' => 'Akademik', 'icon' => 'book-open', 'items' => [
      ['label' => 'Kalender Akademik', 'icon' => 'calendar-check', 'url' => '/adminkalender'],
      ['label' => 'Prestasi', 'icon' => 'trophy', 'url' => '/adminprestasi'],
      ['label' => 'Fasilitas', 'icon' => 'building', 'url' => '/adminfasilitas'],
  ]],
  ['type' => 'single', 'label' => 'PPDB', 'icon' => 'user-plus', 'url' => '/adminppdb'],
  ['type' => 'group', 'label' => 'Sekolah', 'icon' => 'school', 'items' => [
      ['label' => 'Profil Sekolah', 'icon' => 'school', 'url' => '/adminprofil'],
      ['label' => 'Testimoni Alumni', 'icon' => 'quote-left', 'url' => '/admintestimoni'],
      ['label' => 'Logo & Branding', 'icon' => 'palette', 'url' => '/adminbranding'],
  ]],
];
if ($isSuperadmin) {
  $menuGroups[] = ['type' => 'single', 'label' => 'Backup & Restore', 'icon' => 'database', 'url' => '/adminbackup'];
}
?>

<div class="sidebar" id="sidebar">
  <div class="sidebar-brand">
    <h5 class="text-warning fw-bold font-display mb-0"><i class="fa-solid fa-graduation-cap me-1"></i> TK IT Quantum School</h5>
  </div>
  <div class="py-2">
    <?php foreach ($menuGroups as $g): ?>
      <?php if ($g['type'] === 'single'):
        $active = str_starts_with($currentPath, ltrim($g['url'], '/'));
      ?>
        <a class="nav-link-item <?= $active ? 'active' : '' ?>" href="<?= $base . $g['url'] ?>">
          <i class="fa-solid fa-<?= $g['icon'] ?> fa-fw"></i> <?= htmlspecialchars($g['label']) ?>
        </a>
      <?php else:
        $groupActive = false;
        foreach ($g['items'] as $it) { if (str_starts_with($currentPath, ltrim($it['url'], '/'))) { $groupActive = true; break; } }
        $collapseId = 'grp-' . preg_replace('/[^a-z0-9]/i', '', $g['label']);
      ?>
        <a class="group-toggle" data-bs-toggle="collapse" href="#<?= $collapseId ?>" role="button"
           aria-expanded="<?= $groupActive ? 'true' : 'false' ?>">
          <span><i class="fa-solid fa-<?= $g['icon'] ?> fa-fw me-2"></i><?= htmlspecialchars($g['label']) ?></span>
          <i class="fa-solid fa-chevron-down chevron"></i>
        </a>
        <div class="collapse <?= $groupActive ? 'show' : '' ?>" id="<?= $collapseId ?>">
          <?php foreach ($g['items'] as $it): $active = str_starts_with($currentPath, ltrim($it['url'], '/')); ?>
            <a class="nav-link-item ps-4 <?= $active ? 'active' : '' ?>" href="<?= $base . $it['url'] ?>">
              <i class="fa-solid fa-<?= $it['icon'] ?> fa-fw"></i> <?= htmlspecialchars($it['label']) ?>
            </a>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    <?php endforeach; ?>
  </div>
</div>

<div class="main-content">
  <div class="topbar d-flex justify-content-between align-items-center">
    <button class="btn btn-sm btn-light d-lg-none" id="sidebarToggle"><i class="fa-solid fa-bars"></i></button>
    <span class="fw-semibold d-none d-lg-block">Halo, <?= htmlspecialchars($_SESSION['nama_lengkap'] ?? '') ?> <span class="badge bg-warning text-dark ms-1"><?= htmlspecialchars($_SESSION['role'] ?? '') ?></span></span>

    <div class="dropdown">
      <button class="user-menu-btn dropdown-toggle" type="button" data-bs-toggle="dropdown">
        <i class="fa-solid fa-circle-user"></i> <?= htmlspecialchars($_SESSION['nama_lengkap'] ?? 'Akun') ?>
      </button>
      <ul class="dropdown-menu dropdown-menu-end shadow border-0">
        <li><a class="dropdown-item" href="<?= $base ?>/auth/gantiPassword"><i class="fa-solid fa-key me-2"></i> Ganti Password</a></li>
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item text-danger" href="<?= $base ?>/auth/logout"><i class="fa-solid fa-right-from-bracket me-2"></i> Logout</a></li>
      </ul>
    </div>
  </div>

  <?php if (!empty($_SESSION['flash_success'])): ?>
    <div class="alert alert-success"><?= htmlspecialchars($_SESSION['flash_success']) ?></div>
    <?php unset($_SESSION['flash_success']); ?>
  <?php endif; ?>
  <?php if (!empty($_SESSION['flash_error'])): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['flash_error']) ?></div>
    <?php unset($_SESSION['flash_error']); ?>
  <?php endif; ?>

  <?= $content ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.getElementById('sidebarToggle')?.addEventListener('click', () => {
  document.getElementById('sidebar').classList.toggle('open');
});

// Helper hapus data — tetap berfungsi walau SweetAlert2 gagal dimuat (mis. tanpa koneksi internet)
window.confirmDelete = function (url, title) {
  title = title || 'Hapus data ini?';
  if (typeof Swal !== 'undefined') {
    Swal.fire({ title, icon: 'warning', showCancelButton: true, confirmButtonColor: '#FF9800', confirmButtonText: 'Ya, hapus' })
      .then(res => { if (res.isConfirmed) window.location.href = url; });
  } else if (confirm(title)) {
    window.location.href = url;
  }
};
document.addEventListener('click', function (e) {
  const btn = e.target.closest('.btn-hapus');
  if (!btn) return;
  e.preventDefault();
  window.confirmDelete(btn.dataset.url, btn.dataset.title);
});
</script>
</body>
</html>
