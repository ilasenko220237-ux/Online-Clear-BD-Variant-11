<?php
class OrderRepository {
    private $pdo;
    public function __construct($pdo) { $this->pdo = $pdo; }

    public function findAllWithFilters($filters = []) {
        $sql = "SELECT o.*, c.last_name, c.first_name, s.service_name, os.status_name, os.sort_order
                FROM orders o
                JOIN clients c ON o.client_id = c.client_id
                JOIN services s ON o.service_id = s.service_id
                JOIN order_statuses os ON o.status_id = os.status_id
                WHERE 1=1";
        $params = [];

        if (!empty($filters['date_from'])) { $sql .= " AND DATE(o.acceptance_datetime) >= ?"; $params[] = $filters['date_from']; }
        if (!empty($filters['date_to'])) { $sql .= " AND DATE(o.acceptance_datetime) <= ?"; $params[] = $filters['date_to']; }
        if (!empty($filters['status_id'])) { $sql .= " AND o.status_id = ?"; $params[] = $filters['status_id']; }
        if (!empty($filters['client_search'])) { 
            $p = "%{$filters['client_search']}%";
            $sql .= " AND (c.last_name LIKE ? OR c.first_name LIKE ? OR c.phone LIKE ?)"; 
            $params[] = $p; $params[] = $p; $params[] = $p;
        }

        $sql .= " ORDER BY o.acceptance_datetime DESC LIMIT 100";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function createOrder($data) {
        $sql = "INSERT INTO orders (client_id, service_id, status_id, acceptance_datetime, planned_delivery_datetime, item_description) 
                VALUES (?, ?, 1, ?, ?, ?)";
        $this->pdo->prepare($sql)->execute([
            $data['client_id'], $data['service_id'],
            $data['acceptance_datetime'], $data['planned_delivery_datetime'],
            $data['item_description'] ?? ''
        ]);
        return $this->pdo->lastInsertId();
    }

    public function changeStatus($id, $newStatusId) {
        $stmt = $this->pdo->prepare("SELECT status_id FROM orders WHERE order_id = ?");
        $stmt->execute([$id]);
        $current = $stmt->fetch();
        if (!$current) throw new Exception("Заказ не найден");

        $stmt = $this->pdo->prepare("SELECT sort_order FROM order_statuses WHERE status_id = ?");
        $stmt->execute([$current['status_id']]);
        $currentSort = (int)$stmt->fetchColumn();

        $stmt->execute([$newStatusId]);
        $newSort = (int)$stmt->fetchColumn();

        if ($newSort <= $currentSort) {
            throw new Exception("Нельзя вернуть заказ в предыдущий статус!");
        }

        $this->pdo->prepare("UPDATE orders SET status_id = ? WHERE order_id = ?")->execute([$newStatusId, $id]);
        return true;
    }

    public function getOverdueOrders() {
        $sql = "SELECT o.*, c.last_name, c.first_name, s.service_name, os.status_name,
                       TIMESTAMPDIFF(HOUR, NOW(), o.planned_delivery_datetime) as hours_overdue
                FROM orders o
                JOIN clients c ON o.client_id = c.client_id
                JOIN services s ON o.service_id = s.service_id
                JOIN order_statuses os ON o.status_id = os.status_id
                WHERE o.planned_delivery_datetime < NOW() 
                  AND o.status_id != 4 
                  AND o.actual_delivery_datetime IS NULL
                ORDER BY o.planned_delivery_datetime ASC";
        return $this->pdo->query($sql)->fetchAll();
    }

    public function getRevenueByStatus($yearMonth) {
        $sql = "SELECT os.status_name, COUNT(o.order_id) as orders_count, SUM(s.price) as revenue
                FROM orders o
                JOIN order_statuses os ON o.status_id = os.status_id
                JOIN services s ON o.service_id = s.service_id
                WHERE DATE_FORMAT(o.acceptance_datetime, '%Y-%m') = ?
                GROUP BY os.status_id, os.status_name
                ORDER BY os.sort_order";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$yearMonth]);
        return $stmt->fetchAll();
    }

    // ✅ Новый метод удаления
    public function delete($id) {
        return $this->pdo->prepare("DELETE FROM orders WHERE order_id = ?")->execute([$id]);
    }
}