<section class="py-5">
  <div class="container text-center" style="max-width:640px;">
    <a href="<?= $this->appConfig['base_url'] ?>/permainan" class="small text-decoration-none d-inline-block mb-2">← Kembali ke Zona Permainan</a>
    <p class="section-eyebrow mb-2" data-aos="fade-up">YUK BERMAIN</p>
    <h1 class="fw-bold font-display mb-2" data-aos="fade-up">🔢 Puzzle Angka</h1>
    <p class="text-muted mb-4" data-aos="fade-up">Geser kotak angka sampai berurutan 1-8 dengan langkah sesedikit mungkin!</p>

    <div class="d-flex justify-content-center gap-3 mb-4 flex-wrap">
      <div class="stat-pill px-4 py-2">
        <div class="stat-num" style="font-size:1.4rem;" id="skorLangkahPuzzle">0</div>
        <small class="text-muted">Langkah</small>
      </div>
      <div class="stat-pill px-4 py-2">
        <div class="stat-num" style="font-size:1.4rem;" id="skorWaktuPuzzle">00:00</div>
        <small class="text-muted">Waktu</small>
      </div>
    </div>

    <div id="papanPuzzle" class="puzzle-board mb-4"></div>

    <button id="btnUlangPuzzle" class="btn btn-accent rounded-pill px-4">🔄 Acak Ulang</button>

    <div id="menangBoxPuzzle" class="glass-card p-4 mt-4 d-none" data-aos="zoom-in">
      <h3 class="fw-bold font-display mb-1">🎉 Berhasil Disusun!</h3>
      <p class="text-muted mb-0">Selesai dalam <strong id="hasilLangkahPuzzle">0</strong> langkah &amp; <strong id="hasilWaktuPuzzle">00:00</strong>. Mantap! 🌟</p>
    </div>

    <?php include __DIR__ . '/_leaderboard.php'; ?>

<?php include __DIR__ . '/_musik-game.php'; ?>
  </div>
</section>

<style>
  .puzzle-board{display:grid; grid-template-columns:repeat(3, 1fr); gap:10px; max-width:340px; margin:0 auto;}
  .puzzle-tile{aspect-ratio:1/1; border-radius:14px; background:linear-gradient(135deg, var(--gold), var(--gold-deep)); color:#fff; display:flex; align-items:center; justify-content:center; font-size:1.8rem; font-weight:700; font-family:'Baloo 2',sans-serif; cursor:pointer; box-shadow:0 4px 10px rgba(43,42,74,.15); transition:transform .15s;}
  .puzzle-tile:active{transform:scale(.95);}
  .puzzle-tile.kosong{background:transparent; box-shadow:none; cursor:default;}
</style>

<script>
(function () {
  const papan = document.getElementById('papanPuzzle');
  const elLangkah = document.getElementById('skorLangkahPuzzle');
  const elWaktu = document.getElementById('skorWaktuPuzzle');
  const menangBox = document.getElementById('menangBoxPuzzle');

  let posisi = [], langkah = 0, waktu = 0, timerId = null, selesaiFlag = false;
  const urutanMenang = [1,2,3,4,5,6,7,8,0]; // 0 = kosong

  function formatWaktu(detik) {
    const m = String(Math.floor(detik / 60)).padStart(2, '0');
    const s = String(detik % 60).padStart(2, '0');
    return m + ':' + s;
  }

  function mulaiTimer() {
    clearInterval(timerId);
    waktu = 0;
    elWaktu.textContent = '00:00';
    timerId = setInterval(() => { waktu++; elWaktu.textContent = formatWaktu(waktu); }, 1000);
  }

  function bisaDiselesaikan(arr) {
    // Hitung jumlah inversi; puzzle 3x3 bisa diselesaikan jika inversi genap
    const flat = arr.filter(n => n !== 0);
    let inv = 0;
    for (let i = 0; i < flat.length; i++)
      for (let j = i + 1; j < flat.length; j++)
        if (flat[i] > flat[j]) inv++;
    return inv % 2 === 0;
  }

  function acakPapan() {
    let arr;
    do {
      arr = [1,2,3,4,5,6,7,8,0];
      for (let i = arr.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [arr[i], arr[j]] = [arr[j], arr[i]];
      }
    } while (!bisaDiselesaikan(arr) || arr.join(',') === urutanMenang.join(','));
    return arr;
  }

  function render() {
    papan.innerHTML = '';
    posisi.forEach((val, idx) => {
      const tile = document.createElement('div');
      tile.className = 'puzzle-tile' + (val === 0 ? ' kosong' : '');
      tile.textContent = val === 0 ? '' : val;
      tile.addEventListener('click', () => geser(idx));
      papan.appendChild(tile);
    });
  }

  function geser(idx) {
    if (selesaiFlag) return;
    const kosongIdx = posisi.indexOf(0);
    const baris = Math.floor(idx / 3), kolom = idx % 3;
    const barisKosong = Math.floor(kosongIdx / 3), kolomKosong = kosongIdx % 3;
    const bersebelahan = (Math.abs(baris - barisKosong) + Math.abs(kolom - kolomKosong)) === 1;
    if (!bersebelahan) return;

    [posisi[idx], posisi[kosongIdx]] = [posisi[kosongIdx], posisi[idx]];
    langkah++;
    elLangkah.textContent = langkah;
    render();

    if (posisi.join(',') === urutanMenang.join(',')) selesai();
  }

  function mulaiBaru() {
    posisi = acakPapan();
    langkah = 0; selesaiFlag = false;
    elLangkah.textContent = '0';
    menangBox.classList.add('d-none');
    render();
    mulaiTimer();
  }

  function selesai() {
    selesaiFlag = true;
    clearInterval(timerId);
    document.getElementById('hasilLangkahPuzzle').textContent = langkah;
    document.getElementById('hasilWaktuPuzzle').textContent = formatWaktu(waktu);
    menangBox.classList.remove('d-none');

    const skor = Math.max(0, 1000 - (langkah * 5) - (waktu * 2));
    const detail = langkah + ' langkah, ' + formatWaktu(waktu);
    setTimeout(() => window.simpanSkorGame('puzzle-angka', skor, detail), 600);
  }

  document.getElementById('btnUlangPuzzle').addEventListener('click', mulaiBaru);
  mulaiBaru();
})();
</script>
