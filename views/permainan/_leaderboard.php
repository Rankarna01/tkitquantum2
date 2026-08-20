<div class="glass-card p-4 mt-4" data-aos="fade-up">
  <h5 class="fw-bold mb-3">🏆 Top 10 Skor Tertinggi</h5>
  <?php if (!empty($leaderboard)): ?>
  <ol class="list-group list-group-numbered">
    <?php foreach ($leaderboard as $i => $s): ?>
    <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 <?= $i < 3 ? 'fw-bold' : '' ?>">
      <span><?= $i === 0 ? '🥇' : ($i === 1 ? '🥈' : ($i === 2 ? '🥉' : '')) ?> <?= htmlspecialchars($s['nama_pemain']) ?></span>
      <span class="text-warning"><?= (int) $s['skor'] ?> poin <small class="text-muted">(<?= htmlspecialchars($s['detail'] ?? '') ?>)</small></span>
    </li>
    <?php endforeach; ?>
  </ol>
  <?php else: ?>
    <p class="text-muted small mb-0">Belum ada skor. Jadilah yang pertama! 🌟</p>
  <?php endif; ?>
</div>

<div class="modal fade" id="modalNamaPemain" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="border-radius:20px;">
      <div class="modal-body p-4 text-center">
        <h4 class="fw-bold font-display mb-2">🎉 Skor Baru!</h4>
        <p class="text-muted" id="teksSkorModal">Kamu dapat skor tinggi!</p>
        <input type="text" id="inputNamaPemain" class="form-control text-center mb-3" placeholder="Masukkan namamu" maxlength="50">
        <button id="btnSimpanSkor" class="btn btn-accent rounded-pill px-4">✨ Simpan ke Papan Skor</button>
      </div>
    </div>
  </div>
</div>

<script>
window.PAPAN_SKOR_BASE = "<?= $this->appConfig['base_url'] ?>";
window.simpanSkorGame = function (gameSlug, skor, detail) {
  document.getElementById('teksSkorModal').textContent = 'Skor kamu: ' + skor + ' poin (' + detail + ')';
  const modalEl = document.getElementById('modalNamaPemain');
  const modal = new bootstrap.Modal(modalEl);
  modal.show();

  document.getElementById('btnSimpanSkor').onclick = function () {
    const nama = document.getElementById('inputNamaPemain').value.trim() || 'Anonim';
    const fd = new FormData();
    fd.append('game_slug', gameSlug);
    fd.append('nama_pemain', nama);
    fd.append('skor', skor);
    fd.append('detail', detail);
    fetch(window.PAPAN_SKOR_BASE + '/permainan/simpanSkor', { method: 'POST', body: fd })
      .then(r => r.json())
      .then(() => { modal.hide(); location.reload(); })
      .catch(() => { modal.hide(); });
  };
};
</script>
