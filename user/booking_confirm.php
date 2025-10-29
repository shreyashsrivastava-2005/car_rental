<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .confirmation-card {
            max-width: 500px;
            margin: 100px auto;
            padding: 30px;
            border-radius: 10px;
            background-color: #fff;
            box-shadow: 0px 0px 15px rgba(0,0,0,0.1);
            text-align: center;
        }
        .confirmation-card h2 {
            color: #198754; /* Bootstrap success color */
            margin-bottom: 20px;
        }
        .confirmation-card p {
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

<div class="confirmation-card">
    <h2>✅ Booking Confirmed!</h2>
    <p>Your booking request has been submitted successfully.</p>
    <p>You can check your booking status in your <a href="profile.php">Profile</a>.</p>
    <a href="profile.php" class="btn btn-primary mt-3">← Back to Cars</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
