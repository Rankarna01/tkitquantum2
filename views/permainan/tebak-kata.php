<section class="py-5">
  <div class="container text-center" style="max-width:560px;">
    <a href="<?= $this->appConfig['base_url'] ?>/permainan" class="small text-decoration-none d-inline-block mb-2">← Kembali ke Zona Permainan</a>
    <p class="section-eyebrow mb-2" data-aos="fade-up">YUK BERMAIN</p>
    <h1 class="fw-bold font-display mb-2" data-aos="fade-up">🔤 Tebak Kata</h1>
    <p class="text-muted mb-4" data-aos="fade-up">Tebak huruf untuk menemukan kata tersembunyi. Salah 6 kali, permainan berakhir!</p>

    <div class="d-flex justify-content-center gap-3 mb-4 flex-wrap">
      <div class="stat-pill px-4 py-2">
        <div class="stat-num" style="font-size:1.4rem;" id="sisaNyawa">6</div>
        <small class="text-muted">Sisa Nyawa ❤️</small>
      </div>
    </div>

    <div class="glass-card p-4 mb-4">
      <p class="text-muted small mb-2" id="petunjukKata">Kategori: Hewan</p>
      <h2 class="fw-bold font-display mb-0" id="kataTampil" style="letter-spacing:.3em;">_ _ _ _ _</h2>
    </div>

    <div id="papanHuruf" class="huruf-board mb-4"></div>

    <button id="btnUlangKata" class="btn btn-accent rounded-pill px-4 d-none">🔄 Main Lagi</button>

    <div id="menangBoxKata" class="glass-card p-4 mt-4 d-none" data-aos="zoom-in">
      <h3 class="fw-bold font-display mb-1" id="judulHasilKata">🎉 Benar!</h3>
      <p class="text-muted mb-0" id="teksHasilKata">Kata rahasianya adalah ...</p>
    </div>

    <?php include __DIR__ . '/_leaderboard.php'; ?>

<?php include __DIR__ . '/_musik-game.php'; ?>
  </div>
</section>

<style>
  .huruf-board{display:flex; flex-wrap:wrap; gap:6px; justify-content:center; max-width:480px; margin:0 auto;}
  .huruf-btn{width:38px; height:38px; border-radius:10px; border:2px solid var(--gold-pale); background:#fff; font-weight:700; font-family:'Baloo 2',sans-serif; cursor:pointer;}
  .huruf-btn:disabled{opacity:.35; cursor:not-allowed;}
  .huruf-btn.benar{background:var(--leaf); color:#fff; border-color:var(--leaf);}
  .huruf-btn.salah{background:#eee; color:#999;}
</style>

<script>
(function () {
  const bankKata = [
    { kata: 'KUCING', kategori: 'Hewan' },
    { kata: 'GAJAH', kategori: 'Hewan' },
    { kata: 'PISANG', kategori: 'Buah' },
    { kata: 'APEL', kategori: 'Buah' },
    { kata: 'SEKOLAH', kategori: 'Tempat' },
    { kata: 'PELANGI', kategori: 'Alam' },
    { kata: 'BINTANG', kategori: 'Alam' },
    { kata: 'GURU', kategori: 'Profesi' },
    { kata: 'DOKTER', kategori: 'Profesi' },
    { kata: 'BOLA', kategori: 'Mainan' },
  ];
  const abjad = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'.split('');

  let kataAktif = '', tertebak = [], salah = 0, selesai = false;
  const elNyawa = document.getElementById('sisaNyawa');
  const elKategori = document.getElementById('petunjukKata');
  const elKataTampil = document.getElementById('kataTampil');
  const papanHuruf = document.getElementById('papanHuruf');
  const btnUlang = document.getElementById('btnUlangKata');
  const menangBox = document.getElementById('menangBoxKata');

  function tampilkanKata() {
    elKataTampil.textContent = kataAktif.split('').map(h => tertebak.includes(h) ? h : '_').join(' ');
  }

  function buatPapanHuruf() {
    papanHuruf.innerHTML = '';
    abjad.forEach((h) => {
      const btn = document.createElement('button');
      btn.className = 'huruf-btn';
      btn.textContent = h;
      btn.addEventListener('click', () => tebakHuruf(h, btn));
      papanHuruf.appendChild(btn);
    });
  }

  function tebakHuruf(huruf, btn) {
    if (selesai) return;
    btn.disabled = true;
    if (kataAktif.includes(huruf)) {
      tertebak.push(huruf);
      btn.classList.add('benar');
      tampilkanKata();
      if (kataAktif.split('').every(h => tertebak.includes(h))) menang();
    } else {
      salah++;
      btn.classList.add('salah');
      elNyawa.textContent = 6 - salah;
      if (salah >= 6) kalah();
    }
  }

  function menang() {
    selesai = true;
    document.getElementById('judulHasilKata').textContent = '🎉 Benar Semua!';
    document.getElementById('teksHasilKata').textContent = 'Kata rahasianya adalah "' + kataAktif + '". Hebat!';
    menangBox.classList.remove('d-none');
    btnUlang.classList.remove('d-none');
    const skor = Math.max(0, (6 - salah) * 50);
    setTimeout(() => window.simpanSkorGame('tebak-kata', skor, (6 - salah) + ' nyawa tersisa'), 500);
  }

  function kalah() {
    selesai = true;
    document.getElementById('judulHasilKata').textContent = '😢 Sayang Sekali';
    document.getElementById('teksHasilKata').textContent = 'Kata rahasianya adalah "' + kataAktif + '". Coba lagi ya!';
    menangBox.classList.remove('d-none');
    btnUlang.classList.remove('d-none');
  }

  function mulai() {
    const pilih = bankKata[Math.floor(Math.random() * bankKata.length)];
    kataAktif = pilih.kata;
    elKategori.textContent = 'Kategori: ' + pilih.kategori;
    tertebak = []; salah = 0; selesai = false;
    elNyawa.textContent = '6';
    menangBox.classList.add('d-none');
    btnUlang.classList.add('d-none');
    buatPapanHuruf();
    tampilkanKata();
  }

  btnUlang.addEventListener('click', mulai);
  mulai();
})();
</script>
