<?php
namespace App\Core;
use PDO;
use PDOException;

class Database {
    private static ?PDO $connection = null;
    public static function connect(): PDO {
        if (self::$connection) return self::$connection;
        $config = require __DIR__ . '/../../config/config.php';
        $db = $config['db'];
        $port = (int)($db['port'] ?? 3306);
        $dsn = "mysql:host={$db['host']};port={$port};dbname={$db['name']};charset={$db['charset']}";
        self::$connection = new PDO($dsn, $db['user'], $db['pass'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
        return self::$connection;
    }
}
