<?php
require_once 'controller/LoginController.php';

session_start();

$controller = new LoginController(); 
$errors = $controller->login();
?> 

<?php include 'header.php'; ?>

<h2>Login</h2>

<?php if (!empty($errors['general'])): ?> 
    <p class="error"><?= htmlspecialchars($errors['general']) ?></p> 
<?php endif; ?>

<form method="post" action="login.php">

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
        