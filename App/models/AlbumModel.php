<?php
// In AlbumModel.php 
class Album {
    private $conn;
    private $table = 'albums';
    public $aid;
    public $title;
    public $artist;
    public $genre;
    public $released_year;
    public $format_type;
    public $country;
    public $album_cover;
    public $quantity;
    public $price;

    public function __construct($db){
        $this->conn = $db;
    }

    // Create a new album in the database
    public function create(){
        $query = "INSERT INTO " . $this->table . " 
            (title, artist, genre, released_year, format_type, country, album_cover, quantity, price) 
            VALUES (:title, :artist, :genre, :released_year, :format_type, :country, :album_cover, :quantity, :price)";
        
        $stmt = $this->conn->prepare($query);

        // Bind parameters
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':artist', $this->artist);
        $stmt->bindParam(':genre', $this->genre);
        $stmt->bindParam(':released_year', $this->released_year);
        $stmt->bindParam(':format_type', $this->format_type);
        $stmt->bindParam(':country', $this->country);
        $stmt->bindParam(':album_cover', $this->album_cover);
        $stmt->bindParam(':quantity', $this->quantity);
        $stmt->bindParam(':price', $this->price);

        if ($stmt->execute()){
            return true;
        }
        return false;
    }

    // Fetch all albums from the database
    public function getAll(){
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // // Add the getAlbumById method
    // public function getAlbumById($aid) {
    //     $query = "SELECT * FROM " . $this->table . " WHERE aid = :aid LIMIT 1";
    //     $stmt = $this->conn->prepare($query);
    //     $stmt->bindParam(':aid', $aid, PDO::PARAM_INT);
    //     $stmt->execute();
        
    //     // Fetch the album data
    //     $album = $stmt->fetch(PDO::FETCH_ASSOC);
    //     return $album;
    // }

    public function getAlbumById($aid) {
        $query = "SELECT * FROM " . $this->table . " WHERE aid = :aid LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':aid', $aid, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Delete an album from the database
    public function delete(){
        $query = "DELETE FROM " . $this->table . " WHERE aid = :aid";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':aid', $this->aid);
        
        if ($stmt->execute()){
            return true;
        }
        return false;
    }

    //Update Album Details
    public function update() {
        $query = "UPDATE " . $this->table . " 
                  SET title = :title, 
                      artist = :artist, 
                      genre = :genre, 
                      released_year = :released_year, 
                      price = :price, 
                      quantity = :quantity, 
                      format_type = :format_type, 
                      country = :country, 
                      album_cover = :album_cover 
                  WHERE aid = :aid";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':aid', $this->aid);
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':artist', $this->artist);
        $stmt->bindParam(':genre', $this->genre);
        $stmt->bindParam(':released_year', $this->released_year);
        $stmt->bindParam(':price', $this->price);
        $stmt->bindParam(':quantity', $this->quantity);
        $stmt->bindParam(':format_type', $this->format_type);
        $stmt->bindParam(':country', $this->country);
        $stmt->bindParam(':album_cover', $this->album_cover);

        if ($stmt->execute()) {
            return true;
        }

        printf("Error: %s.\n", $stmt->error);
        return false;
    }
    
    //Get Album Count
    public function countAlbums() {
        $query = "SELECT COUNT(*) as total FROM albums";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }
}
?>
