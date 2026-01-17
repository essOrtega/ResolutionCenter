<?php
session_start();

require_once '../core/auth_middleware.php';
require_role(['admin']);

require_once '../controller/UserController.php';

$userController = new UserController();
$errors = [];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = $userController->addEmployee($_POST);

    if (empty($errors)) {
        header("Location: admin_dashboard.php");
        exit;
    }
}

include '../header.php';
?>

<h2>Add New Employee</h2>

<?php if (!empty($errors)): ?>
    <div style="color:red;">
        <?php foreach ($errors as $e): ?>
            <p><?= htmlspecialchars($e) ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<form method="post">

    <label>User ID (cannot be changed later):</label><br>
    <input type="number" name="user_id" required><br><br>

    <label>First Name:</label><br>
    <input type="text" name="first_name" required><br><br>

    <label>Last Name:</label><br>
    <input type="text" name="last_name" required><br><br>

    <label>Email:</label><br>
    <input type="email" name="email" required><br><br>

    <label>Phone:</label><br>
    <input type="text" name="phone" required><br><br>

    <label>Role:</label><br>
    <select name="role" required>
        <option value="">-- Select Role --</option>
        <option value="technician">Technician</option>
        <option value="admin">Administrator</option>
    </select><br><br>

    <label>Temporary Password:</label><br>
    <input type="password" name="password" required><br><br>

    <button type="submit">Add Employee</button>
</form>

<br>
<a href="admin_dashboard.php">Back to Dashboard</a>

<?php include '../footer.php'; ?>
