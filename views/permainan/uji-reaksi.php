<section class="py-5">
  <div class="container text-center" style="max-width:560px;">
    <a href="<?= $this->appConfig['base_url'] ?>/permainan" class="small text-decoration-none d-inline-block mb-2">← Kembali ke Zona Permainan</a>
    <p class="section-eyebrow mb-2" data-aos="fade-up">YUK BERMAIN</p>
    <h1 class="fw-bold font-display mb-2" data-aos="fade-up">⚡ Uji Reaksi</h1>
    <p class="text-muted mb-4" data-aos="fade-up">Klik kotaknya secepat mungkin begitu warnanya berubah jadi hijau! Ada 5 ronde.</p>

    <div class="d-flex justify-content-center gap-3 mb-4 flex-wrap">
      <div class="stat-pill px-4 py-2">
        <div class="stat-num" style="font-size:1.4rem;" id="rondeReaksi">0/5</div>
        <small class="text-muted">Ronde</small>
      </div>
      <div class="stat-pill px-4 py-2">
        <div class="stat-num" style="font-size:1.4rem;" id="rataReaksi">-</div>
        <small class="text-muted">Rata-rata (ms)</small>
      </div>
    </div>

    <div id="kotakReaksi" class="reaksi-box mb-4">Klik "Mulai" untuk bermain</div>

    <button id="btnMulaiReaksi" class="btn btn-accent rounded-pill px-4">▶️ Mulai Main</button>

    <div id="menangBoxReaksi" class="glass-card p-4 mt-4 d-none" data-aos="zoom-in">
      <h3 class="fw-bold font-display mb-1">🎉 Selesai!</h3>
      <p class="text-muted mb-0">Rata-rata waktu reaksimu: <strong id="hasilRataReaksi">0</strong> ms. <span id="komentarReaksi"></span></p>
    </div>

    <?php include __DIR__ . '/_leaderboard.php'; ?>

<?php include __DIR__ . '/_musik-game.php'; ?>
  </div>
</section>

<style>
  .reaksi-box{max-width:320px; height:220px; margin:0 auto; border-radius:24px; background:#E9ECEF; color:#6c757d; display:flex; align-items:center; justify-content:center; font-weight:700; font-family:'Baloo 2',sans-serif; font-size:1.2rem; cursor:pointer; transition:background .15s; text-align:center; padding:1rem;}
  .reaksi-box.tunggu{background:#FFD6DE; color:#c2185b;}
  .reaksi-box.siap{background:var(--leaf); color:#fff;}
</style>

<script>
(function () {
  const kotak = document.getElementById('kotakReaksi');
  const btnMulai = document.getElementById('btnMulaiReaksi');
  const elRonde = document.getElementById('rondeReaksi');
  const elRata = document.getElementById('rataReaksi');
  const menangBox = document.getElementById('menangBoxReaksi');
  const totalRonde = 5;

  let ronde = 0, waktuMulai = 0, hasilArr = [], status = 'idle', timeoutId = null;

  function rondeBaru() {
    status = 'tunggu';
    kotak.className = 'reaksi-box tunggu';
    kotak.textContent = 'Tunggu warna hijau...';
    const delay = 1000 + Math.random() * 2500;
    timeoutId = setTimeout(() => {
      status = 'siap';
      kotak.className = 'reaksi-box siap';
      kotak.textContent = 'KLIK SEKARANG!';
      waktuMulai = performance.now();
    }, delay);
  }

  function klikKotak() {
    if (status === 'idle') return;
    if (status === 'tunggu') {
      clearTimeout(timeoutId);
      kotak.textContent = 'Terlalu cepat! Tunggu warnanya berubah 😅';
      kotak.className = 'reaksi-box tunggu';
      setTimeout(rondeBaru, 1000);
      return;
    }
    if (status === 'siap') {
      const reaksi = Math.round(performance.now() - waktuMulai);
      hasilArr.push(reaksi);
      ronde++;
      elRonde.textContent = ronde + '/' + totalRonde;
      kotak.textContent = reaksi + ' ms!';
      kotak.className = 'reaksi-box';
      status = 'idle';

      if (ronde >= totalRonde) {
        setTimeout(selesai, 800);
      } else {
        setTimeout(rondeBaru, 900);
      }
    }
  }

  function selesai() {
    const rata = Math.round(hasilArr.reduce((a, b) => a + b, 0) / hasilArr.length);
    elRata.textContent = rata;
    document.getElementById('hasilRataReaksi').textContent = rata;
    let komentar = 'Lumayan! 👍';
    if (rata < 300) komentar = 'Wah, secepat kilat! ⚡';
    else if (rata < 450) komentar = 'Reaksimu keren! 🌟';
    document.getElementById('komentarReaksi').textContent = komentar;
    menangBox.classList.remove('d-none');
    btnMulai.disabled = false;
    btnMulai.textContent = '🔄 Main Lagi';

    const skor = Math.max(0, 1000 - rata);
    setTimeout(() => window.simpanSkorGame('uji-reaksi', skor, 'rata-rata ' + rata + ' ms'), 500);
  }

  function mulai() {
    ronde = 0; hasilArr = []; status = 'idle';
    elRonde.textContent = '0/' + totalRonde;
    elRata.textContent = '-';
    menangBox.classList.add('d-none');
    btnMulai.disabled = true;
    rondeBaru();
  }

  kotak.addEventListener('click', klikKotak);
  btnMulai.addEventListener('click', mulai);
})();
</script>
