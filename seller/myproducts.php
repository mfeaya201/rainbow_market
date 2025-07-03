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

// Fetch all products added by this seller
$query = "SELECT * FROM products WHERE seller_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $seller_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>My Products - Seller</title>
    <link rel="stylesheet" href="/rainbow_market/styles/styles.css" />
    <link rel="stylesheet" href="/rainbow_market/styles/dashboard.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
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
        <h2>My Products</h2>
        <?php if ($result->num_rows > 0): ?>
        <table class="product-table">
          <thead>
            <tr>
              <th>Image</th>
              <th>Product</th>
              <th>Description</th>
              <th>Price</th>
              <th><strong>Stock</strong></th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
              <tr>
                <td>
                  <?php
                    $images = json_decode($row['images'], true);
                    $thumbnail = $images[0] ?? '/rainbow_market/images/default.jpg';
                  ?>
                  <img src="<?= htmlspecialchars($thumbnail) ?>" class="product-thumb" alt="Product Image">
                </td>
                <td><?= htmlspecialchars($row['title']) ?></td>
                <td><?= htmlspecialchars($row['description']) ?></td>
                <td>R <?= number_format($row['price'], 2) ?></td>
                <td><?= (int)$row['stock'] ?></td> <!-- Add this line -->
                <td><span class="status <?= $row['status'] === 'active' ? 'active' : 'out' ?>">
                  <?= ucfirst($row['status']) ?>
                  <br>
                    <button class="toggle-stock-btn" data-id="<?= $row['id'] ?>">
                      <?= $row['status'] === 'active' ? 'Mark Out of Stock' : 'Mark Active' ?>
                    </button>
                </span></td>
                <td>
                  <a href="/rainbow_market/seller/edit_product.php?id=<?= $row['id'] ?>" class="edit-btn">Edit</a>
                  <button class="delete-btn" data-id="<?=$row['id']?>">Delete</button>
                </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      <?php else: ?>
        <p>You haven't listed any products yet.</p>
      <?php endif; ?>
      </section>
    </main>

    <script src="/rainbow_market/scripts/components.js"></script>
    <script>
      document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', () => {
          const id = button.getAttribute('data-id');
          if (confirm("Are you sure you want to delete this product?")) {
            fetch(`/rainbow_market/seller/delete_product.php?id=${id}`, {
              method: 'GET'
            }).then(res => {
              if (res.ok) location.reload();
              else alert("Failed to delete.");
            });
          }
        });
      });

      document.querySelectorAll('.toggle-stock-btn').forEach(button => {
        button.addEventListener('click', () => {
          const id = button.getAttribute('data-id');
          fetch(`/rainbow_market/seller/toggle_stock.php?id=${id}`, {
            method: 'GET'
          }).then(res => {
            if (res.ok) location.reload();
            else alert("Failed to update stock status.");
          });
        });
      });
    </script>
  </body>
</html>
