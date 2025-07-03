<?php

session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/rainbow_market/admin/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Sanitize and cast inputs here:
  $product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : null;
  $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

  // Check for invalid quantity here:
  if (!$product_id || $quantity < 1) {
    header("Location: /rainbow_market/buyer/cart.php?error=Invalid product or quantity");
    exit();
  }

  // Fetch product info
  $stmt = $conn->prepare("SELECT id, title, price, images FROM products WHERE id = ? AND status = 'active'");
  $stmt->bind_param("i", $product_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $product = $result->fetch_assoc();

  if (!$product) {
    header("Location: /rainbow_market/buyer/cart.php?error=Product not found");
    exit();
  }

  // Create cart item structure
  $item = [
    'id' => $product['id'],
    'title' => $product['title'],
    'price' => $product['price'],
    'images' => $product['images'],
    'quantity' => (int)$quantity,
  ];

  // Initialize cart session if not set
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }

  // Check if product is already in cart
  $found = false;
  foreach ($_SESSION['cart'] as &$cartItem) {
    if ($cartItem['id'] === $item['id']) {
      $cartItem['quantity'] += $item['quantity'];
      $found = true;
      break;
    }
  }
  unset($cartItem); 
  if (!$found) {
    $_SESSION['cart'][] = $item;
  }

  header("Location: /rainbow_market/buyer/cart.php?success=Added to cart");
  exit();
} else {
  header("Location: /rainbow_market/index.php");
  exit();
}
?>
