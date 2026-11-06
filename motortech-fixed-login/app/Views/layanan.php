<section class="page-head">
  <h1>Daftar Layanan Bengkel</h1>
  <p>Pilih jenis perawatan atau perbaikan yang sesuai dengan kebutuhan motor.</p>
</section>

<?php if (!$services): ?>
  <div class="empty-state">
    <strong>Belum ada layanan</strong>
    <p class="muted">Layanan akan tampil di sini setelah ditambahkan oleh admin.</p>
  </div>
<?php else: ?>
  <div class="service-grid">
    <?php foreach ($services as $service): ?>
      <article class="service-card">
        <div class="service-card-top">
          <h3><?=e($service['name'])?></h3>
          <p class="muted"><?=e($service['description'] ?: 'Layanan bengkel MOTORTECH.')?></p>
        </div>
        <span class="gear">⚙</span>
        <strong class="price"><?=money($service['price'])?></strong>
        <a class="btn btn-red btn-block" href="index.php?page=booking&service_id=<?=(int)$service['id']?>">Pilih Layanan</a>
      </article>
    <?php endforeach; ?>
  </div>
<?php endif; ?>
