<?php
require_once __DIR__ . '/../core/Model.php';

class User extends Model {
    protected $table = "users";

    public function register($data) {
        $sql = "INSERT INTO users (first_name, last_name, email, password, street, city, state, zip, phone)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param(
            "sssssssss",
            $data['first_name'],
            $data['last_name'],
            $data['email'],
            $data['password'],   // already hashed before calling this
            $data['street'],
            $data['city'],
            $data['state'],
            $data['zip'],
            $data['phone']
        );

        return $stmt->execute();
    }

    public function findByEmail($email) {
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function updateProfile($id, $data) {
        $sql = "UPDATE users SET first_name=?, last_name=?, street=?, city=?, state=?, zip=?, phone=? 
                WHERE id=?";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param(
            "sssssssi",
            $data['first_name'],
            $data['last_name'],
            $data['street'],
            $data['city'],
            $data['state'],
            $data['zip'],
            $data['phone'],
            $id
        );

        return $stmt->execute();
    }

    public function changePassword($id, $hashedPassword) {
        $sql = "UPDATE users SET password=? WHERE id=?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("si", $hashedPassword, $id);
        return $stmt->execute();
    }

    public function getAllUsers() {
        $sql = "SELECT * FROM users ORDER BY last_name";
        $result = $this->db->query($sql);
        return $result;
    }

}
