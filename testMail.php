<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// If using Composer
require __DIR__ . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = 'mail.bikesinavan.co.uk';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'info@bikesinavan.co.uk';
    $mail->Password   = 'emailPassword';
    $mail->SMTPSecure = 'tls';  // use 'ssl' if port 465
    $mail->Port       = 587;    // use 465 if SSL

    $mail->setFrom('info@bikesinavan.co.uk', 'BikesInAVan');
    $mail->addAddress('youremail@example.com');

    $mail->Subject = 'Test email';
    $mail->Body    = 'This is a test email from BikesInAVan quote page.';
    $mail->send();

    echo "Email sent successfully!";
} catch (Exception $e) {
    echo "Mailer Error: " . $mail->ErrorInfo;
}