<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/rainbow_market/admin/db.php';

// Check if it's an admin logout
if (isset($_SESSION['admin_id']) && isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    // Clear admin session
    $_SESSION = [];
    session_destroy();

    // Redirect to admin login
    header("Location: /rainbow_market/admin-login.html");
    exit();
}

// Check if it's a seller or buyer logout
if (isset($_SESSION['user_id']) && isset($_SESSION['role']) && ($_SESSION['role'] === 'seller' || $_SESSION['role'] === 'buyer')) {
    // Clear user session
    $_SESSION = [];
    session_destroy();

    // Redirect to user registration/login
    header("Location: /rainbow_market/registration.html");
    exit();
}

// Default: just destroy session and redirect to home
$_SESSION = [];
session_destroy();
header("Location: /rainbow_market/index.html");
exit();
?>
