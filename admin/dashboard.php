<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['admin_id'])){
    header("Location: admin_login.php");
    exit;
}

// Count totals
$users = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users"));
$cars = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM cars"));
$bookings = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM bookings"));

// Fetch booking data for charts
$chartData = [];
$incomeData = [];

$query = mysqli_query($conn, "
    SELECT DATE(start_date) as date, 
           COUNT(id) as total_bookings, 
           SUM(total_price) as total_income 
    FROM bookings 
    GROUP BY DATE(start_date) 
    ORDER BY DATE(start_date) ASC
");

while($row = mysqli_fetch_assoc($query)){
    $chartData[] = $row['date'];
    $incomeData[] = [
        'bookings' => $row['total_bookings'],
        'income' => $row['total_income']
    ];
}

// Prepare data for JS
$dates = json_encode($chartData);
$bookingsData = json_encode(array_column($incomeData, 'bookings'));
$incomeDataValues = json_encode(array_column($incomeData, 'income'));
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard | Car Rental</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="admin_style.css">

  <style>
    body {
      background: #f4f6f9;
    }

    .main-content {
      margin-left: 250px;
      padding: 30px;
    }

    .card {
      border: none;
      border-radius: 12px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    canvas {
      width: 100% !important;
      height: 400px !important;
    }

    @media (max-width: 992px) {
      .main-content {
        margin-left: 0;
      }
    }
  </style>
</head>
<body>

  <!-- Sidebar -->
  <?php include("sidebar.php"); ?>

  <!-- Main Content -->
  <div class="main-content">
    <h3 class="fw-bold mb-4">📊 Dashboard Overview</h3>

    <!-- Overview cards -->
    <div class="row g-4 mb-4">
      <div class="col-md-4">
        <div class="card text-center p-4 bg-primary text-white">
          <h5>Total Users</h5>
          <h2><?php echo $users; ?></h2>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card text-center p-4 bg-success text-white">
          <h5>Total Cars</h5>
          <h2><?php echo $cars; ?></h2>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card text-center p-4 bg-warning text-white">
          <h5>Total Bookings</h5>
          <h2><?php echo $bookings; ?></h2>
        </div>
      </div>
    </div>

    <!-- Charts -->
    <div class="row g-4">
      <div class="col-md-6">
        <div class="card p-4">
          <h5 class="fw-bold mb-3 text-center">🚗 Cars Rented per Day</h5>
          <canvas id="bookingsChart"></canvas>
        </div>
      </div>

      <div class="col-md-6">
        <div class="card p-4">
          <h5 class="fw-bold mb-3 text-center">💰 Daily Income from Rentals</h5>
          <canvas id="incomeChart"></canvas>
        </div>
      </div>
    </div>

  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <script>
    // Prepare data
    const dates = <?php echo $dates; ?>;
    const bookings = <?php echo $bookingsData; ?>;
    const income = <?php echo $incomeDataValues; ?>;

    // Cars rented per day chart
    const ctx1 = document.getElementById('bookingsChart').getContext('2d');
    new Chart(ctx1, {
      type: 'bar',
      data: {
        labels: dates,
        datasets: [{
          label: 'Cars Rented',
          data: bookings,
          backgroundColor: 'rgba(54, 162, 235, 0.6)',
          borderColor: 'rgba(54, 162, 235, 1)',
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        scales: { y: { beginAtZero: true } }
      }
    });

    // Daily income chart
    const ctx2 = document.getElementById('incomeChart').getContext('2d');
    new Chart(ctx2, {
      type: 'line',
      data: {
        labels: dates,
        datasets: [{
          label: 'Income (₹)',
          data: income,
          fill: true,
          backgroundColor: 'rgba(40, 167, 69, 0.2)',
          borderColor: 'rgba(40, 167, 69, 1)',
          tension: 0.3
        }]
      },
      options: {
        responsive: true,
        scales: { y: { beginAtZero: true } }
      }
    });
  </script>
</body>
</html>
