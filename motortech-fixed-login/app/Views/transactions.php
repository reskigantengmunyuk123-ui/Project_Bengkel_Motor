<?php
$q = strtolower(trim((string)($_GET['q'] ?? '')));
$date = trim((string)($_GET['date'] ?? ''));
$completed = array_values(array_filter($allBookings, static function ($booking) use ($q, $date) {
    if ($booking['status'] !== 'Selesai') return false;
    if ($date !== '' && $booking['booking_date'] !== $date) return false;
    if ($q !== '') {
        $haystack = strtolower($booking['booking_code'] . ' ' . $booking['customer_name'] . ' ' . $booking['service_name']);
        return str_contains($haystack, $q);
    }
    return true;
}));
$completedToday = array_filter($allBookings, static fn($booking) => $booking['status'] === 'Selesai' && $booking['booking_date'] === date('Y-m-d'));
$todayRevenue = array_sum(array_map(static fn($booking) => (float)($booking['service_price'] ?? 0), $completedToday));
$revenue = array_sum(array_map(static fn($booking) => (float)($booking['service_price'] ?? 0), $completed));
?>
<div class="stat-grid stat-grid-3 transaction-stats">
  <article class="stat-card"><span>Transaksi Hari Ini</span><strong class="stat-red"><?=count($completedToday)?></strong></article>
  <article class="stat-card"><span>Pendapatan Hari Ini</span><strong class="stat-green"><?=money($todayRevenue)?></strong></article>
  <article class="stat-card"><span>Total Riwayat Terfilter</span><strong class="stat-blue"><?=money($revenue)?></strong></article>
</div>

<section class="card transaction-history-card">
  <div class="transaction-heading">
    <div><h2>Riwayat Transaksi</h2><p class="muted">Pembayaran servis dari booking yang berstatus selesai.</p></div>
    <span class="transaction-count"><?=count($completed)?> transaksi</span>
  </div>
  <form class="search-form transaction-filter" method="get" action="index.php">
    <input type="hidden" name="page" value="transactions">
    <input class="form-control" name="q" placeholder="Cari kode, pelanggan, atau layanan..." value="<?=e($q)?>" aria-label="Cari transaksi">
    <input class="form-control date-input" type="date" name="date" value="<?=e($date)?>" aria-label="Filter tanggal transaksi">
    <button class="btn btn-outline-red" type="submit">Cari transaksi</button>
    <?php if ($q !== '' || $date !== ''): ?><a class="btn btn-quiet" href="index.php?page=transactions">Reset</a><?php endif; ?>
  </form>

  <div class="table-scroll transaction-table-wrap">
    <table class="data-table transaction-table">
      <thead><tr><th>Tanggal</th><th>Kode Booking</th><th>Pelanggan</th><th>Layanan</th><th>Nominal</th><th>Status</th></tr></thead>
      <tbody>
        <?php if (!$completed): ?>
          <tr><td colspan="6" class="table-empty">Belum ada transaksi yang cocok dengan pencarian ini.</td></tr>
        <?php else: foreach ($completed as $row): ?>
          <tr>
            <td><?=tanggal_short_id($row['booking_date'])?></td>
            <td class="cell-strong"><?=e($row['booking_code'])?></td>
            <td><?=e($row['customer_name'])?></td>
            <td><?=e($row['service_name'])?></td>
            <td class="transaction-amount"><?=money($row['service_price'] ?? 0)?></td>
            <td><span class="status-text is-done">Lunas</span></td>
          </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
  <div class="transaction-mobile-list">
    <?php if (!$completed): ?>
      <p class="table-empty">Belum ada transaksi yang cocok dengan pencarian ini.</p>
    <?php else: foreach ($completed as $row): ?>
      <article class="transaction-mobile-item">
        <div class="transaction-mobile-top"><b><?=e($row['booking_code'])?></b><span class="status-text is-done">Lunas</span></div>
        <strong><?=e($row['customer_name'])?></strong>
        <span class="muted"><?=e($row['service_name'])?> · <?=tanggal_short_id($row['booking_date'])?></span>
        <b class="transaction-amount"><?=money($row['service_price'] ?? 0)?></b>
      </article>
    <?php endforeach; endif; ?>
  </div>
</section>
