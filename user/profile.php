<?php
session_start();
include("../config/db.php");

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch user data
$user_query = mysqli_query($conn, "SELECT * FROM users WHERE id='$user_id'");
$user = mysqli_fetch_assoc($user_query);

// ✅ Handle ID proof upload (only if not verified)
if (isset($_POST['upload_id']) && !$user['is_verified']) {
    $file_name = $_FILES['id_proof']['name'];
    $file_tmp = $_FILES['id_proof']['tmp_name'];
    $target_dir = "../images/id_proofs/";
    if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
    $file_path = $target_dir . time() . '_' . basename($file_name);

    $allowed_types = ['jpg', 'jpeg', 'png', 'pdf'];
    $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    if (!in_array($ext, $allowed_types)) {
        $error = "Only JPG, PNG, or PDF files allowed.";
    } else {
        if (move_uploaded_file($file_tmp, $file_path)) {
            mysqli_query($conn, "UPDATE users SET id_proof='$file_path', is_verified=0 WHERE id='$user_id'");
            $success = "File uploaded successfully. Waiting for admin verification.";
            $user_query = mysqli_query($conn, "SELECT * FROM users WHERE id='$user_id'");
            $user = mysqli_fetch_assoc($user_query);
        } else {
            $error = "File upload failed. Try again.";
        }
    }
}

