
<?php

require_once 'API.php';
function getDistanceMatrix('DN76LX', $destination, $apiKey) {
    $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=" 
            . urlencode($origin) 
            . "&destinations=" . urlencode($destination) 
            . "&key=" . $apiKey;

    $response = file_get_contents($url);

    if ($response === FALSE) {
        die('Error occurred while requesting data from Google Maps Distance Matrix API');
    }

    $data = json_decode($response, true);
    
    return $data;
}

$result = getDistanceMatrix('New York, NY', 'Los Angeles, CA', $apiKey);

echo "<pre>";
print_r($result);
echo "</pre>";

?>