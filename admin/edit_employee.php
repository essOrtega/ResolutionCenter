<?php
session_start();

require_once '../core/auth_middleware.php';
require_role(['admin']);

require_once '../controller/UserController.php';

$userController = new UserController();

// Ensure employee ID is provided
if (!isset($_GET['id'])) {
    echo "No employee selected.";
    exit;
}

$employeeId = (int) $_GET['id'];

// Fetch employee data
$employee = $userController->getUserById($employeeId);

if (!$employee) {
    echo "Employee not found.";
    exit;
}

$e = $employee->fetch_assoc();
$errors = [];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = $userController->updateEmployee($employeeId, $_POST);

    if (empty($errors)) {
        header("Location: admin_dashboard.php");
        exit;
    }
}

include '../header.php';
?>

<h2>Edit Employee: <?= htmlspecialchars($e['first_name'] . ' ' . $e['last_name']) ?></h2>

<?php if (!empty($errors)): ?>
    <div style="color:red;">
        <?php foreach ($errors as $err): ?>
            <p><?= htmlspecialchars($err) ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<form method="post">

    <label>User ID (cannot be changed):</label><br>
    <input type="text" value="<?= $e['user_id'] ?>" disabled><br><br>

    <label>First Name:</label><br>
    <input type="text" name="first_name" value="<?= htmlspecialchars($e['first_name']) ?>" required><br><br>

    <label>Last Name:</label><br>
    <input type="text" name="last_name" value="<?= htmlspecialchars($e['last_name']) ?>" required><br><br>

    <label>Email:</label><br>
    <input type="email" name="email" value="<?= htmlspecialchars($e['email']) ?>" required><br><br>

    <label>Phone:</label><br>
    <input type="text" name="phone_ext" value="<?= htmlspecialchars($e['phone']) ?>" required><br><br>

    <label>Role:</label><br>
    <select name="role" required>
        <option value="technician" <?= $e['role'] === 'technician' ? 'selected' : '' ?>>Technician</option>
        <option value="admin" <?= $e['role'] === 'admin' ? 'selected' : '' ?>>Administrator</option>
    </select><br><br>

    <button type="submit">Update Employee</button>
</form>

<br>
<a href="admin_dashboard.php">Back to Dashboard</a>

<?php include '../footer.php'; ?>
