<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$submitted_quote = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['quoteValue'])) {
    // Sanitize values
    $submitted_quote = [
        'collection' => htmlspecialchars($_POST['collection'] ?? ''),
        'delivery'   => htmlspecialchars($_POST['delivery'] ?? ''),
        'miles'      => htmlspecialchars($_POST['miles'] ?? ''),
        'minutes'    => htmlspecialchars($_POST['minutes'] ?? ''),
        'quote'      => htmlspecialchars($_POST['quoteValue'] ?? '')
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>BikesInAVan — Motorcycle Transport (bikesinavan.co.uk)</title>
    <meta name="description" content="Safe, insured transport for cherished motorcycles around the UK." />

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;800&family=Roboto:wght@300;400;700&display=swap" rel="stylesheet"/>
    <link href="style.css" rel="stylesheet" />

    <style>
        /* Calculator styling merged into site */
        .calc-card {
            background: var(--card);
            padding: 18px;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.12);
            margin-top: 20px;
        }
        .calc-card label {
            display: block;
            margin-top: 10px;
            font-weight: 600;
        }
        .calc-card input {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            box-sizing: border-box;
            border-radius: 6px;
            border: 1px solid #ccc;
        }
        #output table {
            margin-top: 16px;
            border-collapse: collapse;
            width: 100%;
            background: white;
        }
        #output th, #output td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        #output th {
            background: #f3f3f3;
        }
        .small { font-size: 0.9em; color: #666; }
        .error { color: #b00; font-weight: 600; margin-top: 10px; }
        .submitted-quote {
            border: 2px solid #4CAF50;
            padding: 18px;
            border-radius: 10px;
            background: #f7fff7;
            margin-top: 20px;
        }
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
    <div style="margin-left:auto;text-align:right">
        <div class="small muted">Call now</div>
        <div style="font-weight:800;font-family:Montserrat">07711926842</div>
        <div class="muted small">info@bikesinavan.co.uk</div>
    </div>
</header>

<section class="hero">
    <div class="hero-left">
        <div class="eyebrow">Trusted motorcycle transport</div>
        <h2>We move cherished bikes safely — door-to-door across the UK</h2>
        <p class="muted">
            Professional, insured motorcycle transport in a secure, enclosed van.
            Perfect for classics, moderns and everything in between.
        </p>

        <div class="cta-row">
            <a class="btn btn-primary" href="#contact">Get a quote</a>
            <a class="btn btn-ghost" href="#services">Our services</a>
        </div>

        <div style="margin-top:18px;color:var(--muted);font-size:14px">
            <strong>Fast pickups</strong> — Local to national with competitive rates.
        </div>
    </div>

    <div class="hero-right">
        <div class="bg" style="
            background-image:url('/mnt/data/A_logo_for_a_motorcycle_transportation_service_web.png');
            width:100%;height:100%;
        "></div>
    </div>
</section>

<section id="services" class="services">
    <div class="card">
        <h3>Door-to-door transport</h3>
        <p>Collection and delivery from home, workshop or showroom.</p>
    </div>
    <div class="card">
        <h3>Enclosed, insured transit</h3>
        <p>All bikes travel inside a secure enclosed van.</p>
    </div>
    <div class="card">
        <h3>Experienced handling</h3>
        <p>Suitable for classics, modern sportbikes, and tourers.</p>
    </div>
</section>

<section class="about">
    <img src="images/driver.jpg" alt="Driver portrait"/>
    <div class="txt">
        <h3>About BikesInAVan</h3>
        <p class="muted">30 years of motorbike ownership and professional driving experience.</p>
    </div>
</section>

<!-- ====================== QUOTE CALCULATOR SECTION ======================= -->
<section id="contact" class="contact">
    <h3>Instant Quote Calculator</h3>

    <div class="calc-card">
        <label for="addrB">Collection address</label>
        <input id="addrB" type="text" placeholder="e.g. BB1 2AB, Blackburn" />

        <label for="addrC">Delivery address</label>
        <input id="addrC" type="text" placeholder="e.g. DN4 5PJ, Doncaster" />

        <button id="calcBtn" class="btn btn-primary" style="margin-top:15px">
            Calculate distance & quote
        </button>

        <div id="output"></div>

        <form id="returnQuoteForm" method="POST" style="display:none;margin-top:20px;">
            <input type="hidden" name="collection" id="formCollection">
            <input type="hidden" name="delivery" id="formDelivery">
            <input type="hidden" name="miles" id="formMiles">
            <input type="hidden" name="minutes" id="formMinutes">
            <input type="hidden" name="quoteValue" id="formQuote">
            <button class="btn btn-primary">Send me this quote</button>
        </form>
    </div>

    <?php if ($submitted_quote): ?>
        <div class="submitted-quote">
            <h4>Your Submitted Quote</h4>
            <p><strong>Collection:</strong> <?= $submitted_quote['collection'] ?></p>
            <p><strong>Delivery:</strong> <?= $submitted_quote['delivery'] ?></p>
            <p><strong>Distance:</strong> <?= $submitted_quote['miles'] ?> miles</p>
            <p><strong>Time:</strong> <?= $submitted_quote['minutes'] ?> minutes</p>
            <p><strong>Your quote:</strong> £<?= $submitted_quote['quote'] ?></p>
        </div>
    <?php endif; ?>

    <aside style="padding:18px;background:var(--card);border-radius:10px;margin-top:20px">
        <h4 style="margin-top:0">Contact</h4>
        <p class="muted">Phone: <strong>07711926842</strong></p>
        <p class="muted">Email: <strong>info@bikesinavan.co.uk</strong></p>
        <p class="muted">Based in South Yorkshire — national coverage.</p>

        <a class="btn btn-primary" href="tel:07711926842" style="margin-top:12px">Call now</a>
    </aside>
</section>

<footer>
    &copy; <span id="year"></span> BikesInAVan • Professional motorcycle transport •
    <span class="muted">All rights reserved</span>
</footer>

</div>

<script>
document.getElementById('year').textContent = new Date().getFullYear();
</script>

<!-- Google Maps API -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDjvloNz5LbhNHNqCS5058HB6PcUJa8Usw&libraries=places&callback=initApp" async defer></script>

<script>
let mapsLoaded = false;
function initApp() { mapsLoaded = true; }

document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('calcBtn').addEventListener('click', onCalculate);
});

