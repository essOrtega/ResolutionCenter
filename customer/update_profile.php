<?php
session_start();

// Access control
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header("Location: ../login.php");
    exit;
}

require_once '../controller/UserController.php';

$userController = new UserController();

// ⭐ FIX: getUserById() returns a mysqli result, so fetch_assoc() is required
$result = $userController->getUserById($_SESSION['user_id']);
$user = $result->fetch_assoc();

$errors = [];
$success = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = [
        'first_name' => trim($_POST['first_name']),
        'last_name' => trim($_POST['last_name']),
        'street' => trim($_POST['street']),
        'city' => trim($_POST['city']),
        'state' => trim($_POST['state']),
        'zip' => trim($_POST['zip']),
        'phone' => trim($_POST['phone'])
    ];

    // Basic validation
    foreach ($data as $key => $value) {
        if (empty($value)) {
            $errors[] = ucfirst(str_replace('_', ' ', $key)) . " is required.";
        }
    }

    if (empty($errors)) {
        $updated = $userController->updateCustomer($_SESSION['user_id'], $data);

        if ($updated) {
            $success = "Profile updated successfully.";

            // Refresh user data
            $result = $userController->getUserById($_SESSION['user_id']);
            $user = $result->fetch_assoc();
        } else {
            $errors[] = "Failed to update profile.";
        }
    }
}

include '../header.php';
?>

<h2>Update Profile</h2>

<?php if (!empty($errors)): ?>
    <div style="color:red;">
        <?php foreach ($errors as $e): ?>
            <p><?= htmlspecialchars($e) ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php if (!empty($success)): ?>
    <div style="color:green;">
        <p><?= htmlspecialchars($success) ?></p>
    </div>
<?php endif; ?>

<form method="post">

    <label>First Name</label><br>
    <input type="text" name="first_name" value="<?= htmlspecialchars($user['first_name'] ?? '') ?>" required><br><br>

    <label>Last Name</label><br>
    <input type="text" name="last_name" value="<?= htmlspecialchars($user['last_name'] ?? '') ?>" required><br><br>

    <label>Email</label><br> 
    <input type="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required><br><br>

    <label>Phone Number</label><br>
    <input type="text" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" required><br><br>

    <label>Street Address</label><br>
    <input type="text" name="street" value="<?= htmlspecialchars($user['street'] ?? '') ?>" required><br><br>

    <label>City</label><br>
    <input type="text" name="city" value="<?= htmlspecialchars($user['city'] ?? '') ?>" required><br><br>

    <label>State</label><br>
    <input type="text" name="state" value="<?= htmlspecialchars($user['state'] ?? '') ?>" required><br><br>

    <label>Zip Code</label><br>
    <input type="text" name="zip" value="<?= htmlspecialchars($user['zip'] ?? '') ?>" required><br><br>

    <button type="submit">Update Profile</button>
</form>

<br>
<a href="customerDashboard.php">Back to Dashboard</a>

<?php include '../footer.php'; ?>

