<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/rainbow_market/admin/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'buyer') {
  header('Location: /rainbow_market/registration.html');
  exit();
}

$user_id = $_SESSION['user_id'];
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim($_POST['email'] ?? '');
  $password = $_POST['password'] ?? '';
  $password_confirm = $_POST['password_confirm'] ?? '';

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = 'Please enter a valid email address.';
  } elseif (!empty($password) && $password !== $password_confirm) {
    $error = 'Passwords do not match.';
  } else {
    if (!empty($password)) {
      $hashed_password = password_hash($password, PASSWORD_DEFAULT);
      $stmt = $conn->prepare("UPDATE users SET email = ?, password = ? WHERE id = ?");
      $stmt->bind_param("ssi", $email, $hashed_password, $user_id);
    } else {
      $stmt = $conn->prepare("UPDATE users SET email = ? WHERE id = ?");
      $stmt->bind_param("si", $email, $user_id);
    }

    if ($stmt->execute()) {
      $message = 'Settings updated successfully.';
    } else {
      $error = 'Something went wrong. Please try again.';
    }
    $stmt->close();
  }
}

$stmt = $conn->prepare("SELECT email FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Buyer Settings - Rainbow Market</title>
  <link rel="stylesheet" href="/rainbow_market/styles/styles.css" />
  <link rel="stylesheet" href="/rainbow_market/styles/dashboard.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
  <style>
    .settings-form {
      background: #fff;
      padding: 2rem;
      border-radius: 8px;
      margin: 2rem;
      flex: 1;
    }
    .settings-form h2 {
      margin-bottom: 1rem;
    }
    .settings-form label {
      display: block;
      margin: 1rem 0 0.3rem;
    }
    .settings-form input {
      width: 100%;
      padding: 0.5rem;
      border: 1px solid #ccc;
      border-radius: 5px;
    }
    .settings-form button {
      margin-top: 1.5rem;
      padding: 0.7rem 1.2rem;
      background: #007bff;
      border: none;
      color: white;
      border-radius: 5px;
      cursor: pointer;
    }
    .message {
      margin-top: 1rem;
      color: green;
    }
    .error {
      margin-top: 1rem;
      color: red;
    }
  </style>
</head>
<body>
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/rainbow_market/navbar.php'; ?>

  <main class="buyer-dashboard">
    <section class="settings-form">
      <h2>Update Buyer Account Settings</h2>

      <?php if ($message): ?>
        <div class="message"><?= htmlspecialchars($message) ?></div>
      <?php endif; ?>
      <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <form method="POST" action="buyer_settings.php">
        <label for="email">Email Address</label>
        <input type="email" name="email" id="email" required value="<?= htmlspecialchars($user['email']) ?>">

        <label for="password">New Password</label>
        <input type="password" name="password" id="password">

        <label for="password_confirm">Confirm New Password</label>
        <input type="password" name="password_confirm" id="password_confirm">

        <button type="submit">Save Changes</button>
      </form>
    </section>
  </main>

  <script src="/rainbow_market/scripts/rainbow-market.js"></script>
</body>
</html>
