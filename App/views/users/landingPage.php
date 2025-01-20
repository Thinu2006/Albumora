<?php
session_start();
if (!isset($_SESSION['CustomerId'])) {
    header("Location: signIn.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Album Store</title>
    <!-- font style -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rowdies:wght@300;400;700&display=swap" rel="stylesheet">
    <!-- tailwind stylesheet -->
    <link href="/Albumora/public/css/tailwind.css" rel="stylesheet">
    <!--icons-->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body class="bg-gray-100">
    <!-- Header -->
    <?php include '../layout/customer_header.php';?>

    <!-- Main Content -->
    <main class="container mx-auto px-4 sm:px-6 lg:px-8 mt-12">
        <!-- Welcome Section -->
        <section class="relative bg-cover bg-center bg-opacity-75 rounded-lg p-6" style="background-image: url('../../../Img/Banner.png');">
            <div class="absolute inset-0 bg-gray-800 bg-opacity-60 rounded-lg"></div>
            <div class="relative text-center box-border p-6 sm:p-10">
                <h2 class="text-4xl sm:text-6xl font-bold text-white mb-4">Welcome</h2>
                <p class="text-lg sm:text-2xl text-gray-100 mx-auto sm:w-3/4 leading-8 p-4">
                    Discover your trusted destination for buying authentic music albums. From the latest hits to rare classics, we offer a carefully curated collection for every music lover.
                </p>
                <p class="text-lg sm:text-2xl text-gray-200 mt-1 mx-auto sm:w-3/4 leading-8 p-2">
                    With secure payments, quality assurance, and excellent customer support, we ensure a seamless shopping experience. Trust us to bring you closer to the music that moves you.
                </p>
            </div>
        </section>

        <!-- Best Selling Albums Section -->
        <section class="mt-20">
            <h3 class="section-title">This Week's Best Selling Albums</h3>
            <div class="section-container">
                <!-- Album 1 -->
                <div class="album-card">
                    <img src="../../../Img/Midnights_-_Taylor_Swift.png" alt="Album 1" class="album-img">
                    <h4 class="album-title">Midnight</h4>
                    <p class="album-artist">Taylor Swift</p>
                </div>
                <!-- Album 2 -->
                <div class="album-card">
                    <img src="../../../Img/im4.jpeg" alt="My Stupid Life" class="album-img">
                    <h4 class="album-title">My Stupid Life</h4>
                    <p class="album-artist">Britney Spencer</p>
                </div>
                <!-- Album 3 -->
                <div class="album-card">
                    <img src="../../../Img/img5.jpeg" alt="Hit Me Hard and Soft" class="album-img">
                    <h4 class="album-title">Hit Me Hard and Soft</h4>
                    <p class="album-artist">Billie Eilish</p>
                </div>
            </div>
        </section>

        <!-- Throwback Albums Section -->
        <section class="mt-20">
            <h3 class="section-title">Throwback Albums Making a Comeback</h3>
            <div class="section-container">
                <!-- Album 4 -->
                <div class="album-card">
                    <img src="../../../Img/img 8.jpeg" alt="Ohio Players" class="album-img">
                    <h4 class="album-title">Ohio Players</h4>
                    <p class="album-artist">The Black Keys</p>
                </div>
                <!-- Album 5 -->
                <div class="album-card">
                    <img src="../../../Img/img 7.jpeg" alt="Seal" class="album-img">
                    <h4 class="album-title">Seal</h4>
                    <p class="album-artist">Seal</p>
                </div>
                <!-- Album 6 -->
                <div class="album-card">
                    <img src="../../../Img/img 6.jpeg" alt="Talk Yes" class="album-img">
                    <h4 class="album-title">Talk Yes</h4>
                    <p class="album-artist">Talk</p>
                </div>
            </div>
        </section>
    </main>
    <!-- Footer -->
    <?php include '../layout/customer_footer.php';?>
</body>
</html>

