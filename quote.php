<?php
require_once 'distance.php';

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    http_response_code(403);
    echo "There was a problem with your submission, please try again.";
    exit;
}
$customerEmail = htmlspecialchars($_POST["customerEmail"]);
$collectPostcode = htmlspecialchars($_POST["collectPostcode"]);
$deliverPostcode = htmlspecialchars($_POST["deliverPostcode"]);
$bikeModel = htmlspecialchars($_POST["bikeModel"]);
$customerName = htmlspecialchars($_POST["customerName"]);
echo"$customerEmail, $collectPostcode, $deliverPostcode, $bikeModel, $customerName";





?>