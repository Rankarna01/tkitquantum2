<section class="py-5">
  <div class="container text-center" style="max-width:560px;">
    <a href="<?= $this->appConfig['base_url'] ?>/permainan" class="small text-decoration-none d-inline-block mb-2">← Kembali ke Zona Permainan</a>
    <p class="section-eyebrow mb-2" data-aos="fade-up">YUK BERMAIN</p>
    <h1 class="fw-bold font-display mb-2" data-aos="fade-up">➕ Hitung Cepat</h1>
    <p class="text-muted mb-4" data-aos="fade-up">Jawab soal penjumlahan & pengurangan sebanyak mungkin dalam 60 detik!</p>

    <div class="d-flex justify-content-center gap-3 mb-4 flex-wrap">
      <div class="stat-pill px-4 py-2">
        <div class="stat-num" style="font-size:1.4rem;" id="skorBenar">0</div>
        <small class="text-muted">Benar</small>
      </div>
      <div class="stat-pill px-4 py-2">
        <div class="stat-num" style="font-size:1.4rem;" id="sisaWaktuHitung">60</div>
        <small class="text-muted">Sisa Detik</small>
      </div>
    </div>

    <div class="glass-card p-4 mb-4">
      <h2 class="fw-bold font-display mb-3" id="soalHitung">? + ? = ?</h2>
      <div class="row g-2" id="pilihanHitung"></div>
    </div>

    <button id="btnMulaiHitung" class="btn btn-accent rounded-pill px-4">▶️ Mulai Main</button>

    <div id="menangBoxHitung" class="glass-card p-4 mt-4 d-none" data-aos="zoom-in">
      <h3 class="fw-bold font-display mb-1">🎉 Waktu Habis!</h3>
      <p class="text-muted mb-0">Kamu menjawab benar <strong id="hasilBenar">0</strong> soal. Keren! 🌟</p>
    </div>

    <?php include __DIR__ . '/_leaderboard.php'; ?>

<?php include __DIR__ . '/_musik-game.php'; ?>
  </div>
</section>

<script>
(function () {
  let benar = 0, sisaWaktu = 60, main = false, timerId = null, jawabanBenar = 0;
  const elBenar = document.getElementById('skorBenar');
  const elWaktu = document.getElementById('sisaWaktuHitung');
  const elSoal = document.getElementById('soalHitung');
  const elPilihan = document.getElementById('pilihanHitung');
  const btnMulai = document.getElementById('btnMulaiHitung');
  const menangBox = document.getElementById('menangBoxHitung');

  function acak(min, max) { return Math.floor(Math.random() * (max - min + 1)) + min; }

  function soalBaru() {
    const op = Math.random() > 0.5 ? '+' : '-';
    let a, b, hasil;
    if (op === '+') {
      a = acak(1, 20); b = acak(1, 20); hasil = a + b;
    } else {
      a = acak(10, 30); b = acak(1, a); hasil = a - b;
    }
    jawabanBenar = hasil;
    elSoal.textContent = a + ' ' + op + ' ' + b + ' = ?';

    const opsi = new Set([hasil]);
    while (opsi.size < 4) opsi.add(Math.max(0, hasil + acak(-5, 5)));
    const opsiArr = Array.from(opsi).sort(() => Math.random() - 0.5);

    elPilihan.innerHTML = '';
    opsiArr.forEach((val) => {
      const col = document.createElement('div');
      col.className = 'col-6';
      const btn = document.createElement('button');
      btn.className = 'btn btn-outline-primary w-100 fw-bold';
      btn.textContent = val;
      btn.addEventListener('click', () => jawab(val));
      col.appendChild(btn);
      elPilihan.appendChild(col);
    });
  }

  function jawab(val) {
    if (!main) return;
    if (val === jawabanBenar) {
      benar++;
      elBenar.textContent = benar;
    }
    soalBaru();
  }

  function mulai() {
    main = true;
    benar = 0; sisaWaktu = 60;
    elBenar.textContent = '0';
    elWaktu.textContent = '60';
    menangBox.classList.add('d-none');
    btnMulai.disabled = true;
    soalBaru();

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
    btnMulai.disabled = false;
    elPilihan.innerHTML = '';
    elSoal.textContent = 'Selesai!';
    document.getElementById('hasilBenar').textContent = benar;
    menangBox.classList.remove('d-none');
    setTimeout(() => window.simpanSkorGame('hitung-cepat', benar, benar + ' soal benar dalam 60 detik'), 500);
  }

  btnMulai.addEventListener('click', mulai);
})();
</script>
