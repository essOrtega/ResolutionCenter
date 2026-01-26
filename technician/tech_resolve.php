<?php
session_start();

if (!empty($_SESSION['force_password_change'])) {
    header("Location: change_password.php");
    exit;
}

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'technician') { 
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

// Handle form submission
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $notes = trim($_POST['resolution_notes'] ?? '');
    $date = date('Y-m-d H:i:s'); 

    if ($notes === '') {
        $errors[] = "Resolution notes are required.";
    }

    if (empty($errors)) {
        $controller->resolveComplaint($complaintId, $notes, $date);
        header("Location: tech_viewComplaint.php?id=" . $complaintId);
        exit;
    }
}
?>

<?php include '../header.php'; ?>

<h2>Resolve Complaint #<?= htmlspecialchars($c['complaint_id']) ?></h2>

<p><strong>Description:</strong> <?= htmlspecialchars($c['description']) ?></p>

<?php if (!empty($errors)): ?>
    <div class="error-box">
        <?php foreach ($errors as $e): ?>
            <p class="error"><?= htmlspecialchars($e) ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<form method="post">
    <label>Resolution Notes (required):<br>
        <textarea name="resolution_notes" rows="5" cols="60"><?= htmlspecialchars($_POST['resolution_notes'] ?? '') ?></textarea>
    </label>
    <br><br>

    <button type="submit">Mark as Resolved</button>
</form>

<br>
<a href="tech_viewComplaint.php?id=<?= $c['complaint_id'] ?>">Back to Complaint</a>

<?php include '../footer.php'; ?>


