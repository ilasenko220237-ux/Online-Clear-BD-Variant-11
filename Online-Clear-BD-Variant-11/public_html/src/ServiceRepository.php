<?php
class ServiceRepository {
    private $pdo;
    public function __construct($pdo) { $this->pdo = $pdo; }

    public function findAll($search = '') {
        $sql = "SELECT s.*, it.type_name, cm.method_name FROM services s
                LEFT JOIN item_types it ON s.type_id = it.type_id
                LEFT JOIN cleaning_methods cm ON s.method_id = cm.method_id
                WHERE s.service_name LIKE ? ORDER BY s.service_name";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(["%$search%"]);
        return $stmt->fetchAll();
    }

    public function findById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM services WHERE service_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $sql = "INSERT INTO services (service_name, price, type_id, method_id) VALUES (?, ?, ?, ?)";
        $this->pdo->prepare($sql)->execute([$data['service_name'], $data['price'], $data['type_id'], $data['method_id']]);
        return $this->pdo->lastInsertId();
    }

    public function update($id, $data) {
        $sql = "UPDATE services SET service_name=?, price=?, type_id=?, method_id=? WHERE service_id=?";
        return $this->pdo->prepare($sql)->execute([$data['service_name'], $data['price'], $data['type_id'], $data['method_id'], $id]);
    }

    public function delete($id) {
        return $this->pdo->prepare("DELETE FROM services WHERE service_id = ?")->execute([$id]);
    }

    public function hasRelatedOrders($id) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM orders WHERE service_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetchColumn() > 0;
    }
}