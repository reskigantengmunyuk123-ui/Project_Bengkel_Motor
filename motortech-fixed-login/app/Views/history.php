<?php
$q = strtolower(trim((string)($_GET['q'] ?? '')));
$date = trim((string)($_GET['date'] ?? ''));
$rows = array_values(array_filter($allBookings, function ($b) use ($q, $date) {
    if (!in_array($b['status'], ['Selesai','Dibatalkan'], true)) return false;
    if ($date !== '' && $b['booking_date'] !== $date) return false;
    if ($q !== '') {
        return str_contains(strtolower($b['booking_code']), $q) || str_contains(strtolower($b['customer_name']), $q);
    }
    return true;
}));
?>
<div class="admin-toolbar">
  <form class="search-form" method="get" action="index.php">
    <input type="hidden" name="page" value="history">
    <input class="form-control" name="q" placeholder="Cari kode / nama pelanggan..." value="<?=e($_GET['q'] ?? '')?>">
    <input class="form-control date-input" type="date" name="date" value="<?=e($date)?>">
    <button class="btn btn-outline-red" type="submit">Filter Tanggal</button>
  </form>
</div>

<section class="card table-card">
  <div class="table-scroll">
    <table class="data-table">
      <thead><tr><th>Tanggal</th><th>Antrean</th><th>Pelanggan</th><th>Layanan</th><th>Status</th></tr></thead>
      <tbody>
        <?php if (!$rows): ?>
          <tr><td colspan="5" class="table-empty">Tidak ada riwayat servis yang cocok.</td></tr>
        <?php else: ?>
          <?php foreach ($rows as $row): ?>
            <tr>
              <td><?=tanggal_short_id($row['booking_date'])?></td>
              <td class="cell-strong"><?=queue_number($row)?></td>
              <td><?=e($row['customer_name'])?></td>
              <td><?=e($row['service_name'])?></td>
              <td><span class="status-text <?=status_class($row['status'])?>"><?=status_label($row['status'])?></span></td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</section>
