<?php $isDirectQueue = $page === 'antri'; ?>
<section class="page-head">
  <h1><?=$isDirectQueue ? 'Antrean Langsung' : 'Booking Servis'?></h1>
  <p><?=$isDirectQueue ? 'Ambil nomor antrean untuk datang ke bengkel hari ini.' : 'Isi data kendaraan, lalu pilih tanggal dan jam yang masih tersedia.'?></p>
</section>

<?php if ($error): ?>
  <div class="alert alert-danger alert-mt" role="alert"><?=e($error)?></div>
<?php endif; ?>

<section class="card form-card">
  <div class="card-head">
    <h2>Data Pelanggan &amp; Kendaraan</h2>
    <p class="muted"><?=$isDirectQueue ? 'Nomor antrean dibuat untuk kunjungan hari ini.' : 'Pastikan nomor WhatsApp aktif untuk menerima konfirmasi jadwal.'?></p>
  </div>
  <form method="post" action="index.php?page=<?=$isDirectQueue ? 'antri' : 'booking'?>">
    <div class="form-grid">
      <div class="form-field">
        <label for="customer_name">Nama Lengkap</label>
        <input id="customer_name" name="customer_name" class="form-control" required placeholder="Masukkan nama pelanggan" value="<?=e($draft['customer_name'] ?? '')?>">
      </div>
      <div class="form-field">
        <label for="whatsapp">Nomor WhatsApp</label>
        <input id="whatsapp" name="whatsapp" class="form-control" required inputmode="tel" placeholder="08xxxxxxxxxx" value="<?=e($draft['whatsapp'] ?? '')?>">
      </div>
      <div class="form-field">
        <label for="motorcycle">Jenis / Tipe Motor</label>
        <input id="motorcycle" name="motorcycle" class="form-control" required placeholder="Contoh: Honda Vario 125" value="<?=e($draft['motorcycle'] ?? '')?>">
      </div>
      <div class="form-field">
        <label for="plate_number">Nomor Polisi</label>
        <input id="plate_number" name="plate_number" class="form-control" required placeholder="Contoh: R 1234 AB" value="<?=e($draft['plate_number'] ?? '')?>">
      </div>
      <div class="form-field form-span">
        <label for="service_id">Pilih Layanan</label>
        <select id="service_id" name="service_id" class="form-control" required>
          <option value="">Pilih layanan yang dibutuhkan</option>
          <?php foreach ($services as $service): ?>
            <option value="<?=(int)$service['id']?>" <?=(string)($draft['service_id'] ?? ($_GET['service_id'] ?? ''))===(string)$service['id']?'selected':''?>><?=e($service['name'])?> — <?=money($service['price'])?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="form-field form-span">
        <label for="complaint">Keluhan Motor</label>
        <textarea id="complaint" name="complaint" class="form-control" rows="3" placeholder="Jelaskan masalah atau kebutuhan servis"><?=e($draft['complaint'] ?? '')?></textarea>
      </div>
    </div>
    <div class="form-actions">
      <button type="submit" class="btn btn-red btn-wide"><?=$isDirectQueue ? 'Ambil Nomor Antrean' : 'Lanjut Pilih Jadwal'?></button>
    </div>
  </form>
</section>
