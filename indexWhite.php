<?php
// index.php - Clean modern redesign (Design A)
// ---------------------------
// Replace the placeholders below with your real credentials on your server.
// ---------------------------

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// --- CONFIG (replace placeholders) ---
$dbHost = 'localhost';
$dbName = 'bikesina_quotes';
$dbUser = 'bikesina_bikesina';
$dbPass = 'Temppassword';

$emailRecipient = 'EMAIL_TO'; // e.g. 'info@bikesinavan.co.uk'
$emailFrom = 'no-reply@yourdomain.co.uk'; // set a valid from address

// --- end config ---

$submitted_quote = null;
 $reference = random_int(10000000, 99999999);

// Connect DB (PDO)
try {
    $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4", $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
} catch (Exception $e) {
    // Fail gracefully for the frontend — show a lightweight message later if needed
    $pdo = null;
    error_log("DB connect error: " . $e->getMessage());
}

// Handle POSTed quote (auto-submitted by JS)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['quoteValue'])) {
    $submitted_quote = [
        'collection' => substr(trim($_POST['collection'] ?? ''), 0, 255),
        'delivery'   => substr(trim($_POST['delivery'] ?? ''), 0, 255),
        'miles'      => substr(trim($_POST['miles'] ?? ''), 0, 20),
        'minutes'    => substr(trim($_POST['minutes'] ?? ''), 0, 20),
        'quote'      => substr(trim($_POST['quoteValue'] ?? ''), 0, 20),
        'email'      => substr(trim($_POST['customerEmail'] ?? ''), 0, 255),
        'bikeModel'  => substr(trim($_POST['bikeModel'] ?? ''), 0, 255),
    ];

    // Send notification email (simple mail). For more reliable delivery use SMTP/PHPMailer.
    $to = $emailRecipient;
    $subject = 'New BikesInAVan Quote Submitted';
    $message = "A new motorcycle transport quote has been submitted:\n\n"
        . "Collection: {$submitted_quote['collection']}\n"
        . "Delivery:   {$submitted_quote['delivery']}\n"
        . "Distance:   {$submitted_quote['miles']} miles\n"
        . "Time:       {$submitted_quote['minutes']} minutes\n"
        . "Bike:       {$submitted_quote['bikeModel']}\n"
        . "Customer:   {$submitted_quote['email']}\n"
        . "Quote:      £{$submitted_quote['quote']}\n\n";
    $headers = "From: {$emailFrom}\r\n";
    $headers .= "Reply-To: {$submitted_quote['email']}\r\n";

    @mail($to, $subject, $message, $headers);

    // Save to DB (if connected)
    if ($pdo) {
        try {
            $stmt = $pdo->prepare("
                INSERT INTO quotes 
                    (collection, delivery, miles, minutes, quote, email, bike_model, created_at, reference)
                VALUES
                    (:collection, :delivery, :miles, :minutes, :quote, :email, :bikeModel, NOW(), :reference)
            ");
            $stmt->execute([
                ':collection' => $submitted_quote['collection'],
                ':delivery'   => $submitted_quote['delivery'],
                ':miles'      => $submitted_quote['miles'],
                ':minutes'    => $submitted_quote['minutes'],
                ':quote'      => $submitted_quote['quote'],
                ':email'      => $submitted_quote['email'],
                ':bikeModel'  => $submitted_quote['bikeModel'],
                ':reference'  => $reference,
            ]);
        } catch (Exception $e) {
            error_log("DB insert error: " . $e->getMessage());
        }
    }
}

?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>BikesInAVan — Instant Quote</title>

<!-- Clean modern (Design A) styles -->
<style>
:root{
  --bg:#ffffff;
  --card:#f8f9fb;
  --muted:#6b7280;
  --accent:#16a34a; /* green */
  --text:#0f172a;
  --radius:12px;
  --max-width:720px;
}
*{box-sizing:border-box}
html,body{height:100%}
body{
  margin:0;
  font-family:Inter, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
  background:var(--bg);
  color:var(--text);
  -webkit-font-smoothing:antialiased;
  -moz-osx-font-smoothing:grayscale;
}
.container{
  max-width:var(--max-width);
  margin:18px auto;
  padding:16px;
}

/* header */
.header{
  display:flex;
  align-items:center;
  gap:12px;
  justify-content:space-between;
}
.site-logo {
  height: 160px    ;   
  width: auto;     
  display: block;
 
}
.brand{
  display:flex;
  gap:12px;
  align-items:center;
}
.brand img{height:46px;width:46px;border-radius:8px;object-fit:cover}
.brand h1{font-size:1.1rem;margin:0}
.header-right{text-align:right;font-size:0.9rem;color:var(--muted)}

