<?php

session_start();

if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

include $_SERVER['DOCUMENT_ROOT'] . '/rainbow_market/admin/db.php';

// Handle quantity update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['update_id'], $_POST['update_qty'])) {
    $update_id = (int)$_POST['update_id'];
    $update_qty = max(1, (int)$_POST['update_qty']);

    // Check available stock
    $stmt = $conn->prepare("SELECT stock FROM products WHERE id = ?");
    $stmt->bind_param("i", $update_id);
    $stmt->execute();
    $stmt->bind_result($stock);
    $stmt->fetch();
    $stmt->close();

    if ($update_qty > $stock) {
      $update_qty = $stock; 
    }

    foreach ($_SESSION['cart'] as &$c) {
      if ($c['id'] == $update_id) {
        $c['quantity'] = $update_qty;
        break;
      }
    }
    unset($c);
  }
  // Handle remove
  if (isset($_POST['remove_id'])) {
    $remove_id = (int)$_POST['remove_id'];
    $_SESSION['cart'] = array_filter($_SESSION['cart'], function($c) use ($remove_id) {
      return $c['id'] != $remove_id;
    });
  }
  // Refresh to avoid resubmission
  header("Location: cart.php");
  exit();
}

$cartItems = [];
$totalPrice = 0;
$cartQuantities = [];
if (!empty($_SESSION['cart'])) {
  foreach ($_SESSION['cart'] as $c) {
    $cartQuantities[$c['id']] = $c['quantity'];
  }
  $ids = array_column($_SESSION['cart'], 'id');
  $placeholders = implode(',', array_fill(0, count($ids), '?'));
  $stmt = $conn->prepare("SELECT id, title, price, images FROM products WHERE id IN ($placeholders)");
  $stmt->bind_param(str_repeat('i', count($ids)), ...$ids);
  $stmt->execute();
  $result = $stmt->get_result();
  $cartItems = $result->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Shopping Cart - Rainbow Market</title>
  <link rel="stylesheet" href="/rainbow_market/styles/styles.css">
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
  />
  <style>
.cart-section {
  padding: 40px 20px;
  max-width: 1000px;
  margin: auto;
}

.cart-container {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.cart-item {
  display: flex;
  align-items: center;
  background: #f7fff6;
  padding: 12px 16px;
  border-radius: 12px;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
  gap: 20px;
}

.cart-item img {
  width: 100px;
  height: 100px;
  object-fit: cover;
  border-radius: 10px;
  flex-shrink: 0;
}

.cart-details {
  display: flex;
  flex: 1;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
  gap: 10px;
}

.cart-title {
  font-size: 1.1rem;
  font-weight: bold;
  color: var(--text-dark);
  flex: 2;
}

.cart-quantity input {
  width: 60px;
  padding: 5px;
  font-size: 1rem;
}

.cart-price {
  font-size: 1.1rem;
  font-weight: bold;
  color: #333;
}

.cart-summary {
  margin-top: 30px;
  text-align: right;
}

.cart-total {
  font-size: 1.4rem;
  font-weight: bold;
  margin-bottom: 12px;
}

.cart-checkout-btn {
  padding: 14px 30px;
  font-size: 1.2rem;
  background-color: #6fb385;
  border: none;
  border-radius: 10px;
  color: white;
  cursor: pointer;
  transition: background-color 0.3s;
}

.cart-checkout-btn:hover {
  background-color: #558f6c;
}
</style>
</head>
<body>
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/rainbow_market/navbar.php'; ?>

  <main class="cart-section">
    <h2>Your Shopping Cart</h2>
    <?php if (count($cartItems) > 0): ?>
      <div class="cart-container">
        <?php foreach ($cartItems as $item): ?>
          <?php
            $images = json_decode($item['images'], true);
            $main_image = isset($images[0]) ? $images[0] : 'images/default.jpg';
            $quantity = $cartQuantities[$item['id']] ?? 1;
            $itemTotal = $item['price'] * $quantity;
            $totalPrice += $itemTotal;
          ?>
          <div class="cart-item">
            <img src="/rainbow_market/<?= htmlspecialchars($main_image) ?>" alt="<?= htmlspecialchars($item['title']) ?>">
            <div class="cart-details">
              <div class="cart-title"><?= htmlspecialchars($item['title']) ?></div>
              <div class="cart-quantity">
                Qty:
                <form method="POST" action="cart.php" style="display:inline;">
                  <input type="hidden" name="update_id" value="<?= $item['id'] ?>">
                  <input type="number" min="1" name="update_qty" value="<?= $quantity ?>" style="width:60px;">
                  <button type="submit" name="update" style="padding:2px 8px;">Update</button>
                </form>
              </div>
              <div class="cart-price">R<?= number_format($itemTotal, 2) ?></div>
              <form method="POST" action="cart.php" style="display:inline;">
                <input type="hidden" name="remove_id" value="<?= $item['id'] ?>">
                <button type="submit" name="remove" style="padding:2px 8px;background:#e74c3c;color:#fff;border:none;border-radius:4px;cursor:pointer;">Remove</button>
              </form>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

      <div class="cart-summary">
        <div class="cart-total">Total: R<?= number_format($totalPrice, 2) ?></div>
        <form method="POST" action="checkout.php">
          <button class="cart-checkout-btn" type="submit">Checkout</button>
        </form>
      </div>
    <?php else: ?>
      <p>Your cart is empty.</p>
    <?php endif; ?>
  </main>

  <?php include $_SERVER['DOCUMENT_ROOT'] . '/rainbow_market/footer.php'; ?>
</body>
</html>
