<?php
session_start();

require_once '../core/auth_middleware.php';
require_role(['admin']);

require_once '../controller/UserController.php';

$userController = new UserController();

// Ensure customer ID is provided
if (!isset($_GET['id'])) {
    echo "No customer selected.";
    exit;
}

$customerId = (int) $_GET['id'];

// Fetch customer data
$customer = $userController->getUserById($customerId);

if (!$customer) {
    echo "Customer not found.";
    exit;
}

$c = $customer->fetch_assoc();
$errors = [];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = $userController->updateCustomer($customerId, $_POST);

    if (empty($errors)) {
        header("Location: admin_dashboard.php");
        exit;
    }
}

include '../header.php';
?>

<h2>Edit Customer: <?= htmlspecialchars($c['first_name'] . ' ' . $c['last_name']) ?></h2>

<?php if (!empty($errors)): ?>
    <div style="color:red;">
        <?php foreach ($errors as $err): ?>
            <p><?= htmlspecialchars($err) ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<form method="post">

    <label>User ID (cannot be changed):</label><br>
    <input type="text" value="<?= $c['user_id'] ?>" disabled><br><br>

    <label>First Name:</label><br>
    <input type="text" name="first_name" value="<?= htmlspecialchars($c['first_name']) ?>" required><br><br>

    <label>Last Name:</label><br>
    <input type="text" name="last_name" value="<?= htmlspecialchars($c['last_name']) ?>" required><br><br>

    <label>Email:</label><br>
    <input type="email" name="email" value="<?= htmlspecialchars($c['email']) ?>" required><br><br>

    <label>Phone:</label><br>
    <input type="text" name="phone" value="<?= htmlspecialchars($c['phone']) ?>" required><br><br>

    <label>Street:</label><br>
    <input type="text" name="street" value="<?= htmlspecialchars($c['street']) ?>" required><br><br>

    <label>City:</label><br>
    <input type="text" name="city" value="<?= htmlspecialchars($c['city']) ?>" required><br><br>

    <label>State:</label><br>
    <input type="text" name="state" value="<?= htmlspecialchars($c['state']) ?>" required><br><br>

    <label>Zip Code:</label><br>
    <input type="text" name="zip" value="<?= htmlspecialchars($c['zip']) ?>" required><br><br>

    <button type="submit">Update Customer</button>
</form>

<br>
<a href="admin_dashboard.php">Back to Dashboard</a>

<?php include '../footer.php'; ?>
