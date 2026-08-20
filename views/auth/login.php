<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login — <?= htmlspecialchars($pengaturan['nama_sekolah'] ?? 'TK IT Quantum School') ?></title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
  body{background:linear-gradient(135deg,#FFD54F,#FFC107);min-height:100vh;display:flex;align-items:center;font-family:'Poppins',sans-serif;}
  .login-card{border-radius:20px;box-shadow:0 15px 40px rgba(0,0,0,.15);border:none;max-width:420px;margin:auto;}
  .btn-accent{background:#FF9800;border:none;color:#fff;}
  .btn-accent:hover{background:#e68900;color:#fff;}
</style>
</head>
<body>
<div class="container">
  <div class="card login-card p-4">
    <div class="text-center mb-4">
      <?php if (!empty($pengaturan['logo_login'])): ?>
        <img src="<?= $this->appConfig['base_url'] ?>/<?= htmlspecialchars($pengaturan['logo_login']) ?>" height="48" class="mb-2">
      <?php else: ?>
        <i class="fa-solid fa-graduation-cap fa-2x text-warning"></i>
      <?php endif; ?>
      <h4 class="fw-bold mt-2"><?= htmlspecialchars($pengaturan['nama_sekolah'] ?? 'TK IT Quantum School') ?></h4>
      <p class="text-muted small mb-0">Masuk ke Dashboard Admin</p>
    </div>

    <?php if (!empty($error)): ?>
      <div class="alert alert-danger py-2 small"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if (isset($_GET['expired'])): ?>
      <div class="alert alert-warning py-2 small">Sesi Anda telah berakhir, silakan login kembali.</div>
    <?php endif; ?>

    <form method="POST" action="<?= $this->appConfig['base_url'] ?>/auth/login">
      <?= Security::csrfField() ?>
      <div class="mb-3">
        <label class="form-label small">Username / Email</label>
        <input type="text" name="identity" class="form-control" required autofocus>
      </div>
      <div class="mb-3">
        <label class="form-label small">Password</label>
        <input type="password" name="password" class="form-control" required>
      </div>
      <button type="submit" class="btn btn-accent w-100 py-2 fw-semibold">Masuk</button>
    </form>
    <p class="text-center small mt-3 mb-0"><a href="<?= $this->appConfig['base_url'] ?>/" class="text-decoration-none text-muted">&larr; Kembali ke Beranda</a></p>
  </div>
</div>
</body>
</html>
