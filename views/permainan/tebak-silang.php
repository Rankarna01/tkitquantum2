<section class="py-5">
  <div class="container text-center" style="max-width:480px;">
    <a href="<?= $this->appConfig['base_url'] ?>/permainan" class="small text-decoration-none d-inline-block mb-2">← Kembali ke Zona Permainan</a>
    <p class="section-eyebrow mb-2" data-aos="fade-up">YUK BERMAIN</p>
    <h1 class="fw-bold font-display mb-2" data-aos="fade-up">❌ Tebak Silang</h1>
    <p class="text-muted mb-4" data-aos="fade-up">Kamu (❌) melawan Komputer (⭕). Buat 3 tanda sejajar untuk menang!</p>

    <div class="d-flex justify-content-center gap-3 mb-4 flex-wrap">
      <div class="stat-pill px-4 py-2">
        <div class="stat-num" style="font-size:1.4rem;" id="skorMenangSilang">0</div>
        <small class="text-muted">Menang</small>
      </div>
    </div>

    <p class="fw-bold mb-3" id="statusSilang">Giliranmu! Klik salah satu kotak.</p>
    <div id="papanSilang" class="silang-board mb-4"></div>

    <button id="btnUlangSilang" class="btn btn-accent rounded-pill px-4">🔄 Main Lagi</button>

    <?php include __DIR__ . '/_leaderboard.php'; ?>

    <?php include __DIR__ . '/_musik-game.php'; ?>
  </div>
</section>

<style>
  .silang-board{display:grid; grid-template-columns:repeat(3, 1fr); gap:8px; max-width:280px; margin:0 auto;}
  .silang-cell{aspect-ratio:1/1; border-radius:14px; background:#fff; border:2px solid var(--gold-pale); display:flex; align-items:center; justify-content:center; font-size:2.2rem; font-weight:700; cursor:pointer; font-family:'Baloo 2',sans-serif;}
  .silang-cell.x{color:var(--gold-deep);}
  .silang-cell.o{color:#4FC3F7;}
  .silang-cell.menang{background:#EFFBF0; border-color:var(--leaf);}
</style>

<script>
(function () {
  let papan = Array(9).fill(''), menangTotal = 0, giliranPemain = true, permainanSelesai = false;
  const garisMenang = [[0,1,2],[3,4,5],[6,7,8],[0,3,6],[1,4,7],[2,5,8],[0,4,8],[2,4,6]];
  const el = document.getElementById('papanSilang');
  const elStatus = document.getElementById('statusSilang');
  const elSkor = document.getElementById('skorMenangSilang');

  function render() {
    el.innerHTML = '';
    papan.forEach((v, i) => {
      const cell = document.createElement('div');
      cell.className = 'silang-cell' + (v === 'X' ? ' x' : v === 'O' ? ' o' : '');
      cell.textContent = v === 'X' ? '❌' : v === 'O' ? '⭕' : '';
      cell.addEventListener('click', () => klik(i));
      el.appendChild(cell);
    });
  }

  function cekMenang(p, tanda) {
    return garisMenang.find(g => g.every(i => p[i] === tanda));
  }

  function klik(i) {
    if (!giliranPemain || permainanSelesai || papan[i] !== '') return;
    papan[i] = 'X';
    render();

    const garisX = cekMenang(papan, 'X');
    if (garisX) return selesai('Kamu menang! 🎉', true);
    if (!papan.includes('')) return selesai('Seri! 🤝', false);

    giliranPemain = false;
    elStatus.textContent = 'Komputer berpikir...';
    setTimeout(giliranKomputer, 550);
  }

  function giliranKomputer() {
    // Coba menang, lalu blokir, lalu acak
    let langkah = cariLangkahTerbaik('O') ?? cariLangkahTerbaik('X') ?? langkahAcak();
    papan[langkah] = 'O';
    render();

    const garisO = cekMenang(papan, 'O');
    if (garisO) return selesai('Komputer menang! 😅', false);
    if (!papan.includes('')) return selesai('Seri! 🤝', false);

    giliranPemain = true;
    elStatus.textContent = 'Giliranmu! Klik salah satu kotak.';
  }

  function cariLangkahTerbaik(tanda) {
    for (const g of garisMenang) {
      const nilai = g.map(i => papan[i]);
      if (nilai.filter(v => v === tanda).length === 2 && nilai.includes('')) {
        return g[nilai.indexOf('')];
      }
    }
    return null;
  }

  function langkahAcak() {
    const kosong = papan.map((v, i) => v === '' ? i : null).filter(v => v !== null);
    return kosong[Math.floor(Math.random() * kosong.length)];
  }

  function selesai(pesan, menang) {
    permainanSelesai = true;
    elStatus.textContent = pesan;
    if (menang) {
      menangTotal++;
      elSkor.textContent = menangTotal;
      setTimeout(() => window.simpanSkorGame('tebak-silang', menangTotal * 10, menangTotal + ' kali menang'), 500);
    }
  }

  function mulai() {
    papan = Array(9).fill('');
    giliranPemain = true;
    permainanSelesai = false;
    elStatus.textContent = 'Giliranmu! Klik salah satu kotak.';
    render();
  }

  document.getElementById('btnUlangSilang').addEventListener('click', mulai);
  mulai();
})();
</script>
