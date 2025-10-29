<?php
session_start();
include("../config/db.php");

// Ensure admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

// ✅ Handle user verification
if (isset($_GET['verify'])) {
    $user_id = intval($_GET['verify']);
    mysqli_query($conn, "UPDATE users SET is_verified = 1 WHERE id = '$user_id'");
    header("Location: admin_verify_users.php?success=1");
    exit;
}

// ✅ Fetch all users who uploaded ID proof
$result = mysqli_query($conn, "SELECT * FROM users WHERE id_proof IS NOT NULL ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Verify Users | Admin Panel</title>

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Common Admin Styles -->
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
    .status-badge {
        padding: 5px 12px;
        border-radius: 8px;
        color: #fff;
        font-weight: 600;
    }
    .verified {
        background-color: #28a745;
    }
    .not-verified {
        background-color: #dc3545;
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
    <h3 class="fw-bold mb-4">👥 User Profile Verification</h3>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">✅ User has been successfully verified!</div>
    <?php endif; ?>

    <div class="card p-4">
        <h5 class="mb-3">📋 Users with Uploaded ID Proof</h5>
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>ID Proof</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php while ($user = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?= $user['id'] ?></td>
                                <td><?= htmlspecialchars($user['fullname']) ?></td>
                                <td><?= htmlspecialchars($user['email']) ?></td>
                                <td>
                                    <?php if (!empty($user['id_proof'])): ?>
                                        <a href="../<?= htmlspecialchars($user['id_proof']) ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                            View ID
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">N/A</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($user['is_verified']): ?>
                                        <span class="status-badge verified">Verified ✅</span>
                                    <?php else: ?>
                                        <span class="status-badge not-verified">Not Verified ❌</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!$user['is_verified']): ?>
                                        <a href="?verify=<?= $user['id'] ?>" 
                                           class="btn btn-success btn-sm"
                                           onclick="return confirm('Are you sure you want to verify this user?')">
                                           Verify
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">—</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-3">No users have uploaded ID proofs yet.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
