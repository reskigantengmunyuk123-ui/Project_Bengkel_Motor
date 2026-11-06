<?php
namespace App\Core;
class Auth {
    public static function check(): bool { return !empty($_SESSION['mechanic_id']); }
    public static function requireLogin(): void {
        if (!self::check()) { header('Location: index.php?page=login'); exit; }
    }
    public static function login(array $mechanic): void {
        session_regenerate_id(true);
        $_SESSION['mechanic_id'] = $mechanic['id'];
        $_SESSION['mechanic_name'] = $mechanic['name'];
    }
    public static function logout(): void {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $p = session_get_cookie_params();
            setcookie(session_name(), '', time()-42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
        }
        session_destroy();
    }
}
