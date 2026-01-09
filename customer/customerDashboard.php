<?php
session_start();

// CHECK LOGIN + ROLE
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header("Location: ../login.php");
    exit;
}

// LOAD CONTROLLER
require_once '../controller/ComplaintController.php';

$controller = new ComplaintController();

// FETCH COMPLAINTS FOR THIS USER
$userId = $_SESSION['user_id'];
$complaints = $controller->getComplaintsByUser($userId);
?>

<?php include '../header.php'; ?>

<h2>Welcome, customer!</h2>
<h3>Your Complaints</h3>

<?php if ($complaints->num_rows === 0): ?>
    <p>You have not submitted any complaints yet.</p>

<?php else: ?>
    <table border="1" cellpadding="8" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>Product</th>
            <th>Type</th>
            <th>Description</th>
            <th>Status</th>
            <th>Submitted</th>
        </tr>

        <?php while ($c = $complaints->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($c['id']) ?></td>
                <td><?= htmlspecialchars($c['product_id']) ?></td>
                <td><?= htmlspecialchars($c['complaint_type_id']) ?></td>
                <td><?= htmlspecialchars($c['description']) ?></td>
                <td><?= htmlspecialchars($c['status']) ?></td>
                <td><?= htmlspecialchars($c['created_at'] ?? '') ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
<?php endif; ?>

<?php include '../footer.php'; ?>
