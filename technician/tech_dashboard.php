<?php
session_start();

// LOAD CONTROLLER
require_once '../controller/ComplaintController.php';
require_once '../core/auth_middleware.php';

require_role(['technician']);

$controller = new ComplaintController();

// FETCH COMPLAINTS ASSIGNED TO THIS TECHNICIAN
$techId = $_SESSION['user_id'];
$complaints = $controller->getComplaintsByTechnician($techId);
?>

<?php include '../header.php'; ?>

<h2>Welcome, technician!</h2>
<h3>Your Assigned Complaints</h3>

<?php if ($complaints->num_rows === 0): ?>
    <p>No complaints have been assigned to you yet.</p>

<?php else: ?>
    <table border="1" cellpadding="8" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>Product</th>
            <th>Type</th>
            <th>Description</th>
            <th>Status</th>
            <th>Submitted</th>
            <th>Actions</th>
        </tr>

        <?php while ($c = $complaints->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($c['id']) ?></td>
                <td><?= htmlspecialchars($c['product_id']) ?></td>
                <td><?= htmlspecialchars($c['complaint_type_id']) ?></td>
                <td><?= htmlspecialchars($c['description']) ?></td>
                <td><?= htmlspecialchars($c['status']) ?></td>
                <td><?= htmlspecialchars($c['created_at'] ?? '') ?></td>

                <td>
                    
                    <a href="view_complaint.php?id=<?= $c['id'] ?>">View</a> |
                    <a href="add_note.php?id=<?= $c['id'] ?>">Add Note</a> |
                    <a href="resolve.php?id=<?= $c['id'] ?>">Resolve</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
<?php endif; ?>

<?php include '../footer.php'; ?>

