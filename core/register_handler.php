<?php
require_once 'db_connect.php';
require_once 'password_policy.php';

$name     = trim($_POST['name'] ?? '');
$email    = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$role     = 'customer'; // default role

// Validate password strength
$errors = validate_password_policy($password);
if (!empty($errors)) {
    // Store errors in session or show them on the form
    session_start();
    $_SESSION['reg_errors'] = $errors;
    header("Location: register.php");
    exit;
}

// Hash password
$hashed = password_hash($password, PASSWORD_DEFAULT);

// Insert user
$stmt = $conn->prepare("INSERT INTO users (name, email, password_hash, role) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $name, $email, $hashed, $role);

if ($stmt->execute()) {
    header("Location: login.php?registered=1");
    exit;
} else {
    error_log("Registration error: " . $stmt->error);
    header("Location: register.php?error=1");
    exit;
}
