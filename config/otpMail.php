<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer
require_once __DIR__ . '/../vendor/autoload.php';

function sendOTPEmail($email, $otp) {
    $mail = new PHPMailer(true);

    try {
        // SMTP Configuration
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; 
        $mail->SMTPAuth = true;
        $mail->Username = 'thinudifernando@gmail.com'; 
        $mail->Password = 'onvc unfy jsuh snop'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Email Settings
        $mail->setFrom('your-email@gmail.com', 'Your Application'); 
        $mail->addAddress($email);
        $mail->Subject = "Your OTP for Registration";
        $mail->Body = "Your OTP is: $otp. Please enter this in the verification form to complete your sign in.";

        // Send email
        $mail->send();
    } catch (Exception $e) {
        echo "Error sending OTP email: " . $mail->ErrorInfo;
    }
}
?>
