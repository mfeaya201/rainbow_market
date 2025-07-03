<?php

session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/rainbow_market/admin/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'buyer') {
    header("Location: /rainbow_market/registration.html");
    exit();
}

// Check if cart is set and not empty
if (!isset($_SESSION['cart']) || count($_SESSION['cart']) === 0) {
    header("Location: /rainbow_market/buyer/cart.php?error=Your cart is empty.");
    exit();
}

$user_id = $_SESSION['user_id'];

// Calculate total price
$total = 0;
foreach ($_SESSION['cart'] as $item) {
    $total += $item['price'] * $item['quantity'];
}

// Fetch wallet balance
$stmt = $conn->prepare("SELECT balance FROM wallet WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$wallet = $result->fetch_assoc();
$current_balance = $wallet ? floatval($wallet['balance']) : 0.00;
$stmt->close();

// If form not submitted, show payment method selection
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['payment_method'])) {
?>
<!DOCTYPE html>
<html>
<head>
  <title>Checkout - Rainbow Market</title>
  <link rel="stylesheet" href="/rainbow_market/styles/styles.css">
</head>
<body>
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/rainbow_market/navbar.php'; ?>
  <main class="cart-section">
    <h2>Checkout</h2>
    <p>Total: R<?= number_format($total, 2) ?></p>
    <form method="POST">
      <label>
        <input type="radio" name="payment_method" value="wallet" required>
        Pay with Wallet (Balance: R<?= number_format($current_balance, 2) ?>)
      </label><br>
      <label>
        <input type="radio" name="payment_method" value="cod" required>
        Cash on Delivery
      </label><br><br>
      <button type="submit">Place Order</button>
    </form>
  </main>
</body>
</html>
<?php
    exit();
}

$payment_method = $_POST['payment_method'] ?? 'wallet';

if ($payment_method === 'wallet') {
    // Check if buyer has enough funds
    if ($current_balance < $total) {
        header("Location: /rainbow_market/buyer/cart.php?error=Insufficient wallet balance.");
        exit();
    }
    // Deduct funds from wallet
    $stmt = $conn->prepare("UPDATE wallet SET balance = balance - ? WHERE user_id = ?");
    $stmt->bind_param("di", $total, $user_id);
    if (!$stmt->execute()) {
        header("Location: /rainbow_market/buyer/cart.php?error=Failed to deduct wallet funds.");
        exit();
    }
    $stmt->close();
    $order_status = 'pending';
    $payment_status = 'paid';
} else {
    // Cash on delivery
    $order_status = 'pending';
    $payment_status = 'cod';
}

// Insert each cart item as an order
foreach ($_SESSION['cart'] as $item) {
    $product_id = $item['id'];
    $quantity = $item['quantity'];
    $created_at = date('Y-m-d H:i:s');

    $stmt = $conn->prepare("INSERT INTO orders (buyer_id, product_id, quantity, status, created_at, payment_method) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iiisss", $user_id, $product_id, $quantity, $order_status, $created_at, $payment_status);
    $stmt->execute();
    $stmt->close();

    // Decrease stock
    $stmt = $conn->prepare("UPDATE products SET stock = stock - ? WHERE id = ? AND stock >= ?");
    $stmt->bind_param("iii", $quantity, $product_id, $quantity);
    $stmt->execute();
    $stmt->close();

    // After decreasing stock
    $stmt = $conn->prepare("UPDATE products SET status = 'inactive' WHERE id = ? AND stock <= 0");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $stmt->close();
}

// Clear the cart
$_SESSION['cart'] = [];

// Save cart before clearing
$cartItems = $_SESSION['cart'];
$_SESSION['cart'] = [];

// Fetch seller names for the products in the order
$sellerNames = [];
foreach ($cartItems as $item) {
    $stmt = $conn->prepare("SELECT s.store_name FROM products p JOIN sellers s ON p.seller_id = s.id WHERE p.id = ?");
    $stmt->bind_param("i", $item['id']);
    $stmt->execute();
    $stmt->bind_result($store_name);
    $stmt->fetch();
    $stmt->close();
    if ($store_name && !in_array($store_name, $sellerNames)) {
        $sellerNames[] = $store_name;
    }
}

// Build the seller message
if (count($sellerNames) === 1) {
    $deliveryMsg = "The seller <strong>" . htmlspecialchars($sellerNames[0]) . "</strong> will contact you regarding which delivery service will be used.";
} else {
    $deliveryMsg = "The sellers <strong>" . htmlspecialchars(implode(', ', $sellerNames)) . "</strong> will contact you regarding which delivery service will be used.";
}

// Show confirmation and delivery message
echo "<div class='checkout-success'>";
echo "<h2>Thank you for your purchase!</h2>";
echo "<p>Your order has been placed successfully.</p>";
echo "<p>$deliveryMsg</p>";
echo "<a href='/rainbow_market/buyer/buyer_orders.php'>View your orders</a>";
echo "</div>";

// ...do not redirect if you want to show this message...
?>