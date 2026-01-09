<?php
require_once __DIR__ . '/../model/Complaint.php';
require_once __DIR__ . '/../validation.php';

class ComplaintController {

    public function submitComplaint() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            $errors = [];

            if (!validateRequired($_POST['product_id'])) $errors[] = "Product is required.";
            if (!validateRequired($_POST['complaint_type_id'])) $errors[] = "Complaint type is required.";
            if (!validateRequired($_POST['description'])) $errors[] = "Description is required.";

            if (!empty($errors)) {
                return $errors;
            }

            // Handle image upload (optional)
            $imagePath = null;
            if (!empty($_FILES['image']['name'])) {
                $targetDir = "uploads/";
                $imagePath = $targetDir . basename($_FILES["image"]["name"]);
                move_uploaded_file($_FILES["image"]["tmp_name"], $imagePath);
            }

            $data = [
                'user_id' => $_POST['user_id'],
                'product_id' => $_POST['product_id'],
                'complaint_type_id' => $_POST['complaint_type_id'],
                'description' => $_POST['description'],
                'image_path' => $imagePath
            ];

            $complaint = new Complaint();
            $complaint->submitComplaint($data);

            header("Location: customer_dashboard.php");
            exit;
        }
    }

    public function updateComplaint($id) {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            $data = [
                'product_id' => $_POST['product_id'],
                'complaint_type_id' => $_POST['complaint_type_id'],
                'description' => $_POST['description']
            ];

            $complaint = new Complaint();
            $complaint->updateComplaint($id, $data);

            header("Location: customer_dashboard.php");
            exit;
        }
    }

    public function getComplaintsByUser($userId) {
        $complaint = new Complaint();
        return $complaint->getComplaintsByUser($userId);
    }

    public function getComplaintsByTechnician($techId) {
        $complaint = new Complaint();
        return $complaint->getComplaintsByTechnician($techId);
    }

    public function getComplaintById($id) {
        $complaint = new Complaint();
        return $complaint->getComplaintById($id);
    }

    public function assignTechnician($complaintId, $techId) {
        $complaint = new Complaint();
        return $complaint->assignTechnician($complaintId, $techId);
    }

    public function resolveComplaint($id, $notes) {
        $complaint = new Complaint();
        return $complaint->resolveComplaint($id, $notes);
    }
}
