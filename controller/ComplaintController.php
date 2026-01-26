<?php
require_once __DIR__ . '/../model/Complaint.php';
require_once __DIR__ . '/../validation.php';
require_once __DIR__ . '/../core/logger.php';

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

            // Default value if no image uploaded 
            $imagePath = null;

            // Handle image upload (optional)
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {

                try{ 
                    $allowedTypes = ['image/jpeg', 'image/png']; 
                    $maxSize = 2 * 1024 * 1024; // 2MB 
                    $fileTmp = $_FILES['image']['tmp_name']; 
                    $fileSize = $_FILES['image']['size']; 
                    $fileType = mime_content_type($fileTmp); 
                        
                    // Validate MIME type 
                    if (!in_array($fileType, $allowedTypes)) { 
                        $errors[] = "Only JPG and PNG images are allowed."; 
                        return $errors; 
                    } 
                        
                    // Validate size 
                    if ($fileSize > $maxSize) { 
                        $errors[] = "Image must be under 2MB."; 
                        return $errors; 
                    } 
                        
                    // Generate safe filename 
                    $ext = ($fileType === 'image/png') ? '.png' : '.jpg'; 
                    $newName = bin2hex(random_bytes(16)) . $ext;
                        
                    // Secure storage directory (outside web root)
                    $targetDir = __DIR__ . '/../../storage/uploads/images/'; 
                        
                    // Ensure directory exists 
                    if (!is_dir($targetDir)) { 
                        mkdir($targetDir, 0777, true); 
                    } 
                        
                    // Move file 
                    if (!move_uploaded_file($fileTmp, $targetDir . $newName)) { 
                        log_event("Failed to move uploaded file for user {$_POST['user_id']}"); 
                        $errors[] = "Failed to upload image."; 
                        return $errors; 
                    } 
                        
                    // Store only filename in DB
                    $imagePath = 'uploads/' . $newName;
                    
                } catch (Exception $e) {
                    $errors['image'] = $e->getMessage(); 
                    return $errors;
                }
            }

            // Save complaint
            $data = [
                'user_id' => $_POST['user_id'],
                'product_id' => $_POST['product_id'],
                'complaint_type_id' => $_POST['complaint_type_id'],
                'description' => $_POST['description'],
                'image_path' => $imagePath
            ];

            $complaint = new Complaint();
            $complaint->submitComplaint($data);

            header("Location: ../customer/customerDashboard.php?success=1");
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

    public function resolveComplaint($id, $notes, $date) {
        $complaint = new Complaint();
        return $complaint->resolveComplaint($id, $notes, $date);
    }

    public function addNoteToComplaint($complaintId, $techId, $noteText) {
        $complaint = new Complaint();
        return $complaint->addTechnicianNote($complaintId, $techId, $noteText);
    }

    public function getAllComplaints() {
        $complaint = new Complaint();
        return $complaint->getAllComplaints();
    }

    public function getAssignedComplaints() {
        $complaint = new Complaint();
        return $complaint->getAssignedComplaints();
    }

    public function getUnassignedComplaints() {
        $complaint = new Complaint();
        return $complaint->getUnassignedComplaints();
    }

    public function getTechnicianWorkload() {
        $complaint = new Complaint();
        return $complaint->getTechnicianWorkload();
    }

    public function showAssignForm($id) {
        $complaint = new Complaint();
        $user = new User();

        $complaintData = $complaint->getComplaintById($id);
        $technicians = $user->getTechnicians();

        return [
            'complaint' => $complaintData->fetch_assoc(),
            'technicians' => $technicians
        ];
    }

    public function assignTechnicianToComplaint($complaintId, $techId) {
        $complaint = new Complaint();
        return $complaint->assignTechnician($complaintId, $techId);
    }

    public function deleteComplaint($complaintId, $userId) {
        $complaint = new Complaint();
        return $complaint->deleteComplaint($complaintId, $userId);
    }

    public function getNotesByComplaintId($complaintId) {
        $complaint = new Complaint();
        return $complaint->getNotesByComplaintId($complaintId);
    }

}
