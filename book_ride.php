<?php
session_start();
include 'db.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

/* LOGIN CHECK */
if (!isset($_SESSION['user_id'])) {
    die("Login required");
}

/* LOCATIONS */
$locations = ["Uttara","Badda","Mirpur","Dhanmondi","Gulshan","Banani","Mohakhali"];

/* READ INPUTS */
$pickup = $_POST['pickup'] ?? '';
$destination = $_POST['destination'] ?? '';
$voucher = strtoupper(trim($_POST['voucher'] ?? ''));

$fare = 0;
$msg = "";

/* DISTANCE FUNCTION */
function dist($a,$b,$loc){
    $x = array_search($a,$loc);
    $y = array_search($b,$loc);

    if($x === false || $y === false) return 0;

    return abs($x - $y);
}

/* AUTO FARE CALC (LIVE ON PAGE LOAD) */
if ($pickup && $destination && $pickup != $destination) {
    $fare = dist($pickup, $destination, $locations) * 50;
}

/* BOOK RIDE */
if (isset($_POST['book'])) {

    $user_id = $_SESSION['user_id'];

    if (empty($pickup) || empty($destination)) {
        $msg = "❌ Select pickup and destination";
    }
    elseif ($pickup == $destination) {
        $msg = "❌ Pickup and destination cannot be same";
    }
    else {

        $fare = dist($pickup, $destination, $locations) * 50;

        /* VOUCHER */
        if (!empty($voucher)) {

            $res = mysqli_query($conn,"
                SELECT * FROM vouchers
                WHERE code='$voucher' AND status='active'
            ");

            if ($res && mysqli_num_rows($res) == 1) {

                $v = mysqli_fetch_assoc($res);
                $discount = $v['discount'];

                $fare = $fare - ($fare * $discount / 100);

            } else {
                $msg = "❌ Invalid voucher code";
            }
        }

        /* INSERT RIDE */
        if (!$msg) {

            $sql = "INSERT INTO rides
                    (user_id, driver_id, pickup, destination, fare, status)
                    VALUES
                    ('$user_id', NULL, '$pickup', '$destination', '$fare', 'pending')";

            if (mysqli_query($conn, $sql)) {

                $_SESSION['success_msg'] = "🚖 Ride Booked Successfully!";
                header("Location: book_ride.php");
                exit();

            } else {
                $msg = "SQL Error: " . mysqli_error($conn);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Book Ride</title>

<style>
body{
    margin:0;
    font-family:Arial;
    background: linear-gradient(135deg,#e3f2fd,#bbdefb);
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
}

.card{
    background:white;
    width:380px;
    padding:30px;
    border-radius:15px;
    box-shadow:0 10px 25px rgba(0,0,0,0.2);
    text-align:center;
}

h2{ color:#1976d2; }

select,input,button{
    width:100%;
    padding:12px;
    margin:8px 0;
    border-radius:8px;
    border:1px solid #ccc;
    box-sizing:border-box;
}

button{
    background:#1976d2;
    color:white;
    border:none;
    font-weight:bold;
    cursor:pointer;
}

.msg-success{
    background:green;
    color:white;
    padding:10px;
    border-radius:8px;
}

.msg-error{
    background:red;
    color:white;
    padding:10px;
    border-radius:8px;
}
</style>

</head>

<body>

<div class="card">

<h2>🚖 Book Ride</h2>

<?php
if (isset($_SESSION['success_msg'])) {
    echo "<div class='msg-success'>".$_SESSION['success_msg']."</div>";
    unset($_SESSION['success_msg']);
}
?>

<?php if (!empty($msg)) { ?>
<div class="msg-error"><?= $msg ?></div>
<?php } ?>

<form method="POST">

<!-- PICKUP -->
<select name="pickup" onchange="this.form.submit()" required>
    <option value="">Select Pickup</option>
    <?php foreach($locations as $l) { ?>
        <option value="<?= $l ?>" <?= ($pickup==$l)?'selected':'' ?>>
            <?= $l ?>
        </option>
    <?php } ?>
</select>

<!-- DESTINATION -->
<select name="destination" onchange="this.form.submit()" required>
    <option value="">Select Destination</option>
    <?php foreach($locations as $l) { ?>
        <option value="<?= $l ?>" <?= ($destination==$l)?'selected':'' ?>>
            <?= $l ?>
        </option>
    <?php } ?>
</select>

<!-- VOUCHER -->
<input type="text" name="voucher" value="<?= htmlspecialchars($voucher) ?>" placeholder="Voucher Code (optional)">

<!-- FARE -->
<input type="text" value="<?= $fare ?> Tk" readonly>

<!-- BOOK -->
<button type="submit" name="book">Book Ride</button>

</form>

<br>

<a href="dashboard.php" style="
    display:inline-block;
    margin-top:10px;
    padding:10px 15px;
    background:#555;
    color:white;
    text-decoration:none;
    border-radius:8px;
    font-weight:bold;
">
⬅ Back to Dashboard
</a>

</div>

</body>
</html>