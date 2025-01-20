<?php
session_start();
require_once __DIR__ . '../../../config/database.php';
require_once __DIR__ . '../../models/AdminModel.php';

class AdminController {
    private $db;
    private $admin;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
        $this->admin = new Admin($this->db);
    }

    public function authenticate() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->admin->Username = $_POST['username'];
            $this->admin->Password = $_POST['password'];

            $user = $this->admin->fetchAdmin();

            if ($user && $user['Password'] === $this->admin->Password) {
                $_SESSION['admin_id'] = $user['id'];
                header("Location: http://localhost/Albumora/App/views/admin/dashboard.php");
                exit;
            } else {
                echo "<script>alert('Invalid username or password. Please try again.');</script>";
            }
        }
    }

    public function adminlogout() {
        session_start();
        session_unset();
        session_destroy();
        header("Location: http://localhost/Albumora/App/views/admin/admin_signUp.php");
        exit;
    }
}

if (isset($_GET['action']) && $_GET['action'] === 'adminlogout') {
    $controller = new AdminController();
    $controller->adminlogout();
}
?>
