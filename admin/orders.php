<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//start session and check if admin is logged in
include $_SERVER['DOCUMENT_ROOT'] . '/rainbow_market/admin/auth.php';
//connect to the database
include $_SERVER['DOCUMENT_ROOT'] . '/rainbow_market/admin/db.php';

/* Fetch orders from the database:
 -order data
 -buyer details
 -products title
 */

$orders = $conn->query("
 SELECT o.id, u.name AS buyer_name, p.title AS product_title, o.status, o.created_at
 FROM orders o
 JOIN users u ON o.buyer_id = u.id 
 JOIN products p ON o.product_id = p.id 
 ORDER BY o.created_at DESC
");
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin - Orders</title>
    <link rel="stylesheet" href="/rainbow_market/styles/admin.css" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
    />
  </head>
  <body>
    <!--Navbar-->
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/rainbow_market/admin/navbar.php'; ?>

    <main class="admin-dashboard">
      <!--Sidebar-->
      <?php include $_SERVER['DOCUMENT_ROOT'] . '/rainbow_market/admin/sidebar.php'; ?>

      <section class="admin-main">
        <h1>Orders</h1>
        <table class="product-table">
          <thead>
            <tr>
              <th>Order ID</th>
              <th>Buyer</th>
              <th>Product</th>
              <th>Status</th>
              <th>Date</th>
            </tr>
          </thead>
          <tbody id="orders-table-body">
            <!--Loop through each order and display a row -->
            <?php if ($orders && $orders->num_rows>0): ?>
              <?php while ($row = $orders->fetch_assoc()): ?>
                <tr>
                  <td><?= $row['id'] ?></td>
                  <td><?= htmlspecialchars($row['buyer_name']) ?></td>
                  <td><?= htmlspecialchars($row['product_title']) ?></td>
                  <td><?= ucfirst($row['status']) ?></td>
                  <td><?= date('Y-m-d', strtotime($row['created_at'])) ?></td>
                </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <!--Show message if there are no orders-->
              <tr><td colspan="5">No orders found</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </section>
    </main>

    <script src="/rainbow_market/scripts/components.js"></script>
  </body>
</html>
