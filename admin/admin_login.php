<?php
session_start();
include("../config/db.php");

if(isset($_POST['login'])){
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM admins WHERE username='$username' AND password='$password'";
    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result) == 1){
        $row = mysqli_fetch_assoc($result);
        $_SESSION['admin_id'] = $row['id'];
        $_SESSION['admin_user'] = $row['username'];
        header("Location: dashboard.php");
        exit;
    } else {
        $msg = "Invalid Username or Password!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Login | Car Rental</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
      background: linear-gradient(135deg, #2b5876, #4e4376);
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .login-card {
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 5px 25px rgba(0,0,0,0.3);
      padding: 40px 30px;
      width: 100%;
      max-width: 400px;
    }
    .btn-primary {
      background-color: #4e4376;
      border: none;
    }
    .btn-primary:hover {
      background-color: #2b5876;
    }
  </style>
</head>
<body>

  <div class="login-card text-center">
    <h3 class="mb-4 text-dark fw-bold">🔐 Admin Login</h3>

    <?php if(isset($msg)) echo "<div class='alert alert-danger'>$msg</div>"; ?>

    <form method="POST">
      <div class="mb-3">
        <input type="text" name="username" class="form-control" placeholder="Enter Username" required>
      </div>
      <div class="mb-3">
        <input type="password" name="password" class="form-control" placeholder="Enter Password" required>
      </div>
      <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
    </form>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
