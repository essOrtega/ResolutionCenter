<?php
require_once __DIR__ . '/../core/Model.php';

class Product extends Model {
    protected $table = "products";

    public function addProduct($name, $description) {
        $sql = "INSERT INTO products (name, description) VALUES (?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ss", $name, $description);
        return $stmt->execute();
    }

    public function updateProduct($id, $name, $description) {
        $sql = "UPDATE products SET name=?, description=? WHERE id=?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ssi", $name, $description, $id);
        return $stmt->execute();
    }

    public function getAllProducts() {
        return $this->findAll(); // uses base Model method
    }
}
