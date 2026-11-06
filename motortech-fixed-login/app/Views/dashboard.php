<?php
$statToday = $bookingModel->todayCount();
$statQueue = $bookingModel->countStatus('Menunggu') + $bookingModel->countStatus('Diproses');
$statDone = $bookingModel->countStatus('Selesai');
$statWaiting = $bookingModel->countStatus('Menunggu');
$maxChart = 1;
foreach ($chartData as $row) $maxChart = max($maxChart, (int)$row['total']);
?>
<div class="stat-grid">
  <article class="stat-card"><span>Booking Hari Ini</span><strong class="stat-red"><?=$statToday?></strong><a href="index.php?page=bookings">Lihat booking →</a></article>
  <article class="stat-card"><span>Antrean Aktif</span><strong class="stat-blue"><?=$statQueue?></strong><a href="index.php?page=queue">Kelola antrean →</a></article>
  <article class="stat-card"><span>Servis Selesai</span><strong class="stat-green"><?=$statDone?></strong><a href="index.php?page=history">Lihat riwayat →</a></article>
  <article class="stat-card"><span>Menunggu Konfirmasi</span><strong class="stat-amber"><?=$statWaiting?></strong><a href="index.php?page=queue">Panggil berikutnya →</a></article>
</div>

<div class="admin-grid">
  <section class="card chart-card">
    <div class="card-head">
      <h2>Grafik Antrean 7 Hari Terakhir</h2>
      <p class="muted">Jumlah booking dan servis per hari</p>
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

  <section class="card activity-card">
    <div class="card-head">
      <h2>Aktivitas Terbaru</h2>
      <p class="muted">Pembaruan sistem</p>
    </div>
    <div class="activity-list">
      <?php if (!$recentBookings): ?>
        <p class="muted">Belum ada aktivitas booking.</p>
      <?php else: ?>
        <?php foreach ($recentBookings as $row): ?>
          <div class="activity-item">
            <b><?=queue_number($row)?></b>
            <div><span><?=e($row['customer_name'])?> • <?=e($row['service_name'])?></span><small><?=status_label($row['status'])?> • <?=tanggal_short_id($row['booking_date'])?></small></div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
    <a class="text-link" href="index.php?page=queue">Lihat semua antrean →</a>
  </section>
</div>
