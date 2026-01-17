<?php
session_start();

// ROLE CHECK
require_once '../core/auth_middleware.php';
require_role(['admin']);

// LOAD CONTROLLERS
require_once '../controller/ComplaintController.php';
require_once '../controller/UserController.php';

$complaintController = new ComplaintController();
$userController = new UserController();

// GET COMPLAINT ID
if (!isset($_GET['id'])) {
    echo "No complaint selected.";
    exit;
}

$complaintId = (int) $_GET['id'];

// FETCH COMPLAINT DETAILS
$complaint = $complaintController->getComplaintById($complaintId);

if (!$complaint) {
    echo "Complaint not found.";
    exit;
}

$c = $complaint->fetch_assoc();

// FETCH TECHNICIANS
$technicians = $userController->getTechnicians();

// HANDLE FORM SUBMISSION
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $techId = (int) $_POST['technician_id'];

    if ($techId > 0) {
        $complaintController->assignTechnician($complaintId, $techId);

        header("Location: admin_dashboard.php");
        exit;
    }
}
?>

<?php include '../header.php'; ?>

<h2>Assign Technician to Complaint #<?= htmlspecialchars($c['complaint_id']) ?></h2>

<p><strong>Description:</strong> <?= htmlspecialchars($c['description']) ?></p>

<form method="post">
    <label>Select Technician:</label><br>
    <select name="technician_id" required>
        <option value="">-- Select Technician --</option>

        <?php while ($t = $technicians->fetch_assoc()): ?>
            <option value="<?= $t['user_id'] ?>">
                <?= htmlspecialchars($t['first_name'] . ' ' . $t['last_name']) ?>
            </option>
        <?php endwhile; ?>
    </select>

    <br><br>
    <button type="submit">Assign Technician</button>
</form>

<br>
<a href="admin_dashboard.php">Back to Dashboard</a>

<?php include '../footer.php'; ?>
