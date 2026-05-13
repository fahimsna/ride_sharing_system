<?php
session_start();
include 'db.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

$msg = "";

/* REGISTER */
if (isset($_POST['register'])) {

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // DEFAULT ROLE FIX
    $role = "user";

    // CHECK EMAIL EXISTS
    $check = mysqli_query($conn, "SELECT id FROM users WHERE email='$email'");

    if (mysqli_num_rows($check) > 0) {

        $msg = "❌ Email already exists!";

    } else {

        $sql = "INSERT INTO users (name, email, password, role)
                VALUES ('$name', '$email', '$password', '$role')";

        if (mysqli_query($conn, $sql)) {
            $msg = "✅ Registration successful! You can login now.";
        } else {
            $msg = "❌ Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Register</title>

<style>
body {
    margin: 0;
    font-family: Arial;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;

    background: url('/ride_sharing_system/images/background.jpg') no-repeat center center/cover;
}

.overlay {
    position: absolute;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
}

.box {
    position: relative;
    z-index: 1;
    background: white;
    padding: 25px;
    width: 320px;
    border-radius: 10px;
    text-align: center;
    box-shadow: 0 10px 25px rgba(0,0,0,0.3);
}

input {
    width: 100%;
    padding: 10px;
    margin: 8px 0;
    border-radius: 6px;
    border: 1px solid #ccc;
}

button {
    width: 100%;
    padding: 10px;
    background: #1976d2;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
}

button:hover {
    background: #0d47a1;
}

.msg {
    margin: 10px 0;
    font-weight: bold;
}
</style>

</head>

<body>

<div class="overlay"></div>

<div class="box">

<h2>Register</h2>

<?php if (!empty($msg)) { ?>
    <div class="msg"><?php echo $msg; ?></div>
<?php } ?>

<form method="POST">

<input type="text" name="name" placeholder="Full Name" required>

<input type="email" name="email" placeholder="Email" required>

<input type="password" name="password" placeholder="Password" required>

<button type="submit" name="register">Register</button>

</form>

<p>
Already have account? <a href="login.php">Login</a>
</p>

</div>

</body>
</html>
