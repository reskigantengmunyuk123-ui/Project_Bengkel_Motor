<?php
$selectedService = null;
foreach ($services as $service) {
    if ((int)$service['id'] === (int)($draft['service_id'] ?? 0)) { $selectedService = $service; break; }
}
$today = date('Y-m-d');
$currentTime = date('H:i');
$prevMonth = date('Y-m', strtotime($month . '-01 -1 month'));
$nextMonth = date('Y-m', strtotime($month . '-01 +1 month'));
?>
<section class="page-head">
  <h1>Pilih Jadwal Servis</h1>
  <p>Tentukan tanggal dan jam kedatangan yang masih tersedia.</p>
</section>

<?php if ($selectedService): ?>
  <div class="selection-strip">
    <span class="gear gear-sm">⚙</span>
    <div><b><?=e($draft['customer_name'] ?? 'Pelanggan')?></b><small><?=e($selectedService['name'])?> • <?=e($draft['motorcycle'] ?? '')?></small></div>
    <strong><?=money($selectedService['price'])?></strong>
  </div>
<?php endif; ?>

<?php if ($error): ?>
  <div class="alert alert-danger alert-mt" role="alert"><?=e($error)?></div>
<?php endif; ?>

<div class="booking-grid">
  <section class="card calendar-card">
    <div class="card-head">
      <h2>Pilih Tanggal</h2>
      <p class="muted">Angka menunjukkan slot kosong. Pilih tanggal untuk melihat jamnya.</p>
    </div>
    <div class="calendar-top">
      <a class="cal-nav" href="index.php?page=booking-jadwal&month=<?=$prevMonth?>">&lsaquo;</a>
      <strong><?=strtoupper(month_label($month))?></strong>
      <a class="cal-nav" href="index.php?page=booking-jadwal&month=<?=$nextMonth?>">&rsaquo;</a>
    </div>
    <div class="calendar-grid">
      <?php foreach (['Min','Sen','Sel','Rab','Kam','Jum','Sab'] as $dow): ?>
        <span class="cal-dow"><?=$dow?></span>
      <?php endforeach; ?>
      <?php foreach ($calendar as $cell): ?>
        <?php if ($cell === null): ?>
          <span class="cal-day is-empty"></span>
        <?php elseif ($cell['date'] < $today): ?>
          <span class="cal-day is-disabled"><?=$cell['day']?></span>
        <?php else: ?>
          <?php
            $dayTaken = $takenByDate[$cell['date']] ?? [];
            $remaining = count(array_filter($slots, static fn($slot) => !in_array($slot, $dayTaken, true) && !($cell['date'] === $today && $slot <= $currentTime)));
          ?>
          <?php if ($remaining > 0): ?>
            <a class="cal-day <?=$selectedDate===$cell['date']?'is-selected':''?>" href="index.php?page=booking-jadwal&month=<?=$month?>&date=<?=$cell['date']?>"><span><?=$cell['day']?></span><small><?=$remaining?> slot</small></a>
          <?php else: ?>
            <span class="cal-day is-disabled is-full"><span><?=$cell['day']?></span><small><?=$cell['date']===$today?'Habis':'Penuh'?></small></span>
          <?php endif; ?>
        <?php endif; ?>
      <?php endforeach; ?>
    </div>
  </section>

  <form method="post" action="index.php?page=booking-jadwal&month=<?=$month?>" class="card time-card">
    <div class="card-head">
      <h2>Pilih Jam</h2>
      <p class="muted"><?=$selectedDate ? 'Slot waktu tersedia untuk ' . tanggal_id($selectedDate) : 'Pilih tanggal terlebih dahulu.'?></p>
    </div>
    <input type="hidden" name="booking_date" value="<?=e($selectedDate)?>">
    <div class="slot-list">
      <?php foreach ($slots as $slot): ?>
          <?php $isTaken = in_array($slot, $taken, true); $isPast = $selectedDate === $today && $slot <= $currentTime; ?>
        <label class="slot <?=($isTaken||$isPast)?'is-taken':''?>">
          <input type="radio" name="booking_time" value="<?=$slot?>" <?=$isTaken||$isPast||!$selectedDate?'disabled':''?> required>
          <span><?=str_replace(':','.',$slot)?></span>
          <?php if ($isTaken): ?><small>Terisi</small><?php endif; ?>
          <?php if (!$isTaken && $isPast): ?><small>Lewat</small><?php endif; ?>
        </label>
      <?php endforeach; ?>
    </div>
    <button type="submit" class="btn btn-red btn-block" <?=!$selectedDate?'disabled':''?>>Konfirmasi Jadwal</button>
  </form>
</div>
