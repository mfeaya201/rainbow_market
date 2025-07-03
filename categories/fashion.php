<?php

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
include $_SERVER['DOCUMENT_ROOT'] . '/rainbow_market/admin/db.php';

if (!$conn) {
  die("Database connection failed.");
}

// Set category based on filename
$category = basename(__FILE__, '.php');

$stmt = $conn->prepare("SELECT id, title, price, images FROM products WHERE category = ? AND status = 'active'");
$stmt->bind_param("s", $category);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?= ucfirst($category) ?> - Rainbow Market</title>
  <link rel="stylesheet" href="/rainbow_market/styles/styles.css" />
  <link rel="stylesheet" href="/rainbow_market/styles/category.css" />
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@1,600&display=swap" rel="stylesheet" />
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
  />
</head>
<body>

  <?php include $_SERVER['DOCUMENT_ROOT'] . '/rainbow_market/navbar.php'; ?>

  <main>
    <section class="category-section">
      <h2><?= ucfirst($category) ?> Products</h2>
      <div class="category-grid">
        <?php if ($result->num_rows > 0): ?>
          <?php while ($row = $result->fetch_assoc()): ?>
            <?php
              $images = json_decode($row['images'], true);
              $main_image = isset($images[0]) ? $images[0] : 'images/default.jpg';
            ?>
            <div class="category-card">
              <div class="card-image">
                <a href="/rainbow_market/product.php?id=<?= $row['id'] ?>">
                  <img src="/rainbow_market/<?= htmlspecialchars($main_image) ?>" alt="<?= htmlspecialchars($row['title']) ?>" />
                  <div class="card-title">
                    <?= htmlspecialchars($row['title']) ?><br>
                    <span class="price">R<?= number_format($row['price'], 2) ?></span>
                  </div>
                </a>
              </div>
              <form method="POST" action="/rainbow_market/buyer/add_to_cart.php" class="add-to-cart-form">
                <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
                <input type="number" name="quantity" value="1" min="1" class="cart-qty" required>
                <button type="submit" class="cart-btn"><i class="fas fa-cart-plus"></i> Add to Cart</button>
              </form>
            </div>
          <?php endwhile; ?>
        <?php else: ?>
          <p>No products found in this category.</p>
        <?php endif; ?>
      </div>
    </section>
  </main>

  <?php include $_SERVER['DOCUMENT_ROOT'] . '/rainbow_market/footer.php'; ?>

  <script src="/rainbow_market/scripts/rainbow-market.js"></script>
  <script src="/rainbow_market/scripts/category.js"></script>
</body>
</html>
