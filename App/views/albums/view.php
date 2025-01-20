<?php
// Include the required controller
require_once '../../../App/controllers/AlbumController.php';

// Create an instance of the AlbumController
$controller = new AlbumController();

// Fetch album details
if (isset($_GET['aid']) && is_numeric($_GET['aid'])) {
    $aid = (int)$_GET['aid'];

    // Fetch a single album by its ID
    $album = $controller->getAlbumById($aid); // Updated method call
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
    <title>View Album</title>
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

    <div class="flex">
        <!-- Main Content -->
        <div class="flex-1 mt-4 p-28">
            <div class="mb-10">
                <h2 class="text-7xl font-bold mb-6 text-gray-800 font-rowdies">Album Information</h2>
                <p class="text-2lx text-gray-500">Detailed information about the album.</p>
            </div>
            
            <!-- Display album details -->
            <div class="bg-white p-8 rounded-lg shadow-lg flex flex-wrap">
                
                <!-- Album Info -->
                <div class="w-full pl-8 mt-6 md:mt-0 grid grid-cols-3 gap-10">
                    <!-- Left Column -->
                    <div>
                        <p class="text-xl text-gray-600 mb-6"><strong>ID:</strong> <?php echo $album['aid'] ?? 'N/A'; ?></p>
                        <p class="text-xl text-gray-600 mb-6"><strong>Title:</strong> <?php echo $album['title'] ?? 'N/A'; ?></p>
                        <p class="text-xl text-gray-600 mb-6"><strong>Artist:</strong> <?php echo $album['artist'] ?? 'N/A'; ?></p>
                        <p class="text-xl text-gray-600 mb-6"><strong>Genre:</strong> <?php echo $album['genre'] ?? 'N/A'; ?></p>
                        <p class="text-xl text-gray-600 mb-6"><strong>Release Year:</strong> <?php echo $album['released_year'] ?? 'N/A'; ?></p>
                        <p class="text-xl text-gray-600 mb-6"><strong>Format:</strong> <?php echo $album['format_type'] ?? 'N/A'; ?></p>
                    </div>

                    <!-- Right Column -->
                    <div>
                        <p class="text-xl text-gray-600 mb-6"><strong>Country:</strong> <?php echo $album['country'] ?? 'N/A'; ?></p>
                        <p class="text-xl text-gray-600 mb-6"><strong>Price:</strong> $<?php echo isset($album['price']) ? number_format($album['price'], 2) : '0.00'; ?></p>
                        <p class="text-xl text-gray-600 mb-6"><strong>Quantity:</strong> <?php echo $album['quantity'] ?? 'N/A'; ?></p>
                    </div>
                    <!-- image Column -->
                    <div>
                        <?php if (!empty($album['album_cover'])): ?>
                            <img 
                                src="../../../uploads/<?php echo htmlspecialchars($album['album_cover']); ?>" 
                                alt="Album Cover" 
                                class="h-30 w-30 rounded shadow-md"
                            />
                        <?php else: ?>
                            <p class="text-gray-600">No album cover available.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Back Button -->
            <div class="mt-6">
                <a href="list.php" class="bg-blue-600 text-white px-5 py-2 rounded hover:bg-blue-700 transition">
                    Back to Album List
                </a>
            </div>
        </div>
    </div>
</body>
</html>
