<?php
require __DIR__ . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host       = 'mail.bikesinavan.co.uk';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'info@bikesinavan.co.uk';
    $mail->Password   = 'emailPassword';
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 465;

    $mail->setFrom('you@bikesinavan.co.uk', 'BikesInAVan');
    $mail->addAddress('youremail@example.com');

    $mail->Subject = 'Test email';
    $mail->Body    = 'This is a test email from BikesInAVan quote page.';
    $mail->send();
    echo "Email sent successfully!";
} catch (Exception $e) {
    echo "Mailer Error: " . $mail->ErrorInfo;
}

