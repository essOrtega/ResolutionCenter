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
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $note = trim($_POST['note']);

    if ($note === '') {
        $errors[] = "Note cannot be empty.";
    }

    if (empty($errors)) { 
        $controller->addNoteToComplaint($complaintId, $_SESSION['user_id'], $note); 
        
        header("Location: view_complaint.php?id=" . $complaintId); 
        exit;
    }
}
?>

<?php include '../header.php'; ?>

<h2>Add Note to Complaint #<?= htmlspecialchars($c['id']) ?></h2>

<p><strong>Description:</strong> <?= htmlspecialchars($c['description']) ?></p>

<?php if (!empty($errors)): ?>
    <div class="error-box">
        <?php foreach ($errors as $e): ?>
            <p class="error"><?= htmlspecialchars($e) ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<form method="post">
    <label>Technician Note:<br>
        <textarea name="note" rows="5" cols="50"><?= htmlspecialchars($_POST['note'] ?? '') ?></textarea>
    </label><br><br>

    <button type="submit">Save Note</button>
</form>

<br>
<a href="view_complaint.php?id=<?= $c['id'] ?>">Back to Complaint</a>

<?php include '../footer.php'; ?>
