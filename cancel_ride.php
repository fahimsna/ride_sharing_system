<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    die("Login required");
}

$ride_id = (int) $_GET['ride_id'];
$user_id = (int) $_SESSION['user_id'];

/* CANCEL ONLY IF PENDING AND OWN RIDE */
mysqli_query($conn, "
    UPDATE rides 
    SET status='cancelled'
    WHERE id=$ride_id 
    AND user_id=$user_id 
    AND status='pending'
");

header("Location: my_rides.php");
exit();
?>