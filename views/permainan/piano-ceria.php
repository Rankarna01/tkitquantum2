<section class="py-5">
  <div class="container text-center" style="max-width:560px;">
    <a href="<?= $this->appConfig['base_url'] ?>/permainan" class="small text-decoration-none d-inline-block mb-2">← Kembali ke Zona Permainan</a>
    <p class="section-eyebrow mb-2" data-aos="fade-up">YUK BERMAIN</p>
    <h1 class="fw-bold font-display mb-2" data-aos="fade-up">🎹 Piano Ceria</h1>
    <p class="text-muted mb-4" data-aos="fade-up">Dengarkan nada yang berbunyi, lalu mainkan ulang urutannya dengan menekan tuts yang sama!</p>

    <div class="d-flex justify-content-center gap-3 mb-4 flex-wrap">
      <div class="stat-pill px-4 py-2">
        <div class="stat-num" style="font-size:1.4rem;" id="rondePiano">0</div>
        <small class="text-muted">Ronde</small>
      </div>
    </div>

    <div class="piano-board mb-4" id="papanPiano">
      <div class="piano-key" data-nada="0" style="background:#FF6FA5;">DO</div>
      <div class="piano-key" data-nada="1" style="background:#FFD93D;">RE</div>
      <div class="piano-key" data-nada="2" style="background:#6BD98F;">MI</div>
      <div class="piano-key" data-nada="3" style="background:#4FC3F7;">FA</div>
      <div class="piano-key" data-nada="4" style="background:#B39DDB;">SOL</div>
    </div>

    <button id="btnMulaiPiano" class="btn btn-accent rounded-pill px-4">▶️ Mulai Main</button>

    <div id="kalahBoxPiano" class="glass-card p-4 mt-4 d-none" data-aos="zoom-in">
      <h3 class="fw-bold font-display mb-1">🎈 Hampir Tepat!</h3>
      <p class="text-muted mb-0">Kamu bertahan sampai ronde ke-<strong id="hasilRondePiano">0</strong>. Coba lagi yuk!</p>
    </div>

    <?php include __DIR__ . '/_leaderboard.php'; ?>

    <?php include __DIR__ . '/_musik-game.php'; ?>
  </div>
</section>

<style>
  .piano-board{display:flex; gap:8px; justify-content:center; max-width:480px; margin:0 auto; flex-wrap:wrap;}
  .piano-key{width:70px; height:110px; border-radius:12px; color:#fff; font-weight:700; font-family:'Baloo 2',sans-serif; display:flex; align-items:flex-end; justify-content:center; padding-bottom:10px; cursor:pointer; opacity:.7; transition:opacity .1s, transform .1s; box-shadow:0 4px 10px rgba(43,42,74,.15);}
  .piano-key.nyala{opacity:1; transform:translateY(4px) scale(1.03);}
</style>

<script>
(function () {
  const frekuensi = [261.6, 293.7, 329.6, 349.2, 392.0]; // DO RE MI FA SOL
  const keys = document.querySelectorAll('.piano-key');
  let urutan = [], milikPemain = [], ronde = 0, main = false, bolehKlik = false;
  const elRonde = document.getElementById('rondePiano');
  const btnMulai = document.getElementById('btnMulaiPiano');
  const kalahBox = document.getElementById('kalahBoxPiano');
  let audioCtx = null;

  function mainkanNada(idx) {
    if (!audioCtx) audioCtx = new (window.AudioContext || window.webkitAudioContext)();
    const osc = audioCtx.createOscillator();
    const gain = audioCtx.createGain();
    osc.type = 'sine';
    osc.frequency.value = frekuensi[idx];
    gain.gain.setValueAtTime(0.25, audioCtx.currentTime);
    gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.4);
    osc.connect(gain).connect(audioCtx.destination);
    osc.start();
    osc.stop(audioCtx.currentTime + 0.4);
  }

  function nyalakan(idx) {
    return new Promise((resolve) => {
      keys[idx].classList.add('nyala');
      mainkanNada(idx);
      setTimeout(() => { keys[idx].classList.remove('nyala'); resolve(); }, 450);
    });
  }

  async function tampilkanUrutan() {
    bolehKlik = false;
    await new Promise(r => setTimeout(r, 500));
    for (const idx of urutan) {
      await nyalakan(idx);
      await new Promise(r => setTimeout(r, 200));
    }
    bolehKlik = true;
    milikPemain = [];
  }

  function tambahRonde() {
    ronde++;
    elRonde.textContent = ronde;
    urutan.push(Math.floor(Math.random() * 5));
    tampilkanUrutan();
  }

  function klikKey(idx) {
    if (!bolehKlik || !main) return;
    nyalakan(idx);
    milikPemain.push(idx);
    const posisi = milikPemain.length - 1;
    if (milikPemain[posisi] !== urutan[posisi]) return kalah();
    if (milikPemain.length === urutan.length) {
      bolehKlik = false;
      setTimeout(tambahRonde, 700);
    }
  }

  function kalah() {
    main = false;
    bolehKlik = false;
    document.getElementById('hasilRondePiano').textContent = ronde;
    kalahBox.classList.remove('d-none');
    btnMulai.classList.remove('d-none');
    btnMulai.textContent = '🔄 Main Lagi';
    const skor = ronde * 30;
    setTimeout(() => window.simpanSkorGame('piano-ceria', skor, 'bertahan ' + ronde + ' ronde'), 500);
  }

  function mulai() {
    urutan = []; ronde = 0; main = true;
    elRonde.textContent = '0';
    kalahBox.classList.add('d-none');
    btnMulai.classList.add('d-none');
    tambahRonde();
  }

  keys.forEach(k => k.addEventListener('click', () => klikKey(parseInt(k.dataset.nada, 10))));
  btnMulai.addEventListener('click', mulai);
})();
</script>
