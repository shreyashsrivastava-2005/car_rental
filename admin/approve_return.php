<?php
session_start();
include("../config/db.php");

if (isset($_POST['approve'])) {
    $booking_id = (int)$_POST['booking_id'];

    // Fetch car id from booking
    $result = mysqli_query($conn, "SELECT car_id FROM bookings WHERE id='$booking_id'");
    $data = mysqli_fetch_assoc($result);
    $car_id = $data['car_id'];

    // Update booking and car
    mysqli_query($conn, "UPDATE bookings SET return_request='approved', status='returned' WHERE id='$booking_id'");
    mysqli_query($conn, "UPDATE cars SET status='available' WHERE id='$car_id'");

} elseif (isset($_POST['reject'])) {
    $booking_id = (int)$_POST['booking_id'];
    mysqli_query($conn, "UPDATE bookings SET return_request='rejected' WHERE id='$booking_id'");
}

header("Location: return_requests.php");
exit;
?>
