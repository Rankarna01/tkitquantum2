<section class="py-5">
  <div class="container text-center" style="max-width:520px;">
    <a href="<?= $this->appConfig['base_url'] ?>/permainan" class="small text-decoration-none d-inline-block mb-2">← Kembali ke Zona Permainan</a>
    <p class="section-eyebrow mb-2" data-aos="fade-up">YUK BERMAIN</p>
    <h1 class="fw-bold font-display mb-2" data-aos="fade-up">🐸 Lompat Kodok</h1>
    <p class="text-muted mb-4" data-aos="fade-up">Klik "LOMPAT!" tepat saat penunjuk masuk area hijau. Ada 8 kali kesempatan!</p>

    <div class="d-flex justify-content-center gap-3 mb-4 flex-wrap">
      <div class="stat-pill px-4 py-2">
        <div class="stat-num" style="font-size:1.4rem;" id="berhasilKodok">0</div>
        <small class="text-muted">Berhasil</small>
      </div>
      <div class="stat-pill px-4 py-2">
        <div class="stat-num" style="font-size:1.4rem;" id="rondeKodok">0/8</div>
        <small class="text-muted">Lompatan</small>
      </div>
    </div>

    <div class="kodok-track mb-4">
      <div class="kodok-zone-hijau" id="zonaHijau"></div>
      <div class="kodok-penunjuk" id="penunjukKodok">🐸</div>
    </div>

    <button id="btnLompatKodok" class="btn btn-accent rounded-pill px-4">▶️ Mulai / LOMPAT!</button>

    <div id="menangBoxKodok" class="glass-card p-4 mt-4 d-none" data-aos="zoom-in">
      <h3 class="fw-bold font-display mb-1">🎉 Selesai!</h3>
      <p class="text-muted mb-0">Kodok berhasil lompat tepat sebanyak <strong id="hasilKodok">0</strong> dari 8 kali!</p>
    </div>

    <?php include __DIR__ . '/_leaderboard.php'; ?>

    <?php include __DIR__ . '/_musik-game.php'; ?>
  </div>
</section>

<style>
  .kodok-track{position:relative; max-width:420px; height:44px; margin:0 auto; background:linear-gradient(90deg,#BBDEFB,#90CAF9); border-radius:22px; overflow:hidden;}
  .kodok-zone-hijau{position:absolute; top:0; bottom:0; width:60px; background:rgba(107,217,143,.7); border-left:2px dashed #fff; border-right:2px dashed #fff;}
  .kodok-penunjuk{position:absolute; top:50%; transform:translateY(-50%); font-size:1.6rem; left:0;}
</style>

<script>
(function () {
  const track = document.querySelector('.kodok-track');
  const zona = document.getElementById('zonaHijau');
  const penunjuk = document.getElementById('penunjukKodok');
  const btn = document.getElementById('btnLompatKodok');
  const elBerhasil = document.getElementById('berhasilKodok');
  const elRonde = document.getElementById("rondeKodok");
  const menangBox = document.getElementById('menangBoxKodok');

  const totalLompatan = 8;
  let ronde = 0, berhasil = 0, posisi = 0, arah = 1, animId = null, siap = false, main = false;

  function acakZona() {
    const lebarTrack = track.clientWidth;
    const posZona = 40 + Math.random() * (lebarTrack - 100);
    zona.style.left = posZona + 'px';
    zona.style.width = '60px';
    return [posZona, posZona + 60];
  }

  let batasZona = [0, 0];

  function animasikan() {
    const lebarTrack = track.clientWidth - 28;
    posisi += arah * 4;
    if (posisi >= lebarTrack) arah = -1;
    if (posisi <= 0) arah = 1;
    penunjuk.style.left = posisi + 'px';
    animId = requestAnimationFrame(animasikan);
  }

  function rondeBaru() {
    if (ronde >= totalLompatan) return selesai();
    siap = true;
    posisi = 0; arah = 1;
    batasZona = acakZona();
    btn.textContent = '🐸 LOMPAT!';
    cancelAnimationFrame(animId);
    animasikan();
  }

  function lompat() {
    if (!main) { mulai(); return; }
    if (!siap) return;
    siap = false;
    cancelAnimationFrame(animId);

    const posKodok = posisi + 14;
    const tepat = posKodok >= batasZona[0] && posKodok <= batasZona[1];
    ronde++;
    elRonde.textContent = ronde + '/' + totalLompatan;
    if (tepat) {
      berhasil++;
      elBerhasil.textContent = berhasil;
      penunjuk.textContent = '🐸✨';
    } else {
      penunjuk.textContent = '🐸💦';
    }
    setTimeout(() => { penunjuk.textContent = '🐸'; rondeBaru(); }, 500);
  }

  function selesai() {
    main = false;
    document.getElementById('hasilKodok').textContent = berhasil;
    menangBox.classList.remove('d-none');
    btn.textContent = '🔄 Main Lagi';
    const skor = berhasil * 60;
    setTimeout(() => window.simpanSkorGame('lompat-kodok', skor, berhasil + '/8 tepat'), 500);
  }

  function mulai() {
    main = true;
    ronde = 0; berhasil = 0;
    elBerhasil.textContent = '0';
    elRonde.textContent = '0/' + totalLompatan;
    menangBox.classList.add('d-none');
    rondeBaru();
  }

  btn.addEventListener('click', lompat);
})();
</script>
