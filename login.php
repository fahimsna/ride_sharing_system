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

    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) == 1) {

        $user = mysqli_fetch_assoc($result);

        if (password_verify($password, $user['password'])) {

            // SESSION SET
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['role'] = $user['role'];

            // ROLE BASED REDIRECT
            if ($user['role'] == "admin") {
                header("Location: admin_dashboard.php");
            } else {
                header("Location: dashboard.php");
            }
            exit();

        } else {
            $msg = "❌ Wrong password!";
        }

    } else {
        $msg = "❌ User not found!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Login</title>

<style>
body {
    margin: 0;
    font-family: Arial;
    height: 100vh;

    background: url('/ride_sharing_system/images/background.jpg') no-repeat center center/cover;
    display: flex;
    justify-content: center;
    align-items: center;
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
    width: 320px;
    background: white;
    padding: 25px;
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
    color: red;
    margin-bottom: 10px;
}
</style>

</head>

<body>

<div class="overlay"></div>

<div class="box">

<h2>Login</h2>

<?php if (!empty($msg)) { ?>
    <div class="msg"><?php echo $msg; ?></div>
<?php } ?>

<form method="POST">

<input type="email" name="email" placeholder="Email" required>

<input type="password" name="password" placeholder="Password" required>

<button type="submit" name="login">Login</button>

</form>

<p>
No account? <a href="register.php">Register</a>
</p>

</div>

</body>
</html>
