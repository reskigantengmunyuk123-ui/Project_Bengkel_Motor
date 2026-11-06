<?php
namespace App\Models;
use App\Core\Database;
class Mechanic {
    public function findByUsername(string $username): ?array {
        $q = Database::connect()->prepare("SELECT * FROM mechanics WHERE LOWER(TRIM(username)) = LOWER(?) LIMIT 1");
        $q->execute([trim($username)]);
        return $q->fetch() ?: null;
    }
    public function authenticate(string $username, string $password): ?array {
        $mechanic = $this->findByUsername($username);
        if (!$mechanic) return null;
        $stored = (string)($mechanic['password'] ?? '');
        $valid = password_verify($password, $stored);
        // Backward compatibility for old plain-text records; upgrade to a secure hash after login.
        if (!$valid && $stored !== '' && hash_equals($stored, $password)) {
            $valid = true;
            $newHash = password_hash($password, PASSWORD_DEFAULT);
            $update = Database::connect()->prepare("UPDATE mechanics SET password=? WHERE id=?");
            $update->execute([$newHash, $mechanic['id']]);
        }
        if (!$valid) return null;
        if (password_needs_rehash($stored, PASSWORD_DEFAULT)) {
            $newHash = password_hash($password, PASSWORD_DEFAULT);
            $update = Database::connect()->prepare("UPDATE mechanics SET password=? WHERE id=?");
            $update->execute([$newHash, $mechanic['id']]);
        }
        return $mechanic;
    }
}
