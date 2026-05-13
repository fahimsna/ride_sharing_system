<?php
include 'db.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

$msg = "";

/* REGISTER DRIVER */
if (isset($_POST['register'])) {

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $vehicle = mysqli_real_escape_string($conn, $_POST['vehicle']);
    $license = mysqli_real_escape_string($conn, $_POST['license']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // CHECK DUPLICATE EMAIL
    $check = mysqli_query($conn, "SELECT id FROM drivers WHERE email='$email'");

    if (mysqli_num_rows($check) > 0) {

        $msg = "❌ Email already registered as driver!";

    } else {

        $sql = "INSERT INTO drivers 
        (name, email, phone, vehicle, license, password, status)
        VALUES 
        ('$name','$email','$phone','$vehicle','$license','$password','pending')";

        if (mysqli_query($conn, $sql)) {
            $msg = "✅ Registration successful! Wait for admin approval.";
        } else {
            $msg = "❌ Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Driver Register</title>

<style>
body{
    margin:0;
    font-family:Arial;
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;

    background: url('/ride_sharing_system/images/background.jpg') no-repeat center center/cover;
}

.overlay{
    position:fixed;
    width:100%;
    height:100%;
    background:rgba(0,0,0,0.5);
}

.card{
    position:relative;
    z-index:1;
    width:380px;
    background:white;
    padding:25px;
    border-radius:12px;
    text-align:center;
    box-shadow:0 10px 25px rgba(0,0,0,0.3);
}

input,button{
    width:100%;
    padding:10px;
    margin:8px 0;
    border-radius:8px;
    border:1px solid #ccc;
}

button{
    background:#1976d2;
    color:white;
    border:none;
    font-weight:bold;
    cursor:pointer;
}

.msg{
    font-weight:bold;
    margin:10px 0;
    color:green;
}
</style>

</head>

<body>

<div class="overlay"></div>

<div class="card">

<h2>🚖 Driver Register</h2>

<?php if (!empty($msg)) { ?>
    <div class="msg"><?php echo $msg; ?></div>
<?php } ?>

<form method="POST">

<input type="text" name="name" placeholder="Full Name" required>

<input type="email" name="email" placeholder="Email" required>

<input type="text" name="phone" placeholder="Phone Number" required>

<input type="text" name="vehicle" placeholder="Vehicle (Car/Bike)" required>

<input type="text" name="license" placeholder="License Number" required>

<input type="password" name="password" placeholder="Password" required>

<button type="submit" name="register">Register as Driver</button>

</form>

</div>

</body>
</html>
