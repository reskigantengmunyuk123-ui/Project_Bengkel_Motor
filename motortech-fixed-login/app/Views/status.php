<?php
$status = $found['status'] ?? 'Menunggu';
$steps = [
    1 => ['Terdaftar', 'is-done'],
    2 => ['Menunggu', 'is-waiting'],
    3 => ['Dikerjakan', 'is-process'],
    4 => ['Selesai', 'is-done'],
];
$activeStep = match ($status) {
    'Menunggu' => 2,
    'Diproses' => 3,
    'Selesai' => 4,
    default => 1,
};
$estimate = match ($status) {
    'Menunggu' => 'Estimasi waktu: ± 15 menit',
    'Diproses' => 'Motor sedang dikerjakan teknisi',
    'Selesai' => 'Servis telah selesai',
    default => 'Booking dibatalkan',
};
?>
<section class="page-head">
  <h1>Cek Status Antrean</h1>
  <p>Masukkan kode booking atau nomor antrean untuk melihat perkembangan servis.</p>
</section>

<form class="card status-search" method="get" action="index.php">
  <input type="hidden" name="page" value="status">
  <div class="form-field">
    <label for="code">Kode Booking / Nomor Antrean</label>
    <input id="code" name="code" class="form-control" placeholder="Contoh: MT-261106-001 atau A-017" value="<?=e($_GET['code'] ?? '')?>">
  </div>
  <button type="submit" class="btn btn-red">Cek Status</button>
</form>

<?php if (isset($_GET['code']) && trim((string)$_GET['code']) !== '' && !$found): ?>
  <div class="alert alert-danger alert-mt" role="alert">Kode booking tidak ditemukan. Periksa kembali kode Anda.</div>
<?php endif; ?>

<?php if ($found): ?>
  <div class="status-grid">
    <section class="card queue-card">
      <div class="card-head">
        <h2>Nomor Antrean</h2>
        <p class="muted">Informasi antrean Anda</p>
      </div>
      <div class="queue-number"><?=queue_number($found)?></div>
      <span class="badge <?=status_class($status)?>"><?=strtoupper(status_label($status))?></span>
      <p class="queue-estimate"><?=e($estimate)?></p>
      <div class="queue-detail">
        <span><?=e($found['customer_name'])?></span>
        <span><?=e($found['service_name'])?></span>
        <span><?=tanggal_short_id($found['booking_date'])?> • <?=jam_id($found['booking_time'])?></span>
      </div>
    </section>

    <section class="card progress-card">
      <div class="card-head">
        <h2>Tahapan Servis</h2>
        <p class="muted">Status akan diperbarui oleh petugas bengkel.</p>
      </div>
      <div class="steps">
        <?php foreach ($steps as $n => [$label, $extra]): ?>
          <?php
            $cls = 'is-pending';
            if ($n < $activeStep) $cls = 'is-done';
            elseif ($n === $activeStep) $cls = $activeStep === 4 ? 'is-done' : 'is-current';
          ?>
          <div class="step <?=$cls?>">
            <span class="step-dot"><?=$n < $activeStep || $activeStep === 4 ? '✓' : $n?></span>
            <small><?=$label?></small>
          </div>
          <?php if ($n < 4): ?><span class="step-line <?=$n < $activeStep?'is-done':''?>"></span><?php endif; ?>
        <?php endforeach; ?>
      </div>
      <p class="progress-updated">Pembaruan terakhir: <?=date('H.i')?> WIB</p>
      <a class="btn btn-outline-red btn-block" href="index.php?page=tiket&code=<?=urlencode($found['booking_code'])?>">Lihat Tiket Antrean</a>
    </section>
  </div>
<?php endif; ?>
