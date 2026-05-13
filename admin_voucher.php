<?php
session_start();
include 'db.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

/* ADMIN CHECK */
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

/* ADD VOUCHER */
if (isset($_POST['add'])) {

    $code = strtoupper(trim(mysqli_real_escape_string($conn, $_POST['code'])));
    $discount = (int) $_POST['discount'];

    if (!empty($code) && $discount > 0) {

        mysqli_query($conn, "
            INSERT INTO vouchers (code, discount, status)
            VALUES ('$code', $discount, 'active')
        ");
    }

    header("Location: admin_voucher.php");
    exit();
}

/* DELETE */
if (isset($_GET['delete'])) {

    $id = (int) $_GET['delete'];

    mysqli_query($conn, "DELETE FROM vouchers WHERE id=$id");

    header("Location: admin_voucher.php");
    exit();
}

/* TOGGLE STATUS */
if (isset($_GET['toggle'])) {

    $id = (int) $_GET['toggle'];

    mysqli_query($conn, "
        UPDATE vouchers 
        SET status = IF(status='active','inactive','active')
        WHERE id=$id
    ");

    header("Location: admin_voucher.php");
    exit();
}

/* FETCH */
$result = mysqli_query($conn, "SELECT * FROM vouchers ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Voucher Panel</title>

<style>
body{
    font-family: Arial;
    margin: 0;
    background: url('/ride_sharing_system/images/background.jpg') no-repeat center center/cover;
}

.overlay{
    position: fixed;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
}

.container{
    position: relative;
    z-index: 1;
    width: 80%;
    margin: 40px auto;
    background: white;
    padding: 20px;
    border-radius: 10px;
}

h2{
    text-align: center;
    color: #1976d2;
}

input, button{
    padding: 10px;
    margin: 5px;
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

a{
    padding: 5px 10px;
    text-decoration: none;
    border-radius: 5px;
    color: white;
}

.del{ background: red; }
.toggle{ background: orange; }

.back{
    display: inline-block;
    margin-top: 15px;
    background: #555;
    padding: 10px;
    border-radius: 8px;
}
</style>

</head>

<body>

<div class="overlay"></div>

<div class="container">

<h2>🎁 Admin Voucher Manager</h2>

<!-- ADD FORM -->
<form method="POST">

<input type="text" name="code" placeholder="Voucher Code" required>
<input type="number" name="discount" placeholder="Discount %" required>

<button type="submit" name="add">Add Voucher</button>

</form>

<!-- TABLE -->
<table>

<tr>
<th>ID</th>
<th>Code</th>
<th>Discount</th>
<th>Status</th>
<th>Action</th>
</tr>

<?php if (mysqli_num_rows($result) == 0) { ?>
<tr>
<td colspan="5">No vouchers found</td>
</tr>
<?php } ?>

<?php while($row = mysqli_fetch_assoc($result)) { ?>

<tr>
<td><?php echo $row['id']; ?></td>
<td><?php echo $row['code']; ?></td>
<td><?php echo $row['discount']; ?>%</td>
<td><?php echo $row['status']; ?></td>
<td>

<a class="toggle" href="?toggle=<?php echo $row['id']; ?>">Toggle</a>
<a class="del" href="?delete=<?php echo $row['id']; ?>" onclick="return confirm('Delete voucher?')">Delete</a>

</td>
</tr>

<?php } ?>

</table>

<a class="back" href="admin_dashboard.php">⬅ Back to Dashboard</a>

</div>

</body>
</html>
