<?php
namespace App\Models;
use App\Core\Database;
class Booking {
    private $db;
    public function __construct() { $this->db = Database::connect(); }

    public function create(array $d): string {
        $q = $this->db->prepare("INSERT INTO bookings (booking_code, customer_name, whatsapp, motorcycle, plate_number, service_id, complaint, booking_date, booking_time, status) VALUES (?,?,?,?,?,?,?,?,?,'Menunggu')");
        for ($attempt = 0; $attempt < 5; $attempt++) {
            $code = 'MT-' . date('ymd') . '-' . str_pad((string)random_int(1,999), 3, '0', STR_PAD_LEFT);
            try {
                $q->execute([$code,$d['customer_name'],$d['whatsapp'],$d['motorcycle'],$d['plate_number'],$d['service_id'],$d['complaint'],$d['booking_date'],$d['booking_time']]);
                return $code;
            } catch (\PDOException $exception) {
                if ((string)$exception->getCode() !== '23000') throw $exception;
            }
        }
        throw new \RuntimeException('Gagal membuat kode booking unik.');
    }

    public function findByCode(string $code): ?array {
        $q=$this->db->prepare("SELECT b.*,s.name AS service_name,s.price AS service_price FROM bookings b JOIN services s ON s.id=b.service_id WHERE b.booking_code=?");
        $q->execute([$code]); $r=$q->fetch(); return $r ?: null;
    }

    public function findByReference(string $reference): ?array {
        $reference = trim($reference);
        if (preg_match('/^A-(\d+)$/i', $reference, $match)) {
            return $this->findById((int)$match[1]);
        }
        return $this->findByCode($reference);
    }

    public function findById(int $id): ?array {
        $q=$this->db->prepare("SELECT b.*,s.name AS service_name,s.price AS service_price FROM bookings b JOIN services s ON s.id=b.service_id WHERE b.id=?");
        $q->execute([$id]); $r=$q->fetch(); return $r ?: null;
    }

    public function all(): array {
        return $this->db->query("SELECT b.*,s.name AS service_name,s.price AS service_price FROM bookings b JOIN services s ON s.id=b.service_id ORDER BY b.booking_date DESC,b.booking_time DESC,id DESC")->fetchAll();
    }

    public function activeQueue(): array {
        return $this->db->query("SELECT b.*,s.name AS service_name,s.price AS service_price FROM bookings b JOIN services s ON s.id=b.service_id WHERE b.status IN ('Menunggu','Diproses') ORDER BY b.booking_date ASC,b.booking_time ASC,id ASC")->fetchAll();
    }

    public function completed(): array {
        return $this->db->query("SELECT b.*,s.name AS service_name,s.price AS service_price FROM bookings b JOIN services s ON s.id=b.service_id WHERE b.status='Selesai' ORDER BY b.booking_date DESC,b.booking_time DESC,id DESC")->fetchAll();
    }

    public function history(): array {
        return $this->db->query("SELECT b.*,s.name AS service_name,s.price AS service_price FROM bookings b JOIN services s ON s.id=b.service_id WHERE b.status IN ('Selesai','Dibatalkan') ORDER BY b.booking_date DESC,b.booking_time DESC,id DESC")->fetchAll();
    }

    public function recent(int $limit=5): array {
        $limit = max(1, $limit);
        return $this->db->query("SELECT b.*,s.name AS service_name FROM bookings b JOIN services s ON s.id=b.service_id ORDER BY b.created_at DESC,b.id DESC LIMIT $limit")->fetchAll();
    }

    public function todayCount(): int { return (int)$this->db->query("SELECT COUNT(*) FROM bookings WHERE booking_date=CURDATE()")->fetchColumn(); }

    public function countStatus(string $status): int {
        $q=$this->db->prepare("SELECT COUNT(*) FROM bookings WHERE status=?"); $q->execute([$status]); return (int)$q->fetchColumn();
    }

    public function last7Days(): array {
        $rows = $this->db->query("SELECT booking_date, COUNT(*) AS total FROM bookings WHERE booking_date >= DATE_SUB(CURDATE(), INTERVAL 6 DAY) GROUP BY booking_date")->fetchAll();
        $map = [];
        foreach ($rows as $row) $map[$row['booking_date']] = (int)$row['total'];
        $labels = ['Min','Sen','Sel','Rab','Kam','Jum','Sab'];
        $out = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i day"));
            $out[] = ['label' => $labels[(int)date('w', strtotime($date))], 'date' => $date, 'total' => $map[$date] ?? 0];
        }
        return $out;
    }

    public function serviceBreakdown(): array {
        return $this->db->query("SELECT s.name, COUNT(*) AS total FROM bookings b JOIN services s ON s.id=b.service_id GROUP BY s.id,s.name ORDER BY total DESC")->fetchAll();
    }

    public function takenSlots(string $date): array {
        $q=$this->db->prepare("SELECT booking_time FROM bookings WHERE booking_date=? AND status <> 'Dibatalkan'");
        $q->execute([$date]);
        return array_map(fn($r)=>substr((string)$r['booking_time'],0,5), $q->fetchAll());
    }

    public function takenSlotsByDate(string $from, string $to): array {
        $q = $this->db->prepare("SELECT booking_date, booking_time FROM bookings WHERE booking_date BETWEEN ? AND ? AND status <> 'Dibatalkan'");
        $q->execute([$from, $to]);
        $taken = [];
        foreach ($q->fetchAll() as $row) {
            $taken[$row['booking_date']][] = substr((string)$row['booking_time'], 0, 5);
        }
        return array_map(static fn($times) => array_values(array_unique($times)), $taken);
    }

    public function updateStatus(int $id,string $status): void {
        $q=$this->db->prepare("UPDATE bookings SET status=? WHERE id=?"); $q->execute([$status,$id]);
    }

    public function delete(int $id): void { $q=$this->db->prepare("DELETE FROM bookings WHERE id=?"); $q->execute([$id]); }
}
