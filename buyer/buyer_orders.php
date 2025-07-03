<?php

session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/rainbow_market/admin/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'buyer') {
  header("Location: /rainbow_market/registration.html");
  exit();
}

$buyer_id = $_SESSION['user_id'];

$query = "
  SELECT o.id AS order_id, p.title AS product_title, o.quantity, o.status, o.created_at
  FROM orders o
  JOIN products p ON o.product_id = p.id
  WHERE o.buyer_id = $buyer_id
  ORDER BY o.created_at DESC
";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>My Orders - Rainbow Market</title>
  <link rel="stylesheet" href="/rainbow_market/styles/dashboard.css">
  <link rel="stylesheet" href="/rainbow_market/styles/styles.css" />
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
  />
</head>
<body>

  <?php include $_SERVER['DOCUMENT_ROOT'] . '/rainbow_market/navbar.php'; ?>
  <h2>My Orders</h2>
  <table>
    <tr>
      <th>Order #</th>
      <th>Product</th>
      <th>Qty</th>
      <th>Status</th>
      <th>Date</th>
      <th>Action</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()) { ?>
      <tr>
        <td>#<?= $row['order_id'] ?></td>
        <td><?= htmlspecialchars($row['product_title']) ?></td>
        <td><?= $row['quantity'] ?></td>
        <td><?= ucfirst($row['status']) ?></td>
        <td><?= $row['created_at'] ?></td>
        <td>
          <?php if ($row['status'] === 'out for delivery'): ?>
            <form method="GET" action="/rainbow_market/buyer/confirm_delivery.php">
              <input type="hidden" name="order_id" value="<?= $row['order_id'] ?>">
              <button type="submit">Confirm Delivery</button>
            </form>
          <?php elseif ($row['status'] === 'completed'): ?>
            Delivered
          <?php else: ?>
            <?= ucfirst($row['status']) ?>
          <?php endif; ?>
        </td>
      </tr>
    <?php } ?>
  </table>

  <?php include $_SERVER['DOCUMENT_ROOT'] . '/rainbow_market/footer.php'; ?>

  <script src="/rainbow_market/scripts/components.js"></script>
</body>
</html>

