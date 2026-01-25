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

    $note = trim($_POST['notes'] ?? '');
    $date = $_POST['resolution_date'] ?? '';

    if ($note === '') {
        $errors[] = "Resolution notes are required to close the complaint.";
    }

    if ($date === '') {
        $errors[] = "Resolution date is required.";
    }

    if (empty($errors)) {
        $controller->addNoteToComplaint($complaintId, $_SESSION['user_id'], $note);

        $controller->resolveComplaint($complaintId, $note, $date);
   
        header("Location: view_complaint.php?id=" . $complaintId);
        exit;
    }    
}
?>

<?php include '../header.php'; ?>

<h2>Resolve Complaint #<?= htmlspecialchars($c['id']) ?></h2>

<p><strong>Description:</strong> <?= htmlspecialchars($c['description']) ?></p>

<?php if (!empty($errors)): ?>
    <div class="error-box"> 
        <?php foreach ($errors as $e): ?> 
            <p class="error"><?= htmlspecialchars($e) ?></p> 
        <?php endforeach; ?> 
    </div> 
<?php endif; ?>

<form method="post">
    <label>Resolution Notes:<br>
        <textarea name="notes" rows="5" cols="50"></textarea>
    </label><br><br>

    <label>Resolution Date:<br>
        <input type="date" name="resolution_date" value="<?= date('Y-m-d') ?>" required>
    </label><br><br>

    <button type="submit">Mark as Resolved</button>
</form>

<br>
<a href="view_complaint.php?id=<?= $c['id'] ?>">Back to Complaint</a>

<?php include '../footer.php'; ?>
