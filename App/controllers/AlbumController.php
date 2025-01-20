<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '../../../config/database.php';
require_once __DIR__ . '../../models/AlbumModel.php';

class AlbumController {
    private $db; // Declare the $db property
    private $album; // Declare the $album property

    public function __construct(){
        $database = new Database();
        $this->db = $database->connect(); // Initialize the database connection
        $this->album = new Album($this->db); // Pass the connection to the album model
    }

    // Create a new album
    public function create(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $album_cover_path = '';
    
            // Check if files are uploaded
            if (isset($_FILES['album_cover']) && $_FILES['album_cover']['error'] == 0) {
                // Save the uploaded file to the server
                $targetDir = '../../../uploads/';
                $fileName = uniqid() . '_' . basename($_FILES['album_cover']['name']);
                $targetFile = $targetDir . $fileName;
    
                if (move_uploaded_file($_FILES['album_cover']['tmp_name'], $targetFile)) {
                    $album_cover_path = $fileName; // Save the file name for the database
                } else {
                    echo "Failed to upload album cover.";
                    return;
                }
            }
    
            $this->album->title = htmlspecialchars($_POST['title']);
            $this->album->artist = htmlspecialchars($_POST['artist']);
            $this->album->genre = htmlspecialchars($_POST['genre']);
            $this->album->released_year = $_POST['released_year'];
            $this->album->format_type = htmlspecialchars($_POST['format_type']);
            $this->album->country = htmlspecialchars($_POST['country']);
            $this->album->album_cover = $album_cover_path;
            $this->album->quantity = $_POST['quantity'];
            $this->album->price = $_POST['price'];
    
            if ($this->album->create()) {
                header('Location: http://localhost/Albumora/App/views/albums/list.php?status=success');
                exit;
            } else {
                echo "Failed to create album.";
            }
        }
    }

    // Get all albums
    public function index(){
        return $this->album->getAll();
    }

    // Delete an album
    public function delete($aid){
        $this->album->aid = $aid;
        if ($this->album->delete()){
            header('Location: http://localhost/Albumora/App/views/albums/list.php?status=success');
            exit;
        } else {
            echo "Failed to delete album.";
        }
    }
    
    public function getAlbumById($aid) {
        $query = "SELECT * FROM albums WHERE aid = :aid";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':aid', $aid, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getAlbumCount() {
        return $this->album->countAlbums();
    }

    public function update() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $this->album->aid = $_POST['aid'];
        $this->album->title = $_POST['title'];
        $this->album->artist = $_POST['artist'];
        $this->album->genre = $_POST['genre'];
        $this->album->released_year = $_POST['released_year'];
        $this->album->price = $_POST['price'];
        $this->album->quantity = $_POST['quantity'];
        $this->album->format_type = $_POST['format_type'];
        $this->album->country = $_POST['country'];

        if (isset($_FILES['album_cover']) && $_FILES['album_cover']['error'] == 0) {
            $targetDir = '../../../uploads/';
            $fileName = uniqid() . '_' . basename($_FILES['album_cover']['name']);
            $targetFile = $targetDir . $fileName;
    
            if (move_uploaded_file($_FILES['album_cover']['tmp_name'], $targetFile)) {
                $this->album->album_cover = $fileName; 
            } else {
                echo "Failed to upload album cover.";
                return;
            }
        } else {
            $album = $this->album->getAlbumById($this->album->aid);
            $this->album->album_cover = $album['album_cover']; 
        }

        if ($this->album->update()) {
            header('Location:http://localhost/Albumora/App/views/albums/list.php?status=success');
            exit;
        } else {
            echo "Failed to update album.";
        }
    }
}
}
?>