<?php
// Include the required controller
require_once '../../../App/controllers/AlbumController.php';

// Create an instance of the controller
$controller = new AlbumController();

// Call the create method to handle form submission
$controller->create();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Album</title>
    <!-- font style -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rowdies:wght@300;400;700&display=swap" rel="stylesheet">
    <!-- tailwind stylesheet -->
    <link href="/Albumora/public/css/tailwind.css" rel="stylesheet">
</head>
<body>
    <?php include '../layout/adminNav.php'; ?>

    <div class="container mx-auto mt-8 p-20">
        <h2 class="text-4xl sm:text-5xl font-rowdies font-bold text-gray-800 mb-4 text-center">Add Album</h2>

        <!-- Display success message if the operation was successful -->
        <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
            <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
                Album added successfully!
            </div>
        <?php endif; ?>

        <!-- Album creation form -->
        <form action="http://localhost/Albumora/App/views/albums/add.php" method="POST" enctype="multipart/form-data" class="space-y-4">
            <!-- Title -->
            <label class="block mb-2 font-semibold">Title:</label>
            <input type="text" name="title" class="w-full border rounded p-2 mb-4" required>

            <!-- Artist -->
            <label class="block mb-2 font-semibold">Artist:</label>
            <input type="text" name="artist" class="w-full border rounded p-2 mb-4" required>

            <!-- Genre -->
            <label class="block mb-2 font-semibold">Genre:</label>
            <input type="text" name="genre" class="w-full border rounded p-2 mb-4" required>

            <!-- Year -->
            <label class="block mb-2 font-semibold">Year:</label>
            <input type="text" name="released_year" class="w-full border rounded p-2 mb-4" required>

            <!-- Price -->
            <label class="block mb-2 font-semibold">Price ($):</label>
            <input type="number" name="price" class="w-full border rounded p-2 mb-4" step="0.01" required>

            <!-- Quantity -->
            <label class="block mb-2 font-semibold">Quantity:</label>
            <input type="number" name="quantity" class="w-full border rounded p-2 mb-4" min="1" required>

            <!-- Format -->
            <label class="block mb-2 font-semibold">Format (CD/Vinyl/Digital):</label>
            <select name="format_type" class="w-full border rounded p-2 mb-4" required>
                <option value="" disabled selected>Select Format</option>
                <option value="CD">CD</option>
                <option value="Vinyl">Vinyl</option>
                <option value="Digital">Digital</option>
            </select>

            <!-- Country -->
            <label class="block mb-2 font-semibold">Country:</label>
            <input type="text" name="country" class="w-full border rounded p-2 mb-4" required>

            <!-- Album Cover -->
            <label class="block mb-2 font-semibold">Album Cover (Image):</label>
            <input type="file" name="album_cover" class="w-full border rounded p-2 mb-4" required>

            <div class="flex items-center justify-between mt-16">
                <!-- Back button -->
                <a href="list.php" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                    Back to Album List
                </a>

                <!-- Submit button to update the album -->
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition mr-20">Add Album</button>
            </div>
        </form>
    </div>
</body>
</html>

