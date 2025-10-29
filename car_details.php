<?php
session_start();
include("config/db.php");
include("includes/header.php");

if(!isset($_GET['id'])){
    die("Car not found!");
}
$id = $_GET['id'];
$car = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM cars WHERE id=$id"));
?>

<h2>🚗 <?= $car['car_name'] ?></h2>
<img src="images/<?= $car['image'] ?>" width="400">
<p><b>Brand:</b> <?= $car['brand'] ?></p>
<p><b>Model:</b> <?= $car['model'] ?></p>
<p><b>Fuel:</b> <?= $car['fuel_type'] ?></p>
<p><b>Seats:</b> <?= $car['seats'] ?></p>
<p><b>Price:</b> ₹<?= $car['price_per_day'] ?>/day</p>

<?php if(isset($_SESSION['user_id'])): ?>
<h3>Book this Car</h3>
<form action="user/booking.php" method="POST">
    <input type="hidden" name="car_id" value="<?= $car['id'] ?>">
    <label>Start Date:</label>
    <input type="date" name="start_date" required><br><br>
    <label>End Date:</label>
    <input type="date" name="end_date" required><br><br>
    <button type="submit" name="book_now">Book Now</button>
</form>
<?php else: ?>
<p style="color:red;">You must <a href="user/login.php">login</a> to book a car.</p>
<?php endif; ?>

<?php include("includes/footer.php"); ?>
