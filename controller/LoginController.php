<?php
require_once __DIR__ . '/../model/User.php';
require_once __DIR__ . '/../validation.php';

class LoginController {

    public function login() {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            return []; // no errors on first load
        }

        $errors = [];

        $email = trim($_POST['email']);
        $password = $_POST['password'];

        // Validation
        if (!validateEmail($email)) {
            $errors['email'] = "Please enter a valid email.";
        }

        if (!validateRequired($password)) {
            $errors['password'] = "Password is required.";
        }

        if (!empty($errors)) {
            return $errors;
        }

        // Check user
        $userModel = new User();
        $user = $userModel->findByEmail($email);

        if (!$user) {
            $errors['general'] = "No account found with that email.";
            return $errors;
        }

        if (!password_verify($password, $user['password'])) {
            $errors['general'] = "Incorrect password.";
            return $errors;
        }

        // Login success
        session_start();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];

        // Redirect based on role
        if ($user['role'] === 'customer') {
            header("Location: customer/dashboard.php");
        } elseif ($user['role'] === 'technician') {
            header("Location: technician/dashboard.php");
        } else {
            header("Location: admin/dashboard.php");
        }
        exit;
    }
}
