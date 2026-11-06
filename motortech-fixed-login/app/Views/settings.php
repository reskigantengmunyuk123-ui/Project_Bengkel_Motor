<div class="settings-grid">
<section class="card settings-card">
  <div class="card-head">
    <h2>Informasi Akun</h2>
    <p class="muted">Akun yang sedang mengakses workspace.</p>
  </div>
  <dl class="detail-list">
    <div><dt>Nama</dt><dd><?=e($_SESSION['mechanic_name'] ?? 'Admin MOTORTECH')?></dd></div>
    <div><dt>Username</dt><dd><?=e($_SESSION['mechanic_username'] ?? 'admin')?></dd></div>
    <div><dt>Peran</dt><dd>Administrator</dd></div>
    <div><dt>Status</dt><dd><span class="status-text is-done">Aktif</span></dd></div>
  </dl>
</section>

<section class="card settings-card">
  <div class="card-head">
    <h2>Informasi Bengkel</h2>
    <p class="muted">Data operasional yang tampil di website publik.</p>
  </div>
  <dl class="detail-list">
    <div><dt>Nama Bengkel</dt><dd>MOTORTECH</dd></div>
    <div><dt>Jam Operasional</dt><dd>Senin – Minggu 08.00 – 21.00</dd></div>
    <div><dt>Alamat</dt><dd>Jl. Pemuda No. 123, Banyumas</dd></div>
    <div><dt>Layanan</dt><dd>Servis rutin, tune up, ganti oli, ganti ban, servis rem</dd></div>
  </dl>
</section>
</div>

<div class="settings-links">
  <a class="quick-link" href="index.php" target="_blank" rel="noopener"><span>↗</span><div><b>Lihat Website Publik</b><small>Buka halaman pelanggan</small></div><i>→</i></a>
  <a class="quick-link" href="index.php?page=logout"><span>↪</span><div><b>Keluar dari Workspace</b><small>Akhiri sesi admin</small></div><i>→</i></a>
</div>
