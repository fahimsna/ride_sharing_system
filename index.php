<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
<title>Ride Sharing System</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    font-family: Arial;
    background:#f5f5f5;
}

/* NAVBAR */
.navbar{
    width:100%;
    background:#1976d2;
    padding:15px 40px;
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.logo{
    color:white;
    font-size:28px;
    font-weight:bold;
}

.nav-links a{
    color:white;
    text-decoration:none;
    margin-left:20px;
    font-size:16px;
}

.nav-links a:hover{
    text-decoration:underline;
}

/* HERO SECTION */
.hero{
    height:90vh;
    background:url('/ride_sharing_system/images/background.jpg') no-repeat center center/cover;
    position:relative;
    display:flex;
    justify-content:center;
    align-items:center;
    text-align:center;
}

.overlay{
    position:absolute;
    width:100%;
    height:100%;
    background:rgba(0,0,0,0.6);
}

.hero-content{
    position:relative;
    z-index:1;
    color:white;
}

.hero-content h1{
    font-size:55px;
    margin-bottom:20px;
}

.hero-content p{
    font-size:22px;
    margin-bottom:30px;
}

.btn{
    display:inline-block;
    padding:12px 20px;
    margin:10px;
    background:#1976d2;
    color:white;
    text-decoration:none;
    border-radius:8px;
    transition:0.3s;
}

.btn:hover{
    background:#0d47a1;
}

/* FEATURES */
.features{
    padding:60px 40px;
    text-align:center;
    background:white;
}

.features h2{
    color:#1976d2;
    margin-bottom:40px;
}

.feature-boxes{
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:20px;
}

.feature{
    background:#f5f5f5;
    padding:30px;
    border-radius:10px;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
}

.feature h3{
    margin-bottom:15px;
    color:#1976d2;
}

/* ABOUT */
.about{
    padding:60px 40px;
    background:#e3f2fd;
    text-align:center;
}

.about h2{
    margin-bottom:20px;
    color:#1976d2;
}

/* FOOTER */
.footer{
    background:#111;
    color:white;
    text-align:center;
    padding:20px;
}

</style>

</head>

<body>

<!-- NAVBAR -->
<div class="navbar">

    <div class="logo">
        🚖 Ride Sharing
    </div>

    <div class="nav-links">
        <a href="index.php">Home</a>
        <a href="login.php">User Login</a>
        <a href="register.php">Register</a>
        <a href="driver_login.php">Driver Login</a>
    </div>

</div>

<!-- HERO SECTION -->
<div class="hero">

    <div class="overlay"></div>

    <div class="hero-content">

        <h1>Welcome To Ride Sharing System</h1>

        <p>
            Fast • Safe • Reliable Transportation
        </p>

        <a class="btn" href="register.php">Get Started</a>

        <a class="btn" href="login.php">Login</a>

    </div>

</div>

<!-- FEATURES -->
<div class="features">

    <h2>Our Features</h2>

    <div class="feature-boxes">

        <div class="feature">
            <h3>🚗 Easy Ride Booking</h3>
            <p>Book rides instantly with simple pickup and destination system.</p>
        </div>

        <div class="feature">
            <h3>⭐ Driver Reviews</h3>
            <p>Customers can rate and review drivers after rides.</p>
        </div>

        <div class="feature">
            <h3>🎁 Bonus System</h3>
            <p>Drivers receive bonus based on customer ratings.</p>
        </div>

    </div>

</div>

<!-- ABOUT -->
<div class="about">

    <h2>Why Choose Us?</h2>

    <p>
        Our Ride Sharing System provides secure and efficient transportation services.
        Customers can easily book rides while drivers can manage rides smoothly through a modern dashboard.
    </p>

</div>

<!-- FOOTER -->
<div class="footer">

    <p>
        © 2026 Ride Sharing System | Developed by Fahim Shahriar Nur
    </p>

</div>

</body>
</html>