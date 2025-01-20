<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_signUp.php");
    exit;
}

// Include and initialize controllers
require_once '../../../App/controllers/CustomerController.php';
require_once '../../../App/controllers/AlbumController.php';
require_once '../../../App/controllers/OrderController.php';

$customerController = new CustomerController();
$albumController = new AlbumController();
$orderController = new OrderController();

// Fetch data
$total_customers = $customerController->getCustomerCount();
$total_albums = $albumController->getAlbumCount();
$total_orders = $orderController->getOrderCount();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rowdies:wght@300;400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Merriweather+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
    <!-- Tailwind CSS -->
    <link href="/Albumora/public/css/tailwind.css" rel="stylesheet">
</head>
<body class="bg-gray-100 font-sans">

    <!-- Main Container -->
    <div class="flex flex-col h-screen">
        <!-- Navbar -->
        <?php include '../layout/adminNav.php'; ?>

        <!-- Body Container -->
        <div class="flex flex-1 flex-col items-center bg-customBackgroundcolor mt-20 px-6">
            <header class="text-center my-10">
                <h1 class="text-4xl sm:text-5xl font-rowdies font-bold text-gray-800">Admin Dashboard</h1>
            </header>
            
            <!-- Navigation Tabs -->
            <nav class="flex flex-wrap justify-center gap-4 sm:gap-8 mb-10 bg-gray-600 px-6 py-4 rounded-lg">
                <a href="../admin/dashboard.php" class="text-black px-6 py-2 rounded-full bg-gray-400 flex items-center">
                    <span>🏠</span>
                    <span class="ml-2">Dashboard</span>
                </a>
                <a href="../admin/customerlist.php" class="text-black px-6 py-2 rounded-full bg-gray-200 flex items-center">
                    <span>👥</span>
                    <span class="ml-2">Customers</span>
                </a>
                <a href="../albums/list.php" class="text-black px-6 py-2 rounded-full bg-gray-200 flex items-center">
                    <span>🎵</span>
                    <span class="ml-2">Albums</span>
                </a>
                <a href="../admin/orderlist.php" class="text-black px-6 py-2 rounded-full bg-gray-200 flex items-center">
                    <span>📦</span>
                    <span class="ml-2">Orders</span>
                </a>
            </nav>

            <!-- Stats Section -->
            <div class="flex flex-wrap justify-center gap-12 ">
                <!-- Total Customers -->
                <div class="flex flex-col items-center bg-customGray p-10 sm:p-8 rounded-lg shadow-lg text-center w-80 sm:w-64 mb-6">
                    <h2 class="text-2xl font-bold text-gray-700 font-merriweatherSans">Total Customers</h2>
                    <div class="my-4">
                        <img src="../../../Img/CustomerBox.png" alt="Customers" class="w-16 h-16 object-contain">
                    </div>
                    <p class="text-xl font-bold text-gray-800"><?php echo $total_customers; ?></p>
                </div>

                <!-- Total Albums -->
                <div class="flex flex-col items-center bg-customGray p-10 sm:p-8 rounded-lg shadow-lg text-center w-80 sm:w-64 mb-6">
                    <h2 class="text-2xl font-bold text-gray-700 font-merriweatherSans">Total Albums</h2>
                    <div class="my-4">
                        <img src="../../../Img/AlbumBox.png" alt="Albums" class="w-16 h-16 object-contain">
                    </div>
                    <p class="text-xl font-bold text-gray-800"><?php echo $total_albums; ?></p>
                </div>

                <!-- Total Orders -->
                <div class="flex flex-col items-center bg-customGray p-10 sm:p-8 rounded-lg shadow-lg text-center w-80 sm:w-64 mb-6">
                    <h2 class="text-2xl font-bold text-gray-700 font-merriweatherSans">Total Orders</h2>
                    <div class="my-4">
                        <img src="../../../Img/OrderBox.png" alt="Orders" class="w-16 h-16 object-contain">
                    </div>
                    <p class="text-xl font-bold text-gray-800"><?php echo $total_orders; ?></p>
                </div>
            </div>

        </div>
    </div>
</body>
</html>
