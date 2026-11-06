<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_set_cookie_params(['lifetime'=>0, 'path'=>'/', 'httponly'=>true, 'samesite'=>'Lax']);
    session_start();
}
require_once __DIR__ . '/../app/Core/Database.php';
require_once __DIR__ . '/../app/Core/Auth.php';
require_once __DIR__ . '/../app/Models/Booking.php';
require_once __DIR__ . '/../app/Models/Service.php';
require_once __DIR__ . '/../app/Models/Mechanic.php';
require_once __DIR__ . '/../app/Models/Sparepart.php';

use App\Core\Auth;
use App\Models\Booking;
use App\Models\Service;
use App\Models\Mechanic;
use App\Models\Sparepart;

function e($v): string { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
function money($v): string { return 'Rp ' . number_format((float)$v,0,',','.'); }
function go(string $page): void { header('Location: index.php?page='.$page); exit; }
function queue_number(array $b): string { return 'A-' . str_pad((string)($b['id'] ?? 0), 3, '0', STR_PAD_LEFT); }
function status_class(string $s): string {
    return match ($s) {
        'Menunggu' => 'is-waiting',
        'Diproses' => 'is-process',
        'Selesai' => 'is-done',
        'Dibatalkan' => 'is-cancel',
        default => 'is-waiting',
    };
}
function tanggal_id(string $date): string {
    $ts = strtotime($date) ?: time();
    $months = [1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    return date('d', $ts) . ' ' . $months[(int)date('n', $ts)] . ' ' . date('Y', $ts);
}
function tanggal_short_id(string $date): string {
    $ts = strtotime($date) ?: time();
    $months = [1=>'Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
    return date('d', $ts) . ' ' . $months[(int)date('n', $ts)] . ' ' . date('Y', $ts);
}
function jam_id(string $time): string { return str_replace(':', '.', substr($time,0,5)) . ' WIB'; }
function status_label(string $s): string { return $s === 'Diproses' ? 'Dikerjakan' : $s; }
function service_duration(string $name): int {
    $map = [
        'Servis Rutin' => 60,
        'Ganti Oli' => 20,
        'Tune Up' => 90,
        'Ganti Ban' => 45,
        'Servis Rem' => 40,
        'Perbaikan Mesin' => 120,
    ];
    return $map[$name] ?? 60;
}
function month_label(string $ym): string {
    $months = [1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    [$y,$m] = array_map('intval', explode('-', $ym));
    return ($months[$m] ?? '') . ' ' . $y;
}
function calendar_days(string $ym): array {
    $first = $ym . '-01';
    $daysInMonth = (int)date('t', strtotime($first));
    $startDow = (int)date('w', strtotime($first));
    $cells = array_fill(0, $startDow, null);
    for ($d = 1; $d <= $daysInMonth; $d++) {
        $cells[] = ['day' => $d, 'date' => sprintf('%s-%02d', $ym, $d)];
    }
    while (count($cells) % 7 !== 0) $cells[] = null;
    return $cells;
}

$adminPages = ['dashboard','queue','bookings','customers','services','spareparts','transactions','history','reports','settings'];
$publicPages = ['home','layanan','booking','antri','booking-jadwal','booking-submit','status','success','tiket','login','sparepart','logout'];

$page = (string)($_GET['page'] ?? 'home');
if (!in_array($page, array_merge($adminPages, $publicPages), true)) {
    http_response_code(404);
    $page = '404';
}

$error = '';
$services = [];
$draft = $_SESSION['booking_draft'] ?? [];

// Login ditangani lebih dulu supaya halaman login tetap tampil walau database bermasalah.
if ($page === 'login') {
    if (Auth::check()) go('dashboard');
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = trim((string)($_POST['username'] ?? ''));
        $password = (string)($_POST['password'] ?? '');
        try {
            $mechanic = (new Mechanic())->authenticate($username, $password);
            if ($mechanic) {
                Auth::login($mechanic);
                go('dashboard');
            }
            $error = 'Username atau password tidak cocok. Periksa kembali data login Anda.';
        } catch (\Throwable $exception) {
            error_log('MOTORTECH login error: ' . $exception->getMessage());
            $error = 'Login belum dapat diproses. Periksa konfigurasi database di config/config.php.';
        }
    }
    $view = __DIR__ . '/../app/Views/login.php';
    include __DIR__ . '/../app/Views/layout.php';
    exit;
}

if ($page === 'logout') { Auth::logout(); go('home'); }
if (in_array($page, $adminPages, true)) Auth::requireLogin();

try {
    $bookingModel = new Booking();
    $serviceModel = new Service();
    $sparepartModel = new Sparepart();
} catch (\PDOException $exception) {
    error_log('MOTORTECH database connection error: ' . $exception->getMessage());
    http_response_code(503);
    include __DIR__ . '/../app/Views/database_error.php';
    exit;
}

if (in_array($page, ['home','layanan','booking','antri','booking-jadwal','booking-submit'], true)) {
    $services = $serviceModel->all();
}

if ($page === 'booking' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $required = ['customer_name','whatsapp','motorcycle','plate_number','service_id'];
    foreach ($required as $key) {
        if (empty(trim((string)($_POST[$key] ?? '')))) $error = 'Mohon lengkapi seluruh data wajib.';
    }
    if (!$error && !$serviceModel->find((int)$_POST['service_id'])) $error = 'Layanan tidak valid.';
    if (!$error) {
        $_SESSION['booking_draft'] = [
            'customer_name' => trim($_POST['customer_name']),
            'whatsapp' => trim($_POST['whatsapp']),
            'motorcycle' => trim($_POST['motorcycle']),
            'plate_number' => strtoupper(trim($_POST['plate_number'])),
            'service_id' => (int)$_POST['service_id'],
            'complaint' => trim($_POST['complaint'] ?? ''),
        ];
        go('booking-jadwal');
    }
    $draft = $_POST;
}

if ($page === 'antri' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $required = ['customer_name','whatsapp','motorcycle','plate_number','service_id'];
    foreach ($required as $key) {
        if (empty(trim((string)($_POST[$key] ?? '')))) $error = 'Mohon lengkapi seluruh data wajib.';
    }
    $service = !$error ? $serviceModel->find((int)$_POST['service_id']) : null;
    if (!$error && !$service) $error = 'Layanan tidak valid.';
    if (!$error) {
        $code = $bookingModel->create([
            'customer_name' => trim($_POST['customer_name']),
            'whatsapp' => trim($_POST['whatsapp']),
            'motorcycle' => trim($_POST['motorcycle']),
            'plate_number' => strtoupper(trim($_POST['plate_number'])),
            'service_id' => (int)$_POST['service_id'],
            'complaint' => trim($_POST['complaint'] ?? ''),
            'booking_date' => date('Y-m-d'),
            'booking_time' => date('H:i:s'),
        ]);
        $_SESSION['last_booking_code'] = $code;
        $_SESSION['last_booking_mode'] = 'antri';
        unset($_SESSION['booking_draft']);
        go('success');
    }
    $draft = $_POST;
}

if ($page === 'booking-jadwal') {
    if (!$draft) go('booking');

    $month = (string)($_GET['month'] ?? date('Y-m'));
    if (!preg_match('/^\d{4}-\d{2}$/', $month)) $month = date('Y-m');
    $selectedDate = (string)($_GET['date'] ?? '');
    $slots = ['08:00','09:00','10:00','11:00','13:00','14:00'];
    $taken = $selectedDate ? $bookingModel->takenSlots($selectedDate) : [];
    $takenByDate = $bookingModel->takenSlotsByDate($month . '-01', date('Y-m-t', strtotime($month . '-01')));
    $calendar = calendar_days($month);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $date = (string)($_POST['booking_date'] ?? '');
        $time = (string)($_POST['booking_time'] ?? '');
        if ($date === '' || $time === '') $error = 'Pilih tanggal dan jam kedatangan.';
        if (!$error && $date < date('Y-m-d')) $error = 'Tanggal booking tidak boleh di masa lalu.';
        if (!$error && !in_array($time, $slots, true)) $error = 'Slot jam tidak valid.';
        if (!$error && $date === date('Y-m-d') && $time <= date('H:i')) $error = 'Jam tersebut sudah lewat. Pilih slot lain.';
        if (!$error && in_array($time, $bookingModel->takenSlots($date), true)) $error = 'Slot jam tersebut sudah terisi. Pilih jam lain.';
        if (!$error) {
            $service = $serviceModel->find((int)$draft['service_id']);
            if (!$service) { $error = 'Layanan tidak valid.'; }
            else {
                $code = $bookingModel->create([
                    'customer_name' => $draft['customer_name'],
                    'whatsapp' => $draft['whatsapp'],
                    'motorcycle' => $draft['motorcycle'],
                    'plate_number' => $draft['plate_number'],
                    'service_id' => (int)$draft['service_id'],
                    'complaint' => $draft['complaint'] ?? '',
                    'booking_date' => $date,
                    'booking_time' => $time,
                ]);
                $_SESSION['last_booking_code'] = $code;
                $_SESSION['last_booking_mode'] = 'booking';
                unset($_SESSION['booking_draft']);
                go('success');
            }
        }
        $selectedDate = $date;
        $taken = $date !== '' ? $bookingModel->takenSlots($date) : [];
    }
}

if ($page === 'booking-submit') { go('booking'); }

if ($page === 'status') {
    $found = null;
    if (isset($_GET['code']) && trim($_GET['code']) !== '') $found = $bookingModel->findByReference(trim($_GET['code']));
}

if ($page === 'success') {
    $successBooking = null;
    if (!empty($_SESSION['last_booking_code'])) $successBooking = $bookingModel->findByCode((string)$_SESSION['last_booking_code']);
    if (!$successBooking) go('home');
}

if ($page === 'tiket') {
    $ticket = null;
    if (isset($_GET['code'])) $ticket = $bookingModel->findByCode(trim((string)$_GET['code']));
    if (!$ticket) go('status');
}

if ($page === 'queue') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = (string)($_POST['action'] ?? '');
        if ($action === 'call_next') {
            $next = $bookingModel->activeQueue();
            foreach ($next as $row) {
                if ($row['status'] === 'Menunggu') { $bookingModel->updateStatus((int)$row['id'], 'Diproses'); break; }
            }
        } elseif (isset($_POST['booking_id'])) {
            $id = (int)$_POST['booking_id'];
            if ($action === 'process') $bookingModel->updateStatus($id, 'Diproses');
            if ($action === 'done') $bookingModel->updateStatus($id, 'Selesai');
        }
        go('queue');
    }
}