async function onCalculate() {
    const a = "DN7 6LX, Hatfield, Doncaster";  // Home base
    const b = document.getElementById('addrB').value.trim();
    const c = document.getElementById('addrC').value.trim();
    const d = "DN7 6LX, Hatfield, Doncaster";  // Return home

    const output = document.getElementById('output');
    output.innerHTML = "";

    if (!b || !c) {
        output.innerHTML = "<div class='error'>Please fill both addresses.</div>";
        return;
    }
    if (!mapsLoaded) {
        output.innerHTML = "<div class='error'>Google Maps API not loaded.</div>";
        return;
    }

    const service = new google.maps.DistanceMatrixService();
    const origins = [a, b, c, d];
    const destinations = [a, b, c, d];

    output.innerHTML = "<div class='small'>Calculating…</div>";

    service.getDistanceMatrix({
        origins,
        destinations,
        travelMode: google.maps.TravelMode.DRIVING,
        unitSystem: google.maps.UnitSystem.METRIC
    }, (response, status) => {

        if (status !== "OK") {
            output.innerHTML = `<div class='error'>Error: ${status}</div>`;
            return;
        }

        const rows = response.rows;

        /* ------------------ Calculate Round Trip A→B→C→A ------------------ */
        let meters = 0;
        let seconds = 0;

        function addLeg(i,j) {
            const el = rows[i].elements[j];
            if (el && el.status === "OK") {
                meters += el.distance.value;
                seconds += el.duration.value;
            }
        }

        addLeg(0,1);
        addLeg(1,2);
        addLeg(2,0);

        const miles = (meters / 1609.34).toFixed(1);
        const mins  = Math.round(seconds / 60);

        /* ------------------ Price calculation ------------------ */
        let quote = 0;
        let rateType = "Long distance";

        if (miles < 50 && mins < 60) {
            quote = 110;
            rateType = "Local rate";
        } else {
            quote = (miles * 1.2).toFixed(0);
        }

        output.innerHTML = `
            <p><strong>Total mileage (A → B → C → A):</strong> ${miles} miles</p>
            <p><strong>Total time:</strong> ${mins} minutes</p>
            <p><strong>${rateType}:</strong> £${quote}</p>
            <p class='small'>Click the button below to send this quote back to the server.</p>
        `;

        // Populate and show return-quote form
        document.getElementById('formCollection').value = b;
        document.getElementById('formDelivery').value = c;
        document.getElementById('formMiles').value = miles;
        document.getElementById('formMinutes').value = mins;
        document.getElementById('formQuote').value = quote;
        document.getElementById('returnQuoteForm').style.display = "block";
    });
}

function escapeHtml(s) {
    return String(s).replace(/[&<>"']/g, m =>
        ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m])
    );
}
</script>

</body>
</html>
