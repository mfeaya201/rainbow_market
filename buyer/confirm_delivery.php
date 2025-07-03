<?php

// Start session and include database connection
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/rainbow_market/admin/db.php';

// Check if order_id is provided in the URL
if (!isset($_GET['order_id'])) {
    echo "Invalid request: Order ID missing.";
    exit();
}
$order_id = (int)$_GET['order_id'];
$buyer_id = $_SESSION['user_id'] ?? null;

// Validate order_id and buyer_id
if (!$order_id || !$buyer_id) {
  echo "Invalid request.";
  exit();
}

// Get the order for this buyer
$order_sql = "SELECT * FROM orders WHERE id = ? AND buyer_id = ?";
$stmt = $conn->prepare($order_sql);
$stmt->bind_param("ii", $order_id, $buyer_id);
$stmt->execute();
$order_result = $stmt->get_result();
$order = $order_result->fetch_assoc();

// Check if order exists and is not already completed
if (!$order) {
  echo "Order not found.";
  exit();
}
if ($order['status'] === 'completed') {
  echo "Order already completed.";
  exit();
}
if ($order['status'] !== 'out for delivery') {
  echo "Order cannot be confirmed yet. Please wait for the seller to mark it as out for delivery.";
  exit();
}

// Retrieve product and seller information
$product_sql = "SELECT * FROM products WHERE id = ?";
$stmt2 = $conn->prepare($product_sql);
$stmt2->bind_param("i", $order['product_id']);
$stmt2->execute();
$product_result = $stmt2->get_result();
$product = $product_result->fetch_assoc();

$seller_id = $product['seller_id'];
$price = $product['price'];
$quantity = $order['quantity'];
$total_amount = $price * $quantity;

// Mark the order as completed
$update_order = $conn->prepare("UPDATE orders SET status = 'completed' WHERE id = ?");
$update_order->bind_param("i", $order_id);
$update_order->execute();

// Get seller's user_id from the sellers table using the product's seller_id
$stmt = $conn->prepare("SELECT user_id FROM sellers WHERE id = ?");
$stmt->bind_param("i", $product['seller_id']);
$stmt->execute();
$stmt->bind_result($seller_user_id);
$stmt->fetch();
$stmt->close();

// Now update the wallet using $seller_user_id
$conn->query("UPDATE wallet SET balance = balance + $total_amount WHERE user_id = $seller_user_id");

// Update seller's total_sales
$conn->query("UPDATE sellers SET total_sales = total_sales + $total_amount WHERE id = $product[seller_id]");

// Record the transaction in the transactions table
$insert_txn = $conn->prepare("INSERT INTO transactions (user_id, amount, type, status) VALUES (?, ?, 'release', 'completed')");
$insert_txn->bind_param("id", $seller_user_id, $total_amount);
$insert_txn->execute();

// Notify the user
echo "Delivery confirmed. Payment released to seller.";
?>
<a href="confirm_delivery.php?order_id=<?= $order['order_id'] ?>">Confirm Delivery</a>
