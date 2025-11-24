<?php

$to = 'info@bikesinavan.co.uk';

// Email subject
$subject = 'Test BikesInAVan Quote Email';

// Sample quote data
$collection = 'BB1 2AB, Blackburn';
$delivery   = 'DN4 5PJ, Doncaster';
$miles      = '45';
$minutes    = '55';
$quote      = '110';
$bikeModel  = 'Honda CBR600RR';
$customerEmail = 'customer@example.com';

// Email body
$message = "
A new quote has been submitted:

Collection: $collection
Delivery: $delivery
Distance: $miles miles
Time: $minutes minutes
Bike: $bikeModel
Customer Email: $customerEmail
Quote: £$quote
";

// Email headers
$headers = "From: info@bikesinavan.co.uk\r\n";
$headers .= "Reply-To: $customerEmail\r\n";

// Send the email
if(mail($to, $subject, $message, $headers)){
    echo "Test email sent successfully to $to!";
} else {
    echo "Failed to send test email. Check your server email settings.";
}
?>
