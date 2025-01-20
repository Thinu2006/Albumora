<?php
require_once '../../../App/controllers/CustomerController.php';

// Create an instance of the controller
$controller = new CustomerController();

// Handle OTP verification
$controller->verifyOTP();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rowdies:wght@300;400;700&display=swap" rel="stylesheet">
    <link href="/Albumora/public/css/tailwind.css" rel="stylesheet">
</head>
<body class="min-h-screen flex items-center justify-center bg-cover bg-center bg-no-repeat bg-black/30" 
      style="background-image: url('../../../Img/OTPPageBG.jpg');">
    <div class="bg-customGray shadow-lg rounded-lg p-12 w-auto h-[400px]">
        <h2 class="form-heading font-rowdies">OTP Verification</h2>
        <form action="otpVerify.php" method="POST" class="form-container">
            <div>
                <label for="otp" class="block text-gray-700 font-bold mb-6 text-xl">Enter OTP:</label>
                <input type="text" name="otp" id="otp" required class="input-field mb-6" placeholder="Enter your OTP">
            </div>
            <div class="text-center">
                <button type="submit" class="btn">Verify OTP</button>
            </div>
        </form>
    </div>
</body>
</html>
