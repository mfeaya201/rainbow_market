<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include $_SERVER['DOCUMENT_ROOT'] . '/rainbow_market/admin/db.php';

// Get email and password from the form
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

// Use prepared statements to prevent SQL injection
$stmt = $conn->prepare("SELECT * FROM admins WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($admin = $result->fetch_assoc()) {
    // Check password (plain text, but should use password_hash in production)
    if ($admin['password'] === $password) {
        session_start();
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_name'] = $admin['name'];
        $_SESSION['admin_email'] = $admin['email'];
        $_SESSION['role'] = 'admin';

        header("Location: /rainbow_market/admin/dashboard.php");
        exit();
    } else {
        echo "Invalid email or password.";
    }
} else {
    echo "Invalid email or password.";
}

$stmt->close();
$conn->close();
?>
