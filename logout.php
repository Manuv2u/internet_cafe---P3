<?php
session_start();

// Optional: save logout time to database
/*
include 'db_connection.php';
$user_id = $_SESSION['user_id'];
$logout_time = date("Y-m-d H:i:s");
mysqli_query($conn, "UPDATE users SET last_logout='$logout_time' WHERE id='$user_id'");
*/

session_unset();        // Remove all session variables
session_destroy();      // Destroy the session

// Redirect to login page
header("Location: login.php?message=logout");
exit();
?>