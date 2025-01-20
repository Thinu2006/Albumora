<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '../../../config/database.php';
require_once __DIR__ . '../../models/OrderModel.php';

class OrderController {
    private $db; 
    private $order; 

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
        $this->order = new Order($this->db);
    }

    public function index() {
        return $this->order->getAllOrders();
    }

    public function getOrderCount() {
        return $this->order->countOrders();
    }
}
?>
