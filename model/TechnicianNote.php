<?php
require_once __DIR__ . '/../core/Model.php';

class TechnicianNote extends Model {
    protected $table = "technician_notes";

    public function addNote($complaintId, $technicianId, $note) {
        $sql = "INSERT INTO technician_notes (complaint_id, technician_id, note, created_at)
                VALUES (?, ?, ?, NOW())";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("iis", $complaintId, $technicianId, $note);
        return $stmt->execute();
    }

    public function updateNote($id, $note) {
        $sql = "UPDATE technician_notes SET note=? WHERE id=?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("si", $note, $id);
        return $stmt->execute();
    }

    public function getNotesByComplaint($complaintId) {
        $sql = "SELECT * FROM technician_notes WHERE complaint_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $complaintId);
        $stmt->execute();
        return $stmt->get_result();
    }
}
