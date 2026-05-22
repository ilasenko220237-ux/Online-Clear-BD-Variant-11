<?php
class ClientRepository {
    private $pdo;
    public function __construct($pdo) { $this->pdo = $pdo; }

    public function findAll($search = '') {
        $sql = "SELECT * FROM clients WHERE last_name LIKE ? OR first_name LIKE ? OR phone LIKE ? ORDER BY last_name";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(["%$search%", "%$search%", "%$search%"]);
        return $stmt->fetchAll();
    }

    public function findById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM clients WHERE client_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $sql = "INSERT INTO clients (last_name, first_name, patronymic, phone, email) VALUES (?, ?, ?, ?, ?)";
        $this->pdo->prepare($sql)->execute([
            $data['last_name'], $data['first_name'], $data['patronymic'] ?? null,
            $data['phone'], $data['email']
        ]);
        return $this->pdo->lastInsertId();
    }

    public function update($id, $data) {
        $sql = "UPDATE clients SET last_name=?, first_name=?, patronymic=?, phone=?, email=? WHERE client_id=?";
        return $this->pdo->prepare($sql)->execute([
            $data['last_name'], $data['first_name'], $data['patronymic'] ?? null,
            $data['phone'], $data['email'], $id
        ]);
    }

    public function delete($id) {
        return $this->pdo->prepare("DELETE FROM clients WHERE client_id = ?")->execute([$id]);
    }

    public function hasRelatedOrders($id) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM orders WHERE client_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetchColumn() > 0;
    }
}