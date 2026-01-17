<?php
require_once 'config.php';

// Turn off error display 
mysqli_report(MYSQLI_REPORT_OFF);

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// If connection fails, log the real error but show a generic message
if (!$conn) {
    error_log("Database connection failed: " . mysqli_connect_error());
    die("Database connection error. Please try again later.");
}

// Always use utf8mb4 for security + full Unicode support
mysqli_set_charset($conn, "utf8mb4");
?>
