<?php
// Include the required controller
require_once '../../../App/controllers/AlbumController.php';

// Create an instance of the AlbumController
$controller = new AlbumController();

// Handle form submission for updating an album
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->update();
}

// Fetch album details for editing
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $albums = $controller->index();

    // Find the album by ID
    $album = null;
    foreach ($albums as $a) {
        if ($a['aid'] == $id) {
            $album = $a;
            break;
        }
    }

    if (!$album) {
        die('Album not found.');
    }
} else {
    die('Invalid album ID.');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Album</title>
    <!-- font style -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rowdies:wght@300;400;700&display=swap" rel="stylesheet">
    <!-- tailwind stylesheet -->
    <link href="/Albumora/public/css/tailwind.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <?php include '../layout/adminNav.php'; ?>

    <!-- Body Container -->
    <div class="flex-1 mt-32 p-6 bg-white shadow-lg space-x-10 mx-32 my-32 rounded-3xl ">

        <div class="container p-6 space-x-24">
            <h2 class="text-3xl font-bold mb-10 text-gray-800 text-center font-rowdies">Edit Album Details</h2>

            <!-- Album Edit Form -->
            <form action="http://localhost/Albumora/App/views/albums/edit.php" method="POST" enctype="multipart/form-data" class="space-y-8">
                <!-- Hidden field to store the album ID -->
                <input type="hidden" name="aid" value="<?php echo $album['aid']; ?>" />

                <div class="flex items-center mb-4">
                    <label for="title" class="w-40 text-l font-bold mr-10">Title</label>
                    <input type="text" name="title" id="title" class="w-full border rounded p-2 mr-20" 
                        value="<?php echo htmlspecialchars($album['title']); ?>" required>
                </div>

                <div class="flex items-center mb-4">
                    <label for="artist" class="w-40 text-l font-bold mr-10">Artist</label>
                    <input type="text" name="artist" id="artist" class="w-full border rounded p-2 mr-20" 
                        value="<?php echo htmlspecialchars($album['artist']); ?>" required>
                </div>

                <div class="flex items-center mb-4">
                    <label for="genre" class="w-40 text-l font-bold mr-10">Genre</label>
                    <input type="text" name="genre" id="genre" class="w-full border rounded p-2 mr-20" 
                        value="<?php echo htmlspecialchars($album['genre']); ?>" required>
                </div>

                <div class="flex items-center mb-4">
                    <label for="released_year" class="w-40 text-l font-bold mr-10">Released Year</label>
                    <input type="text" name="released_year" id="released_year" class="w-full border rounded p-2 mr-20" 
                        value="<?php echo htmlspecialchars($album['released_year']); ?>" required>
                </div>

                <div class="flex items-center mb-4">
                    <label for="format_type" class="w-40 text-l font-bold mr-10">Format Type</label>
                    <input type="text" name="format_type" id="format_type" class="w-full border rounded p-2 mr-20" 
                        value="<?php echo htmlspecialchars($album['format_type']); ?>" required>
                </div>

                <div class="flex items-center mb-4">
                    <label for="country" class="w-40 text-l font-bold mr-10">Country</label>
                    <input type="text" name="country" id="country" class="w-full border rounded p-2 mr-20" 
                        value="<?php echo htmlspecialchars($album['country']); ?>" required>
                </div>

                <div class="flex items-center mb-4">
                    <label for="quantity" class="w-40 text-l font-bold mr-10">Quantity</label>
                    <input type="number" name="quantity" id="quantity" class="w-full border rounded p-2 mr-20" 
                        value="<?php echo htmlspecialchars($album['quantity']); ?>" required>
                </div>

                <div class="flex items-center mb-4">
                    <label for="price" class="w-40 text-l font-bold mr-10">Price</label>
                    <input type="text" name="price" id="price" class="w-full border rounded p-2 mr-20" 
                        value="<?php echo htmlspecialchars($album['price']); ?>" required>
                </div>

                <!-- Display existing album cover -->
                <div class="flex items-center mb-4 ">
                    <label for="album_cover" class="w-40 text-l font-bold mr-10">Album Cover</label>
                    <?php if (!empty($album['album_cover'])): ?>
                        <img src="../../../uploads/<?php echo htmlspecialchars($album['album_cover']); ?>" alt="Album Cover" class="mb-4 w-32 h-32 object-cover ml-4 mr-2">
                    <?php else: ?>
                        <p>No cover image available.</p>
                    <?php endif; ?>
                    <input type="file" name="album_cover" id="album_cover" class="w-full border rounded p-2 mr-20">
                </div>

                <div class="flex items-center justify-between mt-8">
                    <!-- Back button -->
                    <a href="list.php" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                        Back to Album List
                    </a>

                    <!-- Submit button to update the album -->
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded ml-4 hover:bg-blue-700 transition mr-20">
                        Update Album
                    </button>
                </div>
            </form>

        </div>
    </div>
</body>
</html>
