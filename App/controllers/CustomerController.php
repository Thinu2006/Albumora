<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '../../../config/database.php';
require_once __DIR__ . '../../models/CustomerModel.php';
require_once __DIR__ . '../../../config/otpMail.php'; 

class CustomerController {
    private $db; 
    private $customer; 

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
        $this->customer = new Customer($this->db);
    }

    // Sanitize and validate input
    private function sanitizeInput($data, $filter = FILTER_SANITIZE_STRING) {
        return filter_var(trim($data), $filter);
    }

    private function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    // Strong password validation function
    private function validatePassword($password) {
        if (strlen($password) < 8) {
            return "Password must be at least 8 characters long.";
        }
        if (!preg_match("/[A-Z]/", $password)) {
            return "Password must contain at least one uppercase letter.";
        }
        if (!preg_match("/[0-9]/", $password)) {
            return "Password must contain at least one number.";
        }
        if (!preg_match("/[\W_]/", $password)) {
            return "Password must contain at least one special character.";
        }
        return true;  // Valid password
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Sanitize inputs
            $name = $this->sanitizeInput($_POST['name']);
            $username = $this->sanitizeInput($_POST['username']);
            $email = $this->sanitizeInput($_POST['email'], FILTER_SANITIZE_EMAIL);
            $password = $this->sanitizeInput($_POST['password']);

            // Validate email
            if (!$this->validateEmail($email)) {
                echo "<script>alert('Invalid email format.');</script>";
                return;
            }

            // Validate password strength
            $passwordValidation = $this->validatePassword($password);
            if ($passwordValidation !== true) {
                echo "<script>alert('$passwordValidation');</script>";
                return;
            }

            // Assign sanitized values
            $this->customer->Name = $name;
            $this->customer->Username = $username;
            $this->customer->Email = $email;
            $this->customer->Password = password_hash($password, PASSWORD_BCRYPT);

            if ($this->customer->create()) {
                // Generate OTP and send to email
                $otp = $this->generateOTP();
                $_SESSION['OTP'] = $otp;
                $_SESSION['OTP_EMAIL'] = $email; // Store email for later comparison
                sendOTPEmail($email, $otp);  // Function to send OTP via email
                
                // Redirect to OTP verification page
                header("Location: http://localhost/Albumora/App/views/users/otpVerify.php");
                exit;
            } else {
                echo "<script>alert('Registration failed. Please try again.');</script>";
            }
        }
    }

    // Generate a random 6-digit OTP
    private function generateOTP() {
        return mt_rand(100000, 999999);
    }

    public function verifyOTP() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $otpEntered = $_POST['otp'];
    
            if ($otpEntered == $_SESSION['OTP']) {
                // OTP is correct, redirect to landing page
                unset($_SESSION['OTP']);  // Clear OTP after verification
                header("Location: http://localhost/Albumora/App/views/users/landingPage.php");
                exit;
            } else {
                echo "<script>alert('Invalid OTP. Please try again.');</script>";
            }
        }
    }

    public function authenticate() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Sanitize inputs
            $username = $this->sanitizeInput($_POST['username']);
            $email = $this->sanitizeInput($_POST['email'], FILTER_SANITIZE_EMAIL);
            $password = $this->sanitizeInput($_POST['password']);
    
            // Validate email
            if (!$this->validateEmail($email)) {
                echo "<script>alert('Invalid email format.');</script>";
                return;
            }
    
            $this->customer->Email = $email;
            $this->customer->Username = $username;
    
            $user = $this->customer->fetchCustomer();
    
            if ($user && 
                (($user['Email'] === $this->customer->Email && $user['Username'] === $this->customer->Username) || 
                ($user['Username'] === $this->customer->Username && password_verify($password, $user['Password'])))) {
    
                session_regenerate_id(true);
                $_SESSION['CustomerId'] = $user['CustomerId'];
                $_SESSION['customer_name'] = $user['Name'];
    
                // Generate OTP and send to email
                $otp = $this->generateOTP();
                $_SESSION['OTP'] = $otp;
                $_SESSION['OTP_EMAIL'] = $email; // Store email for later comparison
                sendOTPEmail($email, $otp);  // Function to send OTP via email
                
                // Redirect to OTP verification page
                header("Location: http://localhost/Albumora/App/views/users/otpVerify.php");
                exit;
            } else {
                echo "<script>alert('Invalid credentials. Please try again.');</script>";
            }
        }
    }

    public function customerlogout() {
        session_start();
        session_unset();
        session_destroy();
        header('Location: http://localhost/Albumora/App/views/users/signIn.php');
        exit;
    }

    public function index() {
        return $this->customer->getAllCustomers();
    }

    public function getCustomerCount() {
        return $this->customer->countCustomers();
    }
}

if (isset($_GET['action']) && $_GET['action'] === 'customerlogout') {
    $controller = new CustomerController();
    $controller->customerlogout();
}
?>
