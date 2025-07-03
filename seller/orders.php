<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start session and check if user is logged in
include $_SERVER['DOCUMENT_ROOT'] . '/rainbow_market/seller/auth_seller.php';
// Connect to database
include $_SERVER['DOCUMENT_ROOT'] . '/rainbow_market/admin/db.php';

// Get seller_id from sellers table using user_id
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

// Fetch orders for products that belong to this seller
$sql = "SELECT o.id AS order_id, o.created_at, o.status, o.quantity, 
               p.title AS product_title, p.price, 
               u.name AS buyer_name
        FROM orders o
        JOIN products p ON o.product_id = p.id
        JOIN users u ON o.buyer_id = u.id
        WHERE p.seller_id = ?
        ORDER BY o.created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $seller_id);
$stmt->execute();
$orders = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Seller Orders - Rainbow Market</title>
    <link rel="stylesheet" href="/rainbow_market/styles/styles.css" />
    <link rel="stylesheet" href="/rainbow_market/styles/dashboard.css" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
    />
  </head>
  <body>
    <!--Navbar-->
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/rainbow_market/seller/seller-navbar.php'; ?>

    <main class="seller-dashboard">
      <!--Sidebar-->
      <?php include $_SERVER['DOCUMENT_ROOT'] . '/rainbow_market/seller/seller-sidebar.php'; ?>

      <section class="dashboard-main">
        <div style="margin-bottom:1rem;">
          <h2>Welcome back, <?= htmlspecialchars($seller_name) ?>!</h2>
        </div>
        <h2>My Orders</h2>

        <?php if ($orders->num_rows > 0): ?>
          <table class="product-table">
            <thead>
              <tr>
                <th>Order ID</th>
                <th>Product</th>
                <th>Buyer</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Status</th>
                <th>Date</th>
              </tr>
            </thead>
            <tbody>
              <?php while ($row = $orders->fetch_assoc()): ?>
                <tr>
                  <td>#<?= $row['order_id'] ?></td>
                  <td><?= htmlspecialchars($row['product_title']) ?></td>
                  <td><?= htmlspecialchars($row['buyer_name']) ?></td>
                  <td><?= $row['quantity'] ?></td>
                  <td>R <?= number_format($row['price'], 2) ?></td>
                  <td>
                    <span class="status <?= $row['status'] === 'completed' ? 'active' : 'pending' ?>">
                      <?= ucfirst($row['status']) ?>
                    </span>
                    <form method="POST" action="update_order_status.php" style="display:inline;">
                      <input type="hidden" name="order_id" value="<?= $row['order_id'] ?>">
                      <select name="status">
                        <option value="pending" <?= $row['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                        <option value="out for delivery" <?= $row['status'] === 'out for delivery' ? 'selected' : '' ?>>Out for Delivery</option>
                      </select>
                      <button type="submit">Update</button>
                    </form>
                  </td>
                  <td><?= date('Y-m-d H:i', strtotime($row['created_at'])) ?></td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        <?php else: ?>
          <p>No orders have been placed for your products yet.</p>
        <?php endif; ?>
      </section>
    </main>

    <script src="/rainbow_market/scripts/components.js"></script>
  </body>
</html>
