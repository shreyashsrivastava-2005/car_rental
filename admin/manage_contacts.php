<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['admin_id'])){
    header("Location: admin_login.php");
    exit;
}

$result = mysqli_query($conn, "SELECT * FROM contact ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Contacts | Admin Panel</title>
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
<?php include("sidebar.php") ?>

<!-- Main Content -->
<div class="main-content">
  <h3 class="fw-bold mb-4">📨 Manage Contacts</h3>

  <div class="table-container">
    <div class="table-responsive">
      <table class="table table-striped table-bordered align-middle text-center">
        <thead class="table-dark">
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Message</th>
            <th>Date</th>
          </tr>
        </thead>
        <tbody>
          <?php while($row = mysqli_fetch_assoc($result)): ?>
          <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= htmlspecialchars($row['message']) ?></td>
            <td><?= $row['date_sent'] ?></td>
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
