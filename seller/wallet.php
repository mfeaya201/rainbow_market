<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start session and check if user is logged in
include $_SERVER['DOCUMENT_ROOT'] . '/rainbow_market/seller/auth_seller.php';
// Connect to database
include $_SERVER['DOCUMENT_ROOT'] . '/rainbow_market/admin/db.php';

// Get seller ID
$seller_id = $_SESSION['user_id'];

// Fetch seller name for welcome banner
$seller_name = '';
$stmt = $conn->prepare("SELECT name FROM users WHERE id = ?");
$stmt->bind_param("i", $seller_id);
$stmt->execute();
$stmt->bind_result($seller_name);
$stmt->fetch();
$stmt->close();

// Fetch wallet balance
$wallet_stmt = $conn->prepare("SELECT balance FROM wallet WHERE user_id = ?");
$wallet_stmt->bind_param("i", $seller_id);
$wallet_stmt->execute();
$wallet_result = $wallet_stmt->get_result();
$wallet = $wallet_result->fetch_assoc();

// Fetch recent transactions (latest 10)
$txn_stmt = $conn->prepare("SELECT amount, type, reference, created_at FROM transactions WHERE user_id = ? ORDER BY created_at DESC LIMIT 10");
$txn_stmt->bind_param("i", $seller_id);
$txn_stmt->execute();
$txn_result = $txn_stmt->get_result();
?>

<!DOCTYPE html> 
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Seller Wallet - Rainbow Market</title>
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

      <section class="dashboard-main">
        <div style="margin-bottom:1rem;">
          <h2>Welcome back, <?= htmlspecialchars($seller_name) ?>!</h2>
        </div>
        <h2>My Wallet</h2>

        <!-- Wallet Balance -->
        <div class="wallet-balance">
          <h3>Available Balance:</h3>
          <?php
            $balance = isset($wallet['balance']) && $wallet['balance'] !== null ? $wallet['balance'] : 0;
          ?>
          <p class="balance-amount">R <?= number_format($balance, 2) ?></p>
        </div>

        <!-- Recent Transactions -->
        <h3>Recent Transactions</h3>
        <?php if ($txn_result->num_rows > 0): ?>
          <table class="product-table">
            <thead>
              <tr>
                <th>Date</th>
                <th>Type</th>
                <th>Amount</th>
                <th>Reference</th>
              </tr>
            </thead>
            <tbody>
              <?php while ($txn = $txn_result->fetch_assoc()): ?>
                <tr>
                  <td><?= date('Y-m-d H:i', strtotime($txn['created_at'])) ?></td>
                  <td>
                    <span class="status <?= $txn['type'] === 'credit' ? 'active' : 'pending' ?>">
                      <?= ucfirst($txn['type']) ?>
                    </span>
                  </td>
                  <td>R <?= number_format($txn['amount'], 2) ?></td>
                  <td><?= htmlspecialchars($txn['reference'] ?? '-') ?></td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        <?php else: ?>
          <p>No recent transactions.</p>
        <?php endif; ?>
      </section>
    </main>

    <script src="/rainbow_market/scripts/components.js"></script>
  </body>
</html>
