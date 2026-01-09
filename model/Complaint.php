<?php
require_once __DIR__ . '/../core/Model.php';

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
}
