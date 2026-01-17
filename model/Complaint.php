<?php
require_once __DIR__ . '/../core/Model.php';
require_once '../core/auth_middleware.php'; 
require_role(['admin']);

class Complaint extends Model {
    protected $table = "complaints";

    public function submitComplaint($data) {
        $sql = "INSERT INTO complaints 
                (user_id, product_id, complaint_type_id, description, image_path, status)
                VALUES (?, ?, ?, ?, ?, 'open')";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param(
            "iiiss",
            $data['user_id'],
            $data['product_id'],
            $data['complaint_type_id'],
            $data['description'],
            $data['image_path']
        );

        return $stmt->execute();
    }

    public function updateComplaint($id, $data) {
        $sql = "UPDATE complaints 
                SET product_id=?, complaint_type_id=?, description=? 
                WHERE id=?";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param(
            "iisi",
            $data['product_id'],
            $data['complaint_type_id'],
            $data['description'],
            $id
        );

        return $stmt->execute();
    }

    public function getComplaintsByUser($userId) {
        $sql = "SELECT * FROM complaints WHERE user_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function getComplaintsByTechnician($techId) {
        $sql = "SELECT * FROM complaints WHERE technician_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $techId);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function assignTechnician($complaintId, $techId) {
        $sql = "UPDATE complaints SET technician_id=? WHERE id=?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $techId, $complaintId);
        return $stmt->execute();
    }

    public function getComplaintById($id) {
        $sql = "SELECT * FROM complaints WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function resolveComplaint($id, $resolutionNotes) {
        $sql = "UPDATE complaints 
                SET status='closed', resolution_notes=?, resolution_date=NOW() 
                WHERE id=?";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("si", $resolutionNotes, $id);
        return $stmt->execute();
    }

    public function addTechnicianNote($complaintId, $techId, $noteText) { 
        $stmt = $this->conn->prepare(" 
            INSERT INTO technician_notes (complaint_id, technician_id, note_text) 
            VALUES (?, ?, ?) 
        "); 
        $stmt->bind_param("iis", $complaintId, $techId, $noteText); 
        return $stmt->execute(); 
    }

    public function getAllComplaints() {
        $sql = "SELECT * FROM complaints";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function getAssignedComplaints() {
        $sql = "SELECT * FROM complaints WHERE technician_id IS NOT NULL AND status = 'Open'";
        return $this->db->query($sql);
    }

    public function getUnassignedComplaints() {
        $sql = "SELECT * FROM complaints WHERE technician_id IS NULL AND status = 'Open'";
        return $this->db->query($sql);
    }

    public function getTechnicianWorkload() {
        $sql = "
            SELECT u.first_name, u.last_name, COUNT(c.complaint_id) AS open_count
            FROM users u
            LEFT JOIN complaints c ON u.user_id = c.technician_id AND c.status = 'Open'
            WHERE u.role = 'technician'
            GROUP BY u.user_id
        ";
        return $this->db->query($sql);
    }

}
