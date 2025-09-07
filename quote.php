<?php
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    http_response_code(403);
    echo "There was a problem with your submission, please try again.";
    exit;
}
$email = htmlspecialchars($_POST["email"]);
echo"$email";
?>