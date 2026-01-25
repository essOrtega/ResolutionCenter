<?php
session_start();

if (!empty($_SESSION['force_password_change'])) {
    header("Location: change_password.php");
    exit;
}

// SIMPLE ACCESS CONTROL 
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['technician', 'admin'])) { 
    header("Location: ../login.php"); 
    exit; 
}

// LOAD CONTROLLER
require_once '../controller/ComplaintController.php';

$controller = new ComplaintController();

// GET COMPLAINT ID FROM URL
if (!isset($_GET['id'])) {
    echo "No complaint selected.";
    exit;
}

$complaintId = (int) $_GET['id'];

// FETCH COMPLAINT DETAILS
$complaint = $controller->getComplaintById($complaintId);

if (!$complaint) {
    echo "Complaint not found.";
    exit;
}

$c = $complaint->fetch_assoc();
?>

<?php include '../header.php'; ?>

<h2>Complaint Details</h2>

<table border="1" cellpadding="8" cellspacing="0">
    <tr><th>ID</th><td><?= htmlspecialchars($c['id']) ?></td></tr>
    <tr><th>Product</th><td><?= htmlspecialchars($c['product_id']) ?></td></tr>
    <tr><th>Type</th><td><?= htmlspecialchars($c['complaint_type_id']) ?></td></tr>
    <tr><th>Description</th><td><?= nl2br(htmlspecialchars($c['description'])) ?></td></tr>
    <tr><th>Status</th><td><?= htmlspecialchars($c['status']) ?></td></tr>
    <tr><th>Submitted</th><td><?= htmlspecialchars($c['created_at'] ?? '') ?></td></tr>

    <?php if (!empty($c['image_path'])): ?>
        <tr>
            <th>Image</th>
            <td> 
                <img src="../serve_image.php?file=<?= urlencode($c['image_path']) ?>" 
                    width="200" style="border:1px solid #ccc; padding:5px;"> 
            </td>
        </tr>
    <?php endif; ?>

    <?php if ($c['status'] === 'resolved'): ?> 
        <tr> 
            <th>Resolution Notes</th> 
            <td><?= nl2br(htmlspecialchars($c['resolution_notes'] ?? '')) ?></td> 
        </tr> 
        
        <tr> 
            <th>Resolution Date</th> 
            <td><?= htmlspecialchars($c['resolution_date'] ?? '') ?></td> 
        </tr> 
    <?php endif; ?>
</table>

<br>

<!-- These will be functional in Week 3+ -->
<a href="add_note.php?id=<?= $c['id'] ?>">Add Note</a> |
<a href="resolve.php?id=<?= $c['id'] ?>">Mark as Resolved</a> |
<a href="technician_dashboard.php">Back to Dashboard</a>

<?php include '../footer.php'; ?>
