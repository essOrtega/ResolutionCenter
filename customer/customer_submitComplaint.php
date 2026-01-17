<?php
require_once '../controller/ProductController.php'; 
require_once '../controller/ComplaintTypeController.php'; 
require_once '../controller/ComplaintController.php';
require_once '../core/auth_middleware.php'; 
require_role(['customer']);

session_start();

// Redirect if not logged in or not a customer
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') { 
    header("Location: ../login.php"); 
    exit; 
}

//Fetch products and complaint types using controllers
$productController = new ProductController(); 
$products = $productController->getProducts(); 

$typeController = new ComplaintTypeController(); 
$types = $typeController->getTypes();

// Handle complaint submission using ComplaintController
$complaintController = new ComplaintController(); 
$errors = $complaintController->submitComplaint();
?>

<?php include '../header.php'; ?>

<h2>Submit a Complaint</h2>

<?php if (!empty($errors)): ?> 
    <div class="error-box"> 
        <?php foreach ($errors as $e): ?> 
            <p class="error"><?= htmlspecialchars($e) ?></p> 
        <?php endforeach; ?> 
    </div> 
<?php endif; ?> 

<form method="post" action="submit_complaint.php" enctype="multipart/form-data"> 
    
    <label>Product/Service: 
        <select name="product_id"> 
            <option value="">-- Select --</option> 
            
            <?php while ($p = $products->fetch_assoc()): ?> 
                <option value="<?= $p['id'] ?>" 
                    <?= (($_POST['product_id'] ?? '') == $p['id']) ? 'selected' : '' ?>> 
                    <?= htmlspecialchars($p['name']) ?> 
                </option> 
            <?php endwhile; ?> 
        
        </select> 
    </label> 
    <span class="error"><?= $errors['product_id'] ?? '' ?></span><br> 
    
    <label>Complaint Type: 
        <select name="complaint_type_id"> 
            <option value="">-- Select --</option> 
            
            <?php while ($t = $types->fetch_assoc()): ?> 
                <option value="<?= $t['id'] ?>" 
                <?= (($_POST['complaint_type_id'] ?? '') == $t['id']) ? 'selected' : '' ?>> 
                <?= htmlspecialchars($t['name']) ?> 
                </option> 
            <?php endwhile; ?> 
        
        </select> 
    </label> 
    <span class="error"><?= $errors['complaint_type_id'] ?? '' ?></span><br>

    <label>Description: 
        <textarea name="description" rows="5" cols="40"><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea> 
    </label> 
    <span class="error"><?= $errors['description'] ?? '' ?></span><br> 
    
    <label>Upload Image (optional): 
        <input type="file" name="image"> 
    </label> 
    <span class="error"><?= $errors['image'] ?? '' ?></span><br> 
    
    <input type="hidden" name="user_id" value="<?= $_SESSION['user_id'] ?>">

    <button type="submit">Submit Complaint</button> 
</form> 

<?php include '../footer.php'; ?>
