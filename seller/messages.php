<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start session and check if user is logged in
include $_SERVER['DOCUMENT_ROOT'] . '/rainbow_market/seller/auth_seller.php';
// Connect to database
include $_SERVER['DOCUMENT_ROOT'] . '/rainbow_market/admin/db.php';

$seller_id = $_SESSION['user_id'];

// Fetch seller name for welcome banner
$seller_name = '';
$stmt = $conn->prepare("SELECT name FROM users WHERE id = ?");
$stmt->bind_param("i", $seller_id);
$stmt->execute();
$stmt->bind_result($seller_name);
$stmt->fetch();
$stmt->close();

// Fetch distinct buyers who messaged the seller
$buyer_query = "
  SELECT DISTINCT u.id, u.name
  FROM messages m
  JOIN users u ON m.sender_id = u.id
  WHERE m.receiver_id = ?
";
$buyer_stmt = $conn->prepare($buyer_query);
$buyer_stmt->bind_param("i", $seller_id);
$buyer_stmt->execute();
$buyers_result = $buyer_stmt->get_result();

// Fetch messages with selected buyer if set
$selected_buyer_id = $_GET['buyer_id'] ?? null;
$messages = [];

if ($selected_buyer_id) {
  $msg_query = "
    SELECT m.*, u.name AS sender_name
    FROM messages m
    JOIN users u ON m.sender_id = u.id
    WHERE (m.sender_id = ? AND m.receiver_id = ?)
       OR (m.sender_id = ? AND m.receiver_id = ?)
    ORDER BY m.created_at ASC
  ";
  $msg_stmt = $conn->prepare($msg_query);
  $msg_stmt->bind_param("iiii", $seller_id, $selected_buyer_id, $selected_buyer_id, $seller_id);
  $msg_stmt->execute();
  $messages = $msg_stmt->get_result();
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Seller Messages - Rainbow Market</title>
    <link rel="stylesheet" href="/rainbow_market/styles/styles.css" />
    <link rel="stylesheet" href="/rainbow_market/styles/dashboard.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
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
        <h2>Messages</h2>

        <!-- Buyer List -->
        <div>
          <h3>Select a Buyer:</h3>
          <ul>
            <?php while ($buyer = $buyers_result->fetch_assoc()): ?>
              <li>
                <a href="?buyer_id=<?= $buyer['id'] ?>">
                  <?= htmlspecialchars($buyer['name']) ?>
                </a>
              </li>
            <?php endwhile; ?>
          </ul>
        </div>

        <!-- Message Thread -->
        <?php if ($selected_buyer_id): ?>
          <div class="messages-thread">
            <h4>Conversation</h4>
            <div style="max-height: 300px; overflow-y: auto; border: 1px solid #ccc; padding: 10px;">
              <?php while ($msg = $messages->fetch_assoc()): ?>
                <p><strong><?= htmlspecialchars($msg['sender_name']) ?>:</strong> <?= htmlspecialchars($msg['message']) ?><br>
                  <small><?= $msg['created_at'] ?></small>
                </p>
              <?php endwhile; ?>
            </div>

            <!-- Reply Form -->
            <form method="POST" action="/rainbow_market/seller/send_message.php" style="margin-top: 10px;">
              <input type="hidden" name="receiver_id" value="<?= $selected_buyer_id ?>">
              <textarea name="message" placeholder="Type your message here..." required style="width: 100%; height: 80px;"></textarea>
              <button type="submit">Send</button>
            </form>
          </div>
        <?php endif; ?>
      </section>
    </main>

    <script src="/rainbow_market/scripts/components.js"></script>
  </body>
</html>
