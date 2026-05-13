<?php
session_start();
include 'db.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = (int) $_SESSION['user_id'];

/* RIDES WITH DRIVER DETAILS */
$rides = mysqli_query($conn, "
    SELECT r.*,
           d.name AS driver_name,
           d.email AS driver_contact,
           d.vehicle
    FROM rides r
    LEFT JOIN drivers d ON r.driver_id = d.id
    WHERE r.user_id = $user_id
    ORDER BY r.id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>My Rides</title>

<style>
body{
    font-family: Arial;
    background: #f5f5f5;
}

.container{
    width: 95%;
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

.status-pending{ color: orange; font-weight: bold; }
.status-accepted{ color: green; font-weight: bold; }
.status-cancelled{ color: red; font-weight: bold; }

.driver-box{
    text-align: left;
    font-size: 14px;
}
</style>

</head>

<body>

<div class="container">

<h2>📋 My Rides</h2>

<table>

<tr>
    <th>ID</th>
    <th>Pickup</th>
    <th>Destination</th>
    <th>Fare</th>
    <th>Status</th>
    <th>Driver Details</th>
    <th>Action</th>
</tr>

<?php if (mysqli_num_rows($rides) == 0) { ?>
<tr>
    <td colspan="7">No rides found</td>
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

<td class="driver-box">

<?php if (!empty($row['driver_id'])) { ?>

    <b>👤 <?= $row['driver_name'] ?></b><br>
    📧 <?= $row['driver_contact'] ?><br>
    🚗 <?= $row['vehicle'] ?>

    <!-- REVIEW BUTTON -->
    <?php if ($row['status'] == 'accepted') { ?>
        <br><br>
        <a href="review_driver.php?ride_id=<?= $row['id'] ?>&driver_id=<?= $row['driver_id'] ?>"
           style="background:green;color:white;padding:6px 10px;text-decoration:none;border-radius:5px;">
           ⭐ Write Review
        </a>
    <?php } ?>

<?php } else { ?>
    ⏳ Not assigned yet
<?php } ?>

</td>

<!-- ACTION COLUMN -->
<td>

<?php if ($row['status'] == 'pending') { ?>

<a href="cancel_ride.php?ride_id=<?= $row['id'] ?>"
   onclick="return confirm('Cancel this ride?')"
   style="background:red;color:white;padding:6px 10px;text-decoration:none;border-radius:5px;">
   ❌ Cancel
</a>

<?php } else { ?>
—
<?php } ?>

</td>

</tr>

<?php } ?>

</table>

<br>

<a href="dashboard.php" style="
    display:inline-block;
    padding:10px 15px;
    background:#555;
    color:white;
    text-decoration:none;
    border-radius:8px;
">
⬅ Back to Dashboard
</a>

</div>

</body>
</html>