# MOTORTECH — Website Bengkel Motor

Website booking servis pelanggan tanpa login dan workspace internal dengan satu role: **Mechanic**.

Aplikasi ini punya **satu basis kode dengan dua area terpisah**:
- **Area pelanggan** — publik, mobile-first, tanpa login (booking, cek antrean, katalog sparepart).
- **Area admin/mechanic** — wajib login, layout workspace terpisah untuk operasional bengkel.

Keduanya memakai database yang sama. Tidak perlu membuat dua website/domain berbeda; pemisahan dilakukan lewat layout, navigasi, dan proteksi halaman admin.

## Teknologi
- PHP 8.0+
- MySQL / MariaDB
- HTML, CSS, JavaScript
- Bootstrap 5.3

## Fitur
### Halaman pelanggan (tanpa login, mobile-first)
- Beranda dengan hero, jam operasional, lokasi, dan layanan profesional
- Daftar layanan dan harga
- Katalog sparepart yang tersedia (hanya lihat)
- Booking servis terjadwal: pilih tanggal, lihat jumlah slot kosong, lalu pilih jam yang tersedia
- Antrean langsung untuk kunjungan hari ini tanpa memilih jadwal
- Kode booking otomatis dan tiket antrean yang bisa dicetak
- Cek status antrean berdasarkan kode booking atau nomor antrean

### Workspace mechanic (wajib login)
- Login admin dengan layout terpisah
- Dashboard: statistik, grafik 7 hari, dan aktivitas terbaru
- Manajemen antrean: panggil berikutnya, proses, dan selesaikan servis
- Manajemen booking: ubah status booking
- Data pelanggan dari riwayat booking
- Manajemen layanan dan stok sparepart
- Transaksi, riwayat servis, laporan, dan pengaturan
- Tema gelap mengikuti desain MOTORTECH (merah #D71920) dan mobile-first

## Menjalankan dengan FlyEnv
1. Tambahkan folder proyek ini sebagai website di FlyEnv dan aktifkan web server serta MySQL.
2. Arahkan document root virtual host ke folder proyek ini. Entry point `index.php` dan aturan `.htaccess` akan meneruskan aplikasi ke `public/`, sehingga membuka domain FlyEnv tidak menampilkan halaman Forbidden karena root tidak memiliki halaman awal.
3. Buat database kosong bernama `motortech_db` melalui database manager FlyEnv.
4. Periksa `config/config.php` dan sesuaikan host, nama database, username, serta password MySQL FlyEnv.
5. Buka `/setup.php` sekali. Setup akan membuat tabel serta layanan awal, lalu membuat atau mereset akun mechanic. Hapus `setup.php` setelah selesai.
6. Buka domain lokal yang ditampilkan FlyEnv.

### Akun awal mechanic
- Username: `mechanic`
- Password: `mechanic123`

Segera ganti password awal sebelum aplikasi digunakan secara publik. Untuk deployment, arahkan document root web server ke folder `public/`.

## Catatan
Versi ini adalah fondasi aplikasi yang bisa dijalankan secara lokal. Belum mencakup integrasi WhatsApp otomatis, upload foto kendaraan, pembayaran online, maupun multi-mekanik.

### Pemisahan area
- Pelanggan membuka `public/index.php` dan hanya melihat halaman publik. Menu **Sparepart** mengarah ke katalog publik (`?page=sparepart`), bukan halaman admin.
- Mechanic membuka **Login Mekanik**, lalu masuk ke workspace (`?page=dashboard`). Halaman inventaris sparepart (`?page=spareparts`) hanya bisa diakses setelah login.
- Jika ingin memakai domain terpisah (mis. `www` untuk pelanggan dan `admin` untuk bengkel), arahkan keduanya ke aplikasi yang sama dan tambahkan aturan redirect di web server. Tidak perlu menduplikasi kode.
