<section class="page-head">
  <h1>Tiket Antrean</h1>
  <p>Tunjukkan tiket ini kepada petugas saat tiba di bengkel.</p>
</section>

<section class="ticket-card printable">
  <div class="ticket-brand">
    <img class="brand-logo" src="assets/images/motortech-logo.png" alt="MOTORTECH Mechanical Engineering">
  </div>
  <h2 class="ticket-title">TIKET ANTREAN</h2>
  <div class="ticket-number"><?=queue_number($ticket)?></div>
  <div class="ticket-divider"></div>
  <div class="ticket-meta">
    <div><small>Kode Booking</small><b><?=e($ticket['booking_code'])?></b></div>
    <div><small>Tanggal</small><b><?=tanggal_short_id($ticket['booking_date'])?></b></div>
    <div><small>Jam</small><b><?=jam_id($ticket['booking_time'])?></b></div>
  </div>
  <p class="ticket-note">Harap datang 10 menit sebelum jadwal.</p>
  <div class="ticket-actions no-print">
    <button type="button" class="btn btn-red" onclick="window.print()">Unduh / Cetak Tiket</button>
    <a class="btn btn-outline-red" href="index.php?page=status&code=<?=urlencode($ticket['booking_code'])?>">Cek Status</a>
  </div>
  <div class="ticket-service"><?=e($ticket['service_name'])?> • <?=e($ticket['customer_name'])?></div>
</section>
