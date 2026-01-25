<?php
session_start();

// Access control
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'technician') {
    header("Location: ../login.php");
    exit;
}

require_once '../controller/UserController.php';

$userController = new UserController();
$errors = [];
$success = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $current = $_POST['current_password'];
    $new     = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];

    // Validation
    if (empty($current) || empty($new) || empty($confirm)) {
        $errors[] = "All fields are required.";
    } elseif ($new !== $confirm) {
        $errors[] = "New passwords do not match.";
    } else {
        // Attempt password change
        $result = $userController->changePassword($_SESSION['user_id'], $current, $new);

        if ($result === true) {

            unset($_SESSION['force_password_change']);

            $success = "Password changed successfully.";

        } else {
            $errors[] = $result; // Controller returns error message
        }
    }
}

include '../header.php';
?>

<h2>Change Password</h2>

<?php if (!empty($errors)): ?>
    <div style="color:red;">
        <?php foreach ($errors as $e) echo "<p>$e</p>"; ?>
    </div>
<?php endif; ?>

<?php if (!empty($success)): ?>
    <div style="color:green;">
        <p><?= $success ?></p>
    </div>

    <!-- Button to go to dashboard --> 
    <a href="tech_dashboard.php" style=" 
        display:inline-block; 
        padding:10px 15px; 
        background:#007bff; 
        color:white; 
        text-decoration:none; 
        border-radius:5px; 
        margin-top:10px; 
        ">Go to Technician Dashboard</a>

<?php else: ?>

<form method="post">
    <label>Current Password</label><br>
    <input type="password" name="current_password" required><br><br>

    <label>New Password</label><br>
    <input type="password" name="new_password" required><br><br>

    <label>Confirm New Password</label><br>
    <input type="password" name="confirm_password" required><br><br>

    <button type="submit">Change Password</button>
</form>
<?php endif; ?>
<?php include '../footer.php'; ?>
