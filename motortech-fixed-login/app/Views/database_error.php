<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="theme-color" content="#0B0C0F">
  <title>Koneksi Database | MOTORTECH</title>
  <style>
    *{box-sizing:border-box}body{margin:0;min-height:100vh;display:grid;place-items:center;padding:24px;background:#0b0c0f;color:#f5f6f8;font:15px/1.65 Inter,Arial,sans-serif}.panel{width:min(100%,620px);padding:clamp(24px,6vw,42px);background:#15171c;border:1px solid #30323a;border-radius:18px}.brand-logo{display:block;width:min(100%,260px);height:72px;object-fit:cover;border-radius:6px;background:#fff;margin-bottom:24px}h1{margin:0 0 10px;font-size:clamp(24px,5vw,34px);line-height:1.15}p{color:#a6a9b2}.steps{padding:16px 18px;margin:20px 0;background:#101115;border-radius:12px;color:#c8cad0}.steps li+li{margin-top:8px}code{color:#ff6474;overflow-wrap:anywhere}.hint{padding:12px 14px;border-left:3px solid #f12d42;background:#211417;border-radius:4px 9px 9px 4px;color:#e2cdd0;font-size:13px}
  </style>
</head>
<body>
  <main class="panel">
    <img class="brand-logo" src="assets/images/motortech-logo.png" alt="MOTORTECH">
    <h1>Database belum tersambung</h1>
    <p>Aplikasi tidak dapat masuk ke MySQL. Periksa kredensial FlyEnv dan pastikan service MySQL sedang berjalan.</p>
    <ol class="steps">
      <li>Di FlyEnv, pastikan MySQL aktif dan database <code>motortech_db</code> sudah dibuat.</li>
      <li>Buka <code>config/config.php</code>, lalu cocokkan <code>host</code>, <code>name</code>, <code>user</code>, dan <code>pass</code> dengan konfigurasi MySQL FlyEnv.</li>
      <li>Simpan file lalu muat ulang halaman ini. Jangan kirim password database ke chat.</li>
    </ol>
    <div class="hint">Jika muncul “Access denied” untuk <code>root</code>, password pada konfigurasi aplikasi tidak cocok dengan password MySQL FlyEnv.</div>
  </main>
</body>
</html>
