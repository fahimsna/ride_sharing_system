<?php
session_start();
session_destroy();
header("Location: /ride_sharing_system/login.php");
exit();
?>