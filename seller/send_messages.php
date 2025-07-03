<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/rainbow_market/admin/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'seller') {
  header("Location: /rainbow_market/registration.html");
  exit();
}

$sender_id = $_SESSION['user_id'];
$receiver_id = $_POST['receiver_id'];
$message = trim($_POST['message']);

if ($message !== '') {
  $stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, message, created_at) VALUES (?, ?, ?, NOW())");
  $stmt->bind_param("iis", $sender_id, $receiver_id, $message);
  $stmt->execute();
}

header("Location: /rainbow_market/seller/messages.php?buyer_id=$receiver_id");
exit();
?>
