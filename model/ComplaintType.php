<?php
require_once __DIR__ . '/../core/Model.php';

class ComplaintType extends Model {
    protected $table = "complaint_types";

    public function addType($name) {
        $sql = "INSERT INTO complaint_types (name) VALUES (?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $name);
        return $stmt->execute();
    }

    public function updateType($id, $name) {
        $sql = "UPDATE complaint_types SET name=? WHERE id=?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("si", $name, $id);
        return $stmt->execute();
    }

    public function getAllTypes() {
        return $this->findAll(); // uses base Model method
    }
}
