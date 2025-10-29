<?
if (isset($_GET['search'])) {
    $userSearch = $_GET['search']; // search for users by username
    $query      = mysql_query("SELECT username, postcode, user_id FROM users WHERE username LIKE '%$userSearch%'");
    $rowcount   = mysql_num_rows($query);

    if ($userSearch == "") {

    } else {
        if ($rowcount != 0) {
            while ($row = mysql_fetch_assoc($query)) {
                $username = $row['username'];
                $postcode = $row['postcode'];

                $user_id = $row['user_id'];
                $sql     = mysql_query("SELECT postcode FROM users WHERE user_id = $user_id");
                $results = mysql_fetch_assoc($sql);
                echo $results['postcode'];
                $distance = getDistance($user_data['postcode'], $postcode); 
                //im not sure where the $user_data comes from but it was in your original code

                echo '<a href="' . $username . '">' . $username . '</a> ' . $postcode . ' is : ' . number_format($distance["miles"], 2) . " Mile(s) away" . '<br/>'; // returns results
            }
        } else {

            echo "No user found";
        }
    }
}

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