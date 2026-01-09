<?php
require_once 'controller/UserController.php';

session_start();

$controller = new UserController(); 
$errors = $controller->register();
?>

<?php include 'header.php'; ?> 

<h2>Create Your Account</h2>

<?php if (!empty($errors)): ?> 
    <div class="error-box"> 
        <?php foreach ($errors as $e): ?> 
            <p class="error"><?= htmlspecialchars($e) ?></p> 
        <?php endforeach; ?> 
    </div> 
<?php endif; ?>

<form method="post" action="register.php">

    <label>First Name:
        <input type="text" name="first_name" value="<?= htmlspecialchars($first_name ?? '') ?>">
    </label>
    <span class="error"><?= $errors['first_name'] ?? '' ?></span><br>

    <label>Last Name:
        <input type="text" name="last_name" value="<?= htmlspecialchars($last_name ?? '') ?>">
    </label>
    <span class="error"><?= $errors['last_name'] ?? '' ?></span><br>

    <label>Email:
        <input type="email" name="email" value="<?= htmlspecialchars($email ?? '') ?>">
    </label>
    <span class="error"><?= $errors['email'] ?? '' ?></span><br>

    <label>Phone:
        <input type="text" name="phone" value="<?= htmlspecialchars($phone ?? '') ?>">
    </label>
    <span class="error"><?= $errors['phone'] ?? '' ?></span><br>

    <label>Street:
        <input type="text" name="street" value="<?= htmlspecialchars($street ?? '') ?>">
    </label>
    <span class="error"><?= $errors['street'] ?? '' ?></span><br>

    <label>City:
        <input type="text" name="city" value="<?= htmlspecialchars($city ?? '') ?>">
    </label>
    <span class="error"><?= $errors['city'] ?? '' ?></span><br>

    <label>State:
        <input type="text" name="state" maxlength="2" value="<?= htmlspecialchars($state ?? '') ?>">
    </label>
    <span class="error"><?= $errors['state'] ?? '' ?></span><br>

    <label>Zip:
        <input type="text" name="zip" value="<?= htmlspecialchars($zip ?? '') ?>">
    </label>
    <span class="error"><?= $errors['zip'] ?? '' ?></span><br>

    <label>Password:
        <input type="password" name="password">
    </label>
    <span class="error"><?= $errors['password'] ?? '' ?></span><br>

    <label>Confirm Password:
        <input type="password" name="confirm_password">
    </label>
    <span class="error"><?= $errors['confirm_password'] ?? '' ?></span><br>

    <button type="submit">Register</button>
</form>

<?php include 'footer.php'; ?>
