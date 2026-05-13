<?php
session_start();
include 'db.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

/* LOGIN CHECK */
if (!isset($_SESSION['driver_id'])) {
    header("Location: driver_login.php");
    exit();
}

$driver_id = (int) $_SESSION['driver_id'];

/* =========================
   ACCEPT RIDE
========================= */
if (isset($_GET['accept'])) {

    $ride_id = (int) $_GET['accept'];

    mysqli_query($conn, "
        UPDATE rides
        SET status='accepted',
            driver_id=$driver_id
        WHERE id=$ride_id
        AND status='pending'
        AND driver_id IS NULL
    ");

    header("Location: driver_dashboard.php");
    exit();
}

/* =========================
   REJECT RIDE
========================= */
if (isset($_GET['reject'])) {

    $ride_id = (int) $_GET['reject'];

    mysqli_query($conn, "
        UPDATE rides
        SET status='cancelled'
        WHERE id=$ride_id
        AND status='pending'
        AND driver_id IS NULL
    ");

    header("Location: driver_dashboard.php");
    exit();
}

/* =========================
   RIDES LIST
   - assigned to this driver
   - OR unassigned pending rides
========================= */
$rides = mysqli_query($conn, "
    SELECT * FROM rides
    WHERE driver_id=$driver_id
    OR (driver_id IS NULL AND status='pending')
    ORDER BY id DESC
");

/* =========================
   BONUS
========================= */
$bonus_res = mysqli_query($conn, "
    SELECT bonus FROM drivers WHERE id=$driver_id
");

$bonus_row = mysqli_fetch_assoc($bonus_res);
$bonus = $bonus_row['bonus'] ?? 0;

/* =========================
   REVIEWS
========================= */
$reviews = mysqli_query($conn, "
    SELECT * FROM reviews
    WHERE driver_id=$driver_id
    ORDER BY id DESC
");

/* =========================
   AVG RATING
========================= */
$avg = mysqli_query($conn, "
    SELECT AVG(rating) as avg_rating
    FROM reviews
    WHERE driver_id=$driver_id
");

$avg_row = mysqli_fetch_assoc($avg);
$avg_rating = $avg_row['avg_rating'];

if ($avg_rating === null) {
    $avg_rating = 0;
} else {
    $avg_rating = round($avg_rating, 1);
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Driver Dashboard</title>

<style>
body{
    font-family: Arial;
    background: #f5f5f5;
}

.container{
    width: 90%;
    margin: auto;
    background: white;
    padding: 20px;
    margin-top: 30px;
    border-radius: 10px;
}

h2{
    text-align: center;
    color: #1976d2;
}

.card{
    background: #e3f2fd;
    padding: 10px;
    margin: 10px 0;
    border-radius: 8px;
}

table{
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

th, td{
    border: 1px solid #ddd;
    padding: 10px;
    text-align: center;
}

th{
    background: #1976d2;
    color: white;
}

.accept{
    background: green;
    color: white;
    padding: 5px 10px;
    text-decoration: none;
    border-radius: 5px;
}

.reject{
    background: red;
    color: white;
    padding: 5px 10px;
    text-decoration: none;
    border-radius: 5px;
}

.status-pending{ color: orange; font-weight: bold; }
.status-accepted{ color: green; font-weight: bold; }
.status-cancelled{ color: red; font-weight: bold; }

.review-box{
    border: 1px solid #ddd;
    padding: 10px;
    margin: 8px 0;
    border-radius: 6px;
}
</style>

</head>

<body>

<div class="container">

<h2>🚖 Driver Dashboard</h2>

<p>Welcome, <b><?= $_SESSION['driver_name'] ?></b></p>

<!-- STATS -->
<div class="card">
    💰 Bonus Earned: <b><?= $bonus ?> Tk</b><br>
    ⭐ Average Rating: <b><?= $avg_rating ?></b> / 5
</div>

<!-- RIDES -->
<h3>🚗 Available & My Rides</h3>

<table>

<tr>
<th>ID</th>
<th>Pickup</th>
<th>Destination</th>
<th>Fare</th>
<th>Status</th>
<th>Action</th>
</tr>

<?php if (mysqli_num_rows($rides) == 0) { ?>
<tr>
<td colspan="6">No rides available</td>
</tr>
<?php } ?>

<?php while($row = mysqli_fetch_assoc($rides)) { ?>

<tr>

<td><?= $row['id'] ?></td>
<td><?= $row['pickup'] ?></td>
<td><?= $row['destination'] ?></td>
<td><?= $row['fare'] ?> Tk</td>

<td class="status-<?= $row['status'] ?>">
    <?= $row['status'] ?>
</td>

<td>

<?php if ($row['status'] == 'pending' && $row['driver_id'] == NULL) { ?>

<a class="accept" href="?accept=<?= $row['id'] ?>">Accept</a>
<a class="reject" href="?reject=<?= $row['id'] ?>">Reject</a>

<?php } else { ?>
—
<?php } ?>

</td>

</tr>

<?php } ?>

</table>

<!-- REVIEWS -->
<h3>⭐ Customer Reviews</h3>

<?php if (mysqli_num_rows($reviews) == 0) { ?>
<p>No reviews yet</p>
<?php } ?>

<?php while($r = mysqli_fetch_assoc($reviews)) { ?>

<div class="review-box">
    ⭐ Rating: <?= $r['rating'] ?><br>
    💬 <?= $r['comment'] ?>
</div>

<?php } ?>

<br>

<a href="logout.php">Logout</a>

</div>

</body>
</html>