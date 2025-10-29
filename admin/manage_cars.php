<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['admin_id'])){
    header("Location: admin_login.php");
    exit;
}

// --- ADD CAR ---
if(isset($_POST['add_car'])){
    $car_name = $_POST['car_name'];
    $brand = $_POST['brand'];
    $model = $_POST['model'];
    $price = $_POST['price'];
    $fuel = $_POST['fuel'];
    $seats = $_POST['seats'];

    // Upload image
    $image = $_FILES['image']['name'];
    $target = "../images/" . basename($image);
    move_uploaded_file($_FILES['image']['tmp_name'], $target);

    $sql = "INSERT INTO cars (car_name, brand, model, price_per_day, fuel_type, seats, image)
            VALUES ('$car_name', '$brand', '$model', '$price', '$fuel', '$seats', '$image')";
    mysqli_query($conn, $sql);
}

// --- DELETE CAR ---
if(isset($_GET['del'])){
    $id = $_GET['del'];
    mysqli_query($conn, "DELETE FROM cars WHERE id=$id");
    header("Location: manage_cars.php");
    exit;
}

// Fetch all cars
$cars = mysqli_query($conn, "SELECT * FROM cars ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Cars | Admin Panel</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="admin_style.css">


  <style>
    body {
      background-color: #f4f6f9;
    }
    .main-content {
      margin-left: 260px;
      padding: 30px;
    }
    .card {
      border: none;
      border-radius: 12px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    img.car-img {
      width: 80px;
      border-radius: 5px;
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
  <?php include('sidebar.php')  ?>

  <!-- Main Content -->
  <div class="main-content">
    <h3 class="fw-bold mb-4">🚗 Manage Cars</h3>

    <!-- Add Car Form -->
    <div class="card p-4 mb-5">
      <h5 class="mb-3">➕ Add New Car</h5>
      <form method="POST" enctype="multipart/form-data" class="row g-3">
        <div class="col-md-6">
          <input type="text" name="car_name" class="form-control" placeholder="Car Name" required>
        </div>
        <div class="col-md-6">
          <input type="text" name="brand" class="form-control" placeholder="Brand" required>
        </div>
        <div class="col-md-6">
          <input type="text" name="model" class="form-control" placeholder="Model" required>
        </div>
        <div class="col-md-6">
          <input type="number" step="0.01" name="price" class="form-control" placeholder="Price per day" required>
        </div>
        <div class="col-md-6">
          <input type="text" name="fuel" class="form-control" placeholder="Fuel Type" required>
        </div>
        <div class="col-md-6">
          <input type="number" name="seats" class="form-control" placeholder="Seats" required>
        </div>
        <div class="col-md-12">
          <input type="file" name="image" class="form-control" required>
        </div>
        <div class="col-12 text-end">
          <button type="submit" name="add_car" class="btn btn-success">Add Car</button>
        </div>
      </form>
    </div>

    <!-- All Cars Table -->
    <div class="card p-4">
      <h5 class="mb-3">📋 All Cars</h5>
      <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
          <thead class="table-dark">
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Brand</th>
              <th>Price</th>
              <th>Fuel</th>
              <th>Seats</th>
              <th>Status</th>
              <th>Image</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php while($row = mysqli_fetch_assoc($cars)): ?>
              <tr>
                <td><?= $row['id'] ?></td>
                <td><?= $row['car_name'] ?></td>
                <td><?= $row['brand'] ?></td>
                <td>₹<?= $row['price_per_day'] ?>/day</td>
                <td><?= $row['fuel_type'] ?></td>
                <td><?= $row['seats'] ?></td>
                <td><span class="badge bg-<?= $row['status']=='available' ? 'success' : 'secondary' ?>"><?= $row['status'] ?></span></td>
                <td><img src="../images/<?= $row['image'] ?>" class="car-img"></td>
                <td>
                  <a href="manage_cars.php?del=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this car?')">Delete</a>
                </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
