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
                WHERE user_id=?";

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

    public function createEmployee($data) {
        $sql = "INSERT INTO users 
                (user_id, first_name, last_name, email, phone, role, password_hash)
                VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param(
            "issssss",
            $data['user_id'],
            $data['first_name'],
            $data['last_name'],
            $data['email'],
            $data['phone'],
            $data['role'],
            $data['password_hash']
        );

        return $stmt->execute();
    }

    public function changePassword($id, $hashedPassword) {
        $sql = "UPDATE users SET password=? WHERE user_id=?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("si", $hashedPassword, $id);
        return $stmt->execute();
    }

    public function getAllUsers() {
        $sql = "SELECT * FROM users ORDER BY last_name";
        $result = $this->db->query($sql);
        return $result;
    }

    public function getCustomers() {
        $sql = "SELECT user_id, first_name, last_name, email, role 
                FROM users 
                WHERE role = 'customer'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function getStaff() {
        $sql = "SELECT user_id, first_name, last_name, email, role 
                FROM users 
                WHERE role IN ('technician', 'admin')";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function getUserById($id) {
        $sql = "SELECT * FROM users WHERE user_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function updateEmployee($id, $data) {
        $sql = "UPDATE users 
                SET first_name=?, last_name=?, email=?, phone=?, role=?
                WHERE user_id=?";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param(
            "sssssi",
            $data['first_name'],
            $data['last_name'],
            $data['email'],
            $data['phone'],
            $data['role'],
            $id
        );

        return $stmt->execute();
    }

    public function updateCustomer($id, $data) {
        $sql = "UPDATE users 
                SET first_name=?, last_name=?, email=?, phone=?, street=?, city=?, state=?, zip=?
                WHERE user_id=?";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param(
            "ssssssssi",
            $data['first_name'],
            $data['last_name'],
            $data['email'],
            $data['phone'],
            $data['street'],
            $data['city'],
            $data['state'],
            $data['zip'],
            $id
        );

        return $stmt->execute();
    }

    public function getTechnicians() {
        $sql = "SELECT user_id, first_name, last_name, email 
                FROM users 
                WHERE role = 'technician'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function emailExists($email) { 
        $stmt = $this->db->prepare("SELECT user_id FROM users WHERE email = ? LIMIT 1"); 
        $stmt->bind_param("s", $email); 
        $stmt->execute(); 
        $result = $stmt->get_result(); 
        return $result->num_rows > 0; 
    }

    public function createUser($first, $last, $email, $password, $phone, $street, $city, $state, $zip) {

        // Hash the password
        $hashed = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users 
                (first_name, last_name, email, password_hash, phone, street, city, state, zip, role) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'customer')";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param(
            "sssssssss",
            $first,
            $last,
            $email,
            $hashed,
            $phone,
            $street,
            $city,
            $state,
            $zip
        );

        return $stmt->execute();
    }

    public function findById($id) { 
        $stmt = $this->db->prepare("SELECT * FROM users WHERE user_id=?"); 
        $stmt->bind_param("i", $id); 
        $stmt->execute(); 
        return $stmt->get_result()->fetch_assoc(); 
    }
    
    public function updatePassword($id, $hash) {
        $stmt = $this->db->prepare("UPDATE users SET password_hash=? WHERE user_id=?");
        $stmt->bind_param("si", $hash, $id);
        return $stmt->execute();
    }

}
