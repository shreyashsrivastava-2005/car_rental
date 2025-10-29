<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

if (isset($_POST['return_request'])) {
    $booking_id = (int)$_POST['booking_id'];

    // Ensure the booking belongs to the current user
    $check = mysqli_query($conn, "SELECT * FROM bookings WHERE id='$booking_id' AND user_id='$user_id'");
    if (mysqli_num_rows($check) > 0) {
        mysqli_query($conn, "UPDATE bookings SET return_request='pending' WHERE id='$booking_id'");
    }

    header("Location: dashboard.php");
    exit;
}
?>
