<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: /rainbow_market/admin-login.html");
    exit();
}
?>
