<?php
session_start();

if(isset($_POST['login'])){
    $user = $_POST['username'];
    $pass = $_POST['password'];

    if($user === "admin" && $pass === "12345"){
        $_SESSION['admin'] = $user;
        $_SESSION['role'] = "admin";
        header("Location: index.php");
        exit;
    }

    $error = "Invalid username or password.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Login</title>
<style>
* { margin:0; padding:0; box-sizing:border-box; font-family:'Segoe UI', Arial, sans-serif; }
body, html { height:100%; overflow:hidden; }

/* Background */
body {
    display: flex;
    justify-content: center;
    align-items: center;
    background: #1e3a8a;
    position: relative;
}

/* Floating Circles */
.bg-circle {
    position: absolute;
    border-radius: 50%;
    opacity: 0.25;
    animation: float 6s linear infinite;
    mix-blend-mode: lighten;
    filter: drop-shadow(0 0 20px rgba(255,255,255,0.3));
}
.bg-circle:nth-child(1) { width: 220px; height: 220px; background: radial-gradient(circle, #fdd835, #fbc02d); top: -50px; left: -80px; animation-duration: 6s;}
.bg-circle:nth-child(2) { width: 280px; height: 280px; background: radial-gradient(circle, #29b6f6, #0288d1); top: 80px; right: -100px; animation-duration: 7s;}
.bg-circle:nth-child(3) { width: 180px; height: 180px; background: radial-gradient(circle, #ab47bc, #8e24aa); bottom: 40px; left: 40px; animation-duration: 5.5s;}
.bg-circle:nth-child(4) { width: 140px; height: 140px; background: radial-gradient(circle, #66bb6a, #388e3c); bottom: -50px; right: 60px; animation-duration: 6.5s;}

@keyframes float {
    0% { transform: translateY(0px) translateX(0px) rotate(0deg);}
    50% { transform: translateY(-70px) translateX(60px) rotate(180deg);}
    100% { transform: translateY(0px) translateX(0px) rotate(360deg);}
}

/* Lightning Streaks */
.lightning {
    position: absolute;
    width: 2px;
    height: 150px;
    background: linear-gradient(to bottom, rgba(255,255,255,0.8), transparent);
    opacity: 0.6;
    filter: blur(2px);
    animation: lightningMove 2.5s linear infinite;
}

.lightning:nth-child(5){ left: 20%; animation-delay: 0s;}
.lightning:nth-child(6){ left: 50%; animation-delay: 0.5s;}
.lightning:nth-child(7){ left: 75%; animation-delay: 1s;}

@keyframes lightningMove {
    0% { top: -200px; opacity:0;}
    10% { opacity:1;}
    50% { top: 100%; opacity:0.6;}
    100% { top: 120%; opacity:0;}
}

/* Spark Particles */
.spark {
    position: absolute;
    width: 6px;
    height: 6px;
    background: rgba(255,255,255,0.8);
    border-radius: 50%;
    filter: blur(1px);
    animation: sparkMove linear infinite;
    opacity: 0.6;
}

@keyframes sparkMove {
    0% { transform: translateY(0px) translateX(0px); opacity:0.3;}
    50% { transform: translateY(-120px) translateX(40px); opacity:1;}
    100% { transform: translateY(0px) translateX(0px); opacity:0.3;}
}

/* Login Card */
.login-box {
    position: relative;
    background: rgba(255,255,255,0.95);
    width: 420px;
    max-width: 90%;
    padding: 60px 40px;
    border-radius: 25px;
    box-shadow: 0 25px 60px rgba(0,0,0,0.3);
    text-align: center;
    z-index: 10;
    animation: fadeInUp 0.8s ease forwards;
}

/* Logo Circle */
.login-box .logo {
    width: 100px;
    height: 100px;
    margin: 0 auto 25px auto;
    background: url('../images/church2.png') no-repeat center center/contain;
    border-radius: 50%;
    box-shadow: 0 0 20px rgba(255,255,255,0.3);
    transition: transform 0.3s;
}
.login-box .logo:hover { transform: scale(1.1); }

/* Heading */
h2 { color: #1e3a8a; font-size: 32px; margin-bottom: 30px; letter-spacing: 1px; }

/* Inputs */
input {
    width: 100%;
    padding: 16px 14px;
    margin-bottom: 20px;
    border: 1px solid #ccc;
    border-radius: 12px;
    font-size: 16px;
    transition: all 0.3s;
}
input:focus { border-color: #1e3a8a; box-shadow: 0 0 15px rgba(30,58,138,0.3); outline: none; }

/* Button */
button {
    width: 100%;
    padding: 16px;
    background: #1e3a8a;
    color: white;
    font-size: 18px;
    border: none;
    border-radius: 12px;
    cursor: pointer;
    font-weight: bold;
    transition: all 0.3s;
    box-shadow: 0 5px 25px rgba(30,58,138,0.4);
}
button:hover { background: #163164; transform: translateY(-2px); }

/* Error */
.error {
    color: #d32f2f;
    margin-bottom: 20px;
    font-size: 14px;
    background: #ffe6e6;
    padding: 12px;
    border-radius: 8px;
    text-align: left;
    animation: shake 0.4s;
}

/* Animations */
@keyframes shake { 0%,100% { transform: translateX(0);} 20%,60% { transform: translateX(-5px);} 40%,80% { transform: translateX(5px);} }
@keyframes fadeInUp { from { opacity: 0; transform: translateY(30px);} to { opacity: 1; transform: translateY(0);} }

/* Responsive */
@media(max-width: 500px){
    .login-box { padding: 45px 25px; }
    h2 { font-size: 28px; }
    input, button { font-size: 15px; padding: 14px; }
}
</style>
</head>
<body>

<!-- Floating Background Circles -->
<div class="bg-circle"></div>
<div class="bg-circle"></div>
<div class="bg-circle"></div>
<div class="bg-circle"></div>

<!-- Lightning Streaks -->
<div class="lightning"></div>
<div class="lightning"></div>
<div class="lightning"></div>

<!-- Spark Particles -->
<?php
// Generate 30 spark divs
for($i=0;$i<30;$i++){
    $left = rand(0, 100);
    $delay = rand(0, 5000)/1000; // delay in seconds
    $duration = rand(4000, 8000)/1000; // duration in seconds
    echo "<div class='spark' style='left:{$left}%; animation-delay:{$delay}s; animation-duration:{$duration}s;'></div>";
}
?>

<div class="login-box">
    <div class="logo"></div>
    <h2>Church Admin Login</h2>
    <?php if(isset($error)) echo "<div class='error'>$error</div>"; ?>
    <form method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="login">Login</button>
    </form>
</div>

</body>
</html>
