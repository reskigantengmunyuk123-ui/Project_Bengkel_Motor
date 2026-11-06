<?php
$totalBookings = count($allBookings);
$completed = array_values(array_filter($allBookings, fn($b) => $b['status'] === 'Selesai'));
$revenue = array_sum(array_map(fn($b) => (float)($b['service_price'] ?? 0), $completed));
$avgDuration = $completed
    ? (int)round(array_sum(array_map(fn($b) => service_duration($b['service_name']), $completed)) / count($completed))
    : 0;
$maxChart = 1;
foreach ($chartData as $row) $maxChart = max($maxChart, (int)$row['total']);
$breakdownTotal = array_sum(array_map(fn($r) => (int)$r['total'], $serviceBreakdown));
?>
<div class="admin-toolbar">
  <div class="period-pill"><span>Periode laporan</span><b><?=date('01')?> – <?=date('t')?> <?=month_label(date('Y-m'))?></b></div>
  <button class="btn btn-red no-print" type="button" onclick="window.print()">Ekspor PDF</button>
</div>

<div class="stat-grid stat-grid-3">
  <article class="stat-card"><span>Total Servis</span><strong class="stat-red"><?=$totalBookings?></strong></article>
  <article class="stat-card"><span>Pendapatan</span><strong class="stat-green"><?=money($revenue)?></strong></article>
  <article class="stat-card"><span>Rata-rata Durasi</span><strong class="stat-blue"><?=$avgDuration?> menit</strong></article>
</div>

<div class="admin-grid">
  <section class="card chart-card">
    <div class="card-head">
      <h2>Grafik Booking 7 Hari Terakhir</h2>
      <p class="muted">Ringkasan aktivitas harian</p>
    </div>
    <div class="bar-chart">
      <?php foreach ($chartData as $row): ?>
        <?php $pct = (int)round(((int)$row['total'] / $maxChart) * 100); ?>
        <div class="bar-col">
          <span class="bar-value"><?=(int)$row['total']?></span>
          <span class="bar" style="height:<?=max(6,$pct)?>%"></span>
          <small><?=$row['label']?></small>
        </div>
      <?php endforeach; ?>
    </div>
  </section>

  <section class="card breakdown-card">
    <div class="card-head">
      <h2>Ringkasan Layanan</h2>
      <p class="muted">Layanan paling banyak digunakan</p>
    </div>
    <div class="breakdown-list">
      <?php if (!$serviceBreakdown): ?>
        <p class="muted">Belum ada data layanan.</p>
      <?php else: ?>
        <?php foreach ($serviceBreakdown as $row): ?>
          <?php $pct = $breakdownTotal ? (int)round(((int)$row['total'] / $breakdownTotal) * 100) : 0; ?>
          <div class="breakdown-item">
            <div><span><?=e($row['name'])?></span><b><?=$pct?>%</b></div>
            <span class="breakdown-bar"><span style="width:<?=$pct?>%"></span></span>
            <small class="muted"><?=(int)$row['total']?> booking</small>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </section>
</div>
