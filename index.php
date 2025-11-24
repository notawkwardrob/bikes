<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Database configuration
$dbHost = 'localhost:3306';
$dbName = 'bikesina_quotes';
$dbUser = 'bikesina_bikesina';
$dbPass = 'Temppassword';

$submitted_quote = null;

try {
    $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName;charset=utf8", $dbUser, $dbPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Optional: handle display of last submitted quote
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['quoteValue'])) {
    $submitted_quote = [
        'collection' => htmlspecialchars($_POST['collection'] ?? ''),
        'delivery'   => htmlspecialchars($_POST['delivery'] ?? ''),
        'miles'      => htmlspecialchars($_POST['miles'] ?? ''),
        'minutes'    => htmlspecialchars($_POST['minutes'] ?? ''),
        'quote'      => htmlspecialchars($_POST['quoteValue'] ?? ''),
        'email'      => htmlspecialchars($_POST['customerEmail'] ?? ''),
        'bikeModel'  => htmlspecialchars($_POST['bikeModel'] ?? '')
    ];

    // Save to database
    $stmt = $pdo->prepare("INSERT INTO quotes 
        (collection, delivery, miles, minutes, quote, email, bike_model)
        VALUES (:collection, :delivery, :miles, :minutes, :quote, :email, :bikeModel)
    ");
    $stmt->execute([
        ':collection' => $submitted_quote['collection'],
        ':delivery'   => $submitted_quote['delivery'],
        ':miles'      => $submitted_quote['miles'],
        ':minutes'    => $submitted_quote['minutes'],
        ':quote'      => $submitted_quote['quote'],
        ':email'      => $submitted_quote['email'],
        ':bikeModel'  => $submitted_quote['bikeModel']
    ]);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>BikesInAVan — Motorcycle Transport</title>
<meta name="description" content="Safe, insured transport for cherished motorcycles around the UK." />
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;800&family=Roboto:wght@300;400;700&display=swap" rel="stylesheet"/>
<link href="style.css" rel="stylesheet"/>
<style>
body { font-family: Arial, sans-serif; margin:0; padding:0; background:#f7f7f7; }
.site { max-width: 900px; margin: 0 auto; padding:20px; }
header { display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; }
.logo img { height:50px; margin-right:10px; vertical-align:middle; }
.calc-card { background:#fff; padding:18px; border-radius:10px; box-shadow:0 2px 6px rgba(0,0,0,0.12); margin-top:20px; }
.calc-card label { display:block; margin-top:10px; font-weight:600; }
.calc-card input { width:100%; padding:10px; margin-top:6px; box-sizing:border-box; border-radius:6px; border:1px solid #ccc; }
.calc-card button { margin-top:12px; padding:10px 14px; cursor:pointer; }
#output table { margin-top:16px; border-collapse: collapse; width:100%; background:white; }
#output th, #output td { border:1px solid #ddd; padding:8px; text-align:center; }
#output th { background:#f3f3f3; }
.small { font-size:0.9em; color:#666; }
.error { color:#b00; font-weight:600; margin-top:10px; }
.submitted-quote { border:2px solid #4CAF50; padding:18px; border-radius:10px; background:#f7fff7; margin-top:20px; }
</style>
</head>
<body>
<div class="site">

<header>
    <div class="logo">
        <img src="images/ChatGPT Image Aug 10, 2025, 02_00_34 PM.png" alt="BikesInAVan logo"/>
        <div class="brand">
            <h1>bikesinavan.co.uk</h1>
            <p>Secure motorcycle transport.</p>
        </div>
    </div>
    <div style="text-align:right">
        <div class="small muted">Call now</div>
        <div style="font-weight:800;font-family:Montserrat">07711926842</div>
        <div class="muted small">info@bikesinavan.co.uk</div>
    </div>
</header>

<section class="hero">
    <div class="calc-card">
        <h2>Get an Instant Quote</h2>
        <label for="addrB">Collection Address</label>
        <input id="addrB" type="text" placeholder="e.g. BB1 2AB, Blackburn" />

        <label for="addrC">Delivery Address</label>
        <input id="addrC" type="text" placeholder="e.g. DN4 5PJ, Doncaster" />

        <label for="customerEmail">Email</label>
        <input type="email" id="customerEmail" placeholder="your@email.com" required/>

        <label for="bikeModel">Bike Make/Model</label>
        <input type="text" id="bikeModel" placeholder="Make and model of bike"/>

        <button id="calcBtn" class="btn btn-primary">Calculate distance & quote</button>

        <div id="output"></div>
    </div>

    <?php if ($submitted_quote): ?>
        <div class="submitted-quote">
            <h4>Your Submitted Quote</h4>
            <p><strong>Collection:</strong> <?= $submitted_quote['collection'] ?></p>
            <p><strong>Delivery:</strong> <?= $submitted_quote['delivery'] ?></p>
            <p><strong>Distance:</strong> <?= $submitted_quote['miles'] ?> miles</p>
            <p><strong>Time:</strong> <?= $submitted_quote['minutes'] ?> minutes</p>
            <p><strong>Bike:</strong> <?= $submitted_quote['bikeModel'] ?></p>
            <p><strong>Email:</strong> <?= $submitted_quote['email'] ?></p>
            <p><strong>Quote:</strong> £<?= $submitted_quote['quote'] ?></p>
        </div>
    <?php endif; ?>
</section>

<footer style="margin-top:40px;">
    &copy; <span id="year"></span> BikesInAVan • Professional motorcycle transport •
    <span class="muted">All rights reserved</span>
</footer>

</div>

<script>
document.getElementById('year').textContent = new Date().getFullYear();
</script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDjvloNz5LbhNHNqCS5058HB6PcUJa8Usw&libraries=places&callback=initApp" async defer></script>

<script>
let mapsLoaded = false;
function initApp() { mapsLoaded = true; }

document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('calcBtn').addEventListener('click', onCalculate);
});

function onCalculate() {
    const a = "DN7 6LX, Hatfield, Doncaster";
    const b = document.getElementById('addrB').value.trim();
    const c = document.getElementById('addrC').value.trim();
    const d = "DN7 6LX, Hatfield, Doncaster";

    const email = document.getElementById('customerEmail').value.trim();
    const bikeModel = document.getElementById('bikeModel').value.trim();

    const output = document.getElementById('output');
    output.innerHTML = "";

    if(!b || !c){ output.innerHTML = "<div class='error'>Please fill both addresses.</div>"; return; }
    if(!email){ output.innerHTML = "<div class='error'>Please enter your email.</div>"; return; }
    if(!mapsLoaded){ output.innerHTML = "<div class='error'>Google Maps API not loaded.</div>"; return; }

    const service = new google.maps.DistanceMatrixService();
    const origins = [a,b,c,d];
    const destinations = [a,b,c,d];

    output.innerHTML = "<div class='small'>Calculating…</div>";

    service.getDistanceMatrix({
        origins,
        destinations,
        travelMode: google.maps.TravelMode.DRIVING,
        unitSystem: google.maps.UnitSystem.METRIC
    }, (response, status) => {
        if(status !== "OK"){ output.innerHTML=`<div class='error'>Error: ${status}</div>`; return; }

        const rows = response.rows;
        let meters=0, seconds=0;
        function addLeg(i,j){ const el = rows[i].elements[j]; if(el && el.status==="OK"){ meters+=el.distance.value; seconds+=el.duration.value; } }
        addLeg(0,1); addLeg(1,2); addLeg(2,0);

        const miles = (meters/1609.34).toFixed(1);
        const mins = Math.round(seconds/60);

        let quote=0, rateType="Long distance";
        if(miles<50 && mins<60){ quote=110; rateType="Local rate"; } else { quote=(miles*1.2).toFixed(0); }

        output.innerHTML = `
            <p><strong>Your quote:</strong> £${quote}</p>
        `;

        // Auto-submit to database via POST using a hidden form
        const form = document.createElement('form');
        form.method = 'POST';
        form.style.display = 'none';

        ['collection','delivery','miles','minutes','quoteValue','customerEmail','bikeModel'].forEach(name=>{
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = name;
            input.value = {
                collection: b,
                delivery: c,
                miles: miles,
                minutes: mins,
                quoteValue: quote,
                customerEmail: email,
                bikeModel: bikeModel
            }[name];
            form.appendChild(input);
        });

        document.body.appendChild(form);
        form.submit();
    });
}
</script>
</body>
</html>
