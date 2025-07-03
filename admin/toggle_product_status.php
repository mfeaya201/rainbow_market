<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
//start session and check if admin is logged in
include $_SERVER['DOCUMENT_ROOT'] . '/rainbow_market/admin/auth.php';
//connect to the database
include $_SERVER['DOCUMENT_ROOT'] . '/rainbow_market/admin/db.php';

//check if product id and current status are sent
if (isset($_POST['product_id']) && isset($_POST['current_status'])) {
    $product_id = (int)$_POST['product_id'];
    $current_status = $_POST['current_status'];

    //Determine the new status
    $new_status = ($current_status === 'active') ? 'out of stock' : 'active';

    //Update the status in the database using prepared statements
    $stmt = $conn->prepare("UPDATE products SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $new_status, $product_id);

    if ($stmt->execute()) {
        //redirect back to the products page
        header("Location: /rainbow_market/admin/products.php");
        exit();
    } else {
        echo "Error updating status: " . $stmt->error;
    }
    $stmt->close();
} else {
    echo "Missing product ID or status";
}
?>