<?php
namespace App\Models;
use App\Core\Database;

class Sparepart {
    private $db;
    public function __construct() { $this->db = Database::connect(); }
    public function all(): array {
        return $this->db->query("SELECT * FROM spareparts ORDER BY name ASC")->fetchAll();
    }
    public function available(): array {
        return $this->db->query("SELECT * FROM spareparts WHERE stock > 0 ORDER BY name ASC")->fetchAll();
    }
    public function create(string $name, string $sku, int $stock, float $price): void {
        $q = $this->db->prepare("INSERT INTO spareparts(name,sku,stock,price) VALUES(?,?,?,?)");
        $q->execute([$name, $sku, $stock, $price]);
    }
}
