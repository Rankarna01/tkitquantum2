<section class="py-5">
  <div class="container text-center" style="max-width:560px;">
    <a href="<?= $this->appConfig['base_url'] ?>/permainan" class="small text-decoration-none d-inline-block mb-2">← Kembali ke Zona Permainan</a>
    <p class="section-eyebrow mb-2" data-aos="fade-up">YUK BERMAIN</p>
    <h1 class="fw-bold font-display mb-2" data-aos="fade-up">⌨️ Balap Ketik</h1>
    <p class="text-muted mb-4" data-aos="fade-up">Ketik kata yang muncul secepat &amp; setepat mungkin. Waktu 30 detik!</p>

    <div class="d-flex justify-content-center gap-3 mb-4 flex-wrap">
      <div class="stat-pill px-4 py-2">
        <div class="stat-num" style="font-size:1.4rem;" id="skorKetik">0</div>
        <small class="text-muted">Kata Benar</small>
      </div>
      <div class="stat-pill px-4 py-2">
        <div class="stat-num" style="font-size:1.4rem;" id="sisaWaktuKetik">30</div>
        <small class="text-muted">Sisa Detik</small>
      </div>
    </div>

    <div class="glass-card p-4 mb-4">
      <h2 class="fw-bold font-display mb-3" id="kataTarget" style="letter-spacing:.05em;">mulai</h2>
      <input type="text" id="inputKetik" class="form-control text-center" placeholder="Ketik di sini..." autocomplete="off" disabled>
    </div>

    <button id="btnMulaiKetik" class="btn btn-accent rounded-pill px-4">▶️ Mulai Main</button>

    <div id="menangBoxKetik" class="glass-card p-4 mt-4 d-none" data-aos="zoom-in">
      <h3 class="fw-bold font-display mb-1">🎉 Waktu Habis!</h3>
      <p class="text-muted mb-0">Kamu berhasil mengetik <strong id="hasilKetik">0</strong> kata dengan benar. Jago! ⌨️</p>
    </div>

    <?php include __DIR__ . '/_leaderboard.php'; ?>

<?php include __DIR__ . '/_musik-game.php'; ?>
  </div>
</section>

<script>
(function () {
  const bankKata = ['sekolah','ceria','belajar','bermain','islami','quantum','pintar','sholeh','sholehah','guru','ananda','indah','riang','semangat','sahabat','hafalan','doa','sujud','ilmu','bintang'];

  let benar = 0, sisaWaktu = 30, main = false, timerId = null, kataAktif = '';
  const elBenar = document.getElementById('skorKetik');
  const elWaktu = document.getElementById('sisaWaktuKetik');
  const elKata = document.getElementById('kataTarget');
  const input = document.getElementById('inputKetik');
  const btnMulai = document.getElementById('btnMulaiKetik');
  const menangBox = document.getElementById('menangBoxKetik');

  function kataBaru() {
    kataAktif = bankKata[Math.floor(Math.random() * bankKata.length)];
    elKata.textContent = kataAktif;
    input.value = '';
  }

  function cekInput() {
    if (!main) return;
    if (input.value.trim().toLowerCase() === kataAktif) {
      benar++;
      elBenar.textContent = benar;
      kataBaru();
    }
  }

  function mulai() {
    main = true;
    benar = 0; sisaWaktu = 30;
    elBenar.textContent = '0';
    elWaktu.textContent = '30';
    menangBox.classList.add('d-none');
    btnMulai.disabled = true;
    input.disabled = false;
    input.focus();
    kataBaru();

    clearInterval(timerId);
    timerId = setInterval(() => {
      sisaWaktu--;
      elWaktu.textContent = sisaWaktu;
      if (sisaWaktu <= 0) selesai();
    }, 1000);
  }

  function selesai() {
    main = false;
    clearInterval(timerId);
    input.disabled = true;
    btnMulai.disabled = false;
    elKata.textContent = 'Selesai!';
    document.getElementById('hasilKetik').textContent = benar;
    menangBox.classList.remove('d-none');
    setTimeout(() => window.simpanSkorGame('balap-ketik', benar, benar + ' kata benar dalam 30 detik'), 500);
  }

  input.addEventListener('input', cekInput);
  btnMulai.addEventListener('click', mulai);
})();
</script>
