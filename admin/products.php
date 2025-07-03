<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//start session and check if admin is logged in
include $_SERVER['DOCUMENT_ROOT'] . '/rainbow_market/admin/auth.php';
//connect to the database
include $_SERVER['DOCUMENT_ROOT'] . '/rainbow_market/admin/db.php';

// Fetch products and seller names
$products = $conn->query("
  SELECT products.id, products.title, products.category, products.status, users.name AS seller_name
  FROM products
  JOIN sellers ON products.seller_id = sellers.id
  JOIN users ON sellers.user_id = users.id
");
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin - Manage Products</title>
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
        <h1>Manage Products</h1>
        <table class="product-table">
          <thead>
            <tr>
              <th>Product ID</th>
              <th>Seller</th>
              <th>Title</th>
              <th>Category</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody id="product-table-body">
            <!--Products will be loaded here-->
            <?php if ($products && $products->num_rows > 0): ?>
              <?php while ($row = $products->fetch_assoc()): ?>
                <tr>
                  <td><?= $row['id'] ?></td>
                  <td><?= htmlspecialchars($row['seller_name']) ?></td>
                  <td><?= htmlspecialchars($row['title']) ?></td>
                  <td><?= htmlspecialchars($row['category']) ?></td>
                  <td><?= htmlspecialchars($row['status']) ?></td>
                  <td>
                    <form method="POST" action="toggle_product_status.php">
                      <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
                      <input type="hidden" name="current_status" value="<?= $row['status'] ?>">
                      <button type="submit">
                        <?= $row['status'] === 'active' ? 'Mark Out of Stock' : 'Mark Active' ?>
                      </button>
                    </form>
                  </td>
                </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr><td colspan="6">No products found</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </section>
    </main>

    <script src="/rainbow_market/scripts/components.js"></script>
  </body>
</html>
