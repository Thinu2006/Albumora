<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_signUp.php");
    exit;
}
// Include the required controller
require_once '../../../App/controllers/CustomerController.php';

// Create an instance of the controller
$controller = new CustomerController();

// Fetch all customers
$customers = $controller->index();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer List</title>
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
        <div class="flex flex-1 flex-col items-center bg-customBackgroundcolor mt-20 px-4">
            <header class="text-center my-10">
                <h1 class="text-4xl sm:text-5xl font-rowdies font-bold text-gray-800">Customers List</h1>
            </header>
            <!-- Navigation Tabs -->
            <nav class="flex flex-wrap justify-center gap-4 sm:gap-8 mb-10 bg-gray-600 px-6 py-4 rounded-lg">
                <a href="../admin/dashboard.php" class="text-black px-6 py-2 rounded-full bg-gray-200 flex items-center">
                    <span>🏠</span>
                    <span class="ml-2">Dashboard</span>
                </a>
                <a href="../admin/customerlist.php" class="text-black px-6 py-2 rounded-full bg-gray-400 flex items-center">
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
            <!-- Table to Display Customers -->
            <div class="overflow-x-auto mb-20">
                <table class="table-auto w-full border-collapse border border-gray-300 text-center bg-customLightGray">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="px-4 py-2 border border-gray-400">CustomerID</th>
                            <th class="px-4 py-2 border border-gray-400">Name</th>
                            <th class="px-4 py-2 border border-gray-400">Email</th>
                        </tr>
                    </thead>
                    <tbody class="bg-customLightGray">
                        <?php if (!empty($customers)): ?>
                            <?php foreach ($customers as $customer): ?>
                                <tr>
                                    <td class="border border-gray-400 px-4 py-2"><?php echo htmlspecialchars($customer['CustomerId']); ?></td>
                                    <td class="border border-gray-400 px-4 py-2"><?php echo htmlspecialchars($customer['Name']); ?></td>
                                    <td class="border border-gray-400 px-4 py-2"><?php echo htmlspecialchars($customer['Email']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" class="text-center border border-gray-400 px-4 py-2">No Customers Found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
