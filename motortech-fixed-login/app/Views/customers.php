<?php
$q = strtolower(trim((string)($_GET['q'] ?? '')));
$customers = [];
foreach ($allBookings as $row) {
    $key = $row['whatsapp'] ?: $row['customer_name'];
    if (!isset($customers[$key])) {
        $customers[$key] = [
            'name' => $row['customer_name'],
            'whatsapp' => $row['whatsapp'],
            'motorcycle' => $row['motorcycle'],
            'visits' => 0,
        ];
    }
    $customers[$key]['visits']++;
}
if ($q !== '') {
    $customers = array_filter($customers, fn($c) => str_contains(strtolower($c['name']), $q) || str_contains(strtolower($c['whatsapp']), $q));
}
?>
<div class="admin-toolbar">
  <form class="search-form" method="get" action="index.php">
    <input type="hidden" name="page" value="customers">
    <input class="form-control" name="q" placeholder="Cari nama / nomor WhatsApp..." value="<?=e($_GET['q'] ?? '')?>">
    <button class="btn btn-outline-red" type="submit">Cari</button>
  </form>
  <a class="btn btn-red" href="index.php?page=booking" target="_blank" rel="noopener">Tambah Pelanggan</a>
</div>

<section class="card table-card">
  <div class="table-scroll">
    <table class="data-table">
      <thead>
        <tr><th>Nama Pelanggan</th><th>WhatsApp</th><th>Kendaraan</th><th>Kunjungan</th></tr>
      </thead>
      <tbody>
        <?php if (!$customers): ?>
          <tr><td colspan="4" class="table-empty">Belum ada data pelanggan.</td></tr>
        <?php else: ?>
          <?php foreach ($customers as $c): ?>
            <tr>
              <td class="cell-strong"><?=e($c['name'])?></td>
              <td><?=e($c['whatsapp'])?></td>
              <td><?=e($c['motorcycle'])?></td>
              <td><?=(int)$c['visits']?> kali</td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</section>
