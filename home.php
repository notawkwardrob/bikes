<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Rob's Game - Login Or Register</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="Transparent login form using html and css" />
<meta name="keywords" content="Bikes in a van - motorcycle transport" />
<meta name="author" content="HTMLCSS3TUTORIALS" />
<link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
<div class="blur"></div>
<div class="formContent blur"> <img src="images/Logo.jpeg" class="avatarImg">
    
    <form method="post" action="login.php">
    <label>Username</label>
    <input type="text" name="username" placeholder="Enter Email" required>
    <label>Password</label>
    <input type="password" name="password" placeholder="Enter Password" required>
    <input type="submit" name="submit" value="Login">
    <div class="remember">
       
<div class="login"><div class="Oroption">OR</div></div>
 <?php if(isset($_GET['message']))
        {
            $message = trim($_GET['message']);
echo "<div> $message </div> ";
        }
        ?>

<div class="links">
    <div class="facebook"><img src="images/facebook.png" alt="Facebook Icon" /> </div>
    <div class="google"> <img src="images/google.png" alt="Facebook Icon" /> </div>
</div>
<div class="signup"> Don't have account? <a href="#">Signup Now</a> </div>
</div>
</form>
</div>
</body>
</html>