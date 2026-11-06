<?php
$adminPages = ['dashboard','queue','bookings','customers','services','spareparts','transactions','history','reports','settings'];
if (in_array($page, $adminPages, true)) {
    include __DIR__ . '/layout_admin.php';
} else {
    include __DIR__ . '/layout_public.php';
}
