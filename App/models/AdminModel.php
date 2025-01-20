<?php
class Admin {
    private $conn;
    private $table = 'admin';
    public $id;
    public $Username;
    public $Password;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Method to fetch admin by username
    public function fetchAdmin() {
        $query = "SELECT * FROM " . $this->table . " WHERE Username = :Username LIMIT 1";
        $stmt = $this->conn->prepare($query);

        // Bind parameter
        $stmt->bindParam(':Username', $this->Username);

        $stmt->execute();

        // Return the admin data if found
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
}
?>
