<section class="py-5">
  <div class="container text-center" style="max-width:640px;">
    <a href="<?= $this->appConfig['base_url'] ?>/permainan" class="small text-decoration-none d-inline-block mb-2">← Kembali ke Zona Permainan</a>
    <p class="section-eyebrow mb-2" data-aos="fade-up">YUK BERMAIN</p>
    <h1 class="fw-bold font-display mb-2" data-aos="fade-up">⭐ Tangkap Bintang</h1>
    <p class="text-muted mb-4" data-aos="fade-up">Klik bintang yang muncul secepat mungkin sebelum menghilang! Waktu 30 detik ✨</p>

    <div class="d-flex justify-content-center gap-3 mb-4 flex-wrap">
      <div class="stat-pill px-4 py-2">
        <div class="stat-num" style="font-size:1.4rem;" id="skorTangkap">0</div>
        <small class="text-muted">Tertangkap</small>
      </div>
      <div class="stat-pill px-4 py-2">
        <div class="stat-num" style="font-size:1.4rem;" id="sisaWaktu">30</div>
        <small class="text-muted">Sisa Detik</small>
      </div>
    </div>

    <div id="papanBintang" class="bintang-board mb-4"></div>

    <button id="btnMulaiBintang" class="btn btn-accent rounded-pill px-4">▶️ Mulai Main</button>

    <div id="menangBoxBintang" class="glass-card p-4 mt-4 d-none" data-aos="zoom-in">
      <h3 class="fw-bold font-display mb-1">🎉 Waktu Habis!</h3>
      <p class="text-muted mb-0">Kamu berhasil menangkap <strong id="hasilTangkap">0</strong> bintang. Hebat! 🌟</p>
    </div>

    <?php include __DIR__ . '/_leaderboard.php'; ?>

<?php include __DIR__ . '/_musik-game.php'; ?>
  </div>
</section>

<style>
  .bintang-board{display:grid; grid-template-columns:repeat(3, 1fr); gap:14px; max-width:420px; margin:0 auto;}
  .bintang-cell{aspect-ratio:1/1; border-radius:18px; background:#fff; border:2px dashed var(--gold-pale); display:flex; align-items:center; justify-content:center; font-size:2.4rem; cursor:pointer; transition:.15s; user-select:none;}
  .bintang-cell.aktif{background:linear-gradient(135deg, var(--sun), var(--gold-deep)); border-color:transparent; animation:popStar .3s;}
  @keyframes popStar{ from{transform:scale(.5);} to{transform:scale(1);} }
</style>

<script>
(function () {
  const jumlahSel = 9;
  const papan = document.getElementById('papanBintang');
  const elTangkap = document.getElementById('skorTangkap');
  const elWaktu = document.getElementById('sisaWaktu');
  const menangBox = document.getElementById('menangBoxBintang');
  const btnMulai = document.getElementById('btnMulaiBintang');

  let tangkap = 0, sisaWaktu = 30, selAktif = -1, timerMain = null, timerBintang = null, main = false;
  let sel = [];

  function buatPapan() {
    papan.innerHTML = '';
    sel = [];
    for (let i = 0; i < jumlahSel; i++) {
      const cell = document.createElement('div');
      cell.className = 'bintang-cell';
      cell.addEventListener('click', () => klikSel(i));
      papan.appendChild(cell);
      sel.push(cell);
    }
  }

  function munculkanBintang() {
    if (selAktif >= 0) sel[selAktif].classList.remove('aktif');
    selAktif = Math.floor(Math.random() * jumlahSel);
    sel[selAktif].classList.add('aktif');
    sel[selAktif].textContent = '⭐';
  }

  function klikSel(i) {
    if (!main) return;
    if (i === selAktif) {
      tangkap++;
      elTangkap.textContent = tangkap;
      sel[i].classList.remove('aktif');
      sel[i].textContent = '';
      selAktif = -1;
      clearTimeout(timerBintang);
      timerBintang = setTimeout(munculkanBintang, 250 + Math.random() * 350);
    }
  }

  function mulai() {
    main = true;
    tangkap = 0; sisaWaktu = 30; selAktif = -1;
    elTangkap.textContent = '0';
    elWaktu.textContent = '30';
    menangBox.classList.add('d-none');
    btnMulai.disabled = true;
    buatPapan();
    munculkanBintang();

    clearInterval(timerMain);
    timerMain = setInterval(() => {
      sisaWaktu--;
      elWaktu.textContent = sisaWaktu;
      if (sisaWaktu <= 0) selesai();
    }, 1000);
  }

  function selesai() {
    main = false;
    clearInterval(timerMain);
    clearTimeout(timerBintang);
    btnMulai.disabled = false;
    document.getElementById('hasilTangkap').textContent = tangkap;
    menangBox.classList.remove('d-none');

    const detail = tangkap + ' tangkap dalam 30 detik';
    setTimeout(() => window.simpanSkorGame('tangkap-bintang', tangkap, detail), 600);
  }

  btnMulai.addEventListener('click', mulai);
  buatPapan();
})();
</script>
