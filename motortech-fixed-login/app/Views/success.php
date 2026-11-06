<?php $isDirectQueue = ($_SESSION['last_booking_mode'] ?? 'booking') === 'antri'; ?>
<section class="page-head">
  <h1><?=$isDirectQueue ? 'Antrean Berhasil Diambil' : 'Booking Berhasil'?></h1>
  <p><?=$isDirectQueue ? 'Antrean Anda sudah tercatat untuk kunjungan hari ini.' : 'Permintaan booking berhasil disimpan. Simpan kode booking dan jadwal berikut.'?></p>
</section>

<section class="card confirm-card">
  <div class="card-head">
    <h2><?=$isDirectQueue ? 'Tiket Antrean Hari Ini' : 'Konfirmasi Booking'?></h2>
    <p class="muted"><?=$isDirectQueue ? 'Tunjukkan nomor antrean ini saat tiba di bengkel.' : 'Periksa kembali detail jadwal servis Anda.'?></p>
  </div>
  <div class="confirm-body">
    <span class="check-badge">✓</span>
    <h2 class="confirm-title"><?=$isDirectQueue ? 'ANTREAN ANDA TERCATAT' : 'BOOKING BERHASIL'?></h2>
    <?php if ($isDirectQueue): ?>
      <p class="confirm-code">Nomor Antrean: <?=e(queue_number($successBooking))?></p>
      <p class="muted">Kode untuk cek status: <?=e($successBooking['booking_code'])?></p>
    <?php else: ?>
      <p class="confirm-code">Kode Booking: <?=e($successBooking['booking_code'])?></p>
    <?php endif; ?>
    <dl class="detail-list">
      <div><dt>Nama</dt><dd><?=e($successBooking['customer_name'])?></dd></div>
      <div><dt>Layanan</dt><dd><?=e($successBooking['service_name'])?></dd></div>
      <div><dt>Tanggal</dt><dd><?=tanggal_id($successBooking['booking_date'])?></dd></div>
      <div><dt>Jam</dt><dd><?=jam_id($successBooking['booking_time'])?></dd></div>
      <div><dt>Motor</dt><dd><?=e($successBooking['motorcycle'])?></dd></div>
    </dl>
    <div class="confirm-actions">
      <a class="btn btn-red" href="index.php?page=tiket&code=<?=urlencode($successBooking['booking_code'])?>">Lihat Tiket Antrean</a>
      <a class="btn btn-outline-red" href="index.php">Kembali ke Beranda</a>
    </div>
  </div>
</section>
