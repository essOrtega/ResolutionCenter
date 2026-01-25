<?php
session_start();

// CHECK LOGIN + ROLE
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header("Location: ../login.php");
    exit;
}

require_once '../controller/ProductController.php'; 
require_once '../controller/ComplaintTypeController.php'; 
require_once '../controller/ComplaintController.php';

// Load controllers
$productController = new ProductController(); 
$products = $productController->getProducts(); 

$typeController = new ComplaintTypeController(); 
$types = $typeController->getTypes();

$complaintController = new ComplaintController(); 
$errors = [];

// If form submitted, process complaint
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = $complaintController->submitComplaint();
}

// Fetch complaints for this user
$userId = $_SESSION['user_id'];
$complaints = $complaintController->getComplaintsByUser($userId);

include '../header.php';
?>

<h2>Welcome!</h2>

<?php if (isset($_GET['success'])): ?>
    <p style="color: green;">Complaint submitted successfully!</p>
<?php endif; ?>

<!-- SUBMIT COMPLAINT BOX -->
<div style="border:1px solid #ccc; padding:15px; margin-bottom:20px; border-radius:8px;">
    <h3>Submit a Complaint</h3>

    <?php if (!empty($errors)): ?> 
        <div class="error-box"> 
            <?php foreach ($errors as $e): ?> 
                <p class="error"><?= htmlspecialchars($e) ?></p> 
            <?php endforeach; ?> 
        </div> 
    <?php endif; ?> 

    <form action="" method="post" enctype="multipart/form-data"> 
        
        <label>Product/Service:</label><br>
        <select name="product_id">
            <option value="">-- Select --</option>
            <?php while ($p = $products->fetch_assoc()): ?>
                <option value="<?= $p['product_id'] ?>">
                    <?= htmlspecialchars($p['name']) ?>
                </option>
            <?php endwhile; ?>
        </select><br><br>

        <label>Complaint Type:</label><br>
        <select name="complaint_type_id">
            <option value="">-- Select --</option>
            <?php while ($t = $types->fetch_assoc()): ?>
                <option value="<?= $t['complaint_type_id'] ?>">
                    <?= htmlspecialchars($t['type_name']) ?>
                </option>
            <?php endwhile; ?>
        </select><br><br>

        <label>Description:</label><br>
        <textarea name="description" rows="4" cols="50"></textarea><br><br>

        <label>Upload Image (optional):</label><br>
        <input type="file" name="image" accept="image/png, image/jpeg"><br><br>

        <input type="hidden" name="user_id" value="<?= $_SESSION['user_id'] ?>">

        <button type="submit">Submit Complaint</button>
    </form>
</div>

<!-- VIEW COMPLAINTS SECTION -->
<h3>Your Complaints</h3>

<?php if ($complaints->num_rows === 0): ?>
    <p>You have not submitted any complaints yet.</p>

<?php else: ?>
    <table border="1" cellpadding="8" cellspacing="0">
        <tr>
            <th>Complaint</th>
            <th>Status</th>
            <th>Technician Notes</th>
            <th>Submitted</th>
        </tr>

        <?php while ($c = $complaints->fetch_assoc()): ?>
            <tr> 
                <td><?= htmlspecialchars($c['description']) ?></td> 
                <td><?= htmlspecialchars($c['status']) ?></td> 
                <td><?= htmlspecialchars($c['technician_notes'] ?? 'None yet') ?></td> 
                <td><?= htmlspecialchars($c['created_at']) ?></td> 
                <td> 
                    <a href="deleteComplaint.php?id=<?= $c['complaint_id'] ?>" 
                        onclick="return confirm('Delete this complaint?');"> 
                        Delete 
                    </a> 
                </td> 
            </tr>
        <?php endwhile; ?>
    </table>
<?php endif; ?>

<br>
<a href="update_profile.php">Update My Information</a>

<?php include '../footer.php'; ?>


