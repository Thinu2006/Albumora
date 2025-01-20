<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Albumora</title>
    <link href="/Albumora/public/css/tailwind.css" rel="stylesheet">
</head>
<body class="bg-gray-200 h-screen w-screen m-0 p-0 flex justify-center items-center">
    <!-- Background Image -->
    <div 
        class="relative w-full h-full bg-cover bg-center bg-no-repeat" 
        style="background-image: url('../../../Img/MainPageBG.PNG');">
        
        <!-- Logo -->
        <div class="absolute top-4 left-4 flex items-center space-x-2 sm:top-8 sm:left-8">
            <img src="../../../Img/Logo.png" alt="Logo" class="h-16 w-auto sm:h-20">
        </div>

        <!-- Albumora Text and Login Button -->
        <div class="absolute top-1/3 left-4 sm:left-20 flex flex-col items-center space-y-8 sm:space-y-16 text-center ">
            <!-- Albumora Text -->
            <h1 class="text-8xl font-bold text-gray-800 p-4">ALBUMORA</h1>
            
            <!-- Login Button -->
            <a href="signIn.php" 
               class="bg-gray-300 px-6 sm:px-10 py-2 rounded-3xl font-medium text-black hover:bg-gray-400 hover:text-white inline-block text-lg sm:text-xl">
                LOGIN
            </a>
        </div>
    </div>
</body>
</html>
