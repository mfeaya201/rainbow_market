<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include database connection and start session
include $_SERVER['DOCUMENT_ROOT'] . '/rainbow_market/admin/db.php';
session_start();

// Get the product ID from the URL, or show an error if missing
$product_id = $_GET['id'] ?? null;
if (!$product_id) {
  echo "Product not found.";
  exit();
}

// Fetch product details and seller's store name from the database
$stmt = $conn->prepare(
  "SELECT p.*, s.store_name 
   FROM products p 
   JOIN sellers s ON p.seller_id = s.id 
   WHERE p.id = ?"
);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if (!$product) {
  echo "Product not found.";
  exit();
}

// Decode the images JSON and set the main image
$images = json_decode($product['images'], true);
if (!is_array($images) || empty($images[0])) {
    $images = ['images/default.jpg'];
}
$main_image = $images[0];

// Ensure main image path starts with 'uploads/' if not default
if ($main_image !== 'images/default.jpg' && !str_starts_with($main_image, 'uploads/')) {
    $main_image = 'uploads/' . ltrim($main_image, '/');
}

// Ensure all thumbnail paths start with 'uploads/' if not default
foreach ($images as &$img) {
    if ($img !== 'images/default.jpg' && !str_starts_with($img, 'uploads/')) {
        $img = 'uploads/' . ltrim($img, '/');
    }
}
unset($img);

// Get the last visited category URL for the back link
$backLink = $_SESSION['last_category_url'] ?? '/rainbow_market/buyer/category.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($product['title']) ?> - Rainbow Market</title>
  <link rel="stylesheet" href="/rainbow_market/styles/styles.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
  <style>
    /* Product page layout and styling */
    .product-page {
      padding: 40px 20px;
      max-width: 1200px;
      margin: auto;
      display: flex;
      flex-wrap: wrap;
      gap: 40px;
    }
    .product-main-image {
      flex: 1 1 40%;
    }
    .product-main-image img {
      width: 100%;
      height: auto;
      border-radius: 12px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    }
    .product-details {
      flex: 1 1 50%;
      background: #f9fff5;
      padding: 24px;
      border-radius: 12px;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }
    .product-details h2 {
      font-size: 1.8rem;
      margin-bottom: 16px;
      color: var(--text-dark);
    }
    .product-details p {
      font-size: 1rem;
      margin-bottom: 12px;
      color: #333;
    }
    .product-details strong {
      color: var(--text-dark);
    }
    .product-details form {
      margin-top: 20px;
      display: flex;
      align-items: center;
      gap: 12px;
    }
    .product-details input[type="number"] {
      width: 70px;
      padding: 6px;
      font-size: 1rem;
    }
    .product-details button {
      padding: 10px 18px;
      font-size: 1rem;
      background-color: var(--primary-light);
      color: white;
      border: none;
      border-radius: 8px;
      cursor: pointer;
    }
    .product-thumbnails {
      margin-top: 30px;
      display: flex;
      gap: 8px;
    }
    .product-thumbnails img {
      width: 60px;
      height: 60px;
      object-fit: cover;
      border-radius: 6px;
      border: 1px solid #ccc;
      cursor: pointer;
      transition: border-color 0.3s;
    }
    .product-thumbnails img:hover {
      border-color: var(--primary-light);
    }
    .back-link {
      display: block;
      margin: 30px auto 0;
      max-width: 1200px;
      text-align: left;
    }
    .back-link a {
      background-color: var(--primary-light);
      color: white;
      padding: 10px 16px;
      border-radius: 8px;
      text-decoration: none;
      font-weight: bold;
    }
    .back-link a:hover {
      background-color: #4f8a67;
    }
  </style>
</head>
<body>
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/rainbow_market/navbar.php'; ?>

  <main class="product-page">
    <!-- Main product image and thumbnails -->
    <div class="product-main-image">
      <img id="mainProductImage" src="/rainbow_market/<?= htmlspecialchars($main_image) ?>" alt="<?= htmlspecialchars($product['title']) ?>">
      <div class="product-thumbnails">
        <?php foreach ($images as $img): ?>
          <img src="/rainbow_market/<?= htmlspecialchars($img) ?>" alt="Thumbnail" onclick="document.getElementById('mainProductImage').src=this.src;">
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Product details and add to cart form -->
    <div class="product-details">
      <h2><?= htmlspecialchars($product['title']) ?></h2>
      <p><strong>Price:</strong> R<?= number_format($product['price'], 2) ?></p>
      <p><strong>Seller:</strong> <?= htmlspecialchars($product['store_name']) ?></p>
      <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>

      <form method="POST" action="/rainbow_market/buyer/add_to_cart.php">
        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
        <label for="quantity"><strong>Quantity:</strong></label>
        <input type="number" name="quantity" id="quantity" value="1" min="1" required>
        <button type="submit">Add to Cart</button>
      </form>
    </div>
  </main>

  <!-- Back to category link -->
  <div class="back-link">
    <a href="<?= htmlspecialchars($backLink) ?>">&larr; Back to Category</a>
  </div>

  <?php include $_SERVER['DOCUMENT_ROOT'] . '/rainbow_market/footer.php'; ?>
</body>
</html>
