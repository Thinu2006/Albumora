<?php
// Include the required controller
require_once '../../../App/controllers/AlbumController.php';

// Check if the album ID is provided in the URL
if (isset($_GET['aid'])) {
    // Instantiate the AlbumController
    $controller = new AlbumController();
    // Call the delete method in AlbumController to delete the album
    $controller->delete($_GET['aid']);
} else {
    // Display a message if no album ID is provided
    echo "No album ID provided";
}
?>
