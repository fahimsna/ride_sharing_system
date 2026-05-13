<?php
session_start();
include 'db.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

$msg = "";

/* LOGIN */
if (isset($_POST['login'])) {

    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM drivers WHERE email='$email'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) == 1) {

        $driver = mysqli_fetch_assoc($result);

        /* PASSWORD CHECK */
        if (password_verify($password, $driver['password'])) {

            /* STATUS CHECK */
            if ($driver['status'] != 'approved') {
                $msg = "❌ Your account is not approved yet!";
            } else {

                /* SESSION SET */
                $_SESSION['driver_id'] = $driver['id'];
                $_SESSION['driver_name'] = $driver['name'];
                $_SESSION['driver_email'] = $driver['email'];

                header("Location: driver_dashboard.php");
                exit();
            }

        } else {
            $msg = "❌ Wrong password!";
        }

    } else {
        $msg = "❌ Driver not found!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Driver Login</title>

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
    width:350px;
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
    color:red;
    font-weight:bold;
}
</style>

</head>

<body>

<div class="overlay"></div>

<div class="card">

<h2>🚖 Driver Login</h2>

<?php if (!empty($msg)) { ?>
    <div class="msg"><?php echo $msg; ?></div>
<?php } ?>

<form method="POST">

<input type="email" name="email" placeholder="Email" required>

<input type="password" name="password" placeholder="Password" required>

<button type="submit" name="login">Login</button>

</form>

<p><a href="driver_register.php">Register as Driver</a></p>

</div>

</body>
</html>
