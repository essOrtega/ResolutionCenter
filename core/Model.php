<?php
require_once __DIR__ . '/Database.php';

abstract class Model {
    protected $db;
    protected $table;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function findAll() {
        $sql = "SELECT * FROM {$this->table}";
        return $this->db->query($sql);
    }

    public function findById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE complaint_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE complaint_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}