/* quote card (shows top after submit) */
.quote-card{
  background:linear-gradient(180deg, #ffffff 0%, #f7fbf7 100%);
  border:1px solid #e6f3ea;
  padding:16px;
  border-radius:var(--radius);
  box-shadow:0 6px 18px rgba(16,24,40,0.04);
  margin:16px 0;
}
.quote-card h2{margin:0 0 8px 0;font-size:1rem}
.quote-grid{
  display:grid;
  grid-template-columns:1fr 1fr;
  gap:8px;
  font-size:0.95rem;
  color:var(--text);
}
.quote-grid p{margin:0}
.quote-amount{
  margin-top:12px;
  font-weight:700;
  font-size:1.25rem;
  color:var(--accent);
}

/* layout */
.main{
  display:grid;
  grid-template-columns:1fr;
  gap:16px;
}

/* hero card with form */
.hero{
  background:var(--card);
  padding:18px;
  border-radius:calc(var(--radius) - 2px);
  box-shadow:0 6px 18px rgba(16,24,40,0.03);
}
.kicker{color:var(--muted);font-weight:600;font-size:0.85rem;margin-bottom:8px}
.hero h2{margin:0 0 8px 0;font-size:1.25rem}
.hero p{margin:0 0 12px 0;color:var(--muted)}

/* calc form */
.calc-card{background:transparent;padding:0;margin-top:8px}
.field{margin-bottom:10px}
.field label{display:block;font-size:0.85rem;color:var(--muted);margin-bottom:6px}
.field input[type="text"],
.field input[type="email"]{
  width:100%;
  padding:12px 14px;
  border-radius:10px;
  border:1px solid #e6e9ef;
  background:#fff;
  color:var(--text);
  font-size:0.95rem;
}
.row{display:flex;gap:10px}
.row .field{flex:1}
.button{
  display:inline-block;
  width:100%;
  padding:12px 14px;
  background:var(--accent);
  color:#fff;
  font-weight:700;
  border-radius:10px;
  border:none;
  cursor:pointer;
  font-size:0.95rem;
}
.small-muted{font-size:0.85rem;color:var(--muted);margin-top:8px}

/* output */
#output{margin-top:12px;color:var(--muted);font-weight:600}

/* footer */
.footer{margin-top:18px;color:var(--muted);font-size:0.85rem;text-align:center}

/* responsive */
@media (min-width:760px){
  .main{grid-template-columns:1fr 320px}
  .hero{padding:24px}
  .calc-card{max-width:580px}
}
</style>
</head>
<body>
<div class="container">
  <header class="header" role="banner">
    <div class="brand">
     <img src="/images/newLogo.png" alt="Site name" class="site-logo">
      <div>
        <h1>BikesInAVan</h1>
        <div style="color:var(--muted);font-size:0.9rem">Motorbike transport</div>
      </div>
    </div>
    <div class="header-right">
      <div>Contact</div>
      <div style="font-weight:800;color:var(--text)"><a href="tel:+447711926842">Call us</a></div>
      <div style="color:var(--muted)"><a href="mailto:info@bikesinavan.co.uk">info@bikesinavan.co.uk</a></div>
    </div>
  </header>

  <?php if ($submitted_quote): ?>
    <section class="quote-card" aria-live="polite">
      <h2>Your Quote</h2>
      <div class="quote-grid">
        <div><strong>Collection</strong><p><?= htmlspecialchars($submitted_quote['collection']) ?></p></div>
        <div><strong>Delivery</strong><p><?= htmlspecialchars($submitted_quote['delivery']) ?></p></div>

        <div><strong>Bike</strong><p><?= htmlspecialchars($submitted_quote['bikeModel']) ?></p></div>
        <div><strong>Email</strong><p><?= htmlspecialchars($submitted_quote['email']) ?></p></div>

        <div><strong>Distance</strong><p><?= htmlspecialchars($submitted_quote['miles']) ?> miles</p></div>
        <div><strong>Time</strong><p><?= htmlspecialchars($submitted_quote['minutes']) ?> mins</p></div>
      </div>

      <div class="quote-amount">£<?= htmlspecialchars($submitted_quote['quote']) ?></div>
      <div class="small-muted">Thank you — Feel free to contact us with your quote reference <b><?php $reference ?><</b>/div>
    </section>
  <?php endif; ?>

  <main class="main" role="main">
    <section class="hero" aria-labelledby="hero-heading">
      <div>
        <h2 id="hero-heading">We move cherished bikes safely — door-to-door across the UK</h2>
        <p class="small-muted">Professional, insured motorcycle transport in a secure, enclosed van. Perfect for classics, moderns and everything in between.</p>
      </div>

      <!-- Only show form if no submitted quote (server-side) -->
      <?php if (!$submitted_quote): ?>
      <div class="calc-card" aria-labelledby="calc-heading">
        <h3 id="calc-heading" style="margin-top:12px;margin-bottom:10px;">Get an instant quote</h3>

        <div class="field">
          <label for="addrB">Collection address</label>
          <input id="addrB" type="text" placeholder="e.g. BB1 2AB, Blackburn" autocomplete="off" inputmode="text" />
        </div>

        <div class="field">
          <label for="addrC">Delivery address</label>
          <input id="addrC" type="text" placeholder="e.g. DN4 5PJ, Doncaster" autocomplete="off" inputmode="text" />
        </div>

        <div class="row">
          <div class="field">
            <label for="customerEmail">Your email</label>
            <input id="customerEmail" type="email" placeholder="you@example.com" />
          </div>
          <div class="field">
            <label for="bikeModel">Bike make / model</label>
            <input id="bikeModel" type="text" placeholder="e.g. Honda CBR600RR" />
          </div>
        </div>

        <button id="calcBtn" class="button" type="button">Calculate quote</button>
        <div id="output" aria-live="polite"></div>
       
      </div>
      <?php endif; ?>
    </section>

    <!-- Right column (keep minimal - can show image or info) -->
    <aside class="hero-right" aria-label="About">
      <div style="background:#fff;padding:14px;border-radius:10px;box-shadow:0 6px 18px rgba(16,24,40,0.04);color:var(--text);">
        <h4 style="margin:0 0 8px 0">Why BikesInAVan</h4>
        <ul style="margin:0;padding-left:18px;color:var(--muted);font-size:0.95rem">
          <li>Enclosed, insured transit</li>
          <li>Handled with care</li>
          <li>Any size of motorbike</li>
        </ul>
      </div>
    </aside>
  </main>

  <footer class="footer">&copy; <span id="year"></span> BikesInAVan — Professional motorcycle transport</footer>
</div>


<script>

</script>

<script>
let mapsLoaded = false;

function initAutocomplete() {
  mapsLoaded = true;

  // Attach Places autocomplete only to visible inputs
  ['addrB','addrC'].forEach(id => {
    try {
      const el = document.getElementById(id);
      if (!el) return;
      // offsetParent !== null indicates element is in layout/visible
      if (el.offsetParent !== null && window.google && google.maps && google.maps.places) {
        new google.maps.places.Autocomplete(el, { types: ['geocode'] });
      }
    } catch (e) {
      // safe no-op, do not crash
      console.error('Autocomplete attach failed for', id, e);
    }
  });
}

document.addEventListener('DOMContentLoaded', () => {
  const btn = document.getElementById('calcBtn');
  if (btn) btn.addEventListener('click', onCalculate);
  document.getElementById('year').textContent = new Date().getFullYear();
});

function onCalculate() {
  const a = "DN7 6LX, Hatfield, Doncaster"; // base location
  const b = (document.getElementById('addrB') || {}).value || '';
  const c = (document.getElementById('addrC') || {}).value || '';
  const email = (document.getElementById('customerEmail') || {}).value || '';
  const bikeModel = (document.getElementById('bikeModel') || {}).value || '';
  const output = document.getElementById('output');

  output.textContent = '';
  if (!b || !c) { output.innerHTML = '<div style="color:#d14343;font-weight:700">Please fill both addresses</div>'; return; }
  if (!email) { output.innerHTML = '<div style="color:#d14343;font-weight:700">Please enter your email</div>'; return; }
  if (!mapsLoaded) { output.innerHTML = '<div style="color:#d14343;font-weight:700">Map services not loaded</div>'; return; }

  output.innerHTML = 'Calculating…';

  const service = new google.maps.DistanceMatrixService();
  service.getDistanceMatrix({
    origins: [a, b, c, a],
    destinations: [a, b, c, a],
    travelMode: google.maps.TravelMode.DRIVING,
    unitSystem: google.maps.UnitSystem.METRIC,
  }, (response, status) => {
    if (status !== 'OK') {
      output.innerHTML = '<div style="color:#d14343;font-weight:700">Distance lookup failed: ' + status + '</div>';
      console.error(status, response);
      return;
    }

    try {
      const rows = response.rows;
      let meters = 0, seconds = 0;
      function add(i,j) {
        const el = rows[i].elements[j];
        if (el && el.status === 'OK') {
          meters += el.distance.value;
          seconds += el.duration.value;
        }
      }
      add(0,1); add(1,2); add(2,0);

      const miles = (meters / 1609.34).toFixed(1);
      const mins = Math.round(seconds / 60);
      let quote;
      if (miles < 50 && mins < 60) {
        quote = 110;
      } else {
        quote = Math.max(60, Math.round(miles * 1.2)); // guard minimum
      }

      output.innerHTML = '<div style="font-weight:700;color:var(--accent)">Your quote: £' + quote + '</div>';

      // auto-submit hidden POST form to server (server saves + emails)
      const form = document.createElement('form');
      form.method = 'POST';
      form.style.display = 'none';
      const fields = {
        collection: b,
        delivery: c,
        miles: miles,
        minutes: mins,
        quoteValue: quote,
        customerEmail: email,
        bikeModel: bikeModel
      };
     
      for (const name in fields) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = name;
        input.value = fields[name];
        form.appendChild(input);
      }
      document.body.appendChild(form);
      form.submit();

    } catch (err) {
      output.innerHTML = '<div style="color:#d14343;font-weight:700">Error processing results</div>';
      console.error(err);
    }
  });
}
</script>

<!-- Load Google Maps - PUT YOUR KEY in place of YOUR_GOOGLE_API_KEY_HERE -->
<script async defer
  src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDjvloNz5LbhNHNqCS5058HB6PcUJa8Usw&libraries=places&callback=initAutocomplete">
</script>

</body>
</html>
