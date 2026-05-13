<?php
session_start();
include 'db.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

/* ADMIN CHECK */
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    die("Access Denied");
}

/* APPROVE DRIVER */
if (isset($_GET['approve'])) {

    $id = (int)$_GET['approve'];

    mysqli_query($conn,"
        UPDATE drivers 
        SET status='approved'
        WHERE id=$id
    ");

    header("Location: admin_drivers.php");
    exit();
}

/* REJECT DRIVER */
if (isset($_GET['reject'])) {

    $id = (int)$_GET['reject'];

    mysqli_query($conn,"
        UPDATE drivers 
        SET status='rejected'
        WHERE id=$id
    ");

    header("Location: admin_drivers.php");
    exit();
}

/* FETCH DRIVERS */
$result = mysqli_query($conn,"SELECT * FROM drivers ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
<title>Driver Approval Panel</title>

<style>
body{
    font-family:Arial;
    background:#f5f5f5;
}

.container{
    width:90%;
    margin:auto;
    background:white;
    padding:20px;
    margin-top:30px;
    border-radius:10px;
}

h2{
    text-align:center;
    color:#1976d2;
}

table{
    width:100%;
    border-collapse:collapse;
    margin-top:20px;
}

th,td{
    border:1px solid #ddd;
    padding:10px;
    text-align:center;
}

th{
    background:#1976d2;
    color:white;
}

.approve{
    background:green;
    color:white;
    padding:6px 10px;
    text-decoration:none;
    border-radius:5px;
}

.reject{
    background:red;
    color:white;
    padding:6px 10px;
    text-decoration:none;
    border-radius:5px;
}

.status-pending{ color:orange; font-weight:bold; }
.status-approved{ color:green; font-weight:bold; }
.status-rejected{ color:red; font-weight:bold; }
</style>

</head>

<body>

<div class="container">

<h2>🚖 Driver Approval Panel</h2>

<table>

<tr>
<th>ID</th>
<th>Name</th>
<th>Email</th>
<th>Phone</th>
<th>Vehicle</th>
<th>License</th>
<th>Status</th>
<th>Action</th>
</tr>

<?php while($row = mysqli_fetch_assoc($result)) { ?>

<tr>
<td><?= $row['id'] ?></td>
<td><?= $row['name'] ?></td>
<td><?= $row['email'] ?></td>
<td><?= $row['phone'] ?></td>
<td><?= $row['vehicle'] ?></td>
<td><?= $row['license'] ?></td>

<td class="status-<?= $row['status'] ?>">
    <?= $row['status'] ?>
</td>

<td>

<?php if ($row['status'] == 'pending') { ?>

<a class="approve" href="?approve=<?= $row['id'] ?>">Approve</a>
<a class="reject" href="?reject=<?= $row['id'] ?>">Reject</a>

<?php } else { ?>
—
<?php } ?>

</td>

</tr>

<?php } ?>

</table>

<br>
<a href="admin_dashboard.php">⬅ Back to Dashboard</a>

</div>

</body>
</html>