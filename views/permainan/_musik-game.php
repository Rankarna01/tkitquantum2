<?php if (!empty($musikGame)): ?>
<audio id="musikGameLatar" loop preload="auto">
  <source src="<?= $this->appConfig['base_url'] ?>/<?= htmlspecialchars($musikGame) ?>">
</audio>
<button id="toggleMusikGame" type="button" class="music-toggle" style="bottom:86px;" title="Musik game">
  <i class="fa-solid fa-music"></i>
</button>
<script>
(function () {
  const audio = document.getElementById('musikGameLatar');
  const btn = document.getElementById('toggleMusikGame');
  let nyala = false;

  function mulaiMusik() {
    audio.volume = 0.5;
    audio.play().then(() => {
      nyala = true;
      btn.innerHTML = '<i class="fa-solid fa-volume-high"></i>';
    }).catch(() => {});
  }

  // Nyalakan otomatis begitu pemain berinteraksi pertama kali (klik tombol Mulai, dsb) — sah menurut kebijakan browser
  document.addEventListener('click', function onFirstClick() {
    if (!nyala) mulaiMusik();
  }, { once: true });

  btn.addEventListener('click', function (e) {
    e.stopPropagation();
    if (nyala) {
      audio.pause();
      nyala = false;
      btn.innerHTML = '<i class="fa-solid fa-music"></i>';
    } else {
      mulaiMusik();
    }
  });
})();
</script>
<?php endif; ?>
