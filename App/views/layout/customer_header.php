<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Album Store</title>
    <link href="/Albumora/public/css/tailwind.css" rel="stylesheet">
    <!--icons-->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body class="bg-gray-100">
    <!-- Header -->
    <header class="bg-gray-800 text-white py-4">
        <div class="container flex mx-auto justify-between items-center ">
            <div class="ml-2">
                <img src="../../../Img/Logo.png" alt="Logo" class="h-20 w-auto">
            </div>
            <div class="mr-4">
                <!-- Form: Hidden on small screens -->
                <form class="items-center space-x-4 hidden sm:inline-flex">
                    <input type="text" name="search" placeholder="Search" class="px-10 py-2 rounded-md text-black">
                    <button type="submit">
                        <i class='bx bx-search-alt-2'></i>
                    </button>
                </form>
                <!-- Cart Button -->
                <button class="inline-block ml-4">
                    <i class='bx bx-cart'></i>
                </button>
                <!-- Logout Button -->
                <button class="inline-block ml-4">
                    <a href="/Albumora/App/controllers/CustomerController.php?action=customerlogout" class="cursor-pointer">
                        <i class='bx bx-log-out'></i>
                    </a>
                </button>
            </div>
        </div>
    </header>
</body>
</html>
