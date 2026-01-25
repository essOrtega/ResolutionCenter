<?php
session_start();

require_once '../controller/ComplaintController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $complaintId = (int) $_POST['complaint_id'];
    $techId = (int) $_POST['technician_id'];

    $controller = new ComplaintController();
    $controller->assignTechnicianToComplaint($complaintId, $techId);

    header("Location: admin_dashboard.php?assigned=1");
    exit;
}
