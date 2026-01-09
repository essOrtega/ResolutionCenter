<?php
require_once __DIR__ . '/../model/User.php';
require_once __DIR__ . '/../validation.php';

class UserController {

    // REGISTER NEW USER
    public function register() {

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return []; // no errors on first load
        }

        $errors = [];

        // Collect form data
        $first = trim($_POST['first_name']);
        $last  = trim($_POST['last_name']);
        $email = trim($_POST['email']);
        $password = $_POST['password'];
        $confirm  = $_POST['confirm_password'];
        $phone = trim($_POST['phone']);
        $street = trim($_POST['street']);
        $city = trim($_POST['city']);
        $state = trim($_POST['state']);
        $zip = trim($_POST['zip']);

        // Validation
        if (!validateRequired($first)) {
            $errors['first_name'] = "First name is required.";
        }

        if (!validateRequired($last)) {
            $errors['last_name'] = "Last name is required.";
        }

        if (!validateEmail($email)) {
            $errors['email'] = "A valid email is required.";
        }

        if (!validatePasswordComplexity($password)) {
            $errors['password'] = "Password must be 8+ chars, with upper, lower, and a number.";
        }

        if ($password !== $confirm) {
            $errors['confirm_password'] = "Passwords do not match.";
        }

        // If validation failed, return errors
        if (!empty($errors)) {
            return $errors;
        }

        // Create user model
        $user = new User();

        // Check if email exists
        if ($user->emailExists($email)) {
            $errors['email'] = "This email is already registered.";
            return $errors;
        }

        // Insert user
        $user->createUser($first, $last, $email, $password, $phone, $street, $city, $state, $zip);

        // Redirect after success
        header("Location: login.php");
        exit;
    }

    // LOGIN USER
    public function login() {

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return []; // no errors on first load
        }

        $errors = [];

        $email = trim($_POST['email']);
        $password = $_POST['password'];

        if (!validateEmail($email)) {
            $errors['email'] = "Please enter a valid email.";
        }

        if (!validateRequired($password)) {
            $errors['password'] = "Password is required.";
        }

        if (!empty($errors)) {
            return $errors;
        }

        $user = new User();
        $found = $user->findByEmail($email);

        if (!$found) {
            $errors['general'] = "No account found with that email.";
            return $errors;
        }

        if (!password_verify($password, $found['password'])) {
            $errors['general'] = "Incorrect password.";
            return $errors;
        }

        // Login success
        session_start();
        $_SESSION['user_id'] = $found['id'];
        $_SESSION['role'] = $found['role'];

        // Redirect based on role
        if ($found['role'] === 'customer') {
            header("Location: customer/customer_dashboard.php");
        } elseif ($found['role'] === 'technician') {
            header("Location: technician/technician_dashboard.php");
        } else {
            header("Location: admin/admin_dashboard.php");
        }
        exit;
    }

    // ADMIN — GET ALL USERS
    public function getAllUsers() {
        $user = new User();
        return $user->getAllUsers();
    }
}

