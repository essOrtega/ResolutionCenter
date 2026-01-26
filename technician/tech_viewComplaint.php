<?php
session_start();

if (!empty($_SESSION['force_password_change'])) {
    header("Location: change_password.php");
    exit;
}

// ACCESS CONTROL
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['technician', 'admin'])) { 
    header("Location: ../login.php"); 
    exit; 
}

require_once '../controller/ComplaintController.php';

$controller = new ComplaintController();

// Validate complaint ID
if (!isset($_GET['id'])) {
    echo "No complaint selected.";
    exit;
}

$complaintId = (int) $_GET['id'];
$complaint = $controller->getComplaintById($complaintId);

if (!$complaint) {
    echo "Complaint not found.";
    exit;
}

$c = $complaint->fetch_assoc();
?>

<?php include '../header.php'; ?>

<h2>Complaint Details</h2>

<table border="1" cellpadding="8" cellspacing="0" style="border-collapse: collapse; width: 100%; max-width: 700px;">
    <tr>
        <th>ID</th>
        <td><?= htmlspecialchars($c['complaint_id']) ?></td>
    </tr>

    <tr>
        <th>Product</th>
        <td><?= htmlspecialchars($c['product_id']) ?></td>
    </tr>

    <tr>
        <th>Type</th>
        <td><?= htmlspecialchars($c['complaint_type_id']) ?></td>
    </tr>

    <tr>
        <th>Description</th>
        <td><?= nl2br(htmlspecialchars($c['description'])) ?></td>
    </tr>

    <tr>
        <th>Status</th>
        <td><?= htmlspecialchars($c['status']) ?></td>
    </tr>

    <tr>
        <th>Submitted</th>
        <td><?= htmlspecialchars($c['created_at'] ?? '') ?></td>
    </tr>

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

<br><br>

<h3>Technician Notes</h3> 
<?php $notes = $controller->getNotesByComplaintId($c['complaint_id']); ?> 

<?php if ($notes->num_rows === 0): ?> 
    <p>No technician notes have been added yet.</p> 
<?php else: ?> 
    <table border="1" cellpadding="8" cellspacing="0" 
        style="border-collapse: collapse; width: 100%; max-width: 700px;"> 
        <tr> 
            <th>Date</th>
            <th>Technician</th> 
            <th>Note</th> 
        </tr> 
        
        <?php while ($n = $notes->fetch_assoc()): ?> 
            <tr> 
                <td><?= htmlspecialchars($n['created_at']) ?></td> 
                <td><?= htmlspecialchars($n['first_name'] . ' ' . $n['last_name']) ?></td> 
                <td><?= nl2br(htmlspecialchars($n['note_text'])) ?></td> 
            </tr> 
        <?php endwhile; ?> 
    </table> 

<?php endif; ?>

<br>

<div style="margin-top: 10px;">
    <a href="tech_addNote.php?id=<?= $c['complaint_id'] ?>">Add Note</a> |
    <a href="tech_resolve.php?id=<?= $c['complaint_id'] ?>">Mark as Resolved</a> |
    <a href="tech_dashboard.php">Back to Dashboard</a>
</div>

<?php include '../footer.php'; ?>

