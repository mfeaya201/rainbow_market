<?php
// Fetch seller name for sidebar if not already set
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
<aside class="seller-sidebar">
  <div class="sidebar-profile">
    <i class="fas fa-user-circle"></i>
    <span class="sidebar-username"><?= htmlspecialchars($seller_name ?? 'Seller') ?></span>
  </div>
  <h2>Seller Menu</h2>
  <ul>
    <li>
      <a href="/rainbow_market/seller/dashboard.php"><i class="fas fa-chart-line"></i> Dashboard</a>
    </li>
    <li>
      <a href="/rainbow_market/seller/myproducts.php"><i class="fas fa-box"></i> My Products</a>
    </li>
    <li>
      <a href="/rainbow_market/seller/addproducts.php"><i class="fas fa-plus-circle"></i> Add Product</a>
    </li>
    <li>
      <a href="/rainbow_market/seller/orders.php"><i class="fas fa-shopping-bag"></i> Orders</a>
    </li>
    <li>
      <a href="/rainbow_market/seller/wallet.php"><i class="fas fa-wallet"></i> Wallet</a>
    </li>
    <li>
      <a href="/rainbow_market/seller/messages.php"><i class="fas fa-comments"></i> Messages</a>
    </li>
    <li>
      <a href="/rainbow_market/seller/settings.php"><i class="fas fa-cog"></i> Store Settings</a>
    </li>
  </ul>
</aside>
