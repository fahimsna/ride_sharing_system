<?php
$conn = mysqli_connect("localhost", "root", "", "ride_sharing");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>