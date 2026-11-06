<div class="admin-toolbar">
  <p class="muted">Atur daftar layanan, deskripsi, estimasi biaya, dan durasi.</p>
  <button class="btn btn-red" type="button" data-bs-toggle="collapse" data-bs-target="#addService" aria-expanded="false">Tambah Layanan</button>
</div>

<div class="collapse" id="addService">
  <section class="card form-card form-card-sm">
    <div class="card-head"><h2>Tambah Layanan Baru</h2><p class="muted">Layanan akan langsung tampil di halaman publik.</p></div>
    <form method="post" action="index.php?page=services">
      <div class="form-grid">
        <div class="form-field"><label for="name">Nama Layanan</label><input id="name" name="name" class="form-control" required placeholder="Contoh: Servis Rutin"></div>
        <div class="form-field"><label for="price">Harga Mulai</label><input id="price" name="price" type="number" min="0" class="form-control" required placeholder="50000"></div>
        <div class="form-field form-span"><label for="description">Deskripsi</label><input id="description" name="description" class="form-control" placeholder="Pemeriksaan berkala dan penyetelan umum"></div>
      </div>
      <div class="form-actions"><button class="btn btn-red" type="submit">Simpan Layanan</button></div>
    </form>
  </section>
</div>

<section class="card table-card">
  <div class="table-scroll">
    <table class="data-table">
      <thead><tr><th>Nama Layanan</th><th>Deskripsi</th><th>Harga Mulai</th><th>Durasi</th><th>Status</th></tr></thead>
      <tbody>
        <?php if (!$allServices): ?>
          <tr><td colspan="5" class="table-empty">Belum ada layanan. Tambahkan layanan pertama Anda.</td></tr>
        <?php else: ?>
          <?php foreach ($allServices as $row): ?>
            <tr>
              <td class="cell-strong"><?=e($row['name'])?></td>
              <td><?=e($row['description'])?></td>
              <td><?=money($row['price'])?></td>
              <td><?=service_duration($row['name'])?> menit</td>
              <td><span class="status-text is-done"><?=!empty($row['is_active'])?'Aktif':'Nonaktif'?></span></td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</section>
