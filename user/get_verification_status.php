<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];

$query = mysqli_query($conn, "SELECT is_verified FROM users WHERE id='$user_id'");
$user = mysqli_fetch_assoc($query);

echo json_encode([
    'is_verified' => (bool)$user['is_verified']
]);
