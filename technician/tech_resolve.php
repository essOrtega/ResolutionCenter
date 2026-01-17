<?php
session_start();

// LOAD CONTROLLER
require_once '../controller/ComplaintController.php';
require_once '../core/auth_middleware.php'; 
require_role(['technician', 'admin']);

$controller = new ComplaintController();

// GET COMPLAINT ID
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

// HANDLE FORM SUBMISSION
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $note = trim($_POST['notes'] ?? '');

    if ($note !== '') {
        $controller->addNoteToComplaint($complaintId, $_SESSION['user_id'], $note);
    }

    $controller->resolveComplaint($complaintId);
    header("Location: view_complaint.php?id=" . $complaintId);
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
