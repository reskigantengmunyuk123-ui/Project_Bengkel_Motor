<div class="admin-toolbar">
  <p class="muted">Kelola permintaan booking, jadwal, dan konfirmasi pelanggan.</p>
  <a class="btn btn-red" href="index.php?page=booking" target="_blank" rel="noopener">Tambah Booking</a>
</div>

<section class="card table-card">
  <div class="table-scroll">
    <table class="data-table">
      <thead>
        <tr><th>Kode Booking</th><th>Pelanggan</th><th>Jadwal</th><th>Layanan</th><th>Status</th></tr>
      </thead>
      <tbody>
        <?php if (!$allBookings): ?>
          <tr><td colspan="5" class="table-empty">Belum ada booking. Tambahkan booking baru untuk memulai.</td></tr>
        <?php else: ?>
          <?php foreach ($allBookings as $row): ?>
            <tr>
              <td class="cell-strong"><?=e($row['booking_code'])?></td>
              <td><?=e($row['customer_name'])?></td>
              <td><?=date('d M', strtotime($row['booking_date']))?> • <?=str_replace(':','.',substr($row['booking_time'],0,5))?></td>
              <td><?=e($row['service_name'])?></td>
              <td>
                <form method="post" action="index.php?page=bookings" class="inline-form">
                  <input type="hidden" name="booking_id" value="<?=(int)$row['id']?>">
                  <select name="status" class="status-select <?=status_class($row['status'])?>" onchange="this.form.submit()">
                    <?php foreach (['Menunggu','Diproses','Selesai','Dibatalkan'] as $st): ?>
                      <option value="<?=$st?>" <?=$row['status']===$st?'selected':''?>><?=$st === 'Diproses' ? 'Dikonfirmasi' : status_label($st)?></option>
                    <?php endforeach; ?>
                  </select>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</section>

<section class="card flow-card">
  <h2>Alur Booking</h2>
  <p class="muted">Booking masuk → Admin konfirmasi → Jadwal tersimpan → Pelanggan menerima kode booking.</p>
</section>
