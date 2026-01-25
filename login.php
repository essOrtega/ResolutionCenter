<?php
session_start();
require_once 'controller/LoginController.php';

$controller = new LoginController(); 
$errors = $controller->login();
?> 

<?php include 'header.php'; ?>

<h2>Login</h2>

<?php if (!empty($errors['general'])): ?> 
    <p class="error"><?= htmlspecialchars($errors['general']) ?></p> 
<?php endif; ?>

<form method="post" action="/Ortega_SDC342L_Project_ResolutionCenter/login.php">

    <label>Email:
        <input type="email" name="email"
               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
    </label>
    <span class="error"><?= $errors['email'] ?? '' ?></span><br>

    <label>Password:
        <input type="password" name="password">
    </label>
    <span class="error"><?= $errors['password'] ?? '' ?></span><br>

    <button type="submit">Login</button>
</form>

<?php include 'footer.php'; ?>
        