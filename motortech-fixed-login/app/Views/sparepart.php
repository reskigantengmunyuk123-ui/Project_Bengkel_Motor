<section class="page-head">
  <h1>Sparepart Bengkel</h1>
  <p>Komponen pilihan yang tersedia di bengkel MOTORTECH. Hubungi mekanik kami saat booking untuk pemasangan.</p>
</section>

<?php if (!$allSpareparts): ?>
  <div class="empty-state">
    <strong>Belum ada sparepart tersedia</strong>
    <p class="muted">Silakan hubungi bengkel untuk ketersediaan komponen tertentu.</p>
  </div>
<?php else: ?>
  <div class="service-grid">
    <?php foreach ($allSpareparts as $sp): ?>
      <article class="service-card part-card">
        <div class="service-card-top">
          <span class="part-sku"><?=e($sp['sku'] ?: 'MOTORTECH')?></span>
          <h3><?=e($sp['name'])?></h3>
          <p class="muted">Stok tersedia: <?=(int)$sp['stock']?></p>
        </div>
        <span class="gear">▣</span>
        <strong class="price"><?=money($sp['price'])?></strong>
        <a class="btn btn-red btn-block" href="index.php?page=booking">Booking Servis</a>
      </article>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

<div class="parts-cta">
  <a class="btn btn-red" href="index.php?page=booking">Booking Servis</a>
  <a class="btn btn-outline-red" href="index.php?page=status">Cek Antrean</a>
</div>
