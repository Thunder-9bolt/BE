<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Adjust the path as needed

// Function to generate a random OTP
function generateOTP() {
    return rand(100000, 999999); // You can adjust the range as needed
}

// Function to send OTP to the user's email
function sendOTP($email, $otp) {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'arpitagiri09@gmail.com'; // Your Gmail address
        $mail->Password   = 'Arpita@123'; // Your Gmail password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 465;

        // Recipients
        $mail->setFrom('arpitagiri09@gmail.com', 'Arpita'); // Replace with your name
        $mail->addAddress($email); // User's email

        // Content
        $mail->isHTML(false);
        $mail->Subject = 'Your One-Time Password (OTP)';
        $mail->Body    = "Your OTP is: $otp";
        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

// Example usage
$userEmail = "tejasgosavi121@gmailcom"; // Replace with the user's actual email

// Generate OTP
$otp = generateOTP();

// Store the OTP securely (e.g., database) for verification later

// Send OTP to the user's email
$sendSuccess = sendOTP($userEmail, $otp);

if ($sendSuccess) {
    echo "OTP sent successfully to $userEmail";
} else {
    echo "Failed to send OTP. Please try again.";
}
?>
