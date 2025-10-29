<?php
include("config/db.php");
include("includes/header.php");

$cars = mysqli_query($conn, "SELECT * FROM cars WHERE status='available' ORDER BY id DESC");
?>

<h2>🚗 Available Cars</h2>
<div style="display:flex; flex-wrap:wrap; gap:20px;">
<?php while($row = mysqli_fetch_assoc($cars)): ?>
    <div style="border:1px solid #ccc; padding:10px; width:250px;">
        <img src="images/<?= $row['image'] ?>" width="230" height="150">
        <h3><?= $row['car_name'] ?></h3>
        <p><b>Brand:</b> <?= $row['brand'] ?></p>
        <p><b>Price:</b> ₹<?= $row['price_per_day'] ?>/day</p>
        <a href="car_details.php?id=<?= $row['id'] ?>">View Details</a>
    </div>
<?php endwhile; ?>
</div>

<?php include("includes/footer.php"); ?>
