<?php
require_once __DIR__ . '/../model/User.php';
require_once __DIR__ . '/../validation.php';
require_once __DIR__ . '/../core/logger.php';

class UserController {

    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    // REGISTER NEW USER
    public function register() {

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return []; // no errors on first load
        }

        $errors = [];

        // Collect form data
        $first = trim($_POST['first_name'] ?? ''); 
        $last = trim($_POST['last_name'] ?? '');
        $email = trim($_POST['email'] ?? ''); 
        $password = $_POST['password'] ?? ''; 
        $confirm = $_POST['confirm_password'] ?? ''; 
        $phone = trim($_POST['phone'] ?? ''); 
        $street = trim($_POST['street'] ?? ''); 
        $city = trim($_POST['city'] ?? ''); 
        $state = trim($_POST['state'] ?? ''); 
        $zip = trim($_POST['zip'] ?? '');

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

        if (!validateRequired($phone)) { 
            $errors['phone'] = "Phone number is required."; 
        } 
        
        if (!validateRequired($street)) { 
            $errors['street'] = "Street address is required."; 
        } 
        
        if (!validateRequired($city)) { 
            $errors['city'] = "City is required."; 
        } 
        
        if (!validateRequired($state)) { 
            $errors['state'] = "State is required."; 
        } 
        
        if (!validateRequired($zip)) { 
            $errors['zip'] = "Zip code is required."; 
        }

        // Password Policy Validation
        if (strlen($password) < 8) {
            $errors['password'] = "Password must be at least 8 characters long.";
        }

        if (!preg_match('/[A-Z]/', $password)) {
            $errors['password'] = "Password must contain at least one uppercase letter.";
        }

        if (!preg_match('/[a-z]/', $password)) {
            $errors['password'] = "Password must contain at least one lowercase letter.";
        }

        if (!preg_match('/[0-9]/', $password)) {
            $errors['password'] = "Password must contain at least one number.";
        }

        if (!preg_match('/[\W_]/', $password)) {
            $errors['password'] = "Password must contain at least one special character.";
        }

        if (!empty($passwordErrors)) { 
            $errors['password'] = $passwordErrors; 
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
            log_event("Failed login: email not found ($email)");
            $errors['general'] = "No account found with that email.";
            return $errors;
        }

        if (!password_verify($password, $found['password'])) {
            log_event("Failed login: wrong password for email ($email)");
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

    public function addEmployee($data) {
        $errors = [];

        // Basic validation
        if (empty($data['user_id'])) $errors[] = "User ID is required.";
        if (empty($data['first_name'])) $errors[] = "First name is required.";
        if (empty($data['last_name'])) $errors[] = "Last name is required.";
        if (empty($data['email'])) $errors[] = "Email is required.";
        if (empty($data['phone'])) $errors[] = "Phone is required.";
        if (empty($data['role'])) $errors[] = "Role is required.";
        if (empty($data['password'])) $errors[] = "Password is required.";

        if (!empty($errors)) return $errors;

        // Hash password
        $passwordHash = password_hash($data['password'], PASSWORD_DEFAULT);

        // Prepare data for model
        $employeeData = [
            'user_id'     => $data['user_id'],
            'first_name'  => $data['first_name'],
            'last_name'   => $data['last_name'],
            'email'       => $data['email'],
            'phone_ext'   => $data['phone_ext'],
            'role'        => $data['role'],
            'password_hash' => $passwordHash
        ];

        $user = new User();
        $user->createEmployee($employeeData);

        return [];
    }

    public function getUserById($id) {
        $user = new User();
        return $user->getUserById($id);
    }

    public function updateEmployee($id, $data) {
        $errors = [];

        if (empty($data['first_name'])) $errors[] = "First name is required.";
        if (empty($data['last_name'])) $errors[] = "Last name is required.";
        if (empty($data['email'])) $errors[] = "Email is required.";
        if (empty($data['phone_ext'])) $errors[] = "Phone extension is required.";
        if (empty($data['role'])) $errors[] = "Role is required.";

        if (!empty($errors)) return $errors;

        $updateData = [
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
            'email'      => $data['email'],
            'phone_ext'  => $data['phone_ext'],
            'role'       => $data['role']
        ];

        $user = new User();
        $user->updateEmployee($id, $updateData);

        return [];
    }

    public function updateCustomer($id, $data) {
        $errors = [];

        if (empty($data['first_name'])) $errors[] = "First name is required.";
        if (empty($data['last_name'])) $errors[] = "Last name is required.";
        if (empty($data['email'])) $errors[] = "Email is required.";
        if (empty($data['phone'])) $errors[] = "Phone is required.";
        if (empty($data['street'])) $errors[] = "Street is required.";
        if (empty($data['city'])) $errors[] = "City is required.";
        if (empty($data['state'])) $errors[] = "State is required.";
        if (empty($data['zip'])) $errors[] = "Zip code is required.";

        if (!empty($errors)) return $errors;

        $updateData = [
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
            'email'      => $data['email'],
            'phone'      => $data['phone'],
            'street'     => $data['street'],
            'city'       => $data['city'],
            'state'      => $data['state'],
            'zip'        => $data['zip']
        ];

        $user = new User();
        $user->updateCustomer($id, $updateData);

        return [];
    }

    // ADMIN — GET ALL USERS
    public function getAllUsers() {
        $user = new User();
        return $user->getAllUsers();
    }

    public function getCustomers() {
        $user = new User();
        return $user->getCustomers();
    }

    public function getStaff() {
        $user = new User();
        return $user->getStaff();
    }

    public function changePassword($userId, $currentPassword, $newPassword) {
        $user = $this->userModel->findById($userId);

        if (!$user) {
            return "User not found.";
        }

        if (!password_verify($currentPassword, $user['password_hash'])) {
            return "Current password is incorrect.";
        }

        $newHash = password_hash($newPassword, PASSWORD_DEFAULT);
        $this->userModel->updatePassword($userId, $newHash);

        return true;
    }

    public function getTechnicians() {
        return $this->userModel->getTechnicians();
    }

}

