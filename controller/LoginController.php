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

        if (!password_verify($password, $user['password_hash'])) {
            $errors['general'] = "Incorrect password.";
            return $errors;
        }

        // Login success
        if (session_status() === PHP_SESSION_NONE) { 
            session_start(); 
        } 
        
        session_regenerate_id(true);  
        $_SESSION['user_id'] = $user['user_id']; 
        $_SESSION['role'] = $user['role'];

        // Redirect based on role (absolute paths) 
        if ($user['role'] === 'customer') { 
            header("Location: /Ortega_SDC342L_Project_ResolutionCenter/customer/customerDashboard.php"); 
            exit; 
        } 
        
        if ($user['role'] === 'technician') { 
            header("Location: /Ortega_SDC342L_Project_ResolutionCenter/technician/tech_dashboard.php"); 
            exit; 
        }
        
        // Default: admin
        header("Location: /Ortega_SDC342L_Project_ResolutionCenter/admin/admin_dashboard.php"); 
        exit; 
    }
}
