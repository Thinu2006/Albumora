<?php
class Customer {
    private $conn; 
    private $table = 'customer'; 
    public $CustomerId;
    public $Name;
    public $Username;
    public $Email;
    public $Password;

    public function __construct($db) {
        $this->conn = $db; 
    }

    //Create a customer
    public function create() {
        $query = "INSERT INTO " . $this->table . " (Name, Username, Email, Password) VALUES (:Name, :Username, :Email, :Password)";
        $stmt = $this->conn->prepare($query);
    
        // Use prepared statements to prevent SQL injection
        $stmt->bindParam(':Name', $this->Name, PDO::PARAM_STR);
        $stmt->bindParam(':Username', $this->Username, PDO::PARAM_STR);
        $stmt->bindParam(':Email', $this->Email, PDO::PARAM_STR);
        $stmt->bindParam(':Password', $this->Password, PDO::PARAM_STR);
    
        return $stmt->execute();
    }

     // Fetch the customer based on both email and username
    public function fetchCustomer() {
       
        $query = "SELECT * FROM " . $this->table . " WHERE Email = :Email AND Username = :Username LIMIT 1";
        $stmt = $this->conn->prepare($query);
    
        $stmt->bindParam(':Email', $this->Email);
        $stmt->bindParam(':Username', $this->Username);
    
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    //Get the all customers 
    public function getAllCustomers(){
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    //Get the count of all the customers 
    public function countCustomers() {
        $query = "SELECT COUNT(*) FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
}
?>
