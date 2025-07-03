<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include $_SERVER['DOCUMENT_ROOT'] . '/rainbow_market/seller/auth_seller.php';
include $_SERVER['DOCUMENT_ROOT'] . '/rainbow_market/admin/db.php';

if (!isset($_GET['id'])) exit;

$id = intval($_GET['id']);
$user_id = $_SESSION['user_id'];

// Fetch seller_id from sellers table using user_id
$stmt = $conn->prepare("SELECT id FROM sellers WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($seller_id);
$stmt->fetch();
$stmt->close();

// Get current status
$stmt = $conn->prepare("SELECT status FROM products WHERE id = ? AND seller_id = ?");
$stmt->bind_param("ii", $id, $seller_id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
  $newStatus = $row['status'] === 'active' ? 'out of stock' : 'active';
  $update = $conn->prepare("UPDATE products SET status = ? WHERE id = ? AND seller_id = ?");
  $update->bind_param("sii", $newStatus, $id, $seller_id);
  $update->execute();
  header("HTTP/1.1 204 No Content");
} else {
  header("HTTP/1.1 404 Not Found");
}
exit();
?>
