<section class="py-5">
  <div class="container text-center" style="max-width:560px;">
    <a href="<?= $this->appConfig['base_url'] ?>/permainan" class="small text-decoration-none d-inline-block mb-2">← Kembali ke Zona Permainan</a>
    <p class="section-eyebrow mb-2" data-aos="fade-up">YUK BERMAIN</p>
    <h1 class="fw-bold font-display mb-2" data-aos="fade-up">🔢 Tebak Angka</h1>
    <p class="text-muted mb-4" data-aos="fade-up">Aku memikirkan angka antara 1-100. Coba tebak dengan tebakan sesedikit mungkin!</p>

    <div class="d-flex justify-content-center gap-3 mb-4 flex-wrap">
      <div class="stat-pill px-4 py-2">
        <div class="stat-num" style="font-size:1.4rem;" id="jumlahTebakan">0</div>
        <small class="text-muted">Tebakan</small>
      </div>
    </div>

    <div id="petunjukAngka" class="glass-card p-4 mb-4">
      <p class="mb-0 fw-bold" id="teksPetunjuk">Silakan masukkan tebakanmu! 🎯</p>
    </div>

    <div class="d-flex justify-content-center gap-2 mb-4">
      <input type="number" id="inputTebakan" class="form-control text-center" style="max-width:160px;" min="1" max="100" placeholder="1-100">
      <button id="btnTebak" class="btn btn-accent rounded-pill px-4">Tebak!</button>
    </div>

    <button id="btnUlangAngka" class="btn btn-outline-secondary rounded-pill px-4 d-none">🔄 Main Lagi</button>

    <div id="menangBoxAngka" class="glass-card p-4 mt-4 d-none" data-aos="zoom-in">
      <h3 class="fw-bold font-display mb-1">🎉 Tepat Sekali!</h3>
      <p class="text-muted mb-0">Kamu menemukan angkanya dalam <strong id="hasilTebakan">0</strong> kali tebakan!</p>
    </div>

    <?php include __DIR__ . '/_leaderboard.php'; ?>

<?php include __DIR__ . '/_musik-game.php'; ?>
  </div>
</section>

<script>
(function () {
  let target = 0, jumlah = 0, selesai = false;
  const elJumlah = document.getElementById('jumlahTebakan');
  const elPetunjuk = document.getElementById('teksPetunjuk');
  const input = document.getElementById('inputTebakan');
  const btnTebak = document.getElementById('btnTebak');
  const btnUlang = document.getElementById('btnUlangAngka');
  const menangBox = document.getElementById('menangBoxAngka');

  function mulai() {
    target = Math.floor(Math.random() * 100) + 1;
    jumlah = 0; selesai = false;
    elJumlah.textContent = '0';
    elPetunjuk.textContent = 'Silakan masukkan tebakanmu! 🎯';
    menangBox.classList.add('d-none');
    btnUlang.classList.add('d-none');
    input.value = ''; input.disabled = false; btnTebak.disabled = false;
    input.focus();
  }

  function tebak() {
    if (selesai) return;
    const nilai = parseInt(input.value, 10);
    if (isNaN(nilai) || nilai < 1 || nilai > 100) {
      elPetunjuk.textContent = 'Masukkan angka antara 1 sampai 100 ya! 😊';
      return;
    }
    jumlah++;
    elJumlah.textContent = jumlah;

    if (nilai === target) {
      selesai = true;
      input.disabled = true; btnTebak.disabled = true;
      document.getElementById('hasilTebakan').textContent = jumlah;
      menangBox.classList.remove('d-none');
      btnUlang.classList.remove('d-none');
      const skor = Math.max(0, 500 - (jumlah * 20));
      setTimeout(() => window.simpanSkorGame('tebak-angka', skor, jumlah + ' kali tebakan'), 500);
    } else if (nilai < target) {
      elPetunjuk.textContent = '⬆️ Lebih besar dari ' + nilai + '!';
    } else {
      elPetunjuk.textContent = '⬇️ Lebih kecil dari ' + nilai + '!';
    }
    input.value = ''; input.focus();
  }

  btnTebak.addEventListener('click', tebak);
  input.addEventListener('keypress', (e) => { if (e.key === 'Enter') tebak(); });
  btnUlang.addEventListener('click', mulai);
  mulai();
})();
</script>
