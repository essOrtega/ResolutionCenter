<?php
session_start();

// CHECK LOGIN + ROLE
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// LOAD CONTROLLERS
require_once '../controller/ComplaintController.php';
require_once '../controller/ProductController.php';
require_once '../controller/ComplaintTypeController.php';
require_once '../controller/UserController.php';

// FETCH DATA FOR ADMIN DASHBOARD
$complaintController = new ComplaintController();
$productController   = new ProductController();
$typeController      = new ComplaintTypeController();
$userController      = new UserController();

$complaints = $complaintController->getAllComplaints();   
$products   = $productController->getProducts();
$types      = $typeController->getTypes();
$users      = $userController->getAllUsers();
?>

<?php include '../header.php'; ?>

<h2>Welcome, admin!</h2>

<!-- COMPLAINTS TABLE -->
<h3>All Complaints</h3>

<?php if ($complaints->num_rows === 0): ?>
    <p>No complaints found.</p>
<?php else: ?>
    <table border="1" cellpadding="8" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>User</th>
            <th>Product</th>
            <th>Type</th>
            <th>Description</th>
            <th>Status</th>
            <th>Submitted</th>
        </tr>

        <?php while ($c = $complaints->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($c['id']) ?></td>
                <td><?= htmlspecialchars($c['user_id']) ?></td>
                <td><?= htmlspecialchars($c['product_id']) ?></td>
                <td><?= htmlspecialchars($c['complaint_type_id']) ?></td>
                <td><?= htmlspecialchars($c['description']) ?></td>
                <td><?= htmlspecialchars($c['status']) ?></td>
                <td><?= htmlspecialchars($c['created_at'] ?? '') ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
<?php endif; ?>

<br><br>

<!-- USERS TABLE -->
<h3>All Users</h3>

<?php if ($users->num_rows === 0): ?>
    <p>No users found.</p>
<?php else: ?>
    <table border="1" cellpadding="8" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
        </tr>

        <?php while ($u = $users->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($u['id']) ?></td>
                <td><?= htmlspecialchars($u['first_name'] . ' ' . $u['last_name']) ?></td>
                <td><?= htmlspecialchars($u['email']) ?></td>
                <td><?= htmlspecialchars($u['role']) ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
<?php endif; ?>

<br><br>

<!-- PRODUCTS TABLE -->
<h3>All Products</h3>

<?php if ($products->num_rows === 0): ?>
    <p>No products found.</p>
<?php else: ?>
    <table border="1" cellpadding="8" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>Name</th>
        </tr>

        <?php while ($p = $products->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($p['id']) ?></td>
                <td><?= htmlspecialchars($p['name']) ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
<?php endif; ?>

<br><br>

<!-- COMPLAINT TYPES TABLE -->
<h3>Complaint Types</h3>

<?php if ($types->num_rows === 0): ?>
    <p>No complaint types found.</p>
<?php else: ?>
    <table border="1" cellpadding="8" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>Type Name</th>
        </tr>

        <?php while ($t = $types->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($t['id']) ?></td>
                <td><?= htmlspecialchars($t['name']) ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
<?php endif; ?>

<?php include '../footer.php'; ?>
