<?php
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    http_response_code(403);
    echo "There was a problem with your submission, please try again.";
    exit;
}
$customerEmail = htmlspecialchars($_POST["customerEmail"]);
$collectPostcode = htmlspecialchars($_POST["collectPostcode"]);
$deliveryPostcode = htmlspecialchars($_POST["deliveryPostcode"]);
$bikeModel = htmlspecialchars($_POST["bikeModel"]);
$customerName = htmlspecialchars($_POST["customerName"]);
echo"$customerEmail, $collectPostcode, $deliveryPostcode, $bikeModel, $customerName";
?>