<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Clear all session data
$_SESSION = [];

// Destroy the session completely
session_destroy();

// Redirect to login page (absolute path)
header("Location: /Ortega_SDC342L_Project_ResolutionCenter/login.php");
exit;
