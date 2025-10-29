<?php


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


function getDistance($start, $end) {
    // Google Map API which returns the distance between 2 postcodes
    $postcode1 = preg_replace('/\s+/', '', $start); 
    $postcode2 = preg_replace('/\s+/', '', $end);
    $result    = array();

    $url = "http://maps.googleapis.com/maps/api/distancematrix/json?origins=$postcode1&destinations=$postcode2&mode=driving&language=en-EN&sensor=false";

    $data   = @file_get_contents($url);
    $result = json_decode($data, true);
    //print_r($result);  //outputs the array

    return array( // converts the units
        "meters" => $result["rows"][0]["elements"][0]["distance"]["value"],
        "kilometers" => $result["rows"][0]["elements"][0]["distance"]["value"] / 1000,
        "yards" => $result["rows"][0]["elements"][0]["distance"]["value"] * 1.0936133,
        "miles" => $result["rows"][0]["elements"][0]["distance"]["value"] * 0.000621371
    );
}
getDistance($collectPostcode, $deliverPostcode)

?>