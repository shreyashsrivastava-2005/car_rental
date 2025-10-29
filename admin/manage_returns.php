<?php
session_start();
include("../config/db.php");

// Fetch all pending return requests
$requests = mysqli_query($conn, "
    SELECT b.*, u.fullname, c.car_name 
    FROM bookings b 
    JOIN users u ON b.user_id=u.id 
    JOIN cars c ON b.car_id=c.id 
    WHERE b.return_request='pending'
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Return Requests | Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
<h3>🔁 Pending Return Requests</h3>
<table class="table table-bordered mt-3 bg-white">
    <thead class="table-dark">
        <tr>
            <th>User</th>
            <th>Car</th>
            <th>Start</th>
            <th>End</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($r = mysqli_fetch_assoc($requests)): ?>
        <tr>
            <td><?php echo htmlspecialchars($r['fullname']); ?></td>
            <td><?php echo htmlspecialchars($r['car_name']); ?></td>
            <td><?php echo htmlspecialchars($r['start_date']); ?></td>
            <td><?php echo htmlspecialchars($r['end_date']); ?></td>
            <td>
                <form method="POST" action="approve_return.php" class="d-inline">
                    <input type="hidden" name="booking_id" value="<?php echo $r['id']; ?>">
                    <button type="submit" name="approve" class="btn btn-success btn-sm">Approve</button>
                </form>
                <form method="POST" action="approve_return.php" class="d-inline">
                    <input type="hidden" name="booking_id" value="<?php echo $r['id']; ?>">
                    <button type="submit" name="reject" class="btn btn-danger btn-sm">Reject</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>
</body>
</html>
