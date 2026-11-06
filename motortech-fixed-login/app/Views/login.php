<section class="login-shell">
  <div class="login-brandside">
    <a class="brand login-brand" href="index.php">
      <img class="brand-logo" src="assets/images/motortech-logo.png" alt="MOTORTECH Mechanical Engineering">
    </a>
    <div class="login-copy">
      <span class="eyebrow red-text">PENGELOLAAN BENGKEL</span>
      <h1>MOTORTECH</h1>
      <p class="login-sub">MECHANICAL ENGINEERING</p>
      <p class="login-desc">Sistem administrasi booking, antrean,<br>dan layanan servis dalam satu tempat.</p>
    </div>
  </div>

  <div class="login-card">
    <div class="card-head">
      <h2>Login Admin</h2>
      <p class="muted">Masuk untuk mengelola operasional bengkel.</p>
    </div>
    <?php if (!empty($error)): ?>
      <div class="alert alert-danger" role="alert"><?=e($error)?></div>
    <?php endif; ?>
    <form method="post" action="index.php?page=login" class="login-form" autocomplete="on">
      <div class="form-field">
        <label for="username">Username</label>
        <input id="username" class="form-control" name="username" required autocomplete="username"
          placeholder="Masukkan username" value="<?=e($_POST['username'] ?? '')?>">
      </div>
      <div class="form-field password-field">
        <label for="password">Password</label>
        <div class="password-wrap">
          <input id="password" type="password" class="form-control" name="password" required
            autocomplete="current-password" placeholder="Masukkan password">
          <button class="password-toggle" type="button" id="togglePassword">Lihat</button>
        </div>
      </div>
      <label class="remember-row"><input type="checkbox" name="remember" value="1"> Ingat saya</label>
      <button type="submit" class="btn btn-red btn-block">Masuk ke Dashboard</button>
    </form>
    <p class="login-footnote">Akses khusus admin / petugas</p>
    <a class="text-link login-back" href="index.php">← Kembali ke website</a>
  </div>
</section>
