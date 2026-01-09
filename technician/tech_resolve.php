<?php
session_start();

// CHECK LOGIN + ROLE
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'technician') {
    header("Location: ../login.php");
    exit;
}

// LOAD CONTROLLER
require_once '../controller/ComplaintController.php';

$controller = new ComplaintController();

// GET COMPLAINT ID
if (!isset($_GET['id'])) {
    echo "No complaint selected.";
    exit;
}

$complaintId = $_GET['id'];

// FETCH COMPLAINT DETAILS
$complaint = $controller->getComplaintById($complaintId);

if (!$complaint) {
    echo "Complaint not found.";
    exit;
}

$c = $complaint->fetch_assoc();

// HANDLE FORM SUBMISSION
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    echo "<p>Complaint resolved (placeholder). Redirecting...</p>";
    exit;
}
?>

<?php include '../header.php'; ?>

<h2>Resolve Complaint #<?= htmlspecialchars($c['id']) ?></h2>

<p><strong>Description:</strong> <?= htmlspecialchars($c['description']) ?></p>

<form method="post">
    <label>Resolution Notes (optional):<br>
        <textarea name="notes" rows="5" cols="50"></textarea>
    </label><br><br>

    <button type="submit">Mark as Resolved</button>
</form>

<br>
<a href="view_complaint.php?id=<?= $c['id'] ?>">Back to Complaint</a>

<?php include '../footer.php'; ?>
