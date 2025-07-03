<?php

session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/rainbow_market/admin/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'buyer') {
    header("Location: /rainbow_market/registration.html");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = '';
$error = '';

// Before updating, ensure wallet row exists
$stmt = $conn->prepare("SELECT id FROM wallet WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows === 0) {
    $stmt->close();
    $stmt = $conn->prepare("INSERT INTO wallet (user_id, balance) VALUES (?, 0)");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
}
$stmt->close();

// Handle top-up form
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['topup_amount'])) {
    $amount = floatval($_POST['topup_amount']);
    $method = $_POST['topup_method'] ?? '';
    $card_number = $_POST['card_number'] ?? '';
    $voucher_code = $_POST['voucher_code'] ?? '';
    $card_name = $_POST['card_name'] ?? '';
    $card_cvv = $_POST['card_cvv'] ?? '';

    if ($amount > 0) {
        if ($method === 'card') {
            if (empty($card_number) || empty($card_name) || empty($card_cvv)) {
                $error = "Please fill in all card details.";
            } elseif (!preg_match('/^\d{16}$/', $card_number)) {
                $error = "Card number must be 16 digits.";
            } elseif (!preg_match('/^\d{3}$/', $card_cvv)) {
                $error = "CVV must be 3 digits.";
            }
        } elseif ($method === 'voucher') {
            if (empty($voucher_code)) {
                $error = "Please enter a voucher code.";
            }
        } elseif ($method === '') {
            $error = "Please select a top up method.";
        }

        // Only update wallet if there is no error
        if (empty($error)) {
            $stmt = $conn->prepare("UPDATE wallet SET balance = balance + ? WHERE user_id = ?");
            $stmt->bind_param("di", $amount, $user_id);
            if ($stmt->execute()) {
                $message = "Wallet topped up successfully!";
            } else {
                $error = "Failed to top up wallet. " . $stmt->error;
            }
            $stmt->close();
        }
    } else {
        $error = "Enter a valid amount.";
    }
}

// Fetch wallet balance
$stmt = $conn->prepare("SELECT balance FROM wallet WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$wallet = $result->fetch_assoc();
$balance = $wallet ? number_format($wallet['balance'], 2) : "0.00";
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Buyer Wallet - Rainbow Market</title>
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
      <h2>My Wallet</h2>
      <p><strong>Balance:</strong> R<?= $balance ?></p>
      <?php if ($message): ?>
        <div style="color:green"><?= htmlspecialchars($message) ?></div>
      <?php endif; ?>
      <?php if ($error): ?>
        <div style="color:red"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <form method="POST" style="margin-top:2rem;" id="topup-form">
        <label for="topup_amount">Top Up Amount (R):</label>
        <input type="number" step="0.01" min="1" name="topup_amount" id="topup_amount" required>

        <label for="topup_method">Top Up Method:</label>
        <select name="topup_method" id="topup_method" required>
          <option value="">Select method</option>
          <option value="card">Card</option>
          <option value="voucher">Voucher</option>
        </select>

        <div id="card-fields" style="display:none;">
          <label for="card_number">Card Number:</label>
          <input type="text" name="card_number" id="card_number" maxlength="16" pattern="\d{16}">

          <label for="card_name">Name on Card:</label>
          <input type="text" name="card_name" id="card_name" maxlength="40">

          <label for="card_cvv">CVV:</label>
          <input type="text" name="card_cvv" id="card_cvv" maxlength="3" pattern="\d{3}">
        </div>

        <div id="voucher-fields" style="display:none;">
          <label for="voucher_code">Voucher Code:</label>
          <input type="text" name="voucher_code" id="voucher_code" maxlength="12">
        </div>

        <button type="submit">Add Funds</button>
      </form>

      <script>
      document.getElementById('topup_method').addEventListener('change', function() {
        document.getElementById('card-fields').style.display = this.value === 'card' ? 'block' : 'none';
        document.getElementById('voucher-fields').style.display = this.value === 'voucher' ? 'block' : 'none';
      });
      </script>
    </main>

    <?php include $_SERVER['DOCUMENT_ROOT'] . '/rainbow_market/footer.php'; ?>
    <script src="/rainbow_market/scripts/components.js"></script>
  </body>
</html>
