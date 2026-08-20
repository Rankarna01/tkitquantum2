<div class="row g-3 mb-4">
  <?php
  $cards = [
    ['label' => 'Guru', 'val' => $stats['guru'], 'icon' => 'chalkboard-user'],
    ['label' => 'Tenaga Kependidikan', 'val' => $stats['tendik'], 'icon' => 'people-group'],
    ['label' => 'Berita', 'val' => $stats['berita'], 'icon' => 'newspaper'],
    ['label' => 'Pengumuman', 'val' => $stats['pengumuman'], 'icon' => 'bullhorn'],
    ['label' => 'Agenda', 'val' => $stats['agenda'], 'icon' => 'calendar-days'],
    ['label' => 'Galeri', 'val' => $stats['galeri'], 'icon' => 'images'],
    ['label' => 'Pendaftar PPDB', 'val' => $stats['pendaftar'], 'icon' => 'user-plus'],
  ];
  ?>
  <?php foreach ($cards as $c): ?>
  <div class="col-6 col-md-3">
    <div class="card stat-card p-3 text-center">
      <i class="fa-solid fa-<?= $c['icon'] ?> fa-2x text-warning mb-2"></i>
      <h3 class="fw-bold mb-0"><?= $c['val'] ?></h3>
      <small class="text-muted"><?= $c['label'] ?></small>
    </div>
  </div>
  <?php endforeach; ?>
</div>

<div class="card stat-card p-4">
  <h6 class="fw-bold mb-3">Ringkasan Konten</h6>
  <canvas id="statChart" height="90"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
new Chart(document.getElementById('statChart'), {
  type: 'bar',
  data: {
    labels: [<?php foreach ($cards as $c) echo "'{$c['label']}',"; ?>],
    datasets: [{
      label: 'Jumlah',
      data: [<?php foreach ($cards as $c) echo "{$c['val']},"; ?>],
      backgroundColor: '#FFC107',
      borderRadius: 6,
    }]
  },
  options: { plugins: { legend: { display: false } } }
});
</script>
