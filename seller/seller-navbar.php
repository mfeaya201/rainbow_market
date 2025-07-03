<?php
// Fetch seller name for navbar if not already set
if (!isset($seller_name)) {
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (isset($_SESSION['user_id'])) {
        include_once $_SERVER['DOCUMENT_ROOT'] . '/rainbow_market/admin/db.php';
        $seller_id = $_SESSION['user_id'];
        $stmt = $conn->prepare("SELECT name FROM users WHERE id = ?");
        $stmt->bind_param("i", $seller_id);
        $stmt->execute();
        $stmt->bind_result($seller_name);
        $stmt->fetch();
        $stmt->close();
    } else {
        $seller_name = "Seller";
    }
}
?>
<div class="seller-navbar">
  <div class="seller-navbar-container">
    <div class="logo">
      <a href="/rainbow_market/seller/dashboard.php">Rainbow Market Seller</a>
    </div>
    <div class="nav-actions">
      <div class="profile-dropdown">
        <i class="fas fa-user-circle"></i>
        <span class="username"><?= htmlspecialchars($seller_name ?? 'Seller') ?></span>
        <div class="dropdown-content">
          <a href="/rainbow_market/seller/settings.php"
            ><i class="fas fa-cog"></i> Account Settings</a
          >
          <a href="/rainbow_market/index.php"
            ><i class="fas fa-exchange-alt"></i> Switch to Buyer Mode</a
          >
          <a href="/rainbow_market/logout.php"
            ><i class="fas fa-sign-out-alt"></i> Logout</a
          >
        </div>
      </div>
    </div>
  </div>
</div>
