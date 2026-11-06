<?php
namespace App\Models;
use App\Core\Database;
class Service {
    private $db;
    public function __construct() { $this->db=Database::connect(); }
    public function all(): array { return $this->db->query("SELECT * FROM services WHERE is_active=1 ORDER BY id")->fetchAll(); }
    public function allForManage(): array { return $this->db->query("SELECT * FROM services ORDER BY id")->fetchAll(); }
    public function find(int $id): ?array { $q=$this->db->prepare("SELECT * FROM services WHERE id=?"); $q->execute([$id]); return $q->fetch() ?: null; }
    public function save(string $name,string $description,float $price): void {
        $q=$this->db->prepare("INSERT INTO services(name,description,price) VALUES(?,?,?)"); $q->execute([$name,$description,$price]);
    }
}
