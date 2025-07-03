<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start session and check if user is logged in
include $_SERVER['DOCUMENT_ROOT'] . '/rainbow_market/seller/auth_seller.php';
// Connect to database
include $_SERVER['DOCUMENT_ROOT'] . '/rainbow_market/admin/db.php';

// Fetch seller id from sellers table using user_id
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT id FROM sellers WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($seller_id);
$stmt->fetch();
$stmt->close();

// Fetch seller name for welcome banner
$seller_name = '';
$stmt = $conn->prepare("SELECT name FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($seller_name);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Add Products - Rainbow Market</title>
    <link rel="stylesheet" href="/rainbow_market/styles/styles.css" />
    <link rel="stylesheet" href="/rainbow_market/styles/dashboard.css" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
    />
  </head>
  <body>
     <!--Navbar-->
     <?php include $_SERVER['DOCUMENT_ROOT'] . '/rainbow_market/seller/seller-navbar.php'; ?>

    <main class="seller-dashboard">
      <!--Sidebar-->
      <?php include $_SERVER['DOCUMENT_ROOT'] . '/rainbow_market/seller/seller-sidebar.php'; ?>

      <section main="add-item-page">
        <div class="add-item-container">
          <h1>Add New Item</h1>
          <div style="margin-bottom:1rem;">
            <h2>Welcome back, <?= htmlspecialchars($seller_name) ?>!</h2>
          </div>
          <form class="add-item-form" method="POST" action="/rainbow_market/seller/upload_products.php" enctype="multipart/form-data">
            <!--Upload Images-->
            <div class="image-upload">
              <label for="images">Upload up to 6 images:</label>
              <div class="image-grid">
                <input type="file" name="images[]" id="images" multiple accept="image/*" required />
              </div>
            </div>

            <!--Title -->
            <div>
              <label for="title">Product Title:</label>
              <input
                type="text"
                name="title"
                id="title"
                placeholder="e.g. Vintage Leather Jacket"
                required
              />
            </div>

            <!--Condition-->
            <div>
              <label for="condition">Condition:</label>
              <select name="condition" id="condition" required>
                <option value="">Select condition</option>
                <option value="new">
                  New – Item has original tags, never worn/used
                </option>
                <option value="like-new">
                  Like New – Worn once or twice, no flaws
                </option>
                <option value="good">
                  Good – Gently used with minimal signs of wear
                </option>
                <option value="fair">Fair – Used with noticeable flaws</option>
              </select>
            </div>

            <!--Description-->
            <div>
              <label for="description">Item Description:</label>
              <textarea
                name="description"
                id="description"
                placeholder="Describe the item clearly. Include brand, material, size, color, visible wear or tear, and any flaws. Be honest to avoid disputes."
                rows="4"
                required
              ></textarea>
            </div>

            <!--Category-->
            <div>
              <label for="category">Category:</label>
              <select name="category" id="category" required>
                <option value="">Select a category</option>
                <option value="fashion">Fashion</option>
                <option value="tech">Tech</option>
                <option value="beauty">Beauty</option>
                <option value="household">Household</option>
                <option value="craft">Craft</option>
                <option value="books">Books</option>
                <option value="other">Other</option>
              </select>
            </div>

            <!--Stock-->
            <div>
              <label for="stock">Stock:</label>
              <input type="number" name="stock" id="stock" min="1" value="1" required />
            </div>

            <!--Province (optional)-->
            <div>
              <label for="province">Province (Optional):</label>
              <select name="province" id="province">
                <option value="">Select Province</option>
                <option>Gauteng</option>
                <option>Western Cape</option>
                <option>KwaZulu-Natal</option>
                <option>Eastern Cape</option>
                <option>Free State</option>
                <option>Limpopo</option>
                <option>Mpumalanga</option>
                <option>North West</option>
                <option>Northern Cape</option>
              </select>
            </div>

            <!--City (optional)-->
            <div>
              <label for="city">City (Optional):</label>
              <input type="text" name="city" id="city" placeholder="Enter city" />
            </div>

            <!--Delivery Options-->
            <div>
              <h3>Delivery</h3>
              <p>Select delivery methods. Buyer pays on purchase.</p>
              <select name="delivery" id="delivery" required>
                <option value="">Select a courier</option>
                <option value="paxi">PAXI – From R59 (7–9 days, up to 5kg)</option>
                <option value="aramex">Aramex – R99 (1–2 working days)</option>
                <option value="postnet">
                  PostNet – R109 (2–4 days, up to 5kg)
                </option>
              </select>
              <div class="condition-info">
                Prices may vary by weight. Choose the option that suits your product
                best.
              </div>
            </div>

            <!--Price-->
            <div>
              <label for="price">Price (Rands):</label>
              <input type="number" name="price" id="price" min="1" required />
            </div>

            <!--Submit-->
            <button class="list-btn" type="submit">List Item</button>
          </form>
        </div>
      </section>
    </main>

    <script src="/rainbow_market/scripts/components.js"></script>
  </body>
</html>
