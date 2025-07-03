<?php

session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'buyer') {
    header("Location: /rainbow_market/registration.html");
    exit();
}

include $_SERVER['DOCUMENT_ROOT'] . '/rainbow_market/admin/db.php';

$buyer_id = $_SESSION['user_id'];

// Fetch buyer wallet balance
$stmt = $conn->prepare("SELECT balance FROM wallet WHERE user_id = ?");
$stmt->bind_param("i", $buyer_id);
$stmt->execute();
$result = $stmt->get_result();
$wallet = $result->fetch_assoc();
$balance = $wallet ? number_format($wallet['balance'], 2) : "0.00";
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Buyer Dashboard - Rainbow Market</title>
    <link rel="stylesheet" href="/rainbow_market/styles/styles.css" />
    <link rel="stylesheet" href="/rainbow_market/styles/dashboard.css" />
    <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
  />
  </head>
  <body>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/rainbow_market/navbar.php'; ?>

    <main class="buyer-dashboard">
      <h1>
        Welcome,
        <?= htmlspecialchars($_SESSION['username'] ?? 'Buyer') ?>
      </h1>

      <section class="dashboard-overview">
        <div class="dashboard-card">
          <h3>My Wallet</h3>
          <p>Balance: R<?= $balance ?></p>
          <a href="wallet.php" class="btn">View Wallet</a>
        </div>
        <div class="dashboard-card">
          <h3>My Orders</h3>
          <a href="buyer_orders.php" class="btn">View Orders</a>
        </div>
        <div class="dashboard-card">
          <h3>Messages</h3>
          <a href="buyer_messages.php" class="btn">View Messages</a>
        </div>
        <div class="dashboard-card">
          <h3>Settings</h3>
          <a href="buyer_settings.php" class="btn">Account Settings</a>
        </div>
      </section>
    </main>

    <?php include $_SERVER['DOCUMENT_ROOT'] . '/rainbow_market/footer.php'; ?>

    <script src="/rainbow_market/scripts/components.js"></script>
  </body>
</html>
