<?php
// Start the session
session_start();

// Check if the user is logged in as an admin
if (!isset($_SESSION['admin_id'])) {
    // If not, redirect to the admin sign-up page
    header("Location: admin_signUp.php");
    exit;
}

// Include the required controller
require_once '../../../App/controllers/AlbumController.php';
// Create an instance of the controller
$controller = new AlbumController();

// Fetch all albums
$albums = $controller->index();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Album List</title>
    <!-- font style -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rowdies:wght@300;400;700&display=swap" rel="stylesheet">
    <!-- tailwind stylesheet -->
    <link href="/Albumora/public/css/tailwind.css" rel="stylesheet">
</head>
<body class="bg-gray-100 font-sans">

    <!-- Main Container -->
    <div class="flex flex-col h-screen ">
        <!-- Navbar -->
        <?php include '../layout/adminNav.php'; ?>

        <!-- Body Container -->
        <div class="flex flex-1 flex-col items-center bg-customBackgroundcolor mt-20 px-12">
            <header class="text-center my-10">
                <h1 class="text-4xl sm:text-5xl font-rowdies font-bold text-gray-800">Albums List</h1>
            </header>
            <!-- Navigation Tabs -->
            <nav class="flex flex-wrap justify-center gap-4 sm:gap-8 mb-10 bg-gray-600 px-6 py-4 rounded-lg">
                <a href="../admin/dashboard.php" class="text-black px-6 py-2 rounded-full bg-gray-200 flex items-center">
                    <span>🏠</span>
                    <span class="ml-2">Dashboard</span>
                </a>
                <a href="../admin/customerlist.php" class="text-black px-6 py-2 rounded-full bg-gray-200 flex items-center">
                    <span>👥</span>
                    <span class="ml-2">Customers</span>
                </a>
                <a href="../albums/list.php" class="text-black px-6 py-2 rounded-full bg-gray-400 flex items-center">
                    <span>🎵</span>
                    <span class="ml-2">Albums</span>
                </a>
                <a href="../admin/orderlist.php" class="text-black px-6 py-2 rounded-full bg-gray-200 flex items-center">
                    <span>📦</span>
                    <span class="ml-2">Orders</span>
                </a>
            </nav>

            <!-- Add Album Button -->
            <div class="w-full flex justify-end mb-10">
                <a href="http://localhost/Albumora/App/views/albums/add.php" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                    + Add New Album
                </a>
            </div>

            <!-- Table to Display Albums -->
            <div class="overflow-x-auto">
                <table class="table-auto w-full border-collapse border border-gray-300 text-center bg-customLightGray">
                    <thead>
                            <tr class="bg-gray-200">
                            <th class="px-4 py-2 border">AID</th>
                            <th class="px-4 py-2 border">Title</th>
                            <th class="px-4 py-2 border">Artist</th>
                            <th class="px-4 py-2 border">Genre</th>
                            <th class="px-4 py-2 border">Released Year</th>
                            <th class="px-4 py-2 border">Format Type</th>
                            <th class="px-4 py-2 border">Country</th>
                            <th class="px-4 py-2 border">Album Cover</th>
                            <th class="px-4 py-2 border">Quantity</th>
                            <th class="px-4 py-2 border">Price</th>
                            <th class="border border-gray-300 px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-customLightGray">
                        <?php if (!empty($albums)): ?>
                            <?php foreach ($albums as $album): ?>
                                <tr>
                                    <td class="border border-gray-400 px-4 py-2"><?php echo $album['aid']; ?></td>
                                    <td class="border border-gray-400 px-4 py-2"><?php echo $album['title']; ?></td>
                                    <td class="border border-gray-400 px-4 py-2"><?php echo $album['artist']; ?></td>
                                    <td class="border border-gray-400 px-4 py-2"><?php echo $album['genre']; ?></td>
                                    <td class="border border-gray-400 px-4 py-2"><?php echo $album['released_year']; ?></td>
                                    <td class="border border-gray-400 px-4 py-2"><?php echo $album['format_type']; ?></td>
                                    <td class="border border-gray-400 px-4 py-2"><?php echo $album['country']; ?></td>
                                    <td class="border border-gray-400 px-4 py-2">
                                        <?php if (!empty($album['album_cover'])): ?>
                                            <img src="/Albumora/uploads/<?php echo htmlspecialchars($album['album_cover']); ?>" alt="Album Cover" class="h-20 w-20 object-cover mx-auto">
                                        <?php else: ?>
                                            No Cover
                                        <?php endif; ?>
                                    </td>
                                    <td class="border border-gray-400 px-4 py-2"><?php echo $album['quantity']; ?></td>
                                    <td class="border border-gray-400 px-4 py-2"><?php echo $album['price']; ?></td>
                                    <td class="border border-gray-400 px-4 py-2 text-center">
                <!-- Container for buttons -->
                                        <div class="flex flex-col space-y-2">
                                            <!-- Edit Button -->
                                            <a href="edit.php?id=<?php echo $album['aid']; ?>" 
                                            class="inline-block text-white bg-gray-400 hover:bg-blue-700 rounded px-4 py-2">
                                                Update
                                            </a>

                                            <!-- View Button -->
                                            <a href="view.php?aid=<?php echo $album['aid']; ?>" 
                                            class="inline-block text-white bg-gray-400 hover:bg-green-700 rounded px-4 py-2">
                                                View
                                            </a>

                                            <!-- Delete Button -->
                                            <a href="delete.php?aid=<?php echo $album['aid']; ?>" 
                                            class="inline-block text-white bg-gray-400 hover:bg-red-700 rounded px-4 py-2"
                                            onclick="return confirm('Are you sure you want to delete this album?');">
                                                Delete
                                            </a>
                                        </div>
                                    </td>

                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="11" class="text-center border border-gray-400 px-4 py-2">No albums found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
