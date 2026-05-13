<?php
session_start();
include 'db.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

/* ADMIN CHECK */
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    die("Access Denied");
}

/* GET ALL RIDES */
$sql = "
SELECT rides.*, users.name AS customer_name
FROM rides
LEFT JOIN users ON rides.user_id = users.id
ORDER BY rides.id DESC
";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
<title>All Rides</title>

<style>

body{
    margin:0;
    font-family:Arial;
    background:#f4f4f4;
}

.container{
    width:90%;
    margin:30px auto;
}

h2{
    text-align:center;
    color:#1976d2;
}

table{
    width:100%;
    border-collapse:collapse;
    background:white;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
}

table th, table td{
    padding:12px;
    border:1px solid #ddd;
    text-align:center;
}

table th{
    background:#1976d2;
    color:white;
}

.back{
    display:inline-block;
    margin-top:20px;
    padding:10px 15px;
    background:#555;
    color:white;
    text-decoration:none;
    border-radius:8px;
}

.status{
    font-weight:bold;
}

</style>

</head>

<body>

<div class="container">

<h2>📋 All Rides</h2>

<table>

<tr>
    <th>ID</th>
    <th>Customer</th>
    <th>Pickup</th>
    <th>Destination</th>
    <th>Fare</th>
    <th>Status</th>
</tr>

<?php while($ride = mysqli_fetch_assoc($result)) { ?>

<tr>

    <td><?php echo $ride['id']; ?></td>

    <td><?php echo $ride['customer_name']; ?></td>

    <td><?php echo $ride['pickup']; ?></td>

    <td><?php echo $ride['destination']; ?></td>

    <td><?php echo $ride['fare']; ?> Tk</td>

    <td class="status"><?php echo $ride['status']; ?></td>

</tr>

<?php } ?>

</table>

<a class="back" href="admin_dashboard.php">
⬅ Back to Dashboard
</a>

</div>

</body>
</html>