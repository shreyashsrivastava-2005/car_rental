<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<div class="sidebar">
  <h4>🚘 Car Rental Admin</h4>
  <p class="text-center mb-3">Welcome, <strong><?php echo $_SESSION['admin_user']; ?></strong></p>

  <a href="dashboard.php" class="<?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'bg-white text-dark fw-bold' : '' ?>">📊 Dashboard</a>
  <a href="manage_cars.php" class="<?= basename($_SERVER['PHP_SELF']) == 'manage_cars.php' ? 'bg-white text-dark fw-bold' : '' ?>">🚗 Manage Cars</a>
  <a href="manage_users.php" class="<?= basename($_SERVER['PHP_SELF']) == 'manage_users.php' ? 'bg-white text-dark fw-bold' : '' ?>">👥 Manage Users</a>
  <a href="manage_bookings.php" class="<?= basename($_SERVER['PHP_SELF']) == 'manage_bookings.php' ? 'bg-white text-dark fw-bold' : '' ?>">📅 Manage Bookings</a>
  <a href="manage_contacts.php" class="<?= basename($_SERVER['PHP_SELF']) == 'manage_contacts.php' ? 'bg-white text-dark fw-bold' : '' ?>">💬 Contact Messages</a>
  <a href="manage_returns.php" class="<?= basename($_SERVER['PHP_SELF']) == 'manage_returns.php' ? 'bg-white text-dark fw-bold' : '' ?>">🔁 Manage Returns</a>    
  <a href="admin_verify_users.php" class="<?= basename($_SERVER['PHP_SELF']) == 'admin_verify_users.php' ? 'bg-white text-dark fw-bold' : '' ?>">👥 Verify Users</a>

  <a href="logout.php" class="btn btn-danger w-75 mx-auto mt-3">🚪 Logout</a>
</div>
