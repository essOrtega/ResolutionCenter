<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Resolution Center</title>
    <link rel="stylesheet" href="/Ortega_SDC342L_Project_ResolutionCenter/styles.css">
</head>
<body>
<header>
    <h1>Resolution Center</h1>
    <nav>
        <a href="/Ortega_SDC342L_Project_ResolutionCenter/index.php">Home</a>

        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="/Ortega_SDC342L_Project_ResolutionCenter/logout.php">Logout</a>
        <?php else: ?>
            <a href="/Ortega_SDC342L_Project_ResolutionCenter/login.php">Login</a>
            <a href="/Ortega_SDC342L_Project_ResolutionCenter/register.php">Register</a>
        <?php endif; ?>
    </nav>
</header>
<main>

