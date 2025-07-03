<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//start session and check if admin is logged in
include $_SERVER['DOCUMENT_ROOT'] . '/rainbow_market/admin/auth.php';
//connect to the database
include $_SERVER['DOCUMENT_ROOT'] . '/rainbow_market/admin/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'])) {
    $user_id = (int) $_POST['user_id'];
    $stmt = $conn->prepare("UPDATE users SET role = 'banned' WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();
}

header("Location: users.php");
exit();
?>