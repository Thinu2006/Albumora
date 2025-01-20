<?php
class Order {
    private $conn; 
    private $table = 'orders'; 
    public $OrderId;
    public $CustomerId;
    public $AlbumId;
    public $OrderDate;
    public $Status;

    public function __construct($db) {
        $this->conn = $db; 
    }

    // Get all orders
    public function getAllOrders() {
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); 
    }

    // Get the count of all orders
    public function countOrders() {
        $query = "SELECT COUNT(*) as count FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] ?? 0;
    }
}
?>