if ($page === 'bookings' && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['booking_id'],$_POST['status'])) {
    $allowed = ['Menunggu','Diproses','Selesai','Dibatalkan'];
    if (in_array($_POST['status'], $allowed, true)) $bookingModel->updateStatus((int)$_POST['booking_id'], $_POST['status']);
    go('bookings');
}
if ($page === 'services' && $_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['name'])) {
    $serviceModel->save(trim($_POST['name']), trim($_POST['description'] ?? ''), (float)($_POST['price'] ?? 0));
    go('services');
}
if ($page === 'spareparts' && $_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['name'])) {
    $sparepartModel->create(trim($_POST['name']), trim($_POST['sku'] ?? ''), (int)($_POST['stock'] ?? 0), (float)($_POST['price'] ?? 0));
    go('spareparts');
}

$allBookings = in_array($page, ['dashboard','queue','bookings','customers','transactions','history','reports'], true) ? $bookingModel->all() : [];
$allServices = $page === 'services' ? $serviceModel->allForManage() : [];
$allSpareparts = [];
if ($page === 'spareparts') $allSpareparts = $sparepartModel->all();
if ($page === 'sparepart') $allSpareparts = $sparepartModel->available();

$queueBookings = $page === 'queue' ? $bookingModel->activeQueue() : [];
$recentBookings = $page === 'dashboard' ? $bookingModel->recent(5) : [];
$chartData = in_array($page, ['dashboard','reports'], true) ? $bookingModel->last7Days() : [];
$serviceBreakdown = $page === 'reports' ? $bookingModel->serviceBreakdown() : [];

$viewPage = $page === 'antri' ? 'booking' : $page;
$view = __DIR__ . '/../app/Views/' . $viewPage . '.php';
if (!file_exists($view)) { http_response_code(404); $view = __DIR__ . '/../app/Views/404.php'; }
include __DIR__ . '/../app/Views/layout.php';
