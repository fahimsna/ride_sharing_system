<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

/* LOGIN CHECK */
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

/* ROLE CHECK (IMPORTANT FIX) */
if ($_SESSION['role'] == 'admin') {
    header("Location: admin_dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Customer Dashboard</title>

<style>
body {
    margin: 0;
    font-family: Arial;
    height: 100vh;

    background: url('/ride_sharing_system/images/background.jpg') no-repeat center center/cover;
}

/* dark overlay */
.overlay {
    position: fixed;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
}

/* box */
.box {
    position: relative;
    z-index: 1;
    width: 350px;
    margin: auto;
    top: 18%;
    background: white;
    padding: 25px;
    border-radius: 12px;
    text-align: center;
    box-shadow: 0 10px 25px rgba(0,0,0,0.3);
}

/* buttons */
a {
    display: block;
    margin: 10px 0;
    padding: 12px;
    background: #1976d2;
    color: white;
    text-decoration: none;
    border-radius: 8px;
}

a:hover {
    background: #0d47a1;
}

/* logout */
.logout {
    background: red;
}
</style>

</head>

<body>

<div class="overlay"></div>

<div class="box">

<h2>Welcome, <?php echo $_SESSION['name']; ?></h2>
<a href="book_ride.php">🚖 Book Ride</a>

<a href="my_rides.php">📋 My Rides</a>

<a href="driver_register.php">🚗 Register as Driver</a>

<a href="driver_login.php">🛵 Driver Login</a>
<a class="logout" href="logout.php">🚪 Logout</a>

</div>

</body>
</html>
