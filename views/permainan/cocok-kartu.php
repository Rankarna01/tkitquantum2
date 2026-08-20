<section class="py-5">
  <div class="container text-center" style="max-width:720px;">
    <a href="<?= $this->appConfig['base_url'] ?>/permainan" class="small text-decoration-none d-inline-block mb-2">← Kembali ke Zona Permainan</a>
    <p class="section-eyebrow mb-2" data-aos="fade-up">YUK BERMAIN</p>
    <h1 class="fw-bold font-display mb-2" data-aos="fade-up">🐬 Cocokkan Kartunya!</h1>
    <p class="text-muted mb-4" data-aos="fade-up">Latih ingatan si kecil sambil bermain. Klik dua kartu untuk mencocokkan gambar yang sama ✨</p>

    <div class="d-flex justify-content-center gap-3 mb-4 flex-wrap">
      <div class="stat-pill px-4 py-2">
        <div class="stat-num" style="font-size:1.4rem;" id="skorLangkah">0</div>
        <small class="text-muted">Langkah</small>
      </div>
      <div class="stat-pill px-4 py-2">
        <div class="stat-num" style="font-size:1.4rem;" id="skorWaktu">00:00</div>
        <small class="text-muted">Waktu</small>
      </div>
      <div class="stat-pill px-4 py-2">
        <div class="stat-num" style="font-size:1.4rem;" id="skorCocok">0/8</div>
        <small class="text-muted">Pasangan</small>
      </div>
    </div>

    <div id="papanGame" class="memory-board mb-4"></div>

    <button id="btnUlang" class="btn btn-accent rounded-pill px-4">🔄 Main Lagi</button>

    <div id="menangBox" class="glass-card p-4 mt-4 d-none" data-aos="zoom-in">
      <h3 class="fw-bold font-display mb-1">🎉 Hore, Kamu Menang!</h3>
      <p class="text-muted mb-0">Selesai dalam <strong id="hasilLangkah">0</strong> langkah &amp; <strong id="hasilWaktu">00:00</strong>. Keren sekali! 🌟</p>
    </div>

    <?php include __DIR__ . '/_leaderboard.php'; ?>

<?php include __DIR__ . '/_musik-game.php'; ?>
  </div>
</section>

<style>
  .memory-board{display:grid; grid-template-columns:repeat(4, 1fr); gap:12px; max-width:520px; margin:0 auto;}
  @media (max-width:480px){ .memory-board{grid-template-columns:repeat(4, 1fr); gap:8px;} }
  .memory-card{aspect-ratio:1/1; border-radius:16px; cursor:pointer; position:relative; perspective:600px;}
  .memory-card-inner{position:relative; width:100%; height:100%; transition:transform .5s; transform-style:preserve-3d;}
  .memory-card.flip .memory-card-inner{transform:rotateY(180deg);}
  .memory-card-face{position:absolute; inset:0; backface-visibility:hidden; border-radius:16px; display:flex; align-items:center; justify-content:center; font-size:2.1rem; box-shadow:0 4px 12px rgba(43,42,74,.12);}
  .memory-card-front{background:linear-gradient(135deg, var(--gold), var(--gold-deep));}
  .memory-card-front::after{content:'❓'; color:#fff; font-size:1.8rem;}
  .memory-card-back{background:#fff; transform:rotateY(180deg); border:2px solid var(--gold-pale);}
  .memory-card.matched .memory-card-back{background:#EFFBF0; border-color:var(--leaf);}
  .memory-card.wrong .memory-card-inner{animation:shakeCard .4s;}
  @keyframes shakeCard{ 0%,100%{transform:rotateY(180deg) translateX(0);} 25%{transform:rotateY(180deg) translateX(-6px);} 75%{transform:rotateY(180deg) translateX(6px);} }
</style>

<script>
(function () {
  const emojiSet = ['🐬','🐳','🦈','🐢','🦋','🌸','⭐','🌈'];
  let cards = [], first = null, second = null, lock = false, langkah = 0, cocok = 0, waktu = 0, timerId = null;

  const papan = document.getElementById('papanGame');
  const elLangkah = document.getElementById('skorLangkah');
  const elWaktu = document.getElementById('skorWaktu');
  const elCocok = document.getElementById('skorCocok');
  const menangBox = document.getElementById('menangBox');

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

  function acak(arr) {
    for (let i = arr.length - 1; i > 0; i--) {
      const j = Math.floor(Math.random() * (i + 1));
      [arr[i], arr[j]] = [arr[j], arr[i]];
    }
    return arr;
  }

  function buatPapan() {
    papan.innerHTML = '';
    langkah = 0; cocok = 0; first = null; second = null; lock = false;
    elLangkah.textContent = '0';
    elCocok.textContent = '0/' + emojiSet.length;
    menangBox.classList.add('d-none');
    cards = acak([...emojiSet, ...emojiSet]);

    cards.forEach((emoji) => {
      const card = document.createElement('div');
      card.className = 'memory-card';
      card.dataset.emoji = emoji;
      card.innerHTML = '<div class="memory-card-inner"><div class="memory-card-face memory-card-front"></div><div class="memory-card-face memory-card-back">' + emoji + '</div></div>';
      card.addEventListener('click', () => balikKartu(card));
      papan.appendChild(card);
    });
    mulaiTimer();
  }

  function balikKartu(card) {
    if (lock || card.classList.contains('flip') || card === first) return;
    card.classList.add('flip');

    if (!first) { first = card; return; }

    second = card;
    langkah++;
    elLangkah.textContent = langkah;
    lock = true;

    if (first.dataset.emoji === second.dataset.emoji) {
      first.classList.add('matched');
      second.classList.add('matched');
      cocok++;
      elCocok.textContent = cocok + '/' + emojiSet.length;
      resetPilihan();
      if (cocok === emojiSet.length) selesai();
    } else {
      first.classList.add('wrong');
      second.classList.add('wrong');
      setTimeout(() => {
        first.classList.remove('flip', 'wrong');
        second.classList.remove('flip', 'wrong');
        resetPilihan();
      }, 700);
    }
  }

  function resetPilihan() { first = null; second = null; lock = false; }

  function selesai() {
    clearInterval(timerId);
    document.getElementById('hasilLangkah').textContent = langkah;
    document.getElementById('hasilWaktu').textContent = formatWaktu(waktu);
    menangBox.classList.remove('d-none');

    const skor = Math.max(0, 1000 - (langkah * 10) - (waktu * 3));
    const detail = langkah + ' langkah, ' + formatWaktu(waktu);
    setTimeout(() => window.simpanSkorGame('cocok-kartu', skor, detail), 600);
  }

  document.getElementById('btnUlang').addEventListener('click', buatPapan);
  buatPapan();
})();
</script>
