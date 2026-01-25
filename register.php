<?php
require_once 'controller/UserController.php';

session_start();

$controller = new UserController(); 
$errors = $controller->register();
?>

<?php include 'header.php'; ?> 

<h2>Create Your Account</h2>

<form method="post" action="register.php">
    
    <div class="form-group"> 
        <label>First Name:</label> 
        <input type="text" name="first_name" value="<?= htmlspecialchars($_POST['first_name'] ?? '') ?>"> 
        <span class="error"><?= $errors['first_name'] ?? '' ?></span> 
    </div>

    <div class="form-group">
        <label>Last Name:</label>
        <input type="text" name="last_name" value="<?= htmlspecialchars($_POST['last_name'] ?? '') ?>">
        <span class="error"><?= $errors['last_name'] ?? '' ?></span>
    </div>

    <div class="form-group">
        <label>Email:</label>
        <input type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
        <span class="error"><?= $errors['email'] ?? '' ?></span>
    </div>

    <div class="form-group">
        <label>Phone:</label>
        <input type="text" name="phone" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
        <span class="error"><?= $errors['phone'] ?? '' ?></span>
    </div>

    <div class="form-group">
        <label>Street:</label>
        <input type="text" name="street" value="<?= htmlspecialchars($_POST['street'] ?? '') ?>">
        <span class="error"><?= $errors['street'] ?? '' ?></span>
    </div>

    <div class="form-group">
        <label>City:</label>
        <input type="text" name="city" value="<?= htmlspecialchars($_POST['city'] ?? '') ?>">
        <span class="error"><?= $errors['city'] ?? '' ?></span>
    </div>

    <div class="form-group">
        <label>State:</label>
        <input type="text" name="state" maxlength="2" value="<?= htmlspecialchars($_POST['state'] ?? '') ?>">
        <span class="error"><?= $errors['state'] ?? '' ?></span>
    </div>

     <div class="form-group">
        <label>Zip:</label>
        <input type="text" name="zip" value="<?= htmlspecialchars($_POST['zip'] ?? '') ?>">
        <span class="error"><?= $errors['zip'] ?? '' ?></span>
    </div>

    <div class="form-group">
        <label>Password:</label>
        <input type="password" name="password">
        <span class="error"><?= $errors['password'] ?? '' ?></span>
    </div>

    <div class="form-group">
        <label>Confirm Password:</label>
        <input type="password" name="confirm_password">
        <span class="error"><?= $errors['confirm_password'] ?? '' ?></span>
    </div>
    
    <button type="submit">Register</button>
</form>

<?php include 'footer.php'; ?>
