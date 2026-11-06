<div class="admin-toolbar">
  <p class="muted">Pantau antrean aktif dan perbarui status pelanggan.</p>
  <form method="post" action="index.php?page=queue">
    <input type="hidden" name="action" value="call_next">
    <button type="submit" class="btn btn-red">Panggil Berikutnya</button>
  </form>
</div>

<section class="card table-card">
  <div class="table-scroll">
    <table class="data-table">
      <thead>
        <tr><th>No. Antrean</th><th>Pelanggan</th><th>Layanan</th><th>Jadwal</th><th>Status</th><th>Aksi</th></tr>
      </thead>
      <tbody>
        <?php if (!$allBookings): ?>
          <tr><td colspan="6" class="table-empty">Belum ada antrean hari ini.</td></tr>
        <?php else: ?>
          <?php foreach ($allBookings as $row): ?>
            <tr>
              <td class="cell-strong"><?=queue_number($row)?></td>
              <td><?=e($row['customer_name'])?></td>
              <td><?=e($row['service_name'])?></td>
              <td><?=tanggal_short_id($row['booking_date'])?> • <?=jam_id($row['booking_time'])?></td>
              <td><span class="status-text <?=status_class($row['status'])?>"><?=status_label($row['status'])?></span></td>
              <td>
                <?php if ($row['status'] === 'Menunggu'): ?>
                  <form method="post" action="index.php?page=queue" class="inline-form">
                    <input type="hidden" name="action" value="process"><input type="hidden" name="booking_id" value="<?=(int)$row['id']?>">
                    <button type="submit" class="btn btn-mini btn-red">Panggil</button>
                  </form>
                <?php elseif ($row['status'] === 'Diproses'): ?>
                  <form method="post" action="index.php?page=queue" class="inline-form">
                    <input type="hidden" name="action" value="done"><input type="hidden" name="booking_id" value="<?=(int)$row['id']?>">
                    <button type="submit" class="btn btn-mini btn-outline-red">Selesai</button>
                  </form>
                <?php else: ?>
                  <a class="btn btn-mini btn-ghost" href="index.php?page=status&code=<?=urlencode($row['booking_code'])?>" target="_blank" rel="noopener">Detail</a>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
  <p class="table-note">Pilih aksi untuk memanggil pelanggan atau memperbarui status pengerjaan.</p>
</section>
