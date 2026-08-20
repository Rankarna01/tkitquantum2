<section class="py-5">
  <div class="container text-center" style="max-width:560px;">
    <a href="<?= $this->appConfig['base_url'] ?>/permainan" class="small text-decoration-none d-inline-block mb-2">← Kembali ke Zona Permainan</a>
    <p class="section-eyebrow mb-2" data-aos="fade-up">YUK BERMAIN</p>
    <h1 class="fw-bold font-display mb-2" data-aos="fade-up">🎨 Ketuk Warna</h1>
    <p class="text-muted mb-4" data-aos="fade-up">Perhatikan urutan warna yang menyala, lalu ulangi dengan mengetuknya. Makin lama makin panjang!</p>

    <div class="d-flex justify-content-center gap-3 mb-4 flex-wrap">
      <div class="stat-pill px-4 py-2">
        <div class="stat-num" style="font-size:1.4rem;" id="ronde">0</div>
        <small class="text-muted">Ronde</small>
      </div>
    </div>

    <div class="simon-board mb-4" id="papanSimon">
      <div class="simon-tile" data-warna="0" style="background:#FF6FA5;"></div>
      <div class="simon-tile" data-warna="1" style="background:#4FC3F7;"></div>
      <div class="simon-tile" data-warna="2" style="background:#FFD93D;"></div>
      <div class="simon-tile" data-warna="3" style="background:#6BD98F;"></div>
    </div>

    <button id="btnMulaiSimon" class="btn btn-accent rounded-pill px-4">▶️ Mulai Main</button>

    <div id="kalahBoxSimon" class="glass-card p-4 mt-4 d-none" data-aos="zoom-in">
      <h3 class="fw-bold font-display mb-1">🎈 Hampir Berhasil!</h3>
      <p class="text-muted mb-0">Kamu bertahan sampai ronde ke-<strong id="hasilRonde">0</strong>. Coba lagi yuk!</p>
    </div>

    <?php include __DIR__ . '/_leaderboard.php'; ?>

<?php include __DIR__ . '/_musik-game.php'; ?>
  </div>
</section>

<style>
  .simon-board{display:grid; grid-template-columns:repeat(2, 1fr); gap:12px; max-width:280px; margin:0 auto;}
  .simon-tile{aspect-ratio:1/1; border-radius:20px; cursor:pointer; opacity:.55; transition:opacity .15s, transform .1s; box-shadow:0 4px 12px rgba(43,42,74,.15);}
  .simon-tile.nyala{opacity:1; transform:scale(1.05);}
  .simon-tile.terkunci{cursor:not-allowed;}
</style>

<script>
(function () {
  let urutan = [], milikPemain = [], ronde = 0, main = false, bolehKlik = false;
  const elRonde = document.getElementById('ronde');
  const tiles = document.querySelectorAll('.simon-tile');
  const btnMulai = document.getElementById('btnMulaiSimon');
  const kalahBox = document.getElementById('kalahBoxSimon');

  function nyalakan(idx) {
    return new Promise((resolve) => {
      tiles[idx].classList.add('nyala');
      setTimeout(() => { tiles[idx].classList.remove('nyala'); resolve(); }, 450);
    });
  }

  async function tampilkanUrutan() {
    bolehKlik = false;
    tiles.forEach(t => t.classList.add('terkunci'));
    await new Promise(r => setTimeout(r, 500));
    for (const idx of urutan) {
      await nyalakan(idx);
      await new Promise(r => setTimeout(r, 200));
    }
    tiles.forEach(t => t.classList.remove('terkunci'));
    bolehKlik = true;
    milikPemain = [];
  }

  function tambahRonde() {
    ronde++;
    elRonde.textContent = ronde;
    urutan.push(Math.floor(Math.random() * 4));
    tampilkanUrutan();
  }

  function klikTile(idx) {
    if (!bolehKlik || !main) return;
    nyalakan(idx);
    milikPemain.push(idx);

    const posisi = milikPemain.length - 1;
    if (milikPemain[posisi] !== urutan[posisi]) {
      kalah();
      return;
    }
    if (milikPemain.length === urutan.length) {
      bolehKlik = false;
      setTimeout(tambahRonde, 700);
    }
  }

  function kalah() {
    main = false;
    bolehKlik = false;
    document.getElementById('hasilRonde').textContent = ronde;
    kalahBox.classList.remove('d-none');
    btnMulai.classList.remove('d-none');
    btnMulai.textContent = '🔄 Main Lagi';

    const skor = ronde * 30;
    setTimeout(() => window.simpanSkorGame('ketuk-warna', skor, 'bertahan ' + ronde + ' ronde'), 500);
  }

  function mulai() {
    urutan = []; ronde = 0; main = true;
    elRonde.textContent = '0';
    kalahBox.classList.add('d-none');
    btnMulai.classList.add('d-none');
    tambahRonde();
  }

  tiles.forEach(t => t.addEventListener('click', () => klikTile(parseInt(t.dataset.warna, 10))));
  btnMulai.addEventListener('click', mulai);
})();
</script>
