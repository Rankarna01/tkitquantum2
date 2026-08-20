<section class="py-5">
  <div class="container text-center" style="max-width:520px;">
    <a href="<?= $this->appConfig['base_url'] ?>/permainan" class="small text-decoration-none d-inline-block mb-2">← Kembali ke Zona Permainan</a>
    <p class="section-eyebrow mb-2" data-aos="fade-up">YUK BERMAIN</p>
    <h1 class="fw-bold font-display mb-2" data-aos="fade-up">🧩 Labirin Ceria</h1>
    <p class="text-muted mb-4" data-aos="fade-up">Bantu 🐰 menemukan jalan ke 🥕! Gunakan tombol panah atau keyboard.</p>

    <div class="d-flex justify-content-center gap-3 mb-4 flex-wrap">
      <div class="stat-pill px-4 py-2">
        <div class="stat-num" style="font-size:1.4rem;" id="langkahLabirin">0</div>
        <small class="text-muted">Langkah</small>
      </div>
      <div class="stat-pill px-4 py-2">
        <div class="stat-num" style="font-size:1.4rem;" id="waktuLabirin">00:00</div>
        <small class="text-muted">Waktu</small>
      </div>
    </div>

    <div id="papanLabirin" class="labirin-board mb-3"></div>

    <div class="d-flex flex-column align-items-center gap-1 mb-4">
      <button class="btn btn-outline-primary btn-arah" data-arah="atas">⬆️</button>
      <div class="d-flex gap-1">
        <button class="btn btn-outline-primary btn-arah" data-arah="kiri">⬅️</button>
        <button class="btn btn-outline-primary btn-arah" data-arah="bawah">⬇️</button>
        <button class="btn btn-outline-primary btn-arah" data-arah="kanan">➡️</button>
      </div>
    </div>

    <button id="btnUlangLabirin" class="btn btn-accent rounded-pill px-4">🔄 Acak Labirin Baru</button>

    <div id="menangBoxLabirin" class="glass-card p-4 mt-4 d-none" data-aos="zoom-in">
      <h3 class="fw-bold font-display mb-1">🎉 Sampai Tujuan!</h3>
      <p class="text-muted mb-0">Berhasil dalam <strong id="hasilLangkahLabirin">0</strong> langkah &amp; <strong id="hasilWaktuLabirin">00:00</strong>!</p>
    </div>

    <?php include __DIR__ . '/_leaderboard.php'; ?>

    <?php include __DIR__ . '/_musik-game.php'; ?>
  </div>
</section>

<style>
  .labirin-board{display:grid; grid-template-columns:repeat(7, 1fr); gap:3px; max-width:360px; margin:0 auto; background:var(--ink); padding:6px; border-radius:14px;}
  .labirin-cell{aspect-ratio:1/1; border-radius:4px; background:#fff; display:flex; align-items:center; justify-content:center; font-size:1.1rem;}
  .labirin-cell.tembok{background:#2E2C55;}
  .btn-arah{width:52px;}
</style>

<script>
(function () {
  const N = 7; // ukuran labirin NxN
  let grid = [], pemain = { r: 0, c: 0 }, tujuan = { r: N - 1, c: N - 1 };
  let langkah = 0, waktu = 0, timerId = null, selesai = false;
  const papan = document.getElementById('papanLabirin');
  const elLangkah = document.getElementById('langkahLabirin');
  const elWaktu = document.getElementById('waktuLabirin');
  const menangBox = document.getElementById('menangBoxLabirin');

  function formatWaktu(d) {
    const m = String(Math.floor(d / 60)).padStart(2, '0');
    const s = String(d % 60).padStart(2, '0');
    return m + ':' + s;
  }

  function buatLabirinAcak() {
    // Grid sederhana: 0 = jalan, 1 = tembok, dibuat acak namun jalur utama dipastikan terbuka
    grid = Array.from({ length: N }, () => Array(N).fill(1));
    // Buat jalur zig-zag pasti terbuka dari (0,0) ke (N-1,N-1)
    let r = 0, c = 0;
    grid[r][c] = 0;
    while (r !== N - 1 || c !== N - 1) {
      if (Math.random() < 0.5 && r < N - 1) r++;
      else if (c < N - 1) c++;
      else if (r < N - 1) r++;
      grid[r][c] = 0;
    }
    // Buka beberapa sel acak tambahan supaya ada pilihan jalan
    for (let i = 0; i < N * 3; i++) {
      const rr = Math.floor(Math.random() * N), cc = Math.floor(Math.random() * N);
      grid[rr][cc] = 0;
    }
    grid[0][0] = 0;
    grid[N - 1][N - 1] = 0;
  }

  function render() {
    papan.innerHTML = '';
    for (let r = 0; r < N; r++) {
      for (let c = 0; c < N; c++) {
        const cell = document.createElement('div');
        cell.className = 'labirin-cell' + (grid[r][c] === 1 ? ' tembok' : '');
        if (r === pemain.r && c === pemain.c) cell.textContent = '🐰';
        else if (r === tujuan.r && c === tujuan.c) cell.textContent = '🥕';
        papan.appendChild(cell);
      }
    }
  }

  function mulaiTimer() {
    clearInterval(timerId);
    waktu = 0;
    elWaktu.textContent = '00:00';
    timerId = setInterval(() => { waktu++; elWaktu.textContent = formatWaktu(waktu); }, 1000);
  }

  function gerak(arah) {
    if (selesai) return;
    let { r, c } = pemain;
    if (arah === 'atas') r--;
    if (arah === 'bawah') r++;
    if (arah === 'kiri') c--;
    if (arah === 'kanan') c++;
    if (r < 0 || r >= N || c < 0 || c >= N || grid[r][c] === 1) return;

    pemain = { r, c };
    langkah++;
    elLangkah.textContent = langkah;
    render();

    if (r === tujuan.r && c === tujuan.c) menang();
  }

  function menang() {
    selesai = true;
    clearInterval(timerId);
    document.getElementById('hasilLangkahLabirin').textContent = langkah;
    document.getElementById('hasilWaktuLabirin').textContent = formatWaktu(waktu);
    menangBox.classList.remove('d-none');
    const skor = Math.max(0, 500 - (langkah * 5) - (waktu * 2));
    setTimeout(() => window.simpanSkorGame('labirin-ceria', skor, langkah + ' langkah, ' + formatWaktu(waktu)), 500);
  }

  function mulai() {
    buatLabirinAcak();
    pemain = { r: 0, c: 0 };
    langkah = 0; selesai = false;
    elLangkah.textContent = '0';
    menangBox.classList.add('d-none');
    render();
    mulaiTimer();
  }

  document.querySelectorAll('.btn-arah').forEach(btn => {
    btn.addEventListener('click', () => gerak(btn.dataset.arah));
  });
  document.addEventListener('keydown', (e) => {
    const map = { ArrowUp: 'atas', ArrowDown: 'bawah', ArrowLeft: 'kiri', ArrowRight: 'kanan' };
    if (map[e.key]) { e.preventDefault(); gerak(map[e.key]); }
  });
  document.getElementById('btnUlangLabirin').addEventListener('click', mulai);
  mulai();
})();
</script>
