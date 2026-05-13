<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    die("Login required");
}

/* =========================
   GET VALUES SAFELY
========================= */
$ride_id = isset($_GET['ride_id']) ? (int)$_GET['ride_id'] : 0;
$driver_id = isset($_GET['driver_id']) ? (int)$_GET['driver_id'] : 0;

/* =========================
   BLOCK EMPTY ACCESS (IMPORTANT FIX)
========================= */
if ($ride_id == 0 || $driver_id == 0) {
    die("Invalid review request. Missing ride or driver ID.");
}

/* =========================
   INSERT REVIEW (YOUR ORIGINAL LOGIC)
========================= */
if (isset($_POST['submit_review'])) {

    $ride_id = (int) $_POST['ride_id'];
    $driver_id = (int) $_POST['driver_id'];
    $user_id = $_SESSION['user_id'];
    $rating = (int) $_POST['rating'];
    $comment = mysqli_real_escape_string($conn, $_POST['comment']);

    // CHECK IF ALREADY REVIEWED
    $check = mysqli_query($conn, "
        SELECT id FROM reviews 
        WHERE ride_id=$ride_id AND user_id=$user_id
    ");

    if (mysqli_num_rows($check) == 0) {

        // INSERT REVIEW
        mysqli_query($conn, "
            INSERT INTO reviews (ride_id, driver_id, user_id, rating, comment)
            VALUES ($ride_id, $driver_id, $user_id, $rating, '$comment')
        ");

        // BONUS SYSTEM (UNCHANGED)
        if ($rating == 5) {
            mysqli_query($conn, "UPDATE drivers SET bonus = bonus + 50 WHERE id=$driver_id");
        } elseif ($rating == 4) {
            mysqli_query($conn, "UPDATE drivers SET bonus = bonus + 20 WHERE id=$driver_id");
        } elseif ($rating == 3) {
            mysqli_query($conn, "UPDATE drivers SET bonus = bonus + 10 WHERE id=$driver_id");
        }
    }

    header("Location: my_rides.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Write Review</title>

<style>
body{
    font-family: Arial;
    background: #f5f5f5;
}

.container{
    width: 400px;
    margin: 80px auto;
    background: white;
    padding: 20px;
    border-radius: 10px;
    text-align: center;
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

input, select, textarea{
    width: 100%;
    padding: 10px;
    margin: 10px 0;
}

button{
    width: 100%;
    padding: 10px;
    background: #1976d2;
    color: white;
    border: none;
    border-radius: 5px;
}

button:hover{
    background: #0d47a1;
}
</style>

</head>

<body>

<div class="container">

<h2>⭐ Write Review</h2>

<!-- REVIEW FORM -->
<form method="POST">

    <input type="hidden" name="ride_id" value="<?= $ride_id ?>">
    <input type="hidden" name="driver_id" value="<?= $driver_id ?>">

    <label>Rating</label>
    <select name="rating" required>
        <option value="5">5 ⭐</option>
        <option value="4">4 ⭐</option>
        <option value="3">3 ⭐</option>
        <option value="2">2 ⭐</option>
        <option value="1">1 ⭐</option>
    </select>

    <textarea name="comment" placeholder="Write your comment..." required></textarea>

    <button type="submit" name="submit_review">Submit Review</button>

</form>

</div>

</body>
</html>