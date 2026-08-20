<section class="py-5">
  <div class="container text-center" style="max-width:560px;">
    <a href="<?= $this->appConfig['base_url'] ?>/permainan" class="small text-decoration-none d-inline-block mb-2">← Kembali ke Zona Permainan</a>
    <p class="section-eyebrow mb-2" data-aos="fade-up">YUK BERMAIN</p>
    <h1 class="fw-bold font-display mb-2" data-aos="fade-up">😄 Tebak Emoji</h1>
    <p class="text-muted mb-4" data-aos="fade-up">Tebak kata dari rangkaian emoji yang ditampilkan. Pilih jawaban yang tepat!</p>

    <div class="d-flex justify-content-center gap-3 mb-4 flex-wrap">
      <div class="stat-pill px-4 py-2">
        <div class="stat-num" style="font-size:1.4rem;" id="skorEmojiBenar">0</div>
        <small class="text-muted">Benar</small>
      </div>
      <div class="stat-pill px-4 py-2">
        <div class="stat-num" style="font-size:1.4rem;" id="soalKe">1/10</div>
        <small class="text-muted">Soal</small>
      </div>
    </div>

    <div class="glass-card p-5 mb-4">
      <div style="font-size:3rem;" id="tampilanEmoji">🐱🐟</div>
    </div>

    <div class="row g-2 mb-4" id="pilihanEmoji"></div>

    <button id="btnUlangEmoji" class="btn btn-outline-secondary rounded-pill px-4 d-none">🔄 Main Lagi</button>

    <div id="menangBoxEmoji" class="glass-card p-4 mt-4 d-none" data-aos="zoom-in">
      <h3 class="fw-bold font-display mb-1">🎉 Selesai!</h3>
      <p class="text-muted mb-0">Kamu menjawab benar <strong id="hasilEmojiBenar">0</strong> dari 10 soal!</p>
    </div>

    <?php include __DIR__ . '/_leaderboard.php'; ?>

    <?php include __DIR__ . '/_musik-game.php'; ?>
  </div>
</section>

<script>
(function () {
  const bankSoal = [
    { emoji: '🐱🐟', jawaban: 'Kucing makan ikan', opsi: ['Kucing makan ikan', 'Anjing lari', 'Burung terbang', 'Ikan berenang'] },
    { emoji: '☀️🌈', jawaban: 'Matahari dan pelangi', opsi: ['Matahari dan pelangi', 'Hujan badai', 'Malam gelap', 'Bulan purnama'] },
    { emoji: '📚✏️', jawaban: 'Belajar', opsi: ['Belajar', 'Bermain bola', 'Tidur siang', 'Makan siang'] },
    { emoji: '🕌🤲', jawaban: 'Berdoa di masjid', opsi: ['Berdoa di masjid', 'Bermain layangan', 'Naik sepeda', 'Menyanyi'] },
    { emoji: '🚗💨', jawaban: 'Mobil melaju cepat', opsi: ['Mobil melaju cepat', 'Pesawat terbang', 'Kapal berlayar', 'Kereta berhenti'] },
    { emoji: '🌙⭐', jawaban: 'Malam berbintang', opsi: ['Malam berbintang', 'Siang terik', 'Sore hujan', 'Pagi cerah'] },
    { emoji: '🍎🍌🍇', jawaban: 'Buah-buahan', opsi: ['Buah-buahan', 'Sayur-sayuran', 'Kue-kue', 'Minuman'] },
    { emoji: '👨‍🏫📖', jawaban: 'Guru mengajar', opsi: ['Guru mengajar', 'Dokter memeriksa', 'Polisi berjaga', 'Koki memasak'] },
    { emoji: '🐣🥚', jawaban: 'Telur menetas', opsi: ['Telur menetas', 'Ayam bertelur', 'Bebek berenang', 'Burung terbang'] },
    { emoji: '🎂🎉', jawaban: 'Pesta ulang tahun', opsi: ['Pesta ulang tahun', 'Hari raya', 'Liburan sekolah', 'Piknik keluarga'] },
  ];

  let urutanSoal = [], soalKe = 0, benar = 0;
  const elTampil = document.getElementById('tampilanEmoji');
  const elPilihan = document.getElementById('pilihanEmoji');
  const elBenar = document.getElementById('skorEmojiBenar');
  const elSoalKe = document.getElementById('soalKe');
  const btnUlang = document.getElementById('btnUlangEmoji');
  const menangBox = document.getElementById('menangBoxEmoji');

  function acak(arr) {
    return [...arr].sort(() => Math.random() - 0.5);
  }

  function tampilkanSoal() {
    const soal = urutanSoal[soalKe];
    elTampil.textContent = soal.emoji;
    elSoalKe.textContent = (soalKe + 1) + '/10';
    elPilihan.innerHTML = '';
    acak(soal.opsi).forEach((opsi) => {
      const col = document.createElement('div');
      col.className = 'col-6';
      const btn = document.createElement('button');
      btn.className = 'btn btn-outline-primary w-100';
      btn.textContent = opsi;
      btn.addEventListener('click', () => jawab(opsi, soal.jawaban));
      col.appendChild(btn);
      elPilihan.appendChild(col);
    });
  }

  function jawab(pilih, jawabanBenar) {
    if (pilih === jawabanBenar) {
      benar++;
      elBenar.textContent = benar;
    }
    soalKe++;
    if (soalKe >= urutanSoal.length) {
      selesai();
    } else {
      tampilkanSoal();
    }
  }

  function selesai() {
    elTampil.textContent = '🏁';
    elPilihan.innerHTML = '';
    document.getElementById('hasilEmojiBenar').textContent = benar;
    menangBox.classList.remove('d-none');
    btnUlang.classList.remove('d-none');
    const skor = benar * 10;
    setTimeout(() => window.simpanSkorGame('tebak-emoji', skor, benar + '/10 benar'), 500);
  }

  function mulai() {
    urutanSoal = acak(bankSoal);
    soalKe = 0; benar = 0;
    elBenar.textContent = '0';
    menangBox.classList.add('d-none');
    btnUlang.classList.add('d-none');
    tampilkanSoal();
  }

  btnUlang.addEventListener('click', mulai);
  mulai();
})();
</script>
