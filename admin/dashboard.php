<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//start session and check if admin is logged in
include $_SERVER['DOCUMENT_ROOT'] . '/rainbow_market/admin/auth.php';
//connect to the database
include $_SERVER['DOCUMENT_ROOT'] . '/rainbow_market/admin/db.php';

// Get total users
$total_users = $conn->query("SELECT COUNT(*) as total FROM users")->fetch_assoc()['total'];

// Get total sellers
$total_sellers = $conn->query("SELECT COUNT(*) as total FROM sellers WHERE status='active'")->fetch_assoc()['total'];

// Get total products
$total_products = $conn->query("SELECT COUNT(*) as total FROM products")->fetch_assoc()['total'];

// Get unresolved reports
$unresolved_reports = $conn->query("SELECT COUNT(*) as total FROM reports WHERE status='pending'")->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard - Rainbow Market</title>
    <link rel="stylesheet" href="/rainbow_market/styles/admin.css" />
    <link rel="stylesheet" href="/rainbow_market/styles/dashboard.css" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
    />
  </head>
  <body>
    <!--Navbar-->
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/rainbow_market/admin/navbar.php'; ?>

    <main class="admin-dashboard">
      <!--Sidebar-->
      <?php include $_SERVER['DOCUMENT_ROOT'] . '/rainbow_market/admin/sidebar.php'; ?>

      <!--Dashboard Content-->
      <section class="admin-main">
        <h1>Admin Dashboard</h1>
        <div class="admin-cards">
          <div class="card">
            <i class="fas fa-users card-icon"></i>
            <h3>Total Users</h3>
            <p id="total-users"><?= $total_users ?></p>
          </div>
          <div class="card">
            <i class="fas fa-store card-icon"></i>
            <h3>Active Sellers</h3>
            <p id="active-sellers"><?= $total_sellers ?></p>
          </div>
          <div class="card">
            <i class="fas fa-box card-icon"></i>
            <h3>Products Listed</h3>
            <p id="total-products"><?= $total_products ?></p>
          </div>
          <div class="card">
            <i class="fas fa-flag card-icon"></i>
            <h3>Unresolved Reports</h3>
            <p id="unresolved-reports"><?= $unresolved_reports ?></p>
          </div>
        </div>
      </section>
    </main>

    <script src="/rainbow_market/scripts/components.js"></script>
  </body>
</html>
