<?php
session_start();
include("config/db.php");

if($_SERVER['REQUEST_METHOD'] == "POST"){
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);
    $created_at = date("Y-m-d H:i:s");

    // Insert into contact table
    $sql = "INSERT INTO contact (name, email, message, date_sent) 
            VALUES ('$name', '$email', '$message', '$created_at')";
    if(mysqli_query($conn, $sql)){
        $_SESSION['success_msg'] = "Your message has been sent successfully!";
    } else {
        $_SESSION['error_msg'] = "Failed to send message. Please try again.";
    }

    // Redirect back to home page
    header("Location: index.php#contact");
    exit;
}
?>
