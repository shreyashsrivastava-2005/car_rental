<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch current user data
$user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='$user_id'"));

// Update profile
if(isset($_POST['update'])){
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    
    // Handle image upload
    if(!empty($_FILES['profile_pic']['name'])){
        $filename = time() . "_" . basename($_FILES['profile_pic']['name']);
        $target = "../images/" . $filename;
        move_uploaded_file($_FILES['profile_pic']['tmp_name'], $target);
        $profile_pic = $filename;
    } else {
        $profile_pic = $user['profile_pic'];
    }

    $update = mysqli_query($conn, "UPDATE users 
        SET fullname='$fullname', email='$email', phone='$phone', address='$address', profile_pic='$profile_pic'
        WHERE id='$user_id'");

    if($update){
        $_SESSION['user_name'] = $fullname;
        $msg = "✅ Profile updated successfully!";
        // Refresh data
        $user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='$user_id'"));
    } else {
        $msg = "❌ Update failed. Try again!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow-lg mx-auto" style="max-width: 600px;">
        <div class="card-header bg-primary text-white text-center">
            <h4>Edit Profile</h4>
        </div>
        <div class="card-body">
            <?php if(isset($msg)) echo "<div class='alert alert-info'>$msg</div>"; ?>

            <form method="POST" enctype="multipart/form-data">
                <div class="text-center mb-3">
                    <img src="../images/<?= $user['profile_pic'] ?: 'default.png' ?>" 
                         class="rounded-circle" width="120" height="120">
                </div>

                <div class="mb-3">
                    <label>Full Name</label>
                    <input type="text" name="fullname" value="<?= $user['fullname'] ?>" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" value="<?= $user['email'] ?>" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Phone</label>
                    <input type="text" name="phone" value="<?= $user['phone'] ?>" class="form-control">
                </div>

                <div class="mb-3">
                    <label>Address</label>
                    <textarea name="address" class="form-control"><?= $user['address'] ?></textarea>
                </div>

                <div class="mb-3">
                    <label>Profile Picture</label>
                    <input type="file" name="profile_pic" class="form-control">
                </div>

                <button type="submit" name="update" class="btn btn-success w-100">Update Profile</button>
            </form>
        </div>
        <div class="card-footer text-center">
            <a href="profile.php" class="btn btn-secondary btn-sm">⬅ Back to Profile</a>
        </div>
    </div>
</div>

</body>
</html>
