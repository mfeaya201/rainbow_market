<?php
// Start session and check if user is logged in
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/rainbow_market/admin/db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'buyer') {
  header("Location: /rainbow_market/registration.html");
  exit();
}

$buyer_id = $_SESSION['user_id'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $receiver_id = intval($_POST['receiver_id']);
  $message = trim($_POST['message_text']);

  if (!empty($message) && $receiver_id > 0) {
    $stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $buyer_id, $receiver_id, $message);
    $stmt->execute();
  }
}

// Fetch sellers for dropdown
$sellers = $conn->query("SELECT id, name FROM users WHERE role = 'seller'");

// Fetch messages involving this buyer
$query = "
  SELECT m.id, m.message, m.created_at, u.name AS sender_name
  FROM messages m
  JOIN users u ON m.sender_id = u.id
  WHERE m.receiver_id = $buyer_id OR m.sender_id = $buyer_id
  ORDER BY m.created_at DESC
";
$result = $conn->query($query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>My Messages - Rainbow Market</title>
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

    <section class="dashboard-main">
      <h2>Messages</h2>

      <!-- Message Sending Form -->
      <form method="POST" style="margin-bottom: 20px;">
        <label for="receiver_id">Send Message To:</label><br />
        <select name="receiver_id" id="receiver_id" required>
          <option value="">-- Select Seller --</option>
          <?php while ($row = $sellers->fetch_assoc()): ?>
            <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['name']) ?></option>
          <?php endwhile; ?>
        </select><br /><br />
        <textarea name="message_text" rows="4" placeholder="Type your message..." required></textarea><br />
        <button type="submit">Send</button>
      </form>

      <!-- Message List -->
      <div class="messages-container">
        <?php if ($result->num_rows > 0): ?>
          <ul class="messages-list">
            <?php while ($row = $result->fetch_assoc()): ?>
              <li class="message-item">
                <strong><?= htmlspecialchars($row['sender_name']) ?>:</strong>
                <span><?= htmlspecialchars($row['message']) ?></span>
                <small><?= date('Y-m-d H:i', strtotime($row['created_at'])) ?></small>
              </li>
            <?php endwhile; ?>
          </ul>
        <?php else: ?>
          <p>No messages yet.</p>
        <?php endif; ?>
      </div>
    </section>
  </main>

  <script src="/rainbow_market/scripts/components.js"></script>
</body>
</html>
