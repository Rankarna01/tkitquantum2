<section class="py-5">
  <div class="container text-center" style="max-width:560px;">
    <a href="<?= $this->appConfig['base_url'] ?>/permainan" class="small text-decoration-none d-inline-block mb-2">← Kembali ke Zona Permainan</a>
    <p class="section-eyebrow mb-2" data-aos="fade-up">YUK BERMAIN</p>
    <h1 class="fw-bold font-display mb-2" data-aos="fade-up">🔺 Klik Bentuk Sama</h1>
    <p class="text-muted mb-4" data-aos="fade-up">Perhatikan bentuk target di atas, lalu klik semua yang sama secepat mungkin!</p>

    <div class="d-flex justify-content-center gap-3 mb-4 flex-wrap">
      <div class="stat-pill px-4 py-2">
        <div class="stat-num" style="font-size:1.4rem;" id="skorBentukBenar">0</div>
        <small class="text-muted">Skor</small>
      </div>
      <div class="stat-pill px-4 py-2">
        <div class="stat-num" style="font-size:1.4rem;" id="sisaWaktuBentuk">30</div>
        <small class="text-muted">Sisa Detik</small>
      </div>
    </div>

    <div class="glass-card p-3 mb-3 d-inline-block">
      <span class="small text-muted d-block mb-1">Target:</span>
      <span style="font-size:2.2rem;" id="targetBentuk">🔺</span>
    </div>

    <div id="papanBentuk" class="bentuk-board mb-4"></div>

    <button id="btnMulaiBentuk" class="btn btn-accent rounded-pill px-4">▶️ Mulai Main</button>

    <div id="menangBoxBentuk" class="glass-card p-4 mt-4 d-none" data-aos="zoom-in">
      <h3 class="fw-bold font-display mb-1">🎉 Waktu Habis!</h3>
      <p class="text-muted mb-0">Skor akhir kamu: <strong id="hasilBentuk">0</strong>. Keren!</p>
    </div>

    <?php include __DIR__ . '/_leaderboard.php'; ?>

    <?php include __DIR__ . '/_musik-game.php'; ?>
  </div>
</section>

<style>
  .bentuk-board{display:grid; grid-template-columns:repeat(4, 1fr); gap:10px; max-width:400px; margin:0 auto;}
  .bentuk-cell{aspect-ratio:1/1; border-radius:14px; background:#fff; border:2px solid var(--gold-pale); display:flex; align-items:center; justify-content:center; font-size:1.8rem; cursor:pointer; transition:.1s;}
  .bentuk-cell:active{transform:scale(.9);}
  .bentuk-cell.benar-klik{background:#EFFBF0; border-color:var(--leaf);}
  .bentuk-cell.salah-klik{background:#FFE9EC; border-color:#FF6FA5;}
</style>

<script>
(function () {
  const bentukSet = ['🔺','🔵','🟩','⭐','🔶'];
  let target = '', skor = 0, sisaWaktu = 30, main = false, timerId = null;
  const papan = document.getElementById('papanBentuk');
  const elTarget = document.getElementById('targetBentuk');
  const elSkor = document.getElementById('skorBentukBenar');
  const elWaktu = document.getElementById('sisaWaktuBentuk');
  const btnMulai = document.getElementById('btnMulaiBentuk');
  const menangBox = document.getElementById('menangBoxBentuk');

  function acakBentuk() {
    return bentukSet[Math.floor(Math.random() * bentukSet.length)];
  }

  function buatPapan() {
    papan.innerHTML = '';
    for (let i = 0; i < 12; i++) {
      const cell = document.createElement('div');
      cell.className = 'bentuk-cell';
      const bentuk = Math.random() < 0.35 ? target : acakBentuk();
      cell.textContent = bentuk;
      cell.dataset.bentuk = bentuk;
      cell.addEventListener('click', () => klikCell(cell));
      papan.appendChild(cell);
    }
  }

  function klikCell(cell) {
    if (!main || cell.classList.contains('benar-klik') || cell.classList.contains('salah-klik')) return;
    if (cell.dataset.bentuk === target) {
      skor++;
      elSkor.textContent = skor;
      cell.classList.add('benar-klik');
    } else {
      skor = Math.max(0, skor - 1);
      elSkor.textContent = skor;
      cell.classList.add('salah-klik');
    }
    setTimeout(() => { cell.textContent = acakBentuk(); cell.dataset.bentuk = cell.textContent; cell.className = 'bentuk-cell'; }, 250);
  }

  function gantiTarget() {
    target = acakBentuk();
    elTarget.textContent = target;
  }

  function mulai() {
    main = true;
    skor = 0; sisaWaktu = 30;
    elSkor.textContent = '0';
    elWaktu.textContent = '30';
    menangBox.classList.add('d-none');
    btnMulai.disabled = true;
    gantiTarget();
    buatPapan();

    clearInterval(timerId);
    timerId = setInterval(() => {
      sisaWaktu--;
      elWaktu.textContent = sisaWaktu;
      if (sisaWaktu % 7 === 0) gantiTarget();
      if (sisaWaktu <= 0) selesai();
    }, 1000);
  }

  function selesai() {
    main = false;
    clearInterval(timerId);
    btnMulai.disabled = false;
    document.getElementById('hasilBentuk').textContent = skor;
    menangBox.classList.remove('d-none');
    setTimeout(() => window.simpanSkorGame('klik-bentuk', skor, 'skor ' + skor + ' dalam 30 detik'), 500);
  }

  btnMulai.addEventListener('click', mulai);
})();
</script>
