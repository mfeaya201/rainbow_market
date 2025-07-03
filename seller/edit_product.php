<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// auth and DB connection
include $_SERVER['DOCUMENT_ROOT'] . '/rainbow_market/seller/auth_seller.php';
include $_SERVER['DOCUMENT_ROOT'] . '/rainbow_market/admin/db.php';

// Get seller_id from sellers table using user_id
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT id FROM sellers WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($seller_id);
$stmt->fetch();
$stmt->close();

if (!isset($_GET['id'])) {
    echo "Product ID is missing.";
    exit;
}

$product_id = $_GET['id'];

// Fetch product
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ? AND seller_id = ?");
$stmt->bind_param("ii", $product_id, $seller_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    echo "Product not found or you do not have permission.";
    exit;
}

// Fetch seller name for welcome banner
$seller_name = '';
$stmt = $conn->prepare("SELECT name FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($seller_name);
$stmt->fetch();
$stmt->close();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = floatval($_POST['price']);
    $stock = $_POST['stock'];

    // Image upload
    $uploaded_images = [];

    if (!empty($_FILES['images']['name'][0])) {
        foreach ($_FILES['images']['tmp_name'] as $index => $tmp_name) {
            // ✅ Check file size (max 2MB)
            if ($_FILES['images']['size'][$index] > 2000000) {
                echo "One of the images is too large (max 2MB). Skipping it.<br>";
                continue;
            }

            // ✅ Check MIME type 
            $file_type = mime_content_type($tmp_name);
            if (strpos($file_type, 'image') !== 0) {
                echo "One of the files is not a valid image. Skipping it.<br>";
                continue;
            }

            $originalName = $_FILES['images']['name'][$index];
            $targetDir = "uploads/";
            $uniqueName = time() . '_' . basename($originalName);
            $targetFile = $targetDir . $uniqueName;

            if (move_uploaded_file($tmp_name, $targetFile)) {
                $uploaded_images[] = $targetFile;
            }
        }
    } else {
        // If no new image uploaded, keep existing ones
        $uploaded_images = json_decode($product['images'], true);
    }

    $images_json = json_encode($uploaded_images);

    $update = $conn->prepare("UPDATE products SET title = ?, description = ?, price = ?, images = ?, stock = ? WHERE id = ? AND seller_id = ?");
    $update->bind_param("ssdssii", $title, $description, $price, $images_json, $stock, $product_id, $seller_id);

    if ($update->execute()) {
        header("Location: /rainbow_market/seller/myproducts.php?msg=Product+updated");
        exit;
    } else {
        echo "Update failed.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Edit Product</title>
  <link rel="stylesheet" href="/rainbow_market/styles/styles.css" />
  <link rel="stylesheet" href="/rainbow_market/styles/dashboard.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
</head>
<body>
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/rainbow_market/seller/seller-navbar.php'; ?>
  <main class="seller-dashboard">
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/rainbow_market/seller/seller-sidebar.php'; ?>
    <section class="dashboard-main">
      <div style="margin-bottom:1rem;">
        <h2>Welcome back, <?= htmlspecialchars($seller_name) ?>!</h2>
      </div>
      <h2>Edit Product</h2>
      <form method="POST" enctype="multipart/form-data" class="edit-form">
        <label>Product Title:</label>
        <input type="text" name="title" value="<?= htmlspecialchars($product['title']) ?>" required />

        <label>Description:</label>
        <textarea name="description" required><?= htmlspecialchars($product['description']) ?></textarea>

        <label>Price (R):</label>
        <input type="number" step="0.01" name="price" value="<?= $product['price'] ?>" required />

        <label>Stock:</label>
        <input type="number" name="stock" value="<?= $product['stock'] ?>" required />

        <label>Current Images:</label><br>
        <?php
          $existing_images = json_decode($product['images'], true);
          foreach ($existing_images as $img) {
              echo "<img src='/$img' class='product-thumb' style='max-width:100px; margin-right:10px;' />";
          }
        ?>

        <label>Upload New Images (optional):</label>
        <input type="file" name="images[]" multiple accept="image/*" />

        <button type="submit">Update Product</button>
      </form>
    </section>
  </main>
</body>
</html>
