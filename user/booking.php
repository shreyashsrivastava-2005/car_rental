<?php
session_start();
include("../config/db.php");

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['car_id'])) {
    header("Location: user_dashboard.php");
    exit;
}

$car_id = intval($_GET['car_id']);
$user_id = $_SESSION['user_id'];

// Fetch car details
$car_query = mysqli_query($conn, "SELECT * FROM cars WHERE id='$car_id'");
if (mysqli_num_rows($car_query) == 0) {
    die("Car not found!");
}
$car = mysqli_fetch_assoc($car_query);

// Handle booking form submission
if (isset($_POST['book_now'])) {
    $start = $_POST['start_date'];
    $end = $_POST['end_date'];

    // Validate date range
    $days = (strtotime($end) - strtotime($start)) / (60 * 60 * 24);
    if ($days < 1) {
        $error = "Invalid date range selected.";
    } else {
        $total = $days * $car['price_per_day'];

        // Insert booking
        $sql = "INSERT INTO bookings (user_id, car_id, start_date, end_date, total_price, status)
                VALUES ('$user_id', '$car_id', '$start', '$end', '$total', 'booked')";
        if (mysqli_query($conn, $sql)) {
            mysqli_query($conn, "UPDATE cars SET status='booked' WHERE id='$car_id'");
            header("Location: user_dashboard.php");
            exit;
        } else {
            $error = "Booking failed. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Book Car | Car Rental</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<style>
body { background-color: #f8f9fa; font-family: 'Poppins', sans-serif; }
.container { max-width: 800px; margin-top: 50px; }
.card { border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); border: none; }
.car-img { width: 100%; border-radius: 15px; height: 300px; object-fit: cover; }
.btn-primary { background-color: #003566; border: none; border-radius: 25px; padding: 10px 25px; }
.btn-primary:hover { background-color: #001d3d; }
.price-display { font-size: 1.2rem; font-weight: 600; color: #003566; }
</style>
</head>
<body>

<div class="container">
    <a href="profile.php" class="btn btn-secondary mb-3"><i class="bi bi-arrow-left"></i> Back to Dashboard</a>

    <div class="card p-4">
        <div class="row">
            <div class="col-md-6">
                <img src="../images/<?php echo htmlspecialchars($car['image']); ?>" alt="Car Image" class="car-img">
            </div>
            <div class="col-md-6">
                <h3 class="fw-bold"><?php echo htmlspecialchars($car['car_name']); ?></h3>
                <p class="text-muted mb-1">Model: <?php echo htmlspecialchars($car['model']); ?></p>
                <p class="text-muted mb-1">Fuel Type: <?php echo htmlspecialchars($car['fuel_type']); ?></p>
                <p class="mb-3">💰 <strong>₹<?php echo htmlspecialchars($car['price_per_day']); ?></strong> per day</p>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>

                <form method="POST" class="mt-3">
                    <div class="mb-3">
                        <label class="form-label">Start Date</label>
                        <input type="date" name="start_date" id="start_date" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">End Date</label>
                        <input type="date" name="end_date" id="end_date" class="form-control" required>
                    </div>

                    <div class="mt-3">
                        <p class="price-display" id="total_price">Total Price: ₹0</p>
                    </div>

                    <button type="submit" name="book_now" class="btn btn-primary w-100">
                        <i class="bi bi-calendar-check"></i> Confirm Booking
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
const startInput = document.getElementById('start_date');
const endInput = document.getElementById('end_date');
const totalDisplay = document.getElementById('total_price');
const pricePerDay = <?php echo $car['price_per_day']; ?>;

function calculateTotal() {
    const start = new Date(startInput.value);
    const end = new Date(endInput.value);
    const diff = (end - start) / (1000 * 60 * 60 * 24);
    if (diff >= 1) {
        totalDisplay.textContent = `Total Price: ₹${diff * pricePerDay}`;
    } else {
        totalDisplay.textContent = "Total Price: ₹0";
    }
}

startInput.addEventListener('change', calculateTotal);
endInput.addEventListener('change', calculateTotal);
</script>

</body>
</html>
