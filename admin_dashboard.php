<?php
session_start();
include 'db.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

/* ADMIN CHECK */
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    die("Access Denied");
}

/* SAFE COUNTS */
$users = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM users"));

$drivers = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM drivers"));

$pending_drivers = mysqli_num_rows(mysqli_query(
    $conn,
    "SELECT id FROM drivers WHERE status='pending'"
));

$rides = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM rides"));
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Dashboard</title>

<style>
body {
    margin: 0;
    font-family: Arial;
    background: url('/ride_sharing_system/images/background.jpg') no-repeat center center/cover;
}

/* overlay */
.overlay {
    position: fixed;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.6);
}

/* container */
.container {
    position: relative;
    z-index: 1;
    padding: 20px;
    color: white;
}

/* header */
.header {
    text-align: center;
    margin-top: 20px;
}

/* cards */
.cards {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 15px;
    margin-top: 30px;
}

.card {
    background: white;
    color: black;
    padding: 20px;
    border-radius: 10px;
    text-align: center;
    box-shadow: 0 5px 15px rgba(0,0,0,0.3);
}

.card h2 {
    margin: 0;
    color: #1976d2;
}

/* menu */
.menu {
    margin-top: 30px;
    text-align: center;
}

.menu a {
    display: inline-block;
    margin: 5px;
    padding: 12px 18px;
    background: #1976d2;
    color: white;
    text-decoration: none;
    border-radius: 8px;
}

.menu a:hover {
    background: #0d47a1;
}

.logout {
    background: red !important;
}
</style>

</head>

<body>

<div class="overlay"></div>

<div class="container">

<div class="header">
    <h1>🧑‍💼 Admin Dashboard</h1>
    <p>Welcome, <?php echo $_SESSION['name']; ?></p>
</div>

<!-- STATS -->
<div class="cards">

    <div class="card">
        <h2>Users</h2>
        <p><?php echo $users; ?></p>
    </div>

    <div class="card">
        <h2>Drivers</h2>
        <p><?php echo $drivers; ?></p>
    </div>

    <div class="card">
        <h2>Pending Drivers</h2>
        <p><?php echo $pending_drivers; ?></p>
    </div>

    <div class="card">
        <h2>Total Rides</h2>
        <p><?php echo $rides; ?></p>
    </div>

</div>

<!-- MENU -->
<div class="menu">

    <a href="admin_drivers.php">🚖 Driver Approval</a>
    <a href="admin_voucher.php">🎁 Vouchers</a>
    <a href="admin_rides.php">📋 All Rides</a>
    <a href="driver_register.php">🚗 Driver Register</a>

    <a class="logout" href="logout.php">🚪 Logout</a>

</div>

</div>

</body>
</html>
