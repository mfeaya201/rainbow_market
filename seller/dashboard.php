<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start session and check login
include $_SERVER['DOCUMENT_ROOT'] . '/rainbow_market/seller/auth_seller.php';
include $_SERVER['DOCUMENT_ROOT'] . '/rainbow_market/admin/db.php';

// Get user_id from session and fetch seller_id from sellers table
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT id FROM sellers WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($seller_id);
$stmt->fetch();
$stmt->close();

// Fetch seller name for welcome banner
$seller_name = '';
$stmt = $conn->prepare("SELECT name FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($seller_name);
$stmt->fetch();
$stmt->close();

// Get total sales
$stmt = $conn->prepare("
    SELECT SUM(o.amount) AS total_sales
    FROM orders o
    JOIN products p ON o.product_id = p.id
    WHERE p.seller_id = ?
");
$stmt->bind_param("i", $seller_id);
$stmt->execute();
$result = $stmt->get_result();
$total_sales = $result->fetch_assoc()['total_sales'] ?? 0;

// Get total products listed
$stmt = $conn->prepare("SELECT COUNT(*) AS count FROM products WHERE seller_id = ?");
$stmt->bind_param("i", $seller_id);
$stmt->execute();
$result = $stmt->get_result();
$product_count = $result->fetch_assoc()['count'] ?? 0;

// Get total orders
$stmt = $conn->prepare("
    SELECT COUNT(*) AS count
    FROM orders o
    JOIN products p ON o.product_id = p.id
    WHERE p.seller_id = ?
");
$stmt->bind_param("i", $seller_id);
$stmt->execute();
$result = $stmt->get_result();
$order_count = $result->fetch_assoc()['count'] ?? 0;

// Get wallet balance
$stmt = $conn->prepare("SELECT balance FROM wallet WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$balance = $result->fetch_assoc()['balance'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Seller Dashboard - Rainbow Market</title>
  <link rel="stylesheet" href="/rainbow_market/styles/styles.css" />
  <link rel="stylesheet" href="/rainbow_market/styles/dashboard.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
</head>
<body>
  <!-- Navbar -->
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/rainbow_market/seller/seller-navbar.php'; ?>

  <main class="seller-dashboard">
    <!-- Sidebar -->
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/rainbow_market/seller/seller-sidebar.php'; ?>

    <section class="dashboard-main">
      <div style="margin-bottom:1rem;">
        <h2>Welcome back, <?= htmlspecialchars($seller_name) ?>!</h2>
      </div>
      <h1>Dashboard Overview</h1>

      <div class="dashboard-cards">
        <div class="card">
          <i class="fas fa-dollar-sign card-icon"></i>
          <h3>Total Sales</h3>
          <p>R <?= number_format($total_sales, 2) ?></p>
        </div>
        <div class="card">
          <i class="fas fa-box-open card-icon"></i>
          <h3>Products Listed</h3>
          <p><?= $product_count ?> Items</p>
        </div>
        <div class="card">
          <i class="fas fa-shopping-cart card-icon"></i>
          <h3>Orders</h3>
          <p><?= $order_count ?> Orders</p>
        </div>
        <div class="card">
          <i class="fas fa-wallet card-icon"></i>
          <h3>Available Balance</h3>
          <p>R <?= number_format($balance, 2) ?></p>
        </div>
      </div>

      <div class="dashboard-actions">
        <a href="/rainbow_market/seller/addproducts.php" class="action-btn">+ Add New Product</a>
        <a href="/rainbow_market/seller/orders.php" class="action-btn">View Orders</a>
      </div>
    </section>
  </main>

  <script src="/rainbow_market/scripts/components.js"></script>
</body>
</html>
