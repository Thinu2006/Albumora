<?php
// Include the required controller for handling sign in
require_once '../../../App/controllers/AdminController.php';

// Create an instance of the controller
$controller = new AdminController();

// Handle form submission for authentication
$controller->authenticate();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login Page</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rowdies:wght@300;400;700&display=swap" rel="stylesheet">
    <link href="/Albumora/public/css/tailwind.css" rel="stylesheet">
</head>
<body class="flex items-center justify-center min-h-screen bg-gray-200">
    <div class="bg-white shadow-lg rounded-3xl flex flex-col md:flex-row w-11/12 md:w-3/4 h-auto md:h-[500px]">
        <div class="hidden md:flex w-full md:w-1/2 panel-left" style="background-image: url('../../../Img/AdminSignInPageBG.PNG');">
            <div class="absolute top-0 left-0 w-full h-full bg-black opacity-30 rounded-l-3xl"></div>
        </div>
        
        <div class="w-full md:w-1/2 panel-right">
            <h2 class="form-heading font-rowdies">ADMIN SIGN IN</h2>
            <form action="admin_signUp.php" method="POST" class="form-container mt-8">
                <div class="mb-10">
                    <label for="username" class="sr-only">Username</label>
                    <input type="text" id="username" name="username" placeholder="Username" class="input-field" required>
                </div>
                <div class="mb-10">
                    <label for="password" class="sr-only">Password</label>
                    <input type="password" id="password" name="password" placeholder="Password" class="input-field" required>
                </div>
               <div class="text-center pt-6 px-10">
                    <button type="submit" class="btn">Sign In</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
