<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['admin_id'])){
    header("Location: admin_login.php");
    exit;
}

// Update booking status
if(isset($_GET['confirm'])){
    $id = $_GET['confirm'];
    mysqli_query($conn, "UPDATE bookings SET status='confirmed' WHERE id=$id");
    header("Location: manage_bookings.php");
    exit;
}
if(isset($_GET['cancel'])){
    $id = $_GET['cancel'];
    mysqli_query($conn, "UPDATE bookings SET status='cancelled' WHERE id=$id");
    header("Location: manage_bookings.php");
    exit;
}

$query = "
    SELECT bookings.*, users.fullname, cars.car_name
    FROM bookings
    JOIN users ON bookings.user_id = users.id
    JOIN cars ON bookings.car_id = cars.id
    ORDER BY bookings.id DESC
";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Bookings | Admin Panel</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="admin_style.css">

<style>
  body {
    background: #f4f6f9;
  }
  .main-content {
    margin-left: 260px;
    padding: 30px;
  }
  .table-container {
    background: white;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
  }
  .btn-action {
    margin: 2px;
  }
  .logout-btn {
    background: #dc3545;
    border: none;
    width: 100%;
    margin-top: 20px;
  }
  @media (max-width: 992px) {
    .sidebar {
      position: relative;
      width: 100%;
      height: auto;
    }
    .main-content {
      margin-left: 0;
    }
  }
</style>
</head>
<body>

<!-- Sidebar -->
<?php  include('sidebar.php') ?>

<!-- Main Content -->
<div class="main-content">
  <h3 class="fw-bold mb-4">📅 Manage Bookings</h3>

  <div class="table-container">
    <div class="table-responsive">
      <table class="table table-striped table-bordered align-middle text-center">
        <thead class="table-dark">
          <tr>
            <th>ID</th>
            <th>User</th>
            <th>Car</th>
            <th>From</th>
            <th>To</th>
            <th>Total</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php while($row = mysqli_fetch_assoc($result)): ?>
          <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['fullname']) ?></td>
            <td><?= htmlspecialchars($row['car_name']) ?></td>
            <td><?= $row['start_date'] ?></td>
            <td><?= $row['end_date'] ?></td>
            <td>₹<?= $row['total_price'] ?></td>
            <td>
              <?php if($row['status']=='pending'): ?>
                <span class="badge bg-warning text-dark"><?= $row['status'] ?></span>
              <?php elseif($row['status']=='confirmed'): ?>
                <span class="badge bg-success"><?= $row['status'] ?></span>
              <?php else: ?>
                <span class="badge bg-secondary"><?= $row['status'] ?></span>
              <?php endif; ?>
            </td>
            <td>
              <?php if($row['status']=='pending'): ?>
                <a href="?confirm=<?= $row['id'] ?>" class="btn btn-sm btn-success btn-action">✅ Confirm</a>
                <a href="?cancel=<?= $row['id'] ?>" class="btn btn-sm btn-danger btn-action">❌ Cancel</a>
              <?php else: ?>
                <?= ucfirst($row['status']) ?>
              <?php endif; ?>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
