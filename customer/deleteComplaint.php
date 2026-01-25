<?php
session_start();
require_once '../controller/ComplaintController.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header("Location: ../login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: customerDashboard.php");
    exit;
}

$complaintId = intval($_GET['id']);
$userId = $_SESSION['user_id'];

$controller = new ComplaintController();
$controller->deleteComplaint($complaintId, $userId);

header("Location: customerDashboard.php?deleted=1");
exit;
