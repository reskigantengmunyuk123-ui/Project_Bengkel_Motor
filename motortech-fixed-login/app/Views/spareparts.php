<div class="admin-toolbar">
  <p class="muted">Catat komponen dan pantau ketersediaan stok bengkel.</p>
  <button class="btn btn-red" type="button" data-bs-toggle="collapse" data-bs-target="#addSparepart" aria-expanded="false">Tambah Sparepart</button>
</div>

<div class="collapse" id="addSparepart">
  <section class="card form-card form-card-sm">
    <div class="card-head"><h2>Tambah Sparepart</h2><p class="muted">Stok akan tersedia di katalog publik.</p></div>
    <form method="post" action="index.php?page=spareparts">
      <div class="form-grid">
        <div class="form-field"><label for="name">Nama Sparepart</label><input id="name" name="name" class="form-control" required placeholder="Contoh: Oli Mesin 1L"></div>
        <div class="form-field"><label for="sku">SKU</label><input id="sku" name="sku" class="form-control" placeholder="SPR-001"></div>
        <div class="form-field"><label for="stock">Stok</label><input id="stock" name="stock" type="number" min="0" class="form-control" required placeholder="10"></div>
        <div class="form-field"><label for="price">Harga</label><input id="price" name="price" type="number" min="0" class="form-control" required placeholder="45000"></div>
      </div>
      <div class="form-actions"><button class="btn btn-red" type="submit">Simpan Sparepart</button></div>
    </form>
  </section>
</div>

<section class="card table-card">
  <div class="table-scroll">
    <table class="data-table">
      <thead><tr><th>Nama Sparepart</th><th>SKU</th><th>Stok</th><th>Harga</th><th>Status</th></tr></thead>
      <tbody>
        <?php if (!$allSpareparts): ?>
          <tr><td colspan="5" class="table-empty">Belum ada sparepart tercatat.</td></tr>
        <?php else: ?>
          <?php foreach ($allSpareparts as $row): ?>
            <tr>
              <td class="cell-strong"><?=e($row['name'])?></td>
              <td><?=e($row['sku'])?></td>
              <td><?=(int)$row['stock']?></td>
              <td><?=money($row['price'])?></td>
              <td><span class="status-text <?=(int)$row['stock']>0?'is-done':'is-cancel'?>"><?=(int)$row['stock']>0?'Tersedia':'Habis'?></span></td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</section>
