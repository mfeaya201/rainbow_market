<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start session and check if user is logged in
include $_SERVER['DOCUMENT_ROOT'] . '/rainbow_market/seller/auth_seller.php';
// Connect to database
include $_SERVER['DOCUMENT_ROOT'] . '/rainbow_market/admin/db.php';

$user_id = $_SESSION['user_id'];

// Check if seller row exists
$stmt = $conn->prepare("SELECT id FROM sellers WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($seller_id);
$stmt->fetch();
$stmt->close();

if (!$seller_id) {
    // Create a new seller row if not exists
    // You can use the user's name as the default store name, or prompt for one
    $stmt = $conn->prepare("SELECT name FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($user_name);
    $stmt->fetch();
    $stmt->close();

    $default_store_name = $user_name . "'s Store";
    $stmt = $conn->prepare("INSERT INTO sellers (user_id, store_name, status) VALUES (?, ?, 'active')");
    $stmt->bind_param("is", $user_id, $default_store_name);
    $stmt->execute();
    $seller_id = $stmt->insert_id;
    $stmt->close();
} else {
    // If seller_id was found, use it as normal
}

// Now $seller_id is guaranteed to be set for the rest of your code

// Form data
$title = trim($_POST['title'] ?? '');
$condition = $_POST['condition'] ?? '';
$description = $_POST['description'] ?? '';
$category = $_POST['category'] ?? '';
$quantity = $_POST['quantity'] ?? 0;
$province = $_POST['province'] ?? '';
$city = $_POST['city'] ?? '';
$delivery = $_POST['delivery'] ?? '';
$price = $_POST['price'] ?? 0;
$stock = $_POST['stock'] ?? 0;

// Validate required fields
if (!$title || !$condition || !$description || !$category || !$stock || !$delivery || !$price) {
    echo "Please fill in all required fields";
    exit();
}

// Handle image uploads
$image_paths = [];
if (!empty($_FILES['images']['name'][0])) {
    $total_files = count($_FILES['images']['name']);
    $max_files = min($total_files, 6); // Limit to 6 images

    for ($i = 0; $i < $max_files; $i++) {
        $tmp_name = $_FILES['images']['tmp_name'][$i];
        $name = basename($_FILES['images']['name'][$i]);
        $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'avif'];

        if (in_array($ext, $allowed)) {
            $new_name = uniqid('img_', true) . '.' . $ext;
            $upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/rainbow_market/uploads/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            $target = $upload_dir . $new_name;
            if (move_uploaded_file($tmp_name, $target)) {
                $image_paths[] = 'uploads/' . $new_name;
            }
        }
    }
}

// Save image paths as JSON, fallback to existing images if none uploaded
if (isset($existing_image_json)) {
    $image_json = !empty($image_paths) ? json_encode($image_paths) : $existing_image_json;
} else {
    $image_json = json_encode($image_paths);
}

// Insert into database
$stmt = $conn->prepare("INSERT INTO products (seller_id, images, title, `condition`, description, category, stock, province, city, delivery, price, status)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'active')");
$stmt->bind_param(
    "isssssisssd",
    $seller_id,
    $image_json,
    $title,
    $condition,
    $description,
    $category,
    $stock,
    $province,
    $city,
    $delivery,
    $price
);

if ($stmt->execute()) {
    header("Location: /rainbow_market/seller/myproducts.php");
    exit();
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();

?>
