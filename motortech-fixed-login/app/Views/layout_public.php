<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<meta name="theme-color" content="#0B0C0F">
<title>MOTORTECH | Bengkel Motor Profesional</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@500;600;700;800;900&family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/css/style.css?v=<?=filemtime(__DIR__ . '/../../public/assets/css/style.css')?>">
</head>
<body class="public-site<?= $page==='login'?' public-bare':'' ?>">
<?php
$navActive = [
    'home' => ['home'],
    'layanan' => ['layanan'],
    'booking' => ['booking','booking-jadwal','success'],
    'antri' => ['antri'],
    'status' => ['status','tiket'],
    'sparepart' => ['sparepart'],
];
$isActive = function (string $key) use ($page, $navActive): string {
    return in_array($page, $navActive[$key] ?? [], true) ? 'active' : '';
};
?>
<?php if ($page !== 'login'): ?>
<header class="topbar-wrap">
  <nav class="topbar navbar navbar-expand-lg navbar-dark">
    <div class="container">
      <a class="navbar-brand brand" href="index.php">
        <img class="brand-logo" src="assets/images/motortech-logo.png" alt="MOTORTECH Mechanical Engineering">
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#siteNavMenu" aria-controls="siteNavMenu" aria-expanded="false" aria-label="Buka menu">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="siteNavMenu">
        <div class="navbar-nav ms-auto align-items-lg-center">
          <a class="nav-link <?= $isActive('home') ?>" href="index.php">Beranda</a>
          <a class="nav-link <?= $isActive('layanan') ?>" href="index.php?page=layanan">Layanan</a>
          <a class="nav-link <?= $isActive('booking') ?>" href="index.php?page=booking">Booking</a>
          <a class="nav-link <?= $isActive('antri') ?>" href="index.php?page=antri">Antrean Langsung</a>
          <a class="nav-link <?= $isActive('status') ?>" href="index.php?page=status">Cek Antrean</a>
          <a class="nav-link <?= $isActive('sparepart') ?>" href="index.php?page=sparepart">Sparepart</a>
          <a class="nav-link" href="index.php#tentang">Tentang</a>
          <a class="nav-link" href="index.php#kontak">Kontak</a>
          <a class="nav-link nav-admin" href="index.php?page=login">Masuk Admin</a>
        </div>
      </div>
    </div>
  </nav>
</header>
<?php endif; ?>
<main class="public-main">
<?php include $view; ?>
</main>
<?php if ($page !== 'login'): ?>
<footer class="site-footer" id="kontak">
  <div class="container footer-inner">
    <div class="footer-brand">
      <img class="brand-logo footer-logo" src="assets/images/motortech-logo.png" alt="MOTORTECH Mechanical Engineering">
      <p>Servis motor lebih mudah, cepat dan terpercaya.</p>
    </div>
    <div class="footer-links">
      <a href="index.php">Beranda</a>
      <a href="index.php?page=layanan">Layanan</a>
      <a href="index.php?page=sparepart">Sparepart</a>
      <a href="index.php?page=booking">Booking</a>
      <a href="index.php?page=status">Cek Antrean</a>
    </div>
    <div class="footer-note">© <?=date('Y')?> MOTORTECH. All Rights Reserved.</div>
  </div>
</footer>
<nav class="mobile-dock" aria-label="Navigasi cepat">
  <a class="<?= $isActive('home') ?>" href="index.php"><span>⌂</span>Beranda</a>
  <a class="<?= $isActive('layanan') ?>" href="index.php?page=layanan"><span>▤</span>Layanan</a>
  <a class="<?= $isActive('booking') ?>" href="index.php?page=booking"><span>＋</span>Booking</a>
  <a class="<?= $isActive('antri') ?>" href="index.php?page=antri"><span>◷</span>Antrean</a>
  <a class="<?= $isActive('status') ?>" href="index.php?page=status"><span>◎</span>Status</a>
</nav>
<?php endif; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/app.js"></script>
</body>
</html>