// Fetch cars and bookings
$cars = mysqli_query($conn, "SELECT * FROM cars WHERE status='available'");
$bookings = mysqli_query($conn, "
    SELECT b.*, c.car_name, c.image 
    FROM bookings b 
    JOIN cars c ON b.car_id=c.id 
    WHERE b.user_id='$user_id'
    ORDER BY b.id DESC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>User Dashboard | Car Rental</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<style>
body { background: #f8f9fa; }
.sidebar { height: 100vh; background: #212529; color: white; width: 250px; position: fixed; padding-top: 20px; }
.sidebar a { color: white; display: block; padding: 12px 20px; text-decoration: none; transition: 0.3s; }
.sidebar a:hover, .sidebar a.active { background: #495057; border-radius: 5px; }
.main-content { margin-left: 260px; padding: 20px; }
.card { border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); border: none; }
.section { display: none; }
.section.active { display: block; }
</style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <h4 class="text-center mb-4">🚗 Car Rental</h4>
    <a href="#" class="active" onclick="showSection('profile', event)"><i class="bi bi-person-circle me-2"></i>Profile</a>
    <a href="#" onclick="showSection('bookcar', event)"><i class="bi bi-plus-circle me-2"></i>Book a Car</a>
    <a href="#" onclick="showSection('bookings', event)"><i class="bi bi-calendar-check me-2"></i>My Bookings</a>
    <a href="#" onclick="showSection('returns', event)"><i class="bi bi-arrow-return-left me-2"></i>Return Request</a>

    <a href="logout.php" class="mt-4"><i class="bi bi-box-arrow-right me-2"></i>Logout</a>
</div>

<!-- Main Content -->
<div class="main-content">

<!-- ================= PROFILE SECTION ================= -->
<div id="profile" class="section active">
    <h3>👋 Welcome, <?php echo htmlspecialchars($user['fullname']); ?>!</h3>

    <?php if (!empty($error)): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?>
    <?php if (!empty($success)): ?><div class="alert alert-success"><?php echo $success; ?></div><?php endif; ?>

    <div class="card p-4 mt-3">
        <div class="row align-items-center">
            <div class="col-md-3 text-center">
                <img src="../images/<?php echo !empty($user['profile_pic']) ? htmlspecialchars($user['profile_pic']) : 'default.png'; ?>" 
                     alt="Profile" class="rounded-circle border border-3 border-primary" width="120" height="120">
            </div>
            <div class="col-md-9">
                <p><strong><i class="bi bi-person"></i> Name:</strong> <?php echo htmlspecialchars($user['fullname']); ?></p>
                <p><strong><i class="bi bi-envelope"></i> Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                <p><strong><i class="bi bi-telephone"></i> Phone:</strong> <?php echo htmlspecialchars($user['phone']); ?></p>
                <p><strong><i class="bi bi-geo-alt"></i> Address:</strong> <?php echo htmlspecialchars($user['address']); ?></p>
                <a href="edit_profile.php" class="btn btn-warning mt-3"><i class="bi bi-pencil-square"></i> Edit Profile</a>
                    


                <div class="mt-4 p-3 bg-light rounded">
                    <h5>Profile Verification</h5>
                    <p>Status: 
                        <span class="fw-bold <?php echo $user['is_verified'] ? 'text-success' : 'text-danger'; ?>">
                            <?php echo $user['is_verified'] ? 'Verified ✅' : 'Not Verified ❌'; ?>
                        </span>
                    </p>

                    <?php if (!$user['is_verified']): ?>
                        <form method="POST" enctype="multipart/form-data">
                            <input type="file" name="id_proof" class="form-control mb-2" required>
                            <button type="submit" name="upload_id" class="btn btn-primary"><i class="bi bi-upload"></i> Upload ID Proof</button>
                        </form>
                    <?php else: ?>
                        <p class="text-success fw-semibold">✅ You are verified. Upload disabled.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ================= BOOK A CAR SECTION ================= -->
<div id="bookcar" class="section">
    <h3>🚘 Available Cars</h3>
    <div class="row mt-4">
        <?php while ($car = mysqli_fetch_assoc($cars)): ?>
        <div class="col-md-4 mb-4">
            <div class="card p-3">
                <img src="../images/<?php echo htmlspecialchars($car['image']); ?>" class="card-img-top rounded mb-3" style="height: 180px; object-fit: cover;">
                <h5><?php echo htmlspecialchars($car['car_name']); ?></h5>
                <p>💰 ₹<?php echo htmlspecialchars($car['price_per_day']); ?> / day</p>
                <a href="booking.php?car_id=<?php echo $car['id']; ?>" class="btn btn-primary w-100">Book Now</a>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>

<!-- ================= BOOKINGS SECTION ================= -->
<div id="bookings" class="section">
    <h3>📅 Your Bookings</h3>
    <a href="download_bookings.php" class="btn btn-info mt-3">Download Bookings</a>
    <div class="table-responsive mt-4">
        <table class="table table-bordered bg-white">
            <thead class="table-dark">
                <tr>
                    <th>Car</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Total Price</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($bookings) > 0): ?>
                    <?php while ($b = mysqli_fetch_assoc($bookings)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($b['car_name']); ?></td>
                            <td><?php echo htmlspecialchars($b['start_date']); ?></td>
                            <td><?php echo htmlspecialchars($b['end_date']); ?></td>
                            <td>₹<?php echo htmlspecialchars($b['total_price']); ?></td>
                            <td><?php echo htmlspecialchars($b['status']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="5" class="text-center text-muted">No bookings yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>


<!-- ================= RETURN REQUEST SECTION ================= -->
<div id="returns" class="section">
    <h3>🔄 Return Your Booked Cars</h3>
    <div class="table-responsive mt-4">
        <table class="table table-bordered bg-white">
            <thead class="table-dark">
                <tr>
                    <th>Car</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Status</th>
                    <th>Return Request</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $return_query = mysqli_query($conn, "
                    SELECT b.*, c.car_name 
                    FROM bookings b 
                    JOIN cars c ON b.car_id=c.id 
                    WHERE b.user_id='$user_id' AND b.status='booked'
                    ORDER BY b.id DESC
                ");

                if (mysqli_num_rows($return_query) > 0):
                    while ($b = mysqli_fetch_assoc($return_query)):
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($b['car_name']); ?></td>
                    <td><?php echo htmlspecialchars($b['start_date']); ?></td>
                    <td><?php echo htmlspecialchars($b['end_date']); ?></td>
                    <td><?php echo htmlspecialchars($b['status']); ?></td>
                    <td>
                        <?php if ($b['return_request'] == 'pending'): ?>
                            <span class="badge bg-warning">Pending Approval</span>
                        <?php elseif ($b['return_request'] == 'approved'): ?>
                            <span class="badge bg-success">Approved</span>
                        <?php elseif ($b['return_request'] == 'rejected'): ?>
                            <span class="badge bg-danger">Rejected</span>
                        <?php else: ?>
                            <form method="POST" action="return_request.php">
                                <input type="hidden" name="booking_id" value="<?php echo $b['id']; ?>">
                                <button type="submit" name="return_request" class="btn btn-sm btn-outline-primary">
                                    Request Return
                                </button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; else: ?>
                    <tr><td colspan="5" class="text-center text-muted">No active bookings found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>


</div>

<!-- ================= JS ================= -->
<script>
function showSection(id, event) {
    document.querySelectorAll('.section').forEach(sec => sec.classList.remove('active'));
    document.getElementById(id).classList.add('active');
    document.querySelectorAll('.sidebar a').forEach(a => a.classList.remove('active'));
    event.currentTarget.classList.add('active');
}
</script>
</body>
</html>
