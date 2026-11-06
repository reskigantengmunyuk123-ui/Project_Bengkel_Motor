<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<meta name="theme-color" content="#0B0C0F">
<title>MOTORTECH Workspace | <?=e($page)?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@500;600;700;800;900&family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/css/style.css?v=<?=filemtime(__DIR__ . '/../../public/assets/css/style.css')?>">
</head>
<body class="admin-site">
<?php
$adminNav = [
    'dashboard' => 'Dashboard',
    'queue' => 'Antrean',
    'bookings' => 'Booking',
    'customers' => 'Pelanggan',
    'services' => 'Layanan',
    'spareparts' => 'Stok Sparepart',
    'transactions' => 'Transaksi',
    'history' => 'Riwayat Servis',
    'reports' => 'Laporan',
    'settings' => 'Pengaturan',
];
$adminMeta = [
    'dashboard' => ['Dashboard Admin', 'Ringkasan aktivitas operasional MOTORTECH hari ini.'],
    'queue' => ['Manajemen Antrean', 'Pantau antrean aktif dan perbarui status pelanggan.'],
    'bookings' => ['Manajemen Booking', 'Kelola permintaan booking, jadwal, dan konfirmasi pelanggan.'],
    'customers' => ['Data Pelanggan', 'Lihat data pelanggan dan riwayat kunjungan.'],
    'services' => ['Manajemen Layanan', 'Atur daftar layanan, deskripsi, estimasi biaya, dan durasi.'],
    'spareparts' => ['Stok Sparepart', 'Catat komponen dan pantau ketersediaan stok bengkel.'],
    'transactions' => ['Transaksi', 'Pantau pendapatan hari ini dan telusuri riwayat transaksi servis.'],
    'history' => ['Riwayat Servis', 'Telusuri pekerjaan servis yang sudah selesai maupun dibatalkan.'],
    'reports' => ['Laporan Bengkel', 'Rekap operasional berdasarkan periode yang dipilih.'],
    'settings' => ['Pengaturan', 'Informasi akun dan konfigurasi workspace.'],
];
[$adminTitle, $adminSubtitle] = $adminMeta[$page] ?? [ucfirst($page), 'MOTORTECH Workspace'];
?>
<div class="admin-shell">
  <header class="admin-mobilebar">
    <a class="brand" href="index.php?page=dashboard">
      <img class="brand-logo" src="assets/images/motortech-logo.png" alt="MOTORTECH">
    </a>
    <button class="admin-menu-btn" type="button" id="adminMenuToggle" aria-controls="adminSidebar" aria-expanded="false" aria-label="Buka menu workspace">
      <span></span><span></span><span></span>
    </button>
  </header>

  <aside class="sidebar" id="adminSidebar">
    <a class="brand sidebar-brand" href="index.php?page=dashboard">
      <img class="brand-logo" src="assets/images/motortech-logo.png" alt="MOTORTECH Mechanical Engineering">
    </a>
    <div class="side-sep"></div>
    <nav class="sidebar-nav">
      <?php foreach ($adminNav as $slug => $label): ?>
        <a class="<?= $page===$slug?'active':'' ?>" href="index.php?page=<?=$slug?>"><span class="dot"></span><?=$label?></a>
      <?php endforeach; ?>
    </nav>
    <div class="side-bottom">
      <div class="side-sep"></div>
      <div class="user-chip"><b><?=e($_SESSION['mechanic_name']??'Admin MOTORTECH')?></b><small>Administrator</small></div>
      <a class="side-link" href="index.php" target="_blank" rel="noopener">↗ Lihat Website</a>
      <a class="side-link side-logout" href="index.php?page=logout" aria-label="Keluar dari akun">
        <svg class="logout-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M10 17l5-5-5-5M15 12H3"/><path d="M12 3h6a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-6"/></svg>
        <span>Keluar</span>
      </a>
    </div>
  </aside>
  <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

  <main class="admin-main">
    <header class="admin-top">
      <div>
        <h1><?=e($adminTitle)?></h1>
        <p><?=e($adminSubtitle)?></p>
      </div>
      <span class="date-pill"><?=date('d M Y')?></span>
    </header>
    <?php include $view; ?>
  </main>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/app.js"></script>
</body>
</html>
